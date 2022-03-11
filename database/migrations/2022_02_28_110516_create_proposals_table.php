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
            $table->string('creditType');
            $table->integer('deadline');
            $table->decimal('monthlyPayment', 10);
            $table->decimal('creditAmount', 10);
            $table->string('creditComment')->nullable();
            $table->string('firstName');
            $table->string('lastName');
            $table->date('birthday');
            $table->string('phoneNumber');
            $table->string('email');
            $table->string('birthplace');
            $table->string('street');
            $table->string('house');
            $table->string('city');
            $table->string('postcode');
            $table->string('residenceType');
            $table->date('residenceDate');
            $table->string('familyStatus');

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
