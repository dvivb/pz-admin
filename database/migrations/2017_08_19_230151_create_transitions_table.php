<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransitionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transitions', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name')->comment('姓名');
            $table->string('id_number')->comment('身份证号码');
            $table->string('villages')->comment('乡镇');
            $table->string('address')->comment('住址');
            $table->string('contact')->comment('联系电话');
            $table->date('signed_at')->comment('签约时间');
            $table->integer('living_area')->comment('居住面积');
            $table->decimal('living_price', 11)->comment('居住过渡费');
            $table->integer('business_area')->comment('商业面积');
            $table->decimal('business_price', 11)->comment('商业过渡费');
            $table->decimal('amount', 11)->comment('总过渡费');
            $table->date('started_at')->comment('起始时间');
            $table->date('ended_at')->comment('起止时间');
            $table->string('signed')->comment('签名');
            $table->date('outed_at')->comment('发放时间');
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
        Schema::dropIfExists('transitions');
    }
}
