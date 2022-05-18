<?php

use App\Models\Proposal;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewColumnsToProposalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proposals', function (Blueprint $table) {
            $table->enum('gender',['male','female'])->default('male')->after('lastName');
            $table->integer('childrenCount')->default(0)->after('familyStatus');
            $table->decimal('rentAmount', 10)->default(0)->after('commission');
            $table->enum('applicantType', Proposal::$applicantTypes)->nullable()->default(Proposal::$applicantTypes[0])->after('commission');
            $table->json('objectData')->nullable()->after('commission');
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
            $table->dropColumn('gender');
            $table->dropColumn('childrenCount');
            $table->dropColumn('rentAmount');
            $table->dropColumn('applicantType');
            $table->dropColumn('objectData');
        });
    }
}
