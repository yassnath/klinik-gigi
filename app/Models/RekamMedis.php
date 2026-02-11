<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekamMedis extends Model
{
    use HasFactory;

    protected $table = 'rekam_medis';

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'dokter_id',
        'tanggal',
        'diagnosa',
        'tindakan',
        'resep',
        'catatan',
        // blockchain (hash-chain)
        'chain_index',
        'prev_hash',
        'block_hash',
    ];

    protected static function booted()
    {
        static::creating(function (RekamMedis $rm) {
            // Assign hash-chain fields (best effort, production safe)
            try {
                app(\App\Services\RekamMedisChainService::class)->assignForNewRecord($rm);
            } catch (\Throwable $e) {
                // Jangan gagalkan penyimpanan rekam medis
                // (chain bisa di-backfill belakangan)
            }
        });
    }

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class, 'pendaftaran_id');
    }

    // Asumsi dokter_id mengarah ke users.id (dokter adalah user dengan role dokter)
    public function dokter()
    {
        return $this->belongsTo(User::class, 'dokter_id');
    }

    public function pasien()
    {
        return $this->belongsTo(User::class, 'pasien_id');
    }
}
