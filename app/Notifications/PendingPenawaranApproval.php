<?php

namespace App\Notifications;

use App\Models\Penawaran;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class PendingPenawaranApproval extends Notification
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
            'message' => 'Penawaran baru membutuhkan ACC.',
            'created_at' => now()->toDateTimeString(),
        ];
    }
}
