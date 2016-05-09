<?php namespace Beysong\Proevent\Models;

use Model;

/**
 * Model
 */
class Event extends Model
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
    public $table = 'beysong_proevent_events';

    public $hasMany = [
        'tickets' => 'Beysong\Proevent\Models\Ticket',
        'orders' => 'Beysong\Proevent\Models\Order',
    ];
}