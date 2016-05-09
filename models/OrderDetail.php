<?php namespace Beysong\Proevent\Models;

use Model;

/**
 * Model
 */
class OrderDetail extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'beysong_proevent_order_details';

    public $belongsTo = [
        'order' => 'Beysong\Proevent\Models\Order'
    ];

    public $belongsToMany = [
        'tickets' => [
            'Beysong\Proevent\Models\Ticket',
            'table'    => 'beysong_proevent_order_detail_ticket',
            'key'      => 'order_detail_id',
            'otherkey' => 'ticket_id'
        ]
    ];
}