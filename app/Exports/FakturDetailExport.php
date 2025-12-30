<?php

namespace App\Exports;

use App\Models\Faktur;
use App\Models\FakturDetail;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FakturDetailExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    protected $fakturs;

    public function __construct($fakturs)
    {
        $this->fakturs = $fakturs;
    }

    public function collection()
    {
        // Gunakan $this->fakturs yang sudah terfilter untuk membuat mapping nomor baris
        $fakturBaris = $this->fakturs->pluck('id')->flip()->map(fn($i) => $i + 1);

        return FakturDetail::whereIn('faktur_id', $this->fakturs->pluck('id'))
            ->orderBy('faktur_id')
            ->get()
            ->map(function ($item) use ($fakturBaris) {
                return [
                    // Ambil nomor baris berdasarkan faktur_id
                    $fakturBaris[$item->faktur_id] ?? null,
                    $item->barang_jasa,
                    $item->kode_barang_jasa,
                    $item->nama_barang_jasa,
                    $item->nama_satuan_ukur,
                    $item->harga_satuan,
                    $item->jumlah_barang_jasa,
                    $item->total_diskon,
                    $item->dpp,
                    $item->dpp_nilai_lain,
                    $item->tarif_ppn,
                    $item->ppn,
                    $item->tarif_ppnbm,
                    $item->ppnbm,
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Baris', 'Barang/Jasa', 'Kode Barang Jasa', 'Nama Barang/Jasa',
            'Nama Satuan Ukur', 'Harga Satuan', 'Jumlah Barang Jasa', 'Total Diskon', 'DPP',
            'DPP Nilai Lain', 'Tarif PPN', 'PPN', 'Tarif PPnBM', 'PPnBM',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Tambahkan teks "END" di kolom 1 baris terakhir
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->setCellValue('A' . ($lastRow + 1), 'END');
            },
        ];
    }

    public function title(): string
    {
        return 'DetailFaktur';
    }
}
