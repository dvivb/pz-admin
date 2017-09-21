<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodsTable extends Migration
{
    /**
     * 项目期数表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->comment('项目表ID');
            $table->integer('period')->default(1)->comment('期数');

            $table->integer('total_nums')->default(0)->comment('总户数');
            $table->integer('total_areas')->default(0)->comment('总面积');
            $table->decimal('total_amount', 11)->default(0)->coment('总金额');

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
        Schema::dropIfExists('periods');
    }
}
