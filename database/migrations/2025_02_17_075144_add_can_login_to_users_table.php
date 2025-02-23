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
    Schema::table('users', function (Blueprint $table) {
        $table->boolean('can_login')->default(true); // or false, up to you
    });
}

public function down()
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn('can_login');
    });
}

};
