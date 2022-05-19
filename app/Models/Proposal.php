<?php

namespace App\Models;

use App\Casts\AsCustomCollection;
use App\Constants\Status;
use App\Mail\SendEmail;
use App\Traits\File;
use Dompdf\Dompdf;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

/**
 * App\Models\Proposal
 *
 * @property int $id
 * @property string|null $number
 * @property int $user_id
 * @property int|null $category_id
 * @property string $status
 * @property string|null $notice
 * @property int|null $deadline
 * @property string|null $monthlyPayment
 * @property string|null $creditAmount
 * @property string|null $creditComment
 * @property string|null $firstName
 * @property string|null $lastName
 * @property string $gender
 * @property \Illuminate\Support\Carbon|null $birthday
 * @property string|null $phoneNumber
 * @property string|null $email
 * @property string|null $birthplace
 * @property string|null $street
 * @property string|null $house
 * @property string|null $city
 * @property string|null $postcode
 * @property string|null $residenceType
 * @property \Illuminate\Support\Carbon|null $residenceDate
 * @property string|null $familyStatus
 * @property int $childrenCount
 * @property |null $oldAddress
 * @property |null $spouse
 * @property |null $insurance
 * @property |null $otherCredit
 * @property |null $files
 * @property string|null $bonus
 * @property string|null $commission
 * @property |null $objectData
 * @property string $applicantType
 * @property string|null $rentAmount
 * @property string|null $communalAmount
 * @property string|null $communalExpenses
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property string|null $pending_at
 * @property string|null $approved_at
 * @property string|null $revision_at
 * @property string|null $denied_at
 * @property-read \App\Models\Category|null $category
 * @property-read string|null $credit_type
 * @property-read float|int $payout_amount
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal newQuery()
 * @method static \Illuminate\Database\Query\Builder|Proposal onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereApplicantType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereApprovedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereBirthplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereChildrenCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCommission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCommunalAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCommunalExpenses($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreditAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreditComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereDeniedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereFamilyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereHouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereObjectData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereOldAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereOtherCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal wherePendingAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereRentAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereResidenceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereResidenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereRevisionAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereSpouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|Proposal withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Proposal withoutTrashed()
 * @mixin \Eloquent
 * @property string|null $invoice_file
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereInvoiceFile($value)
 */
class Proposal extends Model
{
    use SoftDeletes, HasFactory, File;

    protected $guarded = [];
    protected $hidden = [];
    protected $appends = ['payoutAmount', 'creditType'];
    public static array $applicantTypes = ['individual', 'juridical'];
    public static array $objectTypes = ['penthouse', 'own_apartment', 'townhouse', 'semi-detached_house', 'house', 'estate', 'house_for_two_families'];
    public static array $creditTypes = ['home', 'private_credit', 'car', 'repair', 'vacation', 'capital', 'other'];
    public static array $residenceTypes = ['rent', 'roommate', 'own', 'lodger'];
    public static array $familyStatuses = ['divorced', 'married', 'unmarried', 'widower', 'cohabitation', 'separately'];
    public static array $uploadFileTypes = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'csv', 'txt', 'xlx', 'xls', 'xlsx', 'pdf'];
    public const MAX_FILE_SIZE = '10000'; //kb
    public const UPLOAD_FILE_PATH = 'uploads';
    public const CURRENCY = '€';
    protected $casts = [
        'files' => AsCustomCollection::class,
        'spouse' => AsCustomCollection::class,
        'oldAddress' => AsCustomCollection::class,
        'insurance' => AsCustomCollection::class,
        'otherCredit' => AsCustomCollection::class,
        'objectData' => AsCustomCollection::class,
        'birthday' => 'date',
        'residenceDate' => 'date',
        'revision_at' => 'datetime',
        'approved_at' => 'datetime',
        'pending_at' => 'datetime',
        'denied_at' => 'datetime',
    ];


    public static function boot()
    {
        parent::boot();
        static::updating(function (Proposal $model) {
            if (!$model->trashed()) {
                $model->pending_at = null;
                $model->approved_at = null;
                $model->revision_at = null;
                $model->denied_at = null;
                if ($model->isDirty('status')) {
                    $date = now();
                    switch ($model->status) {
                        case Status::APPROVED:
                            $model->approved_at = $date;
                            $model->invoiceGenerate();
                            break;
                        case Status::DENIED:
                            $model->denied_at = $date;
                            break;
                        case Status::PENDING:
                            $model->pending_at = $date;
                            break;
                        case Status::REVISION:
                            $model->revision_at = $date;
                            break;
                    }
                }
            }
        });
        static::creating(function (Proposal $model) {
            if (!$model->trashed()) {
                $model->pending_at = now();
            }
        });
        static::updated(function (Proposal $model) {
            if (!$model->trashed() && $model->isDirty('status')) {
                $data = ['url' => route('proposal.index')];
                $message = $user = null;
                switch ($model->status) {
                    case Status::APPROVED:
                        if ($model->invoice_file) {
                            $data['invoice_pdf'] = public_path("storage/{$model->invoice_file}");
                        }
                        $message = __("Proposal approved") . ': ' . $model->approved_at->format('d.m.Y');
                        $user = $model->user;
                        break;
                    case Status::DENIED:
                        $message = __("Proposal denied") . ': ' . $model->denied_at->format('d.m.Y');
                        $user = $model->user;
                        break;
                    case Status::REVISION:
                        $message = __("Proposal for revision") . ': ' . $model->revision_at->format('d.m.Y');
                        $user = $model->user;
                        $data['url'] = route('proposal.edit', [$model->id]);
                        break;
                    case Status::PENDING:
                        if (auth()->check() && auth()->user()->id === $model->user_id) {
                            $message = __("Proposal for confirmation") . ': ' . $model->pending_at->format('d.m.Y');
                            $user = User::admin();
                            $data['url'] = route('admin.proposals.edit', [$model->id]);
                        }
                        break;
                }
                if (!is_null($message) && !is_null($user)) {
                    $user->sendEmail('<h1 style="text-align: center">' . $message . '</h1>', $data);
                }
            }
        });
        static::created(function (Proposal $model) {
            if (!$model->trashed() && $admin = User::admin()) {
                $message = '<h1 style="text-align: center">' . __('New Proposal') . ': ' . $model->created_at->format('d.m.Y H:i:s') . '</h1>';
                $data = ['url' => route('admin.proposals.edit', [$model->id])];
                $admin->sendEmail($message, $data);
            }
        });
    }

    public function invoiceGenerate(): ?string
    {
        $proposal = $this;
        if ($proposal->invoice_file && $this->deleteFile($proposal->invoice_file)) {
            $proposal->invoice_file = null;
        }
        $fileName = "invoice_$proposal->id.pdf";
        $path = self::UPLOAD_FILE_PATH . '/' . $fileName;
        $dompdf = new Dompdf(['defaultFont' => 'DejaVu Serif']);
        $dompdf->loadHtml(view('proposal.invoice', compact('proposal'))->render());
        $dompdf->setPaper('A4');
        $dompdf->render();
        $output = $dompdf->output();
        if (Storage::disk(self::$locale)->put($path, $output)) {
            $this->invoice_file = $path;
        }
        return $this->invoice_file;
    }

    /**
     * @return string|null
     */
    public function getCreditTypeAttribute(): ?string
    {
        return optional($this->category)->name;
    }

    /**
     * @return string
     */
    public function getPayoutAmountAttribute()
    {
        try {
            $payoutAmount = (($this->creditAmount * (($this->commission ?? 0) + ($this->bonus ?? 0))) / 100);
        } catch (\Throwable $throwable) {
            $payoutAmount = 0;
        }
        return number_format((float)$payoutAmount, 2);
    }

    public function creditAmountFormat(): ?string
    {
        return !isset($this->creditAmount) ? null : number_format((float)$this->creditAmount, 2);
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function deadlineDateFormat(): ?\Illuminate\Support\Carbon
    {
        return $this->created_at && $this->deadline ? $this->created_at->addMonths($this->deadline) : null;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function deleteAllFiles()
    {
        $files = $this->files ?? [];
        if (!is_null($this->invoice_file)) {
            $files[] = $this->invoice_file;
        }
        $this->deleteFiles($files);
    }

    public function makeZip(): ?string
    {
        try {
            $files = $this->files->map(function ($file) {
                $file = trim($file, '/');
                return public_path("storage/$file");
            });
            $path = storage_path("app/tmp/archive.zip");

            return $this->makeZipWithFiles($path, $files->toArray()) ? $path : null;
        } catch (Exception $e) {
            Log::error("make zip {$e->getMessage()}");
        }
        return null;
    }

    public function saveData(array $data, $allFilesName = []): bool
    {
        try {
            foreach ($data as $key => $value) {
                if ($key === 'uploads') continue;
                $this->$key = $value;
            }
            $files = [];
            if (!is_null($this->files)) {
                $allFilesName = collect($allFilesName)->map(function ($fileName) {
                    return trim(self::UPLOAD_FILE_PATH . "/$fileName", '/');
                });
                $files = $this->files->filter(function ($value) use ($allFilesName) {
                    return in_array(trim($value, '/'), $allFilesName->toArray());
                })->values()->toArray();
                $this->deleteFiles($this->files->diff($files));
            }
            foreach ($data['uploads'] ?? [] as $file) {
                $files[] = $this->fileUpload($file);
            }
            $this->files = $files;
            return $this->save();
        } catch (Exception $exception) {
            Log::error("Proposal::saveData {$exception->getMessage()}");
        }
        return false;
    }

    public function statusBgColor(): string
    {
        switch ($this->status) {
            case Status::APPROVED:
                $bgColor = 'bg-green-300';
                break;
            case Status::DENIED:
                $bgColor = 'bg-red-300';
                break;
            case Status::PENDING:
                $bgColor = 'bg-yellow-300';
                break;
            default:
                $bgColor = '';
        }
        return $bgColor;
    }
}
