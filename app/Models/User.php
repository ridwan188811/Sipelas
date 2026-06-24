<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name', 'email', 'password', 'role', 'jabatan', 'no_hp',
    'nik', 'tempat_lahir', 'tanggal_lahir', 'jenis_kelamin',
    'kewarganegaraan', 'agama', 'pekerjaan', 'status_pernikahan',
    'alamat_lengkap', 'rt', 'rw', 'kelurahan', 'kecamatan', 'kota'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Send custom email verification notification in Bahasa Indonesia.
     */
    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifikasiEmailNotification());
    }

    /**
     * Check if the user's profile is complete.
     */
    public function isProfileComplete(): bool
    {
        return !empty($this->nik) &&
               !empty($this->name) &&
               !empty($this->tempat_lahir) &&
               !empty($this->tanggal_lahir) &&
               !empty($this->jenis_kelamin) &&
               !empty($this->kewarganegaraan) &&
               !empty($this->agama) &&
               !empty($this->pekerjaan) &&
               !empty($this->status_pernikahan) &&
               !empty($this->alamat_lengkap) &&
               !empty($this->rt) &&
               !empty($this->rw) &&
               !empty($this->kelurahan) &&
               !empty($this->kecamatan) &&
               !empty($this->kota);
    }
}
