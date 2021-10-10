<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminFiles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', '1024');
            $table->string('path', '1024');
            $table->string('type', 32)->default(null)
                ->comment('image,avatar,file等');
            $table->string('disk')
                ->comment('存储驱动类型，如local，qiniu');
            $table->string('local_path', '1024')->default(null)
                ->comment('本地存储路径，如果设置了本地存储');
            $table->string('md5', '32')->default(null)
                ->comment('文件md5');
            $table->string('sha1', '256')->default(null)
                ->comment('文件sha1');

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
        Schema::dropIfExists('admin_files');
    }
}
