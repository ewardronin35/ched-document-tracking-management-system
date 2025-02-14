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
            $table->id(); // Primary Key

            // Additional columns from your Excel snippet
            $table->string('status')->nullable();
            $table->string('processing_slip_number')->nullable();
            $table->string('region')->nullable();

            $table->string('hei_name')->nullable();
            $table->string('hei_uii')->nullable();

            $table->string('special_order_number')->nullable();

            // Student info
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('extension_name')->nullable(); // II, IV, Jr., Sr., etc.
            $table->enum('sex', ['Male', 'Female', 'Other']);

            // Numeric fields
            $table->integer('total')->nullable();

            // Foreign keys
            $table->foreignId('program_id')->constrained()->onDelete('cascade');
            $table->string('psced_code')->nullable();  // If you store PSCED code as a string
            $table->foreignId('major_id')->constrained()->onDelete('cascade');

            // Academic timeline
            $table->date('started')->nullable();
            $table->date('ended')->nullable();
            $table->date('date_of_application')->nullable();
            $table->date('date_of_issuance')->nullable();
            $table->string('registrar')->nullable();
            $table->string('govt_permit_recognition')->nullable(); // "Govt. Permit/Recognition"
            $table->string('signed_by')->nullable();               // "Signed By (Approving Authority)"

            // Semester & Academic Year (first set)
            $table->enum('semester', ['First', 'Second', 'Summer'])->nullable();
            $table->string('academic_year')->nullable(); // e.g., 2023-2024
            $table->date('date_of_graduation')->nullable();

            // Semester & Academic Year (second set, if needed)
            $table->enum('semester2', ['First', 'Second', 'Summer'])->nullable();
            $table->string('academic_year2')->nullable(); // e.g., 2023-2024

            $table->timestamps(); // created_at, updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('so_master_lists');
    }
};
