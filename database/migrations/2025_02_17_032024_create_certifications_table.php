<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCertificationsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('certifications', function (Blueprint $table) {
            $table->id();
            // O- prefix (for example, a code or static prefix)
            $table->string('o_prefix')->nullable();
            // CAV No.
            $table->string('cav_no')->nullable();
            // Certification type (e.g., "DEFUNCT / KUWAIT / MARINA / DFA / STUDENT VERIFICATION")
            $table->string('certification_type')->nullable();
            $table->string('surname')->nullable();
            $table->string('first_name')->nullable();
            $table->string('extension_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('full_name_of_hei')->nullable();
            $table->string('program_name')->nullable(); // Do not abbreviate
            $table->string('major')->nullable();        // Do not abbreviate
            $table->date('date_of_entry')->nullable();    // DATE OF ENTRY YYYY-MM-DD
            $table->date('date_ended')->nullable();       // DATE ENDED YYYY/MM/DD
            $table->date('year_graduated')->nullable();     // YEAR GRADUATED YYYY-MM-DD
            $table->string('so_no')->nullable();          // SO. NO.
            $table->string('or_no')->nullable();          // OR. NO.
            $table->date('date_applied')->nullable();
            $table->date('date_released')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('certifications');
    }
}
