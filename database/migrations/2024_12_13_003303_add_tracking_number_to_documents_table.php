<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTrackingNumberToDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::table('documents', function (Blueprint $table) {
            $table->string('tracking_number', 12)->unique()->after('document_id');
        });
    }
    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    { 
        Schema::table('documents', function (Blueprint $table) {
            $table->dropColumn('tracking_number');
        });
    }
}
