<?php

namespace App\Models;

use App\Casts\AsCustomCollection;
use App\Traits\File;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
 * @property string|null $bonus
 * @property string|null $commission
 * @property-read mixed $payout_amount
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereBonus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCommission($value)
 * @property string|null $number
 * @property int|null $category_id
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereNumber($value)
 * @property-read \App\Models\Category|null $category
 * @property-read string|null $credit_type
 * @property string $gender
 * @property int $childrenCount
 * @property string $rentAmount
 * @property string $applicantType
 * @property |null $objectData
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereApplicantType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereChildrenCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereObjectData($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Proposal whereRentAmount($value)
 */
class Proposal extends Model
{
    use HasFactory, File;

    protected $guarded = [];
    public static array $applicantTypes = ['individual', 'juridical'];
    public static array $objectTypes = ['penthouse', 'own_apartment', 'townhouse', 'semi-detached_house', 'house', 'estate', 'house_for_two_families'];
    public static array $creditTypes = ['home', 'private_credit', 'car', 'repair', 'vacation', 'capital', 'other'];
    public static array $residenceTypes = ['rent', 'roommate', 'own', 'lodger'];
    public static array $familyStatuses = ['divorced', 'married', 'unmarried', 'widower', 'cohabitation', 'separately'];
    public static array $uploadFileTypes = ['jpg', 'jpeg', 'png', 'doc', 'docx', 'csv', 'txt', 'xlx', 'xls', 'pdf'];
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
    ];
    protected $appends = ['payoutAmount', 'creditType'];

    /**
     * @return string|null
     */
    public function getCreditTypeAttribute(): ?string
    {
        return optional($this->category)->name;
    }

    /**
     * @return float|int|string
     */
    public function getPayoutAmountAttribute()
    {
        return (($this->creditAmount * ($this->commission ?? 0))) <= 0
            ? 0
            : (($this->creditAmount * ($this->commission ?? 0)) / 100) + ($this->bonus ?? 0);
    }

    /**
     * @return \Illuminate\Support\Carbon|null
     */
    public function deadlineDateFormat(): ?\Illuminate\Support\Carbon
    {
        return $this->created_at ? $this->created_at->addMonths($this->deadline) : null;
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
        $this->deleteFiles($this->files ?? []);
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
}
