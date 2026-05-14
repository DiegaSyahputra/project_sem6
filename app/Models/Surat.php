<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Notifications\Notifiable;


class Surat extends Model
{
    protected $table = 'surat';

    // protected $table = 'surat';
    use HasFactory, Notifiable;
    protected $fillable = [
        'mahasiswa_id',
        'jenis',
        'tgl',
        'foto_surat',
        'keterangan',
        'status',
        'catatan_konfirmator',
        'dikonfirmasi_oleh',
        'dikonfirmasi_at',
    ];

    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class, 'mahasiswa_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh', 'id');
    }

}
