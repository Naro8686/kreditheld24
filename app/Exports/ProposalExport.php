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

class ProposalExport implements ShouldAutoSize, FromQuery, WithHeadings, WithMapping
{

    public int $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

//{data: 'id', name: 'id'},
//{data: 'category.parent.name', name: 'category.parent.name', searchable: false},
//{data: 'category.name', name: 'category.name'},
//{data: 'number', name: 'number'},
//{data: 'user.email', name: 'user.email'},
//{data: 'creditAmount', name: 'creditAmount'},
//{data: 'created_at', name: 'created_at'},
//{data: 'status', name: 'status'},
//{data: 'payoutAmount', name: 'payoutAmount', orderable: false, searchable: false},
//{data: 'deadline', name: 'deadline'},
//{data: 'birthday', name: 'birthday'},
//{data: 'email', name: 'email'},
//    public function headings(): array
//    {
//        return [
//            '#'
//        ];
//    }

    public function map($proposal): array
    {
//        dd(ProposalResource::make($proposal)->toArray(request()));
        return ProposalResource::make($proposal)->toArray(request());
    }

    public function query()
    {
        return Proposal::whereId($this->id);
    }

    public function prepareRows($rows)
    {
        return $rows->transform(function ($proposal) {
            $proposal->id .= ' (prepared)';
            return $proposal;
        });
    }

    public function headings(): array
    {
        return [
            '#'
        ];
    }
}
