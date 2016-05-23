<?php namespace Beysong\Proevent;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{

	public function registerComponents()
	{
	    return [
	        'Beysong\Proevent\Components\Proevents' => 'Proevents',
	        'Beysong\Proevent\Components\OrderView' => 'OrderView'
	    ];
	}

    public function registerSettings()
    {
    }
}
