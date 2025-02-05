<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutgoingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::create('outgoings', function (Blueprint $table) {
            $table->id();
            $table->string('control_no')->unique(); // CONTROL NO.
            $table->date('date_released'); // DATE RELEASED
            $table->string('category'); // DROPDOWN (Assuming this represents a category or type)
            $table->string('addressed_to'); // ADDRESSED TO
            $table->string('email'); // EMAIL
            $table->string('subject_of_letter'); // SUBJECT OF LETTER
            $table->text('remarks')->nullable(); // REMARKS (who initiates the process)
            $table->string('libcap_no')->nullable(); // LIBCAP # (Assuming it's optional)
            $table->enum('status', ['Pending', 'In Progress', 'Completed', 'Rejected'])->default('Pending'); // STATUS
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('outgoings');
    }
}
