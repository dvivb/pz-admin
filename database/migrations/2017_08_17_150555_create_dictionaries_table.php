<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDictionariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dictionaries', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('subject', ['house_structure', 'annexe_structure', 'attach', 'structure', 'equipment', 'land_status', 'young_crop'])->comment('主题名称');

            $table->integer('parent_id')->default(0)->comment('父级ID');
            $table->string('name')->comment('名称');
            $table->string('unit', 50)->nullable()->comment('单位');
            $table->decimal('price', 11)->default(0)->comment('补偿标准');
            $table->string('remarks')->nullable()->comment('备注');
            $table->integer('sort')->nullable()->comment('排序');

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
        Schema::dropIfExists('dictionaries');
    }
}
