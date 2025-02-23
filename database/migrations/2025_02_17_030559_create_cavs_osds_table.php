<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCavsOsdsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('cavs_osd', function (Blueprint $table) {
            $table->id();
            $table->string('quarter')->nullable();
            $table->string('o')->nullable();         // e.g., O-
            $table->string('seq')->nullable();
            $table->string('cav_osds')->nullable();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->enum('sex', ['Male', 'Female'])->nullable();
            $table->string('institution_code')->nullable();
            $table->string('full_name_of_hei')->nullable();
            $table->string('address_of_hei')->nullable();
            $table->string('type_of_heis')->nullable();
            $table->string('discipline_code')->nullable();
            $table->string('program_name')->nullable();
            $table->string('major')->nullable();
            $table->string('program_level')->nullable();
            $table->string('status_of_the_program')->nullable();
            $table->date('date_started')->nullable();
            $table->date('date_ended')->nullable();
            $table->date('graduation_date')->nullable();
            $table->integer('units_earned')->nullable();
            $table->string('special_order_no')->nullable();
            $table->date('date_applied')->nullable();
            $table->date('date_released')->nullable();
            $table->text('purpose_of_cav')->nullable();
            $table->string('target_country')->nullable();
            // Additional fields for academic period:
            $table->string('semester')->nullable();
            $table->string('academic_year')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('cavs_osd');
    }
}
