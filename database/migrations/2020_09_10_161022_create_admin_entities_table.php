<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminEntitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(config('deep_admin.database.entities_table'), function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 50);
            $table->string('table_name', 60)->default('')->unique();
            $table->string('description', 200)->nullable();
            $table->string('default_sort', 20)->default('id')->comment('默认排序字段');
            $table->string('sort_type', 20)->default('desc')->comment('排序类型');
            $table->string('actions', 255)->default('["create","edit","delete"]')->comment(' 支持操作');
            $table->integer('actions_width')->default(100)->comment('操作栏宽度');
            $table->integer('actions_time_type')->default(1)->comment('操作时间类型');
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
        Schema::dropIfExists(config('deep_admin.database.entities_table'));
    }
}
