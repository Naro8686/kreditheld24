<?php

namespace App\Http\Requests;

use App\Constants\Status;
use App\Models\Proposal;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Str;

class ProposalRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    public function rules()
    {
        $familyStatuses = implode(',', Proposal::$familyStatuses);
        $applicantTypes = implode(',', Proposal::$applicantTypes);
        $residenceTypes = implode(',', Proposal::$residenceTypes);
        $uploadFileTypes = implode(',', Proposal::$uploadFileTypes);
        $uploadFileMaxSize = Proposal::MAX_FILE_SIZE;
        $statuses = implode(',', [
            Status::PENDING,
            Status::REVISION,
            Status::DENIED,
            Status::APPROVED
        ]);
        $otherCreditCount = $this['otherCreditCount'] ?? 0;
        $validates = [
            "draft" => "required|boolean",
            "category_id" => "required|exists:categories,id",
            "creditComment" => "string|max:255|nullable",
            "deadline" => "required_if:monthlyPayment,null|int|min:1",
            "monthlyPayment" => "required_if:deadline,null|numeric|min:.01",
            "creditAmount" => "required|numeric|min:1",
            "rentAmount" => "sometimes|nullable|numeric|min:0",
            "communalAmount" => "sometimes|nullable|numeric|min:0",
            "communalExpenses" => "sometimes|nullable|numeric|min:0",
            "gender" => "required|in:male,female",
            "applicantType" => "required|in:$applicantTypes",
            "childrenCount" => "sometimes|nullable|integer|min:0",
            "firstName" => "required|string|min:2",
            "lastName" => "required|string|min:2",
            "street" => "sometimes|nullable|string|min:2",
            "house" => "sometimes|nullable|string|min:2",
            "city" => "sometimes|nullable|string|min:2",
            "postcode" => "sometimes|nullable|regex:/\b\d{4,10}\b/",
            "birthday" => "sometimes|nullable|date|before:today|date_format:Y-m-d",
            "residenceDate" => "sometimes|nullable|date|after_or_equal:birthday|before:today|date_format:Y-m-d",
            "phoneNumber" => "required|numeric|phone_number:6",
            "email" => "required|email",
            "birthplace" => "required|string|min:2",
            "residenceType" => "sometimes|nullable|in:$residenceTypes",
            "familyStatus" => "sometimes|nullable|in:$familyStatuses",
            "otherCreditCount" => "required|int|min:0|max:4",
        ];
        if (!count($this->get('allFilesName', [])) && !$this->isDraft()) {
            $validates["uploads"] = "required|array|min:1";
            $validates["uploads.*"] = "required|mimes:$uploadFileTypes|max:$uploadFileMaxSize";
        } else {
            $validates["uploads"] = "sometimes|array|min:1";
            $validates["uploads.*"] = "sometimes|mimes:$uploadFileTypes|max:$uploadFileMaxSize";
        }
        if ($this['residenceDate']) {
            $date = Carbon::createFromFormat('Y-m-d', $this['residenceDate']);
            if ($date->diff(Carbon::now()->subYears(2))->invert) {
                $validates["oldAddress"] = "required|array";
                $validates["oldAddress.street"] = $validates["street"];
                $validates["oldAddress.house"] = $validates["house"];
                $validates["oldAddress.city"] = $validates["city"];
                $validates["oldAddress.postcode"] = $validates["postcode"];
            }
        }
        if ($this['familyStatus'] && ($this['familyStatus'] === 'married' || $this['familyStatus'] === 'cohabitation')) {
            $validates["spouse"] = "required|array";
            $validates["spouse.firstName"] = $validates["firstName"];
            $validates["spouse.lastName"] = $validates["lastName"];
            $validates["spouse.birthday"] = $validates["birthday"];
            $validates["spouse.birthplace"] = $validates["birthplace"];
        }
        if ($otherCreditCount > 0) {
            $validates["otherCredit"] = "required|array|min:{$otherCreditCount}|max:4";
            $validates["otherCredit.*.monthlyPayment"] = $validates['monthlyPayment'];
            $validates["otherCredit.*.creditBalance"] = $validates['creditAmount'];
            $validates["otherCredit.*.repay"] = "required|in:yes,no";
            $validates["otherCredit.*.bankNumber"] = "required_if:otherCredit.*.repay,yes";
        }
        if ($this->get('status')) {
            $validates["status"] = "required|in:$statuses";
            if ($this['status'] === Status::APPROVED) {
                $validates["bonus"] = "sometimes|nullable|numeric|min:1|max:100";
                $validates["commission"] = "required|numeric|min:1|max:100";
            }
        }
        if ($this['objectData']) {
            $objectTypes = implode(',', Proposal::$objectTypes);
            $validates["objectData"] = "required|array";
            $validates["objectData.street"] = "sometimes|nullable|string|min:2";
            $validates["objectData.house"] = "sometimes|nullable|string|min:2";
            $validates["objectData.city"] = "sometimes|nullable|string|min:2";
            $validates["objectData.postcode"] = "sometimes|nullable|regex:/\b\d{4,10}\b/";
            $validates["objectData.objectType"] = "sometimes|nullable|in:$objectTypes";
            $validates["objectData.yearConstruction"] = "sometimes|nullable|integer|min:1900";
            $validates["objectData.yearRepair"] = "sometimes|nullable|integer|min:1900";
            $validates["objectData.plotSize"] = "sometimes|nullable|numeric";
            $validates["objectData.livingSpace"] = "sometimes|nullable|numeric";
            $validates["objectData.buildPrice"] = "sometimes|nullable|numeric";
            $validates["objectData.accumulation"] = "sometimes|nullable|numeric";
            $validates["objectData.brokerageFees"] = "sometimes|nullable|integer|min:0|max:100";
        }
        $validates = collect($validates);
//        if ($this->isDraft()) $validates->transform(function ($item, $key) {
//            return $key === 'draft' ? $item : str_replace("required", "sometimes|nullable", $item);
//        });
        return $this->isDraft() ? [] : $validates->all();
    }

    public function messages()
    {
        return [
            'phoneNumber.phone_number' => Str::ucfirst(__('enter the correct phone number.')),
        ];
    }

    public function attributes(): array
    {
        return [
            'otherCredit.*.bankNumber' => '"' . Str::lower(__('Bank number')) . '"',
            'otherCredit.*.repay' => '"' . Str::lower(__('Repay a credit ?')) . '"',
            'otherCredit.*.monthlyPayment' => '"' . Str::lower(__('Monthly Payment')) . '"',
            'otherCredit.*.creditBalance' => '"' . Str::lower(__('Credit balance')) . '"',
            'oldAddress.postcode' => '"' . Str::lower(__('Postcode')) . '"',
            'category_id' => '"' . Str::lower(__('Credit Type')) . '"',
            'deadline' => '"' . Str::lower(__('For what time (month) ?')) . '"',
            'number' => '"' . Str::lower(__('Proposal number')) . '"',
            'monthlyPayment' => '"' . Str::lower(__('Desired amount of payment per month ?')) . '"',
            'creditAmount' => '"' . Str::lower(__('Desired loan amount ?')) . '"',
            'firstName' => '"' . Str::lower(__('Name')) . '"',
            'lastName' => '"' . Str::lower(__('Surname')) . '"',
            'street' => '"' . Str::lower(__('Street')) . '"',
            'house' => '"' . Str::lower(__('House')) . '"',
            'postcode' => '"' . Str::lower(__('Postcode')) . '"',
            'city' => '"' . Str::lower(__('City')) . '"',
            'residenceDate' => '"' . Str::lower(__('residence Date')) . '"',
            'birthday' => '"' . Str::lower(__('Birthday')) . '"',
            'phoneNumber' => '"' . Str::lower(__('Phone Number')) . '"',
            'email' => '"' . Str::lower(__('Email')) . '"',
            'birthplace' => '"' . Str::lower(__('Birthplace')) . '"',
            'residenceType' => '"' . Str::lower(__('residence Type')) . '"',
            'familyStatus' => '"' . Str::lower(__('Family status')) . '"',
            'uploads' => '"' . Str::lower(__('files')) . '"',
            'uploads.*' => '"' . Str::lower(__('files')) . '"',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this['creditAmount']) $this->merge([
            "creditAmount" => $this->parse_number($this['creditAmount']),
        ]);
        $this->merge(['deleted_at' => $this->isDraft() ? now() : null]);
        if ($this['phoneNumber']) $this->merge([
            "phoneNumber" => Str::replace('+', '', $this['phoneNumber']),
        ]);
        if ($this->get('creditAmount', 0) > 0) {
            if ($this->get('monthlyPayment', 0) > 0) $this->merge([
                "deadline" => round(($this->get('creditAmount', 0) / $this->get('monthlyPayment'))),
            ]);

            if ($this->get('deadline', 0) > 0) $this->merge([
                "monthlyPayment" => round(($this->get('creditAmount', 0) / $this->get('deadline')), 2),
            ]);
        }

        $this->merge(['insurance' => [
            'unemployment' => $this->has('insurance.unemployment'),
            'disease' => $this->has('insurance.disease'),
            'death' => $this->has('insurance.death'),
        ]]);
    }

    public function isDraft(): bool
    {
        return $this->boolean('draft');
    }

    function parse_number($number, $dec_point = null): float
    {
        if (empty($dec_point)) {
            $locale = localeconv();
            $dec_point = $locale['decimal_point'];
        }
        return floatval(str_replace($dec_point, '.', preg_replace('/[^\d' . preg_quote($dec_point) . ']/', '', $number)));
    }
}
