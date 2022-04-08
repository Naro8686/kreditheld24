<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Exports\ProposalsExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\StatisticResource;
use App\Models\Proposal;
use App\Models\Role;
use App\Traits\File;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Throwable;

class DashboardController extends Controller
{
    use File;

    public function __construct()
    {
        $this->middleware(['auth', 'role:' . Role::ADMIN]);
    }

    public function index()
    {
//        dd(3);
//        return Excel::download(new ProposalsExport(), 'Proposals.pdf');

//        return (new ProposalsExport)->download('invoices.pdf', \Maatwebsite\Excel\Excel::MPDF);
        $proposals = Proposal::orderByDesc('id')->paginate();
        return view('admin.dashboard', compact('proposals'));
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

    public function statistics(Request $request)
    {
        try {
            $format = "Y-m-d";
            $unit = $request->get('unit', 'hour');
            list($min, $max) = array_pad($request->get('dates',
                [Carbon::now()->subDay()->format($format), Carbon::now()->format($format)]),
                2,
                Carbon::now()->format($format));
            $from = Carbon::createFromFormat($format, $min)->toDateString();
            $to = Carbon::createFromFormat($format, $max)->toDateString();

            if (!in_array($unit, ['hour', 'day', 'week', 'month', 'year'])) {
                throw new Exception('Wrong unit format');
            }
            switch ($unit) {
                case 'day':
                    $sqlFormat = '%Y-%m-%d';
                    break;
                case 'week':
                    $sqlFormat = '%x-%v';
                    break;
                case 'month':
                    $sqlFormat = '%Y-%m';
                    break;
                case 'year':
                    $sqlFormat = '%Y';
                    break;
                case 'hour':
                default:
                    $sqlFormat = '%Y-%m-%d %H';
                    break;
            }
            $approved = Status::APPROVED;
            $purchases = Proposal::select([
                DB::raw("IFNULL(SUM(`proposals`.`creditAmount`),0) AS 'sum'"),
                DB::raw("DATE_FORMAT(`proposals`.`created_at`, '$sqlFormat') AS 'unit'")
            ])->where('status', $approved)->where(function ($query) use ($from, $to) {
                return $query
                    ->whereDate('created_at', '>=', $from)
                    ->whereDate('created_at', '<=', $to);
            })->groupBy("unit")->orderBy("unit");

            $orders = Proposal::select([
                DB::raw("COUNT(id) AS 'total'"),
                DB::raw("IFNULL(SUM(CASE WHEN status = '{$approved}' THEN 1 ELSE 0 END),0) AS 'completed'"),
                DB::raw("IFNULL(SUM(CASE WHEN status = '{$approved}' THEN `proposals`.`creditAmount` ELSE 0 END),0) AS 'sum'")
            ])
                ->where(function ($query) use ($from, $to) {
                    return $query
                        ->whereDate('created_at', '>=', $from)
                        ->whereDate('created_at', '<=', $to);
                })->limit(1)->first();
            return response()->json([
                'purchases' => StatisticResource::collection($purchases->get()),
                'populars' => [],
                'orders' => $orders
            ]);
        } catch (Exception|Throwable $exception) {
            return response()->json([
                'errors' => [
                    'msg' => $exception->getMessage(),
                    'line' => $exception->getLine(),
                    'trace' => $exception->getTrace(),
                ],
            ], 500);
        }
    }
}
