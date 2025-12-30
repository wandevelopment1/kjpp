<?php

namespace App\Support;

use App\Models\Penawaran;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PenawaranPlaceholderResolver
{
    public static function replace(?string $content, Penawaran $penawaran): string
    {
        if (!$content) {
            return '';
        }

        $map = self::map($penawaran);

        return preg_replace_callback('/{{\s*([^}]+)\s*}}/', function ($matches) use ($map, $penawaran) {
            $token = trim($matches[1]);

            if (array_key_exists($token, $map)) {
                return $map[$token];
            }

            return self::resolveDynamicValue($penawaran, $token) ?? '';
        }, $content);
    }

    public static function map(Penawaran $penawaran): array
    {
        $map = self::computedValues($penawaran);

        foreach ($penawaran->getAttributes() as $key => $value) {
            $map[$key] = self::formatValue($value);
        }

        foreach (self::relationMap($penawaran) as $prefix => $relation) {
            if ($relation instanceof Model) {
                foreach ($relation->getAttributes() as $attr => $value) {
                    $map[sprintf('%s.%s', $prefix, $attr)] = self::formatValue($value);
                }

                $name = $relation->getAttribute('name');
                if ($name !== null) {
                    $map[sprintf('%s_name', $prefix)] = self::formatValue($name);
                }
            }
        }

        return array_map(fn ($value) => $value ?? '', $map);
    }

    public static function valueForToken(Penawaran $penawaran, string $token): ?string
    {
        $map = self::map($penawaran);

        if (array_key_exists($token, $map)) {
            return $map[$token];
        }

        return self::resolveDynamicValue($penawaran, $token);
    }

    private static function computedValues(Penawaran $penawaran): array
    {
        $biayaJasa = self::calculateBiayaJasaBreakdown($penawaran);

        return [
            'nama' => $penawaran->nasabah_nama
                ?? $penawaran->kepada_nama
                ?? optional($penawaran->owner)->name
                ?? '',
            'alamat' => $penawaran->nasabah_alamat
                ?? $penawaran->kepada_alamat_pemberi_tugas
                ?? $penawaran->pengguna_laporan_alamat
                ?? '',
            'tanggal' => now()->translatedFormat('d F Y'),
            'no_spk' => $penawaran->kepada_no_spk ?? '',
            'no_lingkup' => $penawaran->kepada_no_lingkup ?? '',
            'tgl_lingkup' => self::formatValue($penawaran->kepada_tgl_lingkup),
            'status_label' => match ($penawaran->status) {
                'acc_1' => 'Project Berjalan',
                'acc_2' => 'Final',
                default => 'Draft 1',
            },
            'status' => $penawaran->status ?? 'draft_1',
            'biaya_jasa_rupiah' => self::formatNumber($biayaJasa['net']),
            'biaya_jasa_ppn_rupiah' => self::formatNumber($biayaJasa['ppn']),
            'biaya_jasa_total_rupiah' => self::formatNumber($biayaJasa['total']),
            'transport_rupiah' => self::formatNumber($penawaran->penilaian_transport_akomodasi),
            'penilaian_transport_akomodasi_rupiah' => self::formatNumber($penawaran->penilaian_transport_akomodasi),
            'ppn_status' => $penawaran->penilaian_ppn_included ? 'Sudah termasuk' : 'Belum termasuk',
            'penilaian_pembayaran_split_label' => $penawaran->penilaian_pembayaran_split ? 'Ya' : 'Tidak',
            'owner_nama' => optional($penawaran->owner)->name ?? '',
            'owner_email' => optional($penawaran->owner)->email ?? '',
        ];
    }

    private static function relationMap(Penawaran $penawaran): array
    {
        return [
            'owner' => $penawaran->owner,
            'penanggung_jawab_company' => $penawaran->penanggungJawabCompany,
            'penanggung_jawab_penanggung_penilai' => $penawaran->penanggungJawabPenanggungPenilai,
            'penanggung_jawab_penilai' => $penawaran->penanggungJawabPenilai,
            'penanggung_jawab_reviewer' => $penawaran->penanggungJawabReviewer,
            'penanggung_jawab_inspeksi' => $penawaran->penanggungJawabInspeksi,
            'pengguna_laporan_pt' => $penawaran->penggunaLaporanPt,
            'pengguna_laporan_nama' => $penawaran->penggunaLaporanNama,
            'pengguna_laporan_jenis_pengguna' => $penawaran->penggunaLaporanJenisPengguna,
            'pengguna_laporan_jenis_industri' => $penawaran->penggunaLaporanJenisIndustri,
            'kepada_kab_kota' => $penawaran->kepadaKabKota,
            'kepada_provinsi' => $penawaran->kepadaProvinsi,
            'nasabah_kab_kota' => $penawaran->nasabahKabKota,
            'nasabah_provinsi' => $penawaran->nasabahProvinsi,
            'status_kepemilikan' => $penawaran->statusKepemilikan,
            'bidang_usaha' => $penawaran->bidangUsaha,
            'penilaian_tujuan' => $penawaran->penilaianTujuan,
            'penilaian_jenis_laporan' => $penawaran->penilaianJenisLaporan,
            'penilaian_nilai' => $penawaran->penilaianNilai,
            'penilaian_jenis_jasa' => $penawaran->penilaianJenisJasa,
            'penilaian_tipe_properti' => $penawaran->penilaianTipeProperti,
            'penilaian_pendekatan_penilaian' => $penawaran->penilaianPendekatan,
            'penilaian_metode_penilaian' => $penawaran->penilaianMetode,
            'obyek_penilaian_obyek' => $penawaran->obyekPenilaianObyek,
            'obyek_penilaian_kepemilikan' => $penawaran->obyekPenilaianKepemilikan,
            'obyek_penilaian_kab_kota' => $penawaran->obyekPenilaianKabKota,
            'obyek_penilaian_provinsi' => $penawaran->obyekPenilaianProvinsi,
            'obyek_penilaian_tipe_properti' => $penawaran->obyekPenilaianTipeProperti,
        ];
    }

    private static function resolveDynamicValue(Penawaran $penawaran, string $path): ?string
    {
        $normalized = str_replace(['-', ' '], '_', $path);
        $value = data_get($penawaran, $normalized);

        return self::formatValue($value);
    }

    private static function formatValue($value): ?string
    {
        if ($value instanceof Carbon) {
            return $value->translatedFormat('d F Y');
        }

        if (is_bool($value)) {
            return $value ? 'Ya' : 'Tidak';
        }

        if (is_numeric($value)) {
            return (string) $value;
        }

        if (is_array($value)) {
            return implode(', ', array_filter($value));
        }

        if ($value === null) {
            return null;
        }

        return (string) $value;
    }

    private static function formatNumber($value): string
    {
        return $value !== null
            ? number_format((float) $value, 0, ',', '.')
            : '';
    }

    private static function calculateBiayaJasaBreakdown(Penawaran $penawaran): array
    {
        $amount = (float) ($penawaran->penilaian_biaya_jasa ?? 0);
        $ppnRate = 0.11;

        if ($amount <= 0) {
            return ['net' => 0.0, 'ppn' => 0.0, 'total' => 0.0];
        }

        if ($penawaran->penilaian_ppn_included) {
            $total = $amount;
            $net = $total / (1 + $ppnRate);
            $ppn = $total - $net;
        } else {
            $net = $amount;
            $ppn = $net * $ppnRate;
            $total = $net + $ppn;
        }

        return [
            'net' => round($net),
            'ppn' => round($ppn),
            'total' => round($total),
        ];
    }
}
