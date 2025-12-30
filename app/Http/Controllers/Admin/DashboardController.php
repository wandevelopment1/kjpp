<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penawaran;
use App\Models\TahapAkhirUpload;
use App\Support\PenawaranApprovalMatrix;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('can:admin.dashboard.index', ['only' => ['index']]);
    }

    public function index()
    {
        $user = request()->user();
        $canViewAll = PenawaranApprovalMatrix::canViewAllPenawaran($user);

        $baseQuery = PenawaranApprovalMatrix::applyVisibilityScope(Penawaran::query(), $user);

        $totalPenawaran = (clone $baseQuery)->count();
        $draftPenawaran = (clone $baseQuery)->where('status', 'draft_1')->count();
        $acc1Penawaran = (clone $baseQuery)->where('status', 'acc_1')->count();
        $acc2Penawaran = (clone $baseQuery)->where('status', 'acc_2')->count();

        $recentPenawaran = (clone $baseQuery)
            ->latest()
            ->take(5)
            ->get(['id', 'kepada_nama', 'status', 'created_at']);

        $finalUploads = TahapAkhirUpload::count();
        $latestUploadAt = TahapAkhirUpload::latest('created_at')->value('created_at');

        return view('admin.dashboard.index', [
            'stats' => [
                [
                    'label' => 'Total Penawaran',
                    'value' => $totalPenawaran,
                    'trend' => $totalPenawaran ? '+' . $totalPenawaran : '+0',
                    'icon' => 'mdi:file-document-edit-outline',
                    'accent' => 'bg-brand-50 text-brand-600'
                ],
                [
                    'label' => 'Draft 1',
                    'value' => $draftPenawaran,
                    'trend' => $draftPenawaran ? $draftPenawaran . ' aktif' : 'kosong',
                    'icon' => 'mdi:progress-clock',
                    'accent' => 'bg-warning-50 text-warning-600'
                ],
                [
                    'label' => 'Project Berjalan',
                    'value' => $acc1Penawaran,
                    'trend' => $acc1Penawaran ? $acc1Penawaran . ' ACC 1' : 'Belum ada',
                    'icon' => 'mdi:rocket-launch-outline',
                    'accent' => 'bg-info-50 text-info-600'
                ],
                [
                    'label' => 'Final',
                    'value' => $acc2Penawaran,
                    'trend' => $acc2Penawaran ? $acc2Penawaran . ' ACC 2' : 'Belum ada',
                    'icon' => 'mdi:check-decagram-outline',
                    'accent' => 'bg-success-50 text-success-600'
                ],
                [
                    'label' => 'File Tahap Akhir',
                    'value' => $finalUploads,
                    'trend' => $latestUploadAt
                        ? 'Terakhir ' . Carbon::parse($latestUploadAt)->diffForHumans()
                        : 'Belum pernah',
                    'icon' => 'mdi:file-upload-outline',
                    'accent' => 'bg-primary-50 text-primary-600'
                ],
            ],
            'recentPenawaran' => $recentPenawaran,
            'dashboardScopeLabel' => $canViewAll
                ? null
                : 'Menampilkan penawaran sesuai role/approver yang Anda miliki.',
        ]);
    }
}
