<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Event\Source\Classes;
use Handlers\Event\Source\Abstracts\EventObserver;


class Dispatcher{
    
    private $events;
    
    public function __construct() {}
    
    public function dispatch(EventObserver $object, $trigger ,$type){
        
        try{
            
            foreach($this->events as $event){
                
                $ownerClass = $event->getOwner();
                
                $owner = new $ownerClass;
                
                if($object instanceof $owner && $owner instanceof EventObserver){
                    
                    $triggers = $event->getTriggers();
                    
                    if(isset($triggers[strtolower($type)]) && false !== array_search(strtolower($trigger), $triggers[strtolower($type)])){
                        
                        $runEvent = $event->action;
                        
                        $runEvent($object);
                        
                    }
                }
            }
        } catch (\Exception $ex) {
            
            throw $ex;

        }
        
    }
    
    private function setUpEventsCollection($observerName, EventCollection $events = NULL){
        
        if(is_null($events)){
            
            $events = new EventCollection();
            
        }
        
        $this->events = $events;
        
        $this->events->fill($observerName);
    }
    
    public function isMethodAttached($methodName, EventObserver $object){
        
        $this->setUpEventsCollection(get_class($object));
        
        try{
            
            foreach($this->events as $event){
                
                $ownerClass = $event->getOwner();
                
                $owner = new $ownerClass;
                
                if($object instanceof $owner && $owner instanceof EventObserver){
                    
                    $triggers = $event->getTriggers();
                    
                    if((false !== array_search(strtolower($methodName), $triggers['before'])) || (false !== array_search(strtolower($methodName), $triggers['after']))){
                        
                        return TRUE;
                        
                    }                    
                    return FALSE;
                }
            }
        } catch (\Exception $ex) {
            
            throw $ex;

        }
    }
    
}
