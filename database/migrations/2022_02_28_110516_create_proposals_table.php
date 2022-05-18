<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')
                ->constrained()
                ->onUpdate('cascade')
                ->onDelete('cascade');
            $table->string('status')->default(\App\Constants\Status::PENDING);
            $table->text('notice')->nullable();
            $table->integer('deadline')->nullable();
            $table->decimal('monthlyPayment', 10)->nullable();
            $table->decimal('creditAmount', 10)->nullable();
            $table->string('creditComment')->nullable();
            $table->string('firstName')->nullable();
            $table->string('lastName')->nullable();
            $table->date('birthday')->nullable();
            $table->string('phoneNumber')->nullable();
            $table->string('email')->nullable();
            $table->string('birthplace')->nullable();
            $table->string('street')->nullable();
            $table->string('house')->nullable();
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('residenceType')->nullable();
            $table->date('residenceDate')->nullable();
            $table->string('familyStatus')->nullable();

            $table->json('oldAddress')->nullable();
            $table->json('spouse')->nullable();
            $table->json('insurance')->nullable();
            $table->json('otherCredit')->nullable();
            $table->json('files')->nullable();

            $table->decimal('bonus', 10)->nullable();
            $table->decimal('commission')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proposals');
    }
}
