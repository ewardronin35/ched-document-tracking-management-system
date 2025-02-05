<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsToOutgoingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    { 
        Schema::table('outgoings', function (Blueprint $table) {
            // Adding new columns
            $table->string('chedrix_2025')->nullable()->after('id'); // CHEDRIX-2025
            $table->string('o')->nullable()->after('chedrix_2025'); // O
            $table->string('q1_jan_feb_mar')->nullable()->after('status'); // Q1 JAN-FEB-MAR
            $table->string('q2_apr_may_june')->nullable()->after('q1_jan_feb_mar'); // Q2 APR-MAY-JUNE
            $table->string('q3_jul_aug_sept')->nullable()->after('q2_apr_may_june'); // Q3 JUL-AUG-SEPT
            $table->string('q4_oct_nov_dec')->nullable()->after('q3_jul_aug_sept'); // Q4 OCT-NOV-DEC

            // Optionally, rename existing columns if necessary
            // Requires the doctrine/dbal package
            // Install it via: composer require doctrine/dbal
            // $table->renameColumn('subject_of_letter', 'subject'); // Example rename
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('outgoings', function (Blueprint $table) {
            // Dropping the new quarterly columns
            $table->dropColumn(['chedrix_2025', 'o', 'q1_jan_feb_mar', 'q2_apr_may_june', 'q3_jul_aug_sept', 'q4_oct_nov_dec']);
        });
    }
}
