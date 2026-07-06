<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengajuanSurat extends Model
{
    protected $guarded = [];

    protected $casts = [
        'is_read_by_user' => 'boolean',
    ];

    public function warga() {
        return $this->belongsTo(Warga::class);
    }

    public function detailSku() {
        return $this->hasOne(DetailSku::class);
    }

    public function detailSktm() {
        return $this->hasOne(DetailSktm::class);
    }

    public function detailDomisili() {
        return $this->hasOne(DetailDomisili::class);
    }

    public function getDataIsianAttribute()
    {
        $warga = $this->warga;
        if (!$warga) return [];
        $data = $warga->toArray();
        
        if ($this->jenis_surat == 'sku' && $this->detailSku) {
            $data = array_merge($data, $this->detailSku->toArray());
        } elseif (($this->jenis_surat == 'sktm' || $this->jenis_surat == 'sktm-sekolah') && $this->detailSktm) {
            $data = array_merge($data, $this->detailSktm->toArray());
        } elseif ($this->jenis_surat == 'domisili' && $this->detailDomisili) {
            $data = array_merge($data, $this->detailDomisili->toArray());
        }

        if (isset($data['name']) && !isset($data['nama_lengkap'])) {
            $data['nama_lengkap'] = $data['name'];
        }

        return $data;
    }

    public function getDokumenPendukungAttribute()
    {
        $dokumen = [];
        if ($this->jenis_surat == 'sku' && $this->detailSku) {
            $dokumen['dokumen_surat_pengantar_rt_rw'] = $this->detailSku->dokumen_surat_pengantar_rt_rw;
            $dokumen['dokumen_fotokopi_ktp_pemohon'] = $this->detailSku->dokumen_fotokopi_ktp_pemohon;
            $dokumen['dokumen_fotokopi_keluarga_kk'] = $this->detailSku->dokumen_fotokopi_keluarga_kk;
            $dokumen['dokumen_foto_tempat_usaha'] = $this->detailSku->dokumen_foto_tempat_usaha;
        } elseif (($this->jenis_surat == 'sktm' || $this->jenis_surat == 'sktm-sekolah') && $this->detailSktm) {
            $dokumen['dokumen_surat_pengantar_rt_rw'] = $this->detailSktm->dokumen_surat_pengantar_rt_rw;
            $dokumen['dokumen_fotokopi_ktp_pemohon'] = $this->detailSktm->dokumen_fotokopi_ktp_pemohon;
            $dokumen['dokumen_fotokopi_keluarga_kk'] = $this->detailSktm->dokumen_fotokopi_keluarga_kk;
        } elseif ($this->jenis_surat == 'domisili' && $this->detailDomisili) {
            $dokumen['dokumen_surat_pengantar_rt_rw'] = $this->detailDomisili->dokumen_surat_pengantar_rt_rw;
            $dokumen['dokumen_fotokopi_ktp_pemohon'] = $this->detailDomisili->dokumen_fotokopi_ktp_pemohon;
            $dokumen['dokumen_fotokopi_keluarga_kk'] = $this->detailDomisili->dokumen_fotokopi_keluarga_kk;
            $dokumen['dokumen_surat_pernyataan_domisili___pemohon'] = $this->detailDomisili->dokumen_surat_pernyataan_domisili___pemohon;
        }
        return array_filter($dokumen, function($val) { return $val !== null && $val !== ''; });
    }
}
