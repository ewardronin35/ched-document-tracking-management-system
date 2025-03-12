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
        Schema::create('cav_abroads', function (Blueprint $table) {
            $table->id();
            $table->string('quarter')->nullable();
            $table->string('cav_no')->nullable();
            $table->string('region')->nullable();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('sex')->nullable();
            $table->string('institution_code')->nullable();
            $table->string('full_name_of_hei')->nullable();
            $table->string('address_of_hei')->nullable();
            $table->string('official_receipt_number')->nullable();
            $table->string('type_of_heis')->nullable();
            $table->string('discipline_code')->nullable();
            $table->string('program_name')->nullable();
            $table->string('major')->nullable();
            $table->string('program_level')->nullable();
            $table->string('status_of_the_program')->nullable();
            $table->string('date_started')->nullable();
            $table->string('date_ended')->nullable();
            $table->date('graduation_date')->nullable();
            $table->integer('units_earned')->nullable();
            $table->string('special_order_no')->nullable();
            $table->string('series')->nullable();
            $table->date('date_applied')->nullable();
            $table->date('date_released')->nullable();
            $table->string('airway_bill_no')->nullable();
            $table->string('serial_number_of_security_paper')->nullable();
            $table->string('purpose_of_cav')->nullable();
            $table->string('target_country')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cav_abroads');
    }
};
