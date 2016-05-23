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

        $order_id = post('order_id');
        $extra = array('success_url'=>'www.baidu.com');
        $ch = \Pingpp\Charge::create(
            array(
                'order_no'  => '123456789',
                'app'       => array('id' => 'app_40WXHKmb5Oa5qfLO'),
                'channel'   => 'alipay_pc_direct',
                'amount'    => 100,
                'client_ip' => '183.204.130.127',
                'currency'  => 'cny',
                'subject'   => 'Your Subject',
                'body'      => 'Your Body',
                'extra'     => $extra
            )
        );

        return json_encode($ch);
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


}