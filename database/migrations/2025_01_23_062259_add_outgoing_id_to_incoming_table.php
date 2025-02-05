<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOutgoingIdToIncomingTable extends Migration
{
    public function up()
    {
        Schema::table('incoming', function (Blueprint $table) {
            $table->unsignedBigInteger('outgoing_id')->nullable()->after('id'); 
            $table->foreign('outgoing_id')->references('id')->on('outgoings')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('incoming', function (Blueprint $table) {
            $table->dropForeign(['outgoing_id']);
            $table->dropColumn('outgoing_id');
        });
    }
}
