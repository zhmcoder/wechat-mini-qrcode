<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminEntityFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('deep_admin.database.entity_fields_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('entity_id');
            $table->string('name', 64);
            $table->string('type', 100);
            $table->integer('field_length');
            $table->integer('field_total')->comment('整数位长度');
            $table->integer('field_scale')->comment('小数位长度');
            $table->string('comment', 100);
            $table->string('default_value', 100)->nullable();
            $table->string('form_name', 20);
            $table->string('form_type', 200);
            $table->string('form_comment', 200)->nullable();
            $table->string('form_params', 1024)->nullable();
            $table->tinyInteger('is_show')->default(1)->comment('1.不显示、2.编辑显示、3.创建显示、4.始终显示');
            $table->tinyInteger('is_list_show')->default(0)->comment(' 是否列表展示（0否、1是） ');
            $table->tinyInteger('is_show_inline');
            $table->tinyInteger('is_edit')->default(1)->comment('1.不显示、2.编辑显示、3.创建显示、4.始终显示');
            $table->tinyInteger('is_required')->default(0)->comment('是否必填（0否、1是）');
            $table->tinyInteger('is_order')->default(0)->comment('支持列表排序（0不支持、1支持）');
            $table->integer('order')->default(100)->comment('表单显示排序');
            $table->integer('list_order')->default(100)->comment('列表显示排序');
            $table->tinyInteger('is_search')->default(0)->comment('是否支持查询');
            $table->tinyInteger('is_unique')->default(0)->comment('是否唯一');
            $table->integer('list_width')->default(0)->comment('列表宽度');
            $table->string('create_rules', 1024)->nullable();
            $table->string('update_rules', 1024)->nullable();
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
        Schema::dropIfExists(config('deep_admin.database.entity_fields_table'));
    }
}
