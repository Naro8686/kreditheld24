<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSurnameBirthdayAddressTelToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('birthday')->nullable()->after('name');
            $table->string('city')->nullable()->after('name');
            $table->string('street')->nullable()->after('name');
            $table->string('house')->nullable()->after('name');
            $table->string('postcode')->nullable()->after('name');
            $table->string('phone')->nullable()->after('name');
            $table->string('surname')->nullable()->after('name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('surname');
            $table->dropColumn('birthday');
            $table->dropColumn('city');
            $table->dropColumn('street');
            $table->dropColumn('house');
            $table->dropColumn('postcode');
            $table->dropColumn('phone');
        });
    }
}
