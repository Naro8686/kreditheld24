<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeColumnsNullableOnProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->date('birthday')->nullable()->change();
            $table->string('familyStatus')->nullable()->change();

            $table->string('street')->nullable()->change();
            $table->string('house')->nullable()->change();
            $table->string('postcode')->nullable()->change();
            $table->string('city')->nullable()->change();
            $table->string('residenceType')->nullable()->change();
            $table->decimal('rentAmount', 10)->nullable()->change();
            $table->date('residenceDate')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proposals', function (Blueprint $table) {
            //
        });
    }
}
