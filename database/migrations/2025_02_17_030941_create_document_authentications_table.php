<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentAuthenticationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('document_authentications', function (Blueprint $table) {
            $table->id();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->string('or_number')->nullable(); // O.R. Number
            $table->string('name_of_heis')->nullable();
            $table->string('program_name')->nullable(); // Do not abbreviate
            $table->string('major')->nullable();        // Do not abbreviate
            $table->date('date_started')->nullable();
            $table->date('date_ended')->nullable();
            $table->year('year_graduated')->nullable();
            $table->integer('units_earned')->nullable(); // For Undergraduate
            $table->text('purpose')->nullable();
            $table->integer('no_of_pcs')->nullable();
            $table->string('special_order')->nullable();
            $table->date('date_applied')->nullable();
            $table->date('date_released')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('document_authentications');
    }
}
