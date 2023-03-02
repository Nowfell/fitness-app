<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFitAppsData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fit_apps_data', function (Blueprint $collection) {
            $collection->id();
            $collection->string('user_id');
            $collection->date('date');
            $collection->integer('steps');
            $collection->timestamps();
            $collection->unique(['user_id', 'date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fit_apps_data');
    }
}
