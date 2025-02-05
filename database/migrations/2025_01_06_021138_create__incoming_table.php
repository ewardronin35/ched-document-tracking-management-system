<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIncomingTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incoming', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('reference_number')->unique(); // For "No."
            $table->date('date_received'); // For "Date"
            $table->time('time_emailed'); // For "Time Emailed"
            $table->string('sender_name'); // For "Sender"
            $table->string('sender_email')->index(); // For "Email Address of Sender"
            $table->string('subject');
            $table->text('remarks')->nullable(); // For "Remarks / RP"
            $table->dateTime('date_time_routed')->nullable(); // For "Date / Time Routed"
            $table->string('routed_to')->nullable(); // For "Route To / Attendee"
            $table->date('date_acted_by_es')->nullable(); // For "Date Acted by ES"
            $table->text('outgoing_details')->nullable(); // For "Outgoing Details"
            
            // Quarterly Fields
            $table->boolean('q1')->default(false);
            $table->boolean('q2')->default(false);
            $table->boolean('q3')->default(false);
            $table->boolean('q4')->default(false);
            
            // If you need to track the year or have multiple years
            $table->year('year')->default(date('Y'));
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming');
    }
};
