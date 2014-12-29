<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Event\Source\Abstracts;
use Handlers\Event\Source\Classes\Dispatcher;

abstract class EventObserver {
    
    protected $Dispatcher;
    
    public function __construct(Dispatcher $dispatcher = NULL) {
        
        if(is_null($dispatcher)){
            
            $dispatcher = new Dispatcher();
            
        }
        
        $this->Dispatcher = $dispatcher;
        
    }
    
    public function __call($name, $arguments) {
        
        if(method_exists($this, $name)){
            
            $method = new \ReflectionMethod($this, $name);
            
            if($this->Dispatcher->isMethodAttached($name, $this) === TRUE){
                
                $method->setAccessible(TRUE);
            
                $this->Dispatcher->dispatch($this, $name, 'before');

                $method->invokeArgs($this, $arguments);

                $this->Dispatcher->dispatch($this, $name, 'after');
                
            }else{
                
                throw new \Exception('method \'' . $name . '\' is not accessible');
                
            }
            
        }else{
            
            throw new \Exception('method does not exists');
        
        }
    }
    
}
