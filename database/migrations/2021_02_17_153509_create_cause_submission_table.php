<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCauseSubmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cause_submission', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('submission_id');
            $table->unsignedBigInteger('cause_id');
            $table->foreign('submission_id')
                ->references('id')
                ->on('submissions');
            $table->foreign('cause_id')
                ->references('id')
                ->on('causes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cause_submission', function ($table) {
            $table->dropForeign(['submission_id']);
        });

        Schema::table('cause_submission', function ($table) {
            $table->dropForeign(['cause_id']);
        });

        Schema::dropIfExists('cause_submission');
    }
}
