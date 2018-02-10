<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntendsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('intends', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name',50)->comment('客户姓名')->default('');;
            $table->string('phone', 11)->default('')->comment('电话号码');
            $table->string('intentions', 11)->default('')->comment('合作意向,默认3：其他;1：公司注册 2：代理记账');
            $table->integer('reply_id')->unsigned()->default(0);
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
        Schema::dropIfExists('intends');
    }
}
