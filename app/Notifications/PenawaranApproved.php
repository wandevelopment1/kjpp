<?php

namespace App\Notifications;

use App\Models\Penawaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PenawaranApproved extends Notification
{
    use Queueable;

    public function __construct(public Penawaran $penawaran)
    {
        //
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'penawaran_id' => $this->penawaran->id,
            'no_spk' => $this->penawaran->kepada_no_spk,
            'nasabah' => $this->penawaran->nasabah_nama ?? $this->penawaran->kepada_nama,
            'message' => 'Penawaran dengan No. SPK ' . ($this->penawaran->kepada_no_spk ?? '-') . ' telah disetujui menjadi ACC 1.',
            'approved_at' => optional($this->penawaran->approved_at)->toDateTimeString(),
        ];
    }
}
