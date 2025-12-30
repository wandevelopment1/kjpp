<?php

// app/Exports/FakturExport.php
namespace App\Exports;

use App\Models\Faktur;
use App\Services\UiConfigService;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\BeforeSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class FakturExport implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize, WithEvents
{
    protected $fakturs;

    public function __construct($fakturs)
    {
        $this->fakturs = $fakturs;
    }

    public function collection()
    {
        return $this->fakturs->map(function($item, $index) {
            return [
                $index + 1, // Baris hitung dari awal
                optional($item->faktur_date)->format('Y-m-d'),
                $item->jenis_faktur,
                $item->kode_transaksi,
                $item->keterangan_tambahan,
                $item->dokumen_pendukung,
                $item->periode_dokumen_pendukung,
                $item->referensi,
                $item->cap_fasilitas,
                $item->id_tku_penjual,
                $item->id_pembeli,
                $item->jenis_id_pembeli,
                $item->negara_id_pembeli,
                $item->nomor_dokumen_pembeli,
                $item->nama_pembeli,
                $item->alamat_pembeli,
                $item->email_pembeli,
                $item->id_tku_pembeli,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Baris', 'Tanggal Faktur', 'Jenis Faktur', 'Kode Transaksi', 'Keterangan Tambahan', 'Dokumen Pendukung', 'Periode Dokumen Pendukung', 'Referensi', 'Cap Fasilitas', 'ID TKU Penjual', 'NPWP/NIK Pembeli', 'Jenis ID Pembeli', 'Negara ID Pembeli', 'Nomor Dokumen Pembeli', 'Nama Pembeli', 'Alamat Pembeli', 'Email Pembeli', 'ID TKU Pembeli',
        ];
    }

    public function startCell(): string
    {
        return 'A3'; // Mulai dari baris 3, baris 1 dan 2 bisa diisi NPWP
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $uiConfigService = app(UiConfigService::class);
                $npwp = $uiConfigService->getValueByGroupSlugAndKey('npwp-penjual', 'npwp_penjual') ?? '0023566599413000';

                // Judul "NPWP Penjual" mengambil 2 kolom (A1:B1), isinya di C1
                $event->sheet->setCellValue('A1', 'NPWP Penjual');
                $event->sheet->mergeCells('A1:B1');
                $event->sheet->setCellValue('C1', $npwp);

                $event->sheet->setCellValue('A2', ''); // Baris kosong sebagai pemisah
            },
             AfterSheet::class => function(AfterSheet $event) {
                // Tambahkan teks "END" di kolom 1 baris terakhir
                $lastRow = $event->sheet->getHighestRow();
                $event->sheet->setCellValue('A' . ($lastRow + 1), 'END');
            },
        ];
    }

    public function title(): string
    {
        return 'Faktur';
    }
}
