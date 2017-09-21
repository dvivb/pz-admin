<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLandLeviesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('land_levies', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('project_id')->comment('项目表ID');

            $table->integer('period_id')->comment('批次号');

            $table->string('id_number')->comment('身份证号');
            $table->string('villages')->comment('乡镇');
            $table->string('household_name')->comment('姓名');
            $table->enum('gender', ['m', 'f'])->comment('性别');
            $table->string('contact')->comment('联系电话');
            $table->string('address')->comment('家庭住址');

            $table->integer('total_areas')->nullable()->comment('总面积'); // 交互属性 - 汇总
            $table->decimal('amount', 18)->nullable()->comment('合计'); // 交互属性 - 汇总

            $table->string('deposit_bank')->nullable()->comment('开户银行');
            $table->string('deposit_account')->nullable()->comment('开户账号');
            $table->timestamp('provided_at')->nullable()->comment('发放时间');
            $table->string('remark')->nullable()->comment('备注');

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
        Schema::dropIfExists('land_levies');
    }
}
