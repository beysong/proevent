<?php namespace Beysong\Proevent\Components;

use Beysong\Proevent\Models\Event as BeysongEvent;
use Beysong\Proevent\Models\Order as Order;
use Beysong\Proevent\Models\OrderDetail as OrderDetail;
use Beysong\Proevent\Models\Ticket as Ticket;

require_once('vendor/autoload.php');

class Proevents extends \Cms\Classes\ComponentBase
{
    public function componentDetails()
    {
        return [
            'name' => 'Events',
            'description' => 'Displays a Event.'
        ];
    }

    //Now event id
    public function event_id()
    {
        return $this->property('events');
    }

    // Tickets of current event
    public function tickets()
    {
    	$event_id = $this->property('events');
    	$tickets = BeysongEvent::find($event_id)->tickets;
        return $tickets;
    }
    //
    public function defineProperties()
    {
        return [
            'events' => [
                 'title'             => 'Available Events',
                 'description'       => 'All Available Events',
                 'type'              => 'dropdown'
            ]
        ];
    }

    public function getEventsOptions()
    {	
    	$events = BeysongEvent::where('end_time','>',time())->lists('name','id');
        return $events;
    }

    /**
     * Executed when this component is bound to a page or layout.
     */
    public function onRun()
    {
    	$this->addCss('/plugins/beysong/proevent/assets/bootstrapvalidator/dist/css/bootstrapValidator.css');
    	$this->addJs('/plugins/beysong/proevent/assets/bootstrapvalidator/dist/js/bootstrapValidator.min.js');
        $this->addJs('/plugins/beysong/proevent/assets/pingpp/src/pingpp-pc.js');
    	$this->addJs('/plugins/beysong/proevent/assets/js/events.js');
    //	$event_id = $this->property('events');
    //	$tickets = BeysongEvent::find($event_id)->tickets;
    //	$this->page['tickets'] = $tickets;

    }

    public function onRegisterPersons()
    {	

    	/*$first_name = post('first_name');
        $last_name = post('last_name');
        $company = post('company');
        $title = post('title');
        $mobile = post('mobile');
        $email = post('email');
        $tickets = post('tickets');
        $first_name = post('first_name');

        $event_id = post('event_id');
        $user_id = post('user_id');
        $this->page['result'] = array('1','42','124');*/

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