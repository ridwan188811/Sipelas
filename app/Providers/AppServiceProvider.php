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
            if (Auth::guard('admin')->check()) {
                $notifList = PengajuanSurat::with('warga')->where('status', 'menunggu')->orderBy('created_at', 'desc')->take(5)->get();
                $notifCount = PengajuanSurat::where('status', 'menunggu')->where('is_read_by_admin', false)->count();
                $view->with('globalNotifList', $notifList)->with('globalNotifCount', $notifCount);
            } elseif (Auth::guard('warga')->check()) {
                $warga = Auth::guard('warga')->user();
                $notifList = PengajuanSurat::where('warga_id', $warga->id)
                    ->whereIn('status', ['disetujui', 'ditolak'])
                    ->orderBy('updated_at', 'desc')
                    ->take(5)
                    ->get();
                $notifCount = PengajuanSurat::where('warga_id', $warga->id)
                    ->whereIn('status', ['disetujui', 'ditolak'])
                    ->where('is_read_by_user', false)
                    ->count();
                $view->with('globalNotifList', $notifList)->with('globalNotifCount', $notifCount);
            }
        });
    }
}
