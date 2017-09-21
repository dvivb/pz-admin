<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectsTable extends Migration
{
    /**
     * 项目表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50)->comment('名称');
            $table->tinyInteger('period')->default(1)->comment('当前期数');

            $table->integer('predict_house_household_nums')->default(0)->comment('房屋征收总户数');
            $table->integer('predict_house_areas')->default(0)->comment('房屋征收总面积');
            $table->integer('predict_house_amount')->default(0)->coment('房屋征收总金额');
            $table->integer('predict_transitions_amount')->default(0)->coment('过渡费总金额');

            /*$table->integer('total_household_nums')->default(0)->comment('总户数');
            $table->integer('total_areas')->default(0)->comment('总面积');
            $table->integer('total_amount')->default(0)->coment('总金额');*/

            $table->integer('predict_land_household_nums')->default(0)->comment('土地征收总户数');
            $table->integer('predict_land_areas')->default(0)->comment('土地征收总面积');
            $table->integer('predict_land_amount')->default(0)->coment('土地征收总金额');

            $table->integer('house_household_nums')->default(0)->comment('房屋征收总户数');
            $table->integer('house_areas')->default(0)->comment('房屋征收总面积');
            $table->integer('house_amount')->default(0)->coment('房屋征收总金额');
            $table->integer('transitions_amount')->default(0)->coment('过渡费总金额');

            $table->integer('land_household_nums')->default(0)->comment('土地征收总户数');
            $table->integer('land_areas')->default(0)->comment('土地征收总面积');
            $table->integer('land_amount')->default(0)->coment('土地征收总金额');

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
        Schema::dropIfExists('projects');
    }
}
