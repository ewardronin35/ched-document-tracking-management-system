<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAuditLogsTable extends Migration
{
    public function up()
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            // Optionally, if the audit is related to a user:
            $table->unsignedBigInteger('user_id')->nullable();
            // For example, event type (e.g., 'login', 'logout', 'update', etc.)
            $table->string('event_type');
            // A description or details about the event
            $table->text('description')->nullable();
            // IP address, if desired
            $table->string('ip_address')->nullable();
            // User Agent, if desired
            $table->string('user_agent')->nullable();
            $table->timestamps();

            // Optional foreign key:
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('audit_logs');
    }
}
