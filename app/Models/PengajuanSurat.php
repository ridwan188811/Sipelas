<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'data_isian' => 'array',
        'dokumen_pendukung' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
