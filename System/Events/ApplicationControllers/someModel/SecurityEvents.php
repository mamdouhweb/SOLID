<?php

/* 
 * SOLID Framework 2014
 */

use Handlers\Event\Source\Classes\Event;
use Auryn\Provider;

$injector = new Provider;

$RequestHandler = new Facade\RequestHandler(new \Facade\HookHandler(new \Facade\ConfigurationHandler));

$massAssignementEvent = new Event(function($model) use($RequestHandler){
    
        echo 'An event been fired </br>';
    
}, 'test');

$massAssignementEvent->bindTo('\\Core\\Models\\Classes\\Model')->before(array('set'));

return $massAssignementEvent;