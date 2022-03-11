<?php

namespace App\Models;

use App\Casts\AsCustomCollection;
use App\Constants\Status;
use App\Http\Requests\ProposalRequest;
use App\Traits\File;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

/**
 * App\Models\Proposal
 *
 * @property int $id
 * @property int $user_id
 * @property string $status
 * @property string|null $notice
 * @property string $creditType
 * @property int $deadline
 * @property string $monthlyPayment
 * @property string $creditAmount
 * @property string|null $creditComment
 * @property string $firstName
 * @property string $lastName
 * @property \Illuminate\Support\Carbon $birthday
 * @property string $phoneNumber
 * @property string $email
 * @property string $birthplace
 * @property string $street
 * @property string $house
 * @property string $city
 * @property string $postcode
 * @property string $residenceType
 * @property \Illuminate\Support\Carbon $residenceDate
 * @property string $familyStatus
 * @property |null $oldAddress
 * @property |null $spouse
 * @property |null $insurance
 * @property |null $otherCredit
 * @property |null $files
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal query()
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereBirthday($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereBirthplace($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreditAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreditComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCreditType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereDeadline($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereFamilyStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereFiles($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereHouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereInsurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereMonthlyPayment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereNotice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereOldAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereOtherCredit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal wherePhoneNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal wherePostcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereResidenceDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereResidenceType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereSpouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereStreet($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereUserId($value)
 * @mixin \Eloquent
 */
class Proposal extends Model
{
    use HasFactory, File;

    protected $guarded = [];
    public static $creditTypes = ['home', 'car', 'repair', 'vacation', 'other'];
    public static $residenceTypes = ['rent', 'roommate', 'own', 'lodger'];
    public static $familyStatuses = ['divorced', 'married', 'unmarried', 'widower'];
    public static $uploadFileTypes = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'csv', 'txt', 'xlx', 'xls', 'pdf'];
    public const MAX_FILE_SIZE = '10000'; //kb
    public const UPLOAD_FILE_PATH = 'uploads'; //kb
    public const CURRENCY = '€'; //kb
    protected $casts = [
        'files' => AsCustomCollection::class,
        'spouse' => AsCustomCollection::class,
        'oldAddress' => AsCustomCollection::class,
        'insurance' => AsCustomCollection::class,
        'otherCredit' => AsCustomCollection::class,
        'birthday' => 'date',
        'residenceDate' => 'date',
    ];
    protected $appends = ['payoutAmount'];

    public function getPayoutAmountAttribute()
    {
        return (($this->creditAmount * ($this->commission ?? 0)) / 100) + ($this->bonus ?? 0);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function deleteAllFiles()
    {
        $this->deleteFiles($this->files ?? []);
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
}
