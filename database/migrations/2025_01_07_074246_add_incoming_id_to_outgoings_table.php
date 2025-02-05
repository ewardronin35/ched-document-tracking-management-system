<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIncomingIdToOutgoingsTable extends Migration
{
    public function up()
    {
        Schema::table('outgoings', function (Blueprint $table) {
            $table->unsignedBigInteger('incoming_id')->nullable()->after('id'); 
            $table->foreign('incoming_id')->references('id')->on('incoming')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('outgoings', function (Blueprint $table) {
            $table->dropForeign(['incoming_id']);
            $table->dropColumn('incoming_id');
        });
    }
}
