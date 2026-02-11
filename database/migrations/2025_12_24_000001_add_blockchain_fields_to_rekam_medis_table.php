<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            // Kolom-kolom yang sudah dipakai di controller tapi belum ada di migration lama
            if (!Schema::hasColumn('rekam_medis', 'pasien_id')) {
                $table->foreignId('pasien_id')
                    ->nullable()
                    ->after('pendaftaran_id')
                    ->constrained('users')
                    ->nullOnDelete();
            }

            if (!Schema::hasColumn('rekam_medis', 'tanggal')) {
                $table->date('tanggal')->nullable()->after('dokter_id');
            }

            if (!Schema::hasColumn('rekam_medis', 'resep')) {
                $table->text('resep')->nullable()->after('tindakan');
            }

            // Blockchain / hash-chain
            if (!Schema::hasColumn('rekam_medis', 'chain_index')) {
                $table->unsignedInteger('chain_index')->nullable()->after('catatan');
            }
            if (!Schema::hasColumn('rekam_medis', 'prev_hash')) {
                $table->string('prev_hash', 64)->nullable()->after('chain_index');
            }
            if (!Schema::hasColumn('rekam_medis', 'block_hash')) {
                $table->string('block_hash', 64)->nullable()->after('prev_hash');
            }
        });

        // Index (best effort)
        try {
            Schema::table('rekam_medis', function (Blueprint $table) {
                if (Schema::hasColumn('rekam_medis', 'pasien_id')) {
                    $table->index('pasien_id');
                }
                if (Schema::hasColumn('rekam_medis', 'chain_index')) {
                    $table->index(['pasien_id', 'chain_index']);
                }
            });
        } catch (\Throwable $e) {
            Log::warning('Index create failed (rekam_medis blockchain)', ['e' => $e->getMessage()]);
        }

        // Backfill chain untuk data lama (best effort, jangan bikin migration gagal)
        try {
            app(\App\Services\RekamMedisChainService::class)->backfillAll();
        } catch (\Throwable $e) {
            Log::warning('Backfill chain failed (migration)', ['e' => $e->getMessage()]);
        }
    }

    public function down(): void
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            // Drop indexes dulu jika ada
            try { $table->dropIndex(['pasien_id']); } catch (\Throwable $e) {}
            try { $table->dropIndex(['rekam_medis_pasien_id_chain_index_index']); } catch (\Throwable $e) {}

            if (Schema::hasColumn('rekam_medis', 'block_hash')) {
                $table->dropColumn('block_hash');
            }
            if (Schema::hasColumn('rekam_medis', 'prev_hash')) {
                $table->dropColumn('prev_hash');
            }
            if (Schema::hasColumn('rekam_medis', 'chain_index')) {
                $table->dropColumn('chain_index');
            }
            if (Schema::hasColumn('rekam_medis', 'resep')) {
                $table->dropColumn('resep');
            }
            if (Schema::hasColumn('rekam_medis', 'tanggal')) {
                $table->dropColumn('tanggal');
            }
            if (Schema::hasColumn('rekam_medis', 'pasien_id')) {
                $table->dropConstrainedForeignId('pasien_id');
            }
        });
    }
};
