<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('incoming', function (Blueprint $table) {
            $table->string('chedrix_2025')->nullable()->after('reference_number');
            $table->string('e,m/zc,m/pag')->nullable()->after('chedrix_2025');
            $table->string('NO')->nullable()->after('e,m/zc,m/pag');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('incoming', function (Blueprint $table) {
            //
        });
    }
};
