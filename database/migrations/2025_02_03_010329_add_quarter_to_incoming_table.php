<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddQuarterToIncomingTable extends Migration
{
    public function up()
    {
        Schema::table('incoming', function (Blueprint $table) {
            // Add an unsigned tiny integer column for the quarter (values 1-4)
            $table->unsignedTinyInteger('quarter')->nullable()->after('date_released');
        });
    }

    public function down()
    {
        Schema::table('incoming', function (Blueprint $table) {
            $table->dropColumn('quarter');
        });
    }
}
