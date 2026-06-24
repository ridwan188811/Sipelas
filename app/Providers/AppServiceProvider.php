<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\PengajuanSurat;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

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
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable, string $url) {
            return (new MailMessage)
                ->subject('Aktivasi Akun SIPELAS Anda')
                ->view('emails.verify-email', ['url' => $url, 'user' => $notifiable]);
        });

        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                if ($user->role == 'admin') {
                    $notifList = PengajuanSurat::with('user')->where('status', 'menunggu')->orderBy('created_at', 'desc')->take(5)->get();
                    $notifCount = PengajuanSurat::where('status', 'menunggu')->where('is_read_by_admin', false)->count();
                } else {
                    $notifList = PengajuanSurat::where('user_id', $user->id)
                        ->whereIn('status', ['disetujui', 'ditolak'])
                        ->orderBy('updated_at', 'desc')
                        ->take(5)
                        ->get();
                    $notifCount = PengajuanSurat::where('user_id', $user->id)
                        ->whereIn('status', ['disetujui', 'ditolak'])
                        ->where('is_read_by_user', false)
                        ->count();
                }
                $view->with('globalNotifList', $notifList)->with('globalNotifCount', $notifCount);
            }
        });
    }
}
