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
        Schema::create('so_master_lists', function (Blueprint $table) {
            $table->id(); // Primary Key: ID
            $table->string('hei_name')->nullable();
            $table->string('hei_uii')->nullable();
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable(); // II, IV, Jr., Sr., etc.
            $table->enum('sex', ['Male', 'Female', 'Other']);
            
            // Foreign Keys
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->foreignId('major_id')->constrained()->onDelete('cascade');
            
            // Academic Details
            $table->date('started')->nullable();
            $table->date('ended')->nullable();
            $table->string('academic_year')->nullable(); // e.g., 2023-2024
            
            // Application Details
            $table->date('date_of_application')->nullable();
            $table->date('date_of_issuance')->nullable();
            $table->string('registrar')->nullable();
            $table->string('govt_permit_reco')->nullable();
            $table->integer('total')->nullable();
            
            // Semester Details
            $table->enum('semester', ['First', 'Second', 'Summer'])->nullable(); // Current Semester
            $table->date('date_of_graduation')->nullable();
            
            // Additional Semester Fields
            $table->date('semester1_start')->nullable();
            $table->date('semester1_end')->nullable();
            $table->date('semester2_start')->nullable();
            $table->date('semester2_end')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('SOmasterList');
    }
};
