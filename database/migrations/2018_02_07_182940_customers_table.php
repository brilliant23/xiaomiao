<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('company_name',50)->comment('公司名称')->default('')->unique();
            $table->string('corporation', 15)->comment('法人')->default('');
            $table->string('phone', 11)->default('');
            $table->tinyInteger('corporate_property')->default(1)->comment('企业性质 默认1：一般纳税人 2：小规模');
            $table->tinyInteger('area')->default(0)->comment('注册地区默认0：无；1：怀柔；2：密云；3：朝阳；4：昌平；5：自由地址；6：附加服务；');
            $table->tinyInteger('address_type')->default(1)->comment('地址类型默认1：一次性；2：年续费；');
            $table->string('trade')->default('')->comment('所属行业');
            $table->string('credit_code')->default('')->comment('信用编码');
            $table->timestamp('cooperate_time')->comment('合作时间')->date();
            $table->timestamp('get_business_time')->comment('下照日期')->date();
            $table->timestamp('revenue_time')->comment('税务报道日期')->date();
            $table->integer('account_id')->unsigned()->comment('负责会计人员')->default(0);
            $table->integer('sale_id')->unsigned()->comment('合作销售')->default(0);
            $table->integer('total_charge')->unsigned()->default(0)->comment('总充值金额');
            $table->integer('sale_charge')->unsigned()->default(0)->comment('实际消费金额');
            $table->integer('last_charge')->unsigned()->default(0)->comment('余额');
            $table->integer('one_charge')->unsigned()->default(0)->comment('当月记账扣费金额');
            $table->integer('created_by')->unsigned()->default(0);
            $table->integer('updated_by')->unsigned()->default(0);
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
        Schema::dropIfExists('customers');
    }
}
