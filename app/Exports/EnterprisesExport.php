<?php

namespace App\Exports;

use App\Models\Enterprises;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EnterprisesExport implements FromQuery
{
    public function headings(): array
    {
        return [
            '#',
            'User',
            'Date',
        ];
    }
    
    public function query()
    {
        return Enterprises::query();
    }
}
