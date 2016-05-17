<?php namespace Beysong\Proevent;

use System\Classes\PluginBase;

class Plugin extends PluginBase
{

	public function registerComponents()
	{
	    return [
	        'Beysong\Proevent\Components\Proevents' => 'Proevents'
	    ];
	}

    public function registerSettings()
    {
    }
}
