<?php namespace Beysong\Proevent\Updates;

use Schema;
use October\Rain\Database\Updates\Migration;

class CreateBeysongProeventTables extends Migration
{

    public function up()
    {
        Schema::create('beysong_proevent_events', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->string('currency')->nullable();
            $table->text('description')->nullable();
            $table->tinyInteger('is_activated')->default(1);
            $table->tinyInteger('status')->default(1);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamps();
        });
        
        Schema::create('beysong_proevent_orders', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('user_id')->unsigned()->index();
            $table->integer('event_id')->unsigned()->index();
            //订单总金额,订单总额
            $table->decimal('total_amount', 11, 2)->default(0.00);
            //已付金额
            $table->decimal('paid_amount', 11, 2)->default(0.00);
            //支付流水号
            $table->string('trade_no', 100)->nullable();
            //支付方式
            $table->string('pay_code', 32)->nullable();
            //订单状态 0默认未完成,1订单完成,2订单删除
            $table->tinyInteger('status')->default(0);
            //支付状态 0未付,1已付,2已退
            $table->tinyInteger('pay_status')->default(0);
            //签到凭证
            $table->string('badge_code', 128)->nullable();
            $table->timestamps();
        });

        Schema::create('beysong_proevent_order_details', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('order_id')->unsigned()->index();
            $table->string('first_name', 32);
            $table->string('last_name', 32);
            $table->string('email');
            $table->string('mobile', 32)->nullable();
            $table->string('telphone', 32)->nullable();
            $table->string('company', 100);
            $table->string('title', 100);
            $table->string('country', 32)->nullable();
            $table->string('address', 100)->nullable();
            $table->string('zip', 32)->nullable();
            $table->string('photo', 128)->nullable();
            
            //订单总额
            $table->decimal('amount', 11, 2)->default(0.00);
            $table->timestamps();
        });
        //订单详细表和门票表多对多关联表
        Schema::create('beysong_proevent_order_detail_ticket', function($table)
        {
            $table->integer('order_detail_id')->unsigned();
            $table->integer('ticket_id')->unsigned();
            //$table->primary(['order_detail_id', 'ticket_id']);
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 11, 2)->default(0.00);
            
        });

        Schema::create('beysong_proevent_tickets', function($table)
        {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('event_id')->unsigned()->index();
            $table->string('name')->nullable();
            $table->string('display_name')->nullable();
            $table->text('description')->nullable();
            $table->decimal('price', 11, 2)->default(0.00);
            $table->tinyInteger('is_activated')->default(1);
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('beysong_proevent_events');
        Schema::dropIfExists('beysong_proevent_orders');
        Schema::dropIfExists('beysong_proevent_order_details');
        Schema::dropIfExists('beysong_proevent_order_detail_ticket');
        Schema::dropIfExists('beysong_proevent_tickets');
    }

}