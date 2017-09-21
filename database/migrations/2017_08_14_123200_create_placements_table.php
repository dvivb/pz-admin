<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlacementsTable extends Migration
{
    /**
     * 安置表
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('placements', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('project_id')->comment('项目表ID');

            $table->string('address')->comment('地址');
            $table->enum('type', [1,2])->comment('类型（1.住房；2.商业）');
            $table->integer('total_areas')->comment('总面积');
            $table->string('remarks')->nullable()->comment('备注');

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
        Schema::dropIfExists('placements');
    }
}
