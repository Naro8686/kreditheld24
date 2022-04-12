<?php

namespace App\Exports;

use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Str;

class ProposalExport implements ShouldAutoSize, FromQuery, WithHeadings, WithMapping
{

    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function headings(): array
    {
        return [
            __('ID'),
            __('Proposal number'),
            __('Status'),
            __('Category'),
            __('Gender'),
            __('Name'),
            __('Surname'),
            __('Phone Number'),
            __('Email'),
            __('Birthday'),
            __('Birthplace'),
            __('Family status'),
            __('Name') . ' (' . __('Wife') . ')',
            __('Surname') . ' (' . __('Wife') . ')',
            __('Birthday') . ' (' . __('Wife') . ')',
            __('Birthplace') . ' (' . __('Wife') . ')',
            __('Count children'),
            __('Street'),
            __('House'),
            __('Postcode'),
            __('City'),
            __('residence Type'),
            __('Rent'),
            __('residence Date'),
            __('Street'). ' (' . __('Old Address') . ')',
            __('House'). ' (' . __('Old Address') . ')',
            __('Postcode'). ' (' . __('Old Address') . ')',
            __('City'). ' (' . __('Old Address') . ')',
            __('Credit Type'),
            __('Comment'),
            __('Desired loan amount ?'),
            __('For what time (month) ?'),
            __('Desired amount of payment per month ?'),
            __('Number of existing loans'),
            __('Insurance'),
            __('Type'),
            __('Street') . ' (' . __('Object Data') . ')',
            __('House') . ' (' . __('Object Data') . ')',
            __('Postcode') . ' (' . __('Object Data') . ')',
            __('City') . ' (' . __('Object Data') . ')',
            __('Object Type') . ' (' . __('Object Data') . ')',
            __('Year of construction') . ' (' . __('Object Data') . ')',
            __('Year of repair') . ' (' . __('Object Data') . ')',
            __('Plot size') . ' (' . __('Object Data') . ')',
            __('Living space') . ' (' . __('Object Data') . ')',
            __('Purchase or build price') . ' (' . __('Object Data') . ')',
            __('Own accumulation') . ' (' . __('Object Data') . ')',
            __('Brokerage fees') . '% (' . __('Object Data') . ')',
        ];
    }

    public function map($row): array
    {
        /** @var Proposal $proposal */
        $proposal = $row;
        $objectType = optional($proposal->objectData)['objectType'];
        $objectType = $objectType ? trans("proposal.objectTypes.$objectType") : '';
        return [
            $proposal->id,
            $proposal->number,
            __("status.{$proposal->status}"),
            optional($proposal->category)->parent->name ?? '',
            __(Str::ucfirst($proposal->gender)),
            $proposal->firstName,
            $proposal->lastName,
            $proposal->phoneNumber,
            $proposal->email,
            $proposal->birthday,
            $proposal->birthplace,
            $proposal->familyStatus,
            optional($proposal->spouse)['firstName'],
            optional($proposal->spouse)['lastName'],
            optional($proposal->spouse)['birthday'],
            optional($proposal->spouse)['birthplace'],
            $proposal->childrenCount,
            $proposal->street,
            $proposal->house,
            $proposal->postcode,
            $proposal->city,
            trans("proposal.residenceTypes.{$proposal->residenceType}"),
            $proposal->rentAmount,
            $proposal->residenceDate,
            optional($proposal->oldAddress)['street'],
            optional($proposal->oldAddress)['house'],
            optional($proposal->oldAddress)['postcode'],
            optional($proposal->oldAddress)['city'],
            optional($proposal->category)->name,
            $proposal->creditComment,
            $proposal->creditAmount,
            $proposal->deadline,
            $proposal->monthlyPayment,
            count(($proposal->otherCredit ?? [])),
            __('Death') . ':' . (optional($proposal->insurance)['death'] ? __('Yes') : __('No')) . PHP_EOL . __('Disease') . ':' . (optional($proposal->insurance)['disease'] ? __('Yes') : __('No')) . PHP_EOL . __('Unemployment') . ':' . (optional($proposal->insurance)['unemployment'] ? __('Yes') : __('No')),
            trans("proposal.applicantTypes.{$proposal->applicantType}"),
            optional($proposal->objectData)['street'],
            optional($proposal->objectData)['house'],
            optional($proposal->objectData)['postcode'],
            optional($proposal->objectData)['city'],
            $objectType,
            optional($proposal->objectData)['yearConstruction'],
            optional($proposal->objectData)['yearRepair'],
            optional($proposal->objectData)['plotSize'],
            optional($proposal->objectData)['livingSpace'],
            optional($proposal->objectData)['buildPrice'],
            optional($proposal->objectData)['accumulation'],
            optional($proposal->objectData)['brokerageFees'],
        ];
    }

    public function query()
    {
        return Proposal::whereId($this->id);
    }
}
