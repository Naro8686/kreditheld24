<?php

namespace App\Exports;

use App\Models\Proposal;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProposalsExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Proposal::all();
    }
}
