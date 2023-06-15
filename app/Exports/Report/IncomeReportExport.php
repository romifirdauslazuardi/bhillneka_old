<?php

namespace App\Exports\Report;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Auth;

class IncomeReportExport implements FromCollection, WithHeadings, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return $this->data;
    }

    public function headings(): array
    {
        $data = [];
        $data[] = "No";
        $data[] = "Kode Transaksi";
        $data[] = "Pendapatan Agen";

        if(Auth::user()->hasRole([\App\Enums\RoleEnum::OWNER])){
            $data[] = "Pendapatan Owner";
            $data[] = "Biaya Penanganan";
        }
        else{
            $data[] = "Jasa Aplikasi & Layanan";
        }

        $data[] = "Total Transaksi";
        $data[] = "Status";
        $data[] = "Tanggal Dibuat";

        return $data;
    }
}
