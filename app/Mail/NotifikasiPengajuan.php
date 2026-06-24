<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\PengajuanSurat;

class NotifikasiPengajuan extends Mailable
{
    use Queueable, SerializesModels;

    public $pengajuan;
    public $jenisNotif;

    /**
     * Create a new message instance.
     */
    public function __construct(PengajuanSurat $pengajuan, $jenisNotif)
    {
        $this->pengajuan = $pengajuan;
        $this->jenisNotif = $jenisNotif; // 'menunggu', 'disetujui', 'ditolak'
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = 'Notifikasi Pengajuan Surat';
        
        $jenisSuratMap = [
            'sku' => 'Surat Keterangan Usaha',
            'sktm' => 'Surat Keterangan Tidak Mampu',
            'sktm-sekolah' => 'Surat Keterangan Tidak Mampu (Sekolah)',
            'domisili' => 'Surat Keterangan Domisili',
            'belum-menikah' => 'Surat Keterangan Belum Menikah',
            'kelahiran' => 'Surat Keterangan Kelahiran',
            'kematian' => 'Surat Keterangan Kematian',
            'pengantar-nikah' => 'Surat Pengantar Nikah',
            'pindah' => 'Surat Keterangan Pindah'
        ];
        
        $jenis = $jenisSuratMap[$this->pengajuan->jenis_surat] ?? ucwords(str_replace('-', ' ', $this->pengajuan->jenis_surat));

        if ($this->jenisNotif === 'menunggu') {
            $subject = "Pengajuan Diterima: $jenis";
        } elseif ($this->jenisNotif === 'disetujui') {
            $subject = "Pengajuan Disetujui: $jenis";
        } elseif ($this->jenisNotif === 'ditolak') {
            $subject = "Pengajuan Ditolak: $jenis";
        }

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.notifikasi-pengajuan',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
