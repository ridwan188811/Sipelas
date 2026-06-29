<?php
namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Warga extends Authenticatable implements MustVerifyEmail
{
    use Notifiable;
    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];
    protected function casts(): array {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }
    
    public function pengajuanSurats() {
        return $this->hasMany(PengajuanSurat::class);
    }
    
    public function sendEmailVerificationNotification(): void {
        $this->notify(new \App\Notifications\VerifikasiEmailNotification());
    }
    
    public function isProfileComplete(): bool {
        return !empty($this->nik) && !empty($this->name) &&
               !empty($this->tempat_lahir) && !empty($this->tanggal_lahir) &&
               !empty($this->jenis_kelamin) && !empty($this->kewarganegaraan) &&
               !empty($this->agama) && !empty($this->pekerjaan) &&
               !empty($this->status_pernikahan) && !empty($this->alamat_lengkap) &&
               !empty($this->rt) && !empty($this->rw) &&
               !empty($this->kelurahan) && !empty($this->kecamatan) && !empty($this->kota);
    }
}
