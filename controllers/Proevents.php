<?php namespace Beysong\Proevent\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Proevents extends Controller
{
    public $implement = [
    'Backend\Behaviors\ListController',
    'Backend\Behaviors\FormController',
    'Backend.Behaviors.RelationController',
    'Backend\Behaviors\ReorderController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $reorderConfig = 'config_reorder.yaml';
    public $relationConfig = 'config_relation.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Beysong.Proevent', 'proevent-event-event', 'proevent-event-order');
    }
}