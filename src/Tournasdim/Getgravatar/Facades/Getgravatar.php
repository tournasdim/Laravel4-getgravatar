<?php namespace Tournasdim\Getgravatar\Facades;

use Illuminate\Support\Facades\Facade;

class Getgravatar extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() 
    { 

    	return 'gravatar' ; 
    	
    }

}