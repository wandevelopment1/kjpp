<?php

namespace App\Providers;

use App\Models\PenawaranTemplateFile;
use App\Notifications\PenawaranApproved;
use Livewire\Livewire;
use App\Models\Service;
use App\Models\UiConfigGroup;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\Penawaran;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use App\Support\PenawaranApprovalMatrix;
use Illuminate\Auth\Notifications\ResetPassword;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    public function boot(): void
    {
        // $this->registerPolicies();

         Livewire::setUpdateRoute(function ($handle) {
            return \Illuminate\Support\Facades\Route::post('/test/livewire/update', $handle);
        });

        Livewire::setScriptRoute(function ($handle) {
            return \Illuminate\Support\Facades\Route::get('/test/livewire/livewire.js', $handle);
        });

        ResetPassword::createUrlUsing(function ($notifiable, string $token) {
        

            // Jika user dari tabel users (admin)
            if ($notifiable instanceof \App\Models\User) {
                return route('admin.password.reset', [
                    'token' => $token,
                    'email' => $notifiable->getEmailForPasswordReset(),
                ]);
            }

            // fallback (jaga-jaga)
            return url('/reset-password/' . $token . '?email=' . urlencode($notifiable->getEmailForPasswordReset()));
        });

        View::composer('components.admin.navbar', function ($view) {
            $pendingPenawaranApprovals = collect();
            $recentApprovedPenawaran = collect();

            if (Auth::check()) {
                $user = Auth::user();

                if ($user->can('admin.penawaran.approval')) {
                    $pendingPenawaranApprovals = Penawaran::where('status', 'draft_1')
                        ->latest()
                        ->take(20)
                        ->get()
                        ->filter(fn ($penawaran) => PenawaranApprovalMatrix::userCanApprovePenawaran($user, $penawaran))
                        ->take(5)
                        ->values();
                } else {
                    $recentApprovedPenawaran = Penawaran::where('user_id', $user->id)
                        ->where('status', 'acc_1')
                        ->latest('approved_at')
                        ->take(5)
                        ->get();
                }
            }

            $view->with([
                'pendingPenawaranApprovals' => $pendingPenawaranApprovals,
                'recentApprovedPenawaran' => $recentApprovedPenawaran,
            ]);
        });

        View::composer('components.admin.toast', function ($view) {
            $penawaranApprovedNotifications = collect();

            if (Auth::check()) {
                $user = Auth::user();
                $penawaranApprovedNotifications = $user->unreadNotifications()
                    ->where('type', PenawaranApproved::class)
                    ->get();

                if ($penawaranApprovedNotifications->isNotEmpty()) {
                    $user->unreadNotifications()
                        ->whereIn('id', $penawaranApprovedNotifications->pluck('id'))
                        ->update(['read_at' => now()]);
                }
            }

            $view->with('penawaranApprovedNotifications', $penawaranApprovedNotifications);
        });
    }
}
