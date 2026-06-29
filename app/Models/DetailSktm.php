<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailSktm extends Model
{
    protected $guarded = [];
    public function pengajuanSurat() {
        return $this->belongsTo(PengajuanSurat::class);
    }
}
