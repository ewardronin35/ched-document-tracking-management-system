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
        Schema::create('condobpobs', function (Blueprint $table) {
            $table->id();
            $table->integer('quarter')->nullable();
            $table->integer('No');            
            $table->string('surname');
            $table->string('first_name');
            $table->string('extension_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->enum('sex', ['Male', 'Female']);
            $table->string('or_number');
            $table->string('name_of_hei');
            $table->string('special_order_no', 18)->nullable();
            $table->string('type_of_correction')->nullable();
            // "From" and "To" are reserved words; using "from_date" and "to_date"
            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();
            $table->date('date_applied')->nullable();
            $table->date('date_released')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('condobpobs');
    }
};
