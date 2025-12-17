<?php

namespace App\Exports;

use App\Models\VolumeHistory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VolumeHistoriesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $start_date;
    protected $end_date;

    public function __construct($start_date, $end_date)
    {
        $this->start_date = $start_date;
        $this->end_date = $end_date;
    }

    public function collection()
    {
        return VolumeHistory::with(['customer', 'employee'])
            ->when($this->start_date && $this->end_date, function ($query) {
                $query->whereBetween('date', [
                    $this->start_date . ' 00:00:00',
                    $this->end_date . ' 23:59:59'
                ]);
            })
            ->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Kode Pelanggan',
            'Nama Pelanggan',
            'Pegawai',
            'Sebelumnya',
            'Pemakaian',
            'Setelah',
            'Catatan'
        ];
    }

    public function map($history): array
    {
        return [
            $history->date,
            $history->customer->code ?? 'N/A',
            $history->customer->name ?? 'N/A',
            $history->employee->name ?? 'N/A',
            $history->before,
            $history->volume,
            $history->after,
            $history->notes ?? ''
        ];
    }
}
