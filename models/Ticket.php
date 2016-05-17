<?php namespace Beysong\Proevent\Models;

use Model;

/**
 * Model
 */
class Ticket extends Model
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
    public $table = 'beysong_proevent_tickets';

    public $belongsTo = [
        'event' => 'Beysong\Proevent\Models\Event'
    ];

    public $belongsToMany = [
        'order_details' => [
            'Beysong\Proevent\Models\OrderDetail',
            'table'    => 'beysong_proevent_order_detail_ticket',
            'key'      => 't_id',
            'otherKey' => 'od_id'
        ]
    ];
}