<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->string('nama_pelapor')->after('user_id');
            $table->string('no_telp', 20)->after('nama_pelapor');
        });
    }

    
    public function down(): void
    {
        Schema::table('laporan', function (Blueprint $table) {
            $table->dropColumn(['nama_pelapor', 'no_telp']);
        });
    }
};