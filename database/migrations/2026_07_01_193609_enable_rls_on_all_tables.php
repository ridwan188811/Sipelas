<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Peringatan Keamanan Supabase: Tabel dapat diakses publik tanpa RLS.
        // Migrasi ini akan mengaktifkan RLS untuk semua tabel jika database driver adalah PostgreSQL.
        if (DB::connection()->getDriverName() === 'pgsql') {
            $tables = [
                'wargas',
                'password_reset_tokens',
                'sessions',
                'cache',
                'cache_locks',
                'jobs',
                'job_batches',
                'failed_jobs',
                'pengajuan_surats',
                'admins',
                'detail_skus',
                'detail_domisilis',
                'detail_sktms'
            ];
            
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::statement("ALTER TABLE {$table} ENABLE ROW LEVEL SECURITY;");
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Membatalkan RLS jika rollback
        if (DB::connection()->getDriverName() === 'pgsql') {
            $tables = [
                'wargas',
                'password_reset_tokens',
                'sessions',
                'cache',
                'cache_locks',
                'jobs',
                'job_batches',
                'failed_jobs',
                'pengajuan_surats',
                'admins',
                'detail_skus',
                'detail_domisilis',
                'detail_sktms'
            ];
            
            foreach ($tables as $table) {
                if (Schema::hasTable($table)) {
                    DB::statement("ALTER TABLE {$table} DISABLE ROW LEVEL SECURITY;");
                }
            }
        }
    }
};
