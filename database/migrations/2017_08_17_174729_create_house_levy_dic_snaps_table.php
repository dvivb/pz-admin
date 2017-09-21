<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHouseLevyDicSnapsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('house_levy_dic_snaps', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('house_levy_id')->comment('房屋征收表ID');
            $table->integer('dictionary_parent_id')->comment('字典表ID');
            $table->integer('dictionary_id')->default(0)->comment('字典表ID');
            $table->enum('subject', ['house_structure', 'annexe_structure', 'attach', 'structure', 'equipment'])->comment('主题名称');

            $table->string('name')->nullable()->comment('名称');
            $table->string('unit', 50)->nullable()->comment('单位');
            $table->decimal('price', 11)->default(0)->comment('补偿标准');
            $table->decimal('numbers', 11, 3)->default(0)->comment('数量');
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
        Schema::dropIfExists('house_levy_dic_snaps');
    }
}
