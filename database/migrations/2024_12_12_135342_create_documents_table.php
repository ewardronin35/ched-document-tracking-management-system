<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDocumentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_id')->unique(); // Unique Document ID
            $table->string('email'); // User's Email
            $table->string('full_name'); // User's Full Name
            $table->string('document_type'); // Type of Document
            $table->string('file_path'); // Path to the Uploaded File
            $table->string('status')->default('Submitted'); // Current Status
            $table->json('status_details')->nullable(); // Detailed Status Information
            $table->timestamps(); // Timestamps for Created and Updated At
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('documents');
    }
}
