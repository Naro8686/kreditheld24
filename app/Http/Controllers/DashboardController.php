<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Resources\StatisticResource;
use App\Models\Proposal;
use App\Models\Role;
use App\Models\User;
use App\Traits\File;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class DashboardController extends Controller
{
    use File;

    public function __construct()
    {
        $this->middleware(['role:' . Role::ADMIN])->only(['downloadZip', 'downloadZipInvoice']);
    }

    public function index()
    {
        $invoices = collect([]);
        $user = auth()->user();
        $proposals = Proposal::orderByDesc('id');
        if ($user->isManager()) {
            $proposals = $proposals->where('user_id', $user->id);
            $invoices = $user->proposals()->whereNotNull('invoice_file')->get();
        }
        $proposals = $proposals->whereNull('archived_at')->paginate(20);
        return view('dashboard', compact('proposals', 'invoices'));
    }

    public function statistics(Request $request)
    {
        try {
            $format = "Y-m-d";
            $unit = $request->get('unit', 'hour');
            $manager_id = $request->get('manager_id');
            list($min, $max) = array_pad($request->get('dates',
                [Carbon::now()->subDay()->format($format), Carbon::now()->format($format)]),
                2,
                Carbon::now()->format($format));
            $from = Carbon::createFromFormat($format, $min)->toDateString();
            $to = Carbon::createFromFormat($format, $max)->toDateString();

            if (!in_array($unit, ['hour', 'day', 'week', 'month', 'year'])) {
                throw new Exception('Wrong unit format');
            }
            $sqlFormat = match ($unit) {
                'day' => '%Y-%m-%d',
                'week' => '%x-%v',
                'month' => '%Y-%m',
                'year' => '%Y',
                default => '%Y-%m-%d %H',
            };
            $auth_user = $request->user();
            $approved = Status::APPROVED;
            $denied = Status::DENIED;
            $year = now()->subYear()->toDateString();
            if ($auth_user->isAdmin()) {
                $purchases = Proposal::when($manager_id, function (Builder $query, $manager_id) {
                    $query->where('user_id', $manager_id);
                });
                $orders = Proposal::when($manager_id, function (Builder $query, $manager_id) {
                    $query->where('user_id', $manager_id);
                });
                $other = Proposal::when($manager_id, function (Builder $query, $manager_id) {
                    $query->where('user_id', $manager_id);
                })->select([
                    DB::raw("IFNULL(SUM(CASE WHEN `proposals`.`status` = '{$approved}' THEN `proposals`.`creditAmount` ELSE 0 END),0) AS 'sum_approved_all'"),
                    DB::raw("IFNULL(SUM(`proposals`.`creditAmount`),0) AS 'sum_all'"),
                    !is_null($manager_id)
                        ? DB::raw("(SELECT IFNULL(SUM(CASE WHEN `proposals`.`status` = '{$approved}' THEN `proposals`.`creditAmount` ELSE 0 END),0) FROM `proposals` WHERE `proposals`.`created_at` >= '$year' AND `proposals`.`user_id` = '$manager_id' AND `proposals`.`deleted_at` is null) as 'sum_approved_year'")
                        : DB::raw("(SELECT IFNULL(SUM(CASE WHEN `proposals`.`status` = '{$approved}' THEN `proposals`.`creditAmount` ELSE 0 END),0) FROM `proposals` WHERE `proposals`.`created_at` >= '$year' AND `proposals`.`deleted_at` is null) as 'sum_approved_year'"),
                    !is_null($manager_id)
                        ? DB::raw("(SELECT IFNULL(SUM(`proposals`.`creditAmount`),0) FROM `proposals` WHERE `proposals`.`created_at` >= '$year' AND `proposals`.`user_id` = '$manager_id' AND `proposals`.`deleted_at` is null) as 'sum_year'")
                        : DB::raw("(SELECT IFNULL(SUM(`proposals`.`creditAmount`),0) FROM `proposals` WHERE `proposals`.`created_at` >= '$year' AND `proposals`.`deleted_at` is null) as 'sum_year'"),
                ])->limit(1)->first();
            } else {
                $purchases = $auth_user->proposals();
                $orders = $auth_user->proposals();
                $other = $auth_user->proposals()->select([
                    DB::raw("IFNULL(SUM(CASE WHEN `proposals`.`status` = '{$approved}' THEN `proposals`.`creditAmount` ELSE 0 END),0) AS 'sum_approved_all'"),
                    DB::raw("(SELECT IFNULL(SUM(CASE WHEN `proposals`.`status` = '{$approved}' THEN `proposals`.`creditAmount` ELSE 0 END),0) FROM `proposals` WHERE `proposals`.`created_at` >= '$year' AND `proposals`.`user_id` = {$auth_user->id} AND `proposals`.`deleted_at` is null) as 'sum_approved_year'"),
                    DB::raw("IFNULL(SUM(`proposals`.`creditAmount`),0) AS 'sum_all'"),
                    DB::raw("(SELECT IFNULL(SUM(`proposals`.`creditAmount`),0) FROM `proposals` WHERE `proposals`.`created_at` >= '$year' AND `proposals`.`user_id` = '{$auth_user->id}' AND `proposals`.`deleted_at` is null) as 'sum_year'"),
                ])->limit(1)->first();
            }
            $purchases = $purchases->select([
                DB::raw("IFNULL(SUM(`proposals`.`creditAmount`),0) AS 'sum'"),
                DB::raw("DATE_FORMAT(`proposals`.`created_at`, '$sqlFormat') AS 'unit'")
            ])->where('status', $approved)->where(function ($query) use ($from, $to) {
                return $query
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            })->groupBy("unit")->orderBy("unit");

            $orders = $orders->select([
                DB::raw("COUNT(id) AS 'total'"),
                DB::raw("IFNULL(SUM(CASE WHEN `proposals`.`status` = '{$approved}' THEN 1 ELSE 0 END),0) AS 'completed'"),
                DB::raw("IFNULL(SUM(CASE WHEN `proposals`.`status` = '{$denied}' THEN 1 ELSE 0 END),0) AS 'denied'"),
                DB::raw("IFNULL(SUM(CASE WHEN `proposals`.`status` = '{$approved}' THEN `proposals`.`creditAmount` ELSE 0 END),0) AS 'sum'")
            ])->where(function ($query) use ($from, $to) {
                return $query
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            })->limit(1)->first();

            if ($auth_user->isAdmin() && !is_null($manager_id)) {
                $user = User::find($manager_id);
            } else {
                $user = $auth_user;
            }

            if (optional($user)->isManager()) {
                $orders->targetPercent = $user->targetPercent($orders->sum);
            }
            return response()->json([
                'purchases' => StatisticResource::collection($purchases->get()),
                'populars' => [],
                'orders' => $orders,
                'other' => $other->only([
                    'sum_all', 'sum_approved_all', 'sum_year', 'sum_approved_year'
                ]),
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'errors' => [
                    'msg' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                ],
            ], 500);
        }
    }

    public function readFile(Request $request)
    {
        $path = $request->get('path');
        if (!is_null($path) && $data = $this->read($path)) {
            $pathToFile = public_path("storage/{$data['meta']['path']}");
            $headers = $data['headers'];
            return response()->file($pathToFile, $headers);
        }
        return redirect()->back()->with('error', __('Not Found'));
    }

    public function downloadFile(Request $request)
    {
        $path = $request->get('path');
        if (!is_null($path) && $data = $this->read($path)) {
            $pathToFile = public_path("storage/{$data['meta']['path']}");
            $headers = $data['headers'];
            $name = str_replace(Proposal::UPLOAD_FILE_PATH . '/', '', $path);
            return response()->download($pathToFile, $name, $headers);
        }
        return redirect()->back()->with('error', __('Not Found'));
    }

    public function downloadZip($proposal_id)
    {
        $proposal = Proposal::findOrFail($proposal_id);
        $zipPath = $proposal->makeZip();
        return is_null($zipPath)
            ? back()->with('error', __("Whoops! Something went wrong."))
            : response()->download($zipPath);
    }

    public function downloadFilesZip(Request $request)
    {
        try {
            $files = $request->get('files', []);
            if (empty($files)) {
                throw new Exception('file not found');
            }
            $path = storage_path("app/tmp/archive.zip");
            $zipPath = $this->makeZipWithFiles($path, $files) ? $path : null;
        } catch (Throwable $e) {
            Log::error(__METHOD__ . "- {$e->getMessage()}");
            $zipPath = null;
        }
        return is_null($zipPath)
            ? back()->with('error', __("Whoops! Something went wrong."))
            : response()->download($zipPath);
    }
}
