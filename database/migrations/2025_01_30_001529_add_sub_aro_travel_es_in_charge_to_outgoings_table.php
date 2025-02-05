<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubAroTravelEsInChargeToOutgoingsTable extends Migration
{
    public function up()
    {
        Schema::table('outgoings', function (Blueprint $table) {
            // Add new columns only if they don't already exist
            // 1) sub_aro_2024_30 as a string (could be text, depending on your needs)
            if (!Schema::hasColumn('outgoings', 'sub_aro_2024_30')) {
                $table->string('sub_aro_2024_30')->nullable()->after('chedrix_2025');
            }

            // 2) travel_date (date or datetime, depending on your needs)
            if (!Schema::hasColumn('outgoings', 'travel_date')) {
                $table->string('travel_date')->nullable()->after('o');
            }

            // 3) es_in_charge (string or text, depending on the length you expect)
            if (!Schema::hasColumn('outgoings', 'es_in_charge')) {
                $table->string('es_in_charge')->nullable()->after('travel_date');
            }
        });
    }

    public function down()
    {
        Schema::table('outgoings', function (Blueprint $table) {
            // Drop the columns if they exist
            if (Schema::hasColumn('outgoings', 'sub_aro_2024_30')) {
                $table->dropColumn('sub_aro_2024_30');
            }
            if (Schema::hasColumn('outgoings', 'travel_date')) {
                $table->dropColumn('travel_date');
            }
            if (Schema::hasColumn('outgoings', 'es_in_charge')) {
                $table->dropColumn('es_in_charge');
            }
        });
    }
}
