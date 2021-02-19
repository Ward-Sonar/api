<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('atmosphere')->nullable();
            $table->tinyInteger('direction')->nullable();
            $table->boolean('abandoned');
            $table->longText('comment')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->timestamps();
            $table->foreign('client_id')
                ->references('id')
                ->on('clients');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('submissions', function ($table) {
            $table->dropForeign(['client_id']);
        });
        Schema::dropIfExists('submissions');
    }
}
