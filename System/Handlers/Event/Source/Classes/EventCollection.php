<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Event\Source\Classes;


class EventCollection extends \ArrayIterator{

    const SYSTEM_EVENTS_DIRECTORY = 'System/Events/';
    
    const APPLICATION_EVENTS_DIRECTORY = 'Application/Events/';
    
    public function attach(Event $event) {
        
        $this->append($event);
        
    }

    public function detach($eventLabel) {
        
        foreach($this as $event){
            
            if(strtolower($event->getLabel()) == strtolower($eventLabel)){
                
                unset($event);
                
            }
        }
    }
    
    public function fill($className){
        
        $className = str_replace('\\', '/', $className);
        
        $systemEventsDirectory = ROOT . DOCUMENT_SEPARATOR . APPNAME . DOCUMENT_SEPARATOR . self::SYSTEM_EVENTS_DIRECTORY . $className;
        
        $applicationEventsDirectory = ROOT . DOCUMENT_SEPARATOR . APPNAME . DOCUMENT_SEPARATOR . self::APPLICATION_EVENTS_DIRECTORY . $className;

        if(file_exists($systemEventsDirectory)){
            
            $this->load($systemEventsDirectory);
            
        }
        
        if(file_exists($applicationEventsDirectory)){
            
            $this->load($applicationEventsDirectory);
            
        }
        
    }
    
    private function load($directory){
        
        $loader = new \Handlers\Loader\Source\Classes\Loader();
        
        $events = $loader->load($directory, TRUE);
        
        if($events !== FALSE){
            
            foreach($events as $event){

                if($event instanceof Event){

                    $this->attach($event);

                }
            }
        }
    }

}
