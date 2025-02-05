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
        Schema::create('records', function (Blueprint $table) {
            $table->id();
            $table->string('control_no');
            $table->string('project');
            $table->string('relevant_hei');
            $table->string('document_type');
            $table->string('name_of_document');
            $table->string('status');
            $table->string('transaction_type');
            $table->string('assigned_staff');
            $table->text('collaborators')->nullable();
            $table->string('upload_files')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
