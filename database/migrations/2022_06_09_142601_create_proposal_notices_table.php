<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProposalNoticesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proposal_notices', function (Blueprint $table) {
            $table->id();
            $table->text('message');
            $table->enum('status', [\App\Constants\Status::PENDING, \App\Constants\Status::APPROVED])->default(\App\Constants\Status::PENDING);
            $table->foreignId('proposal_id')
                ->constrained()
                ->cascadeOnDelete()
                ->cascadeOnUpdate();
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
        Schema::dropIfExists('proposal_notices');
    }
}
