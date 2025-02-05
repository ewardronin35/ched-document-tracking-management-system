<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('majors', function (Blueprint $table) {
            $table->id(); // ID
            $table->string('name'); // Major Name
            $table->foreignId('program_id')->constrained()->onDelete('cascade'); // Foreign key to programs
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('majors');
    }
};
