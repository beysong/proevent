<?php namespace Beysong\Proevent\Components;

use Beysong\Proevent\Models\Event as BeysongEvent;
use Beysong\Proevent\Models\Order as Order;
use Beysong\Proevent\Models\OrderDetail as OrderDetail;
use Beysong\Proevent\Models\Ticket as Ticket;

require_once('vendor/autoload.php');

class OrderView extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Order View',
            'description' => 'Displays a Order View.'
        ];
    }

    // Tickets of current event
    public function onPay()
    {
        \Pingpp\Pingpp::setApiKey('sk_test_0WnnH4nLuH4O5CavH8e5anjT');
        \Pingpp\Pingpp::setPrivateKeyPath(storage_path('rsa_private_key.pem'));

        $extra = array('success_url'=>'www.baidu.com');
        return \Pingpp\Charge::create(
            array(
                'order_no'  => '123456789',
                'app'       => array('id' => 'app_40WXHKmb5Oa5qfLO'),
                'channel'   => 'alipay',
                'amount'    => 100,
                'client_ip' => '127.0.0.1',
                'currency'  => 'cny',
                'subject'   => 'Your Subject',
                'body'      => 'Your Body'
            )
        );
    }

    // order
    public function orderDetail()
    {
        $order_id = $this->param('order_id');
        $order = Order::find($order_id);
        $order_details = $order->order_details;
        $event = $order->event;
        $data = array(
                'order'=> $order,
                'order_details'=> $order_details,
                'event'=> $event
            );
        return $data;
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
        $this->addJs('/plugins/beysong/proevent/assets/pingpp/src/pingpp-pc.js');
        $this->addJs('/plugins/beysong/proevent/assets/js/orderview.js');
    //	$event_id = $this->property('events');
    //	$tickets = BeysongEvent::find($event_id)->tickets;
    //	$this->page['tickets'] = $tickets;

    }

    public function onRegisterPersons()
    {	

        $order = new Order();

        foreach(post('userlist') as $k=>$v){
            $order_detail = new OrderDetail();
            $order_detail->first_name = $v['first_name'];
            $order_detail->last_name = $v['last_name'];
            $order_detail->company = $v['company'];
            $order_detail->title = $v['title'];
            $order_detail->mobile = $v['mobile'];
            $order_detail->email = $v['email'];
            $order_detail->first_name = $v['first_name'];
            //计算总价格
            $order_detail->amount = \Db::table('beysong_proevent_tickets')
                                        ->whereIn('id', $v['tickets'])
                                        ->sum('price');
            //订单总价
            $order->total_amount += $order_detail->amount;
            $order->event_id = post('event_id');
            $order->pay_code = post('pay_code');
            $order->save();

            $order_detail = $order->order_details()->save($order_detail);

            $order_detail->tickets()->sync($v['tickets']);


        }

        //邮件提醒
        if ($order->total_amount <= 0) {
            # 发送注册成功邮件（发送badge code）
        } else {
            # 发送注册确认邮件（提醒付款）
        }
        
        $message = array(
                'result'=>'success'
            );



		return json_encode($message);

    }

    public function gopay($orderid){
        \Pingpp\Pingpp::setApiKey('sk_test_0WnnH4nLuH4O5CavH8e5anjT');
        \Pingpp\Pingpp::setPrivateKeyPath('../rsa_private_key.pem');

        $extra = array('success_url'=>'www.baidu.com');
        return \Pingpp\Charge::create(
            array(
                'order_no'  => '123456789',
                'app'       => array('id' => 'app_40WXHKmb5Oa5qfLO'),
                'channel'   => 'alipay',
                'amount'    => 100,
                'client_ip' => '127.0.0.1',
                'currency'  => 'cny',
                'subject'   => 'Your Subject',
                'body'      => 'Your Body',
                'extra'     => $extra
            )
        );
    }

    public function cliendpay(){
        
    }
}