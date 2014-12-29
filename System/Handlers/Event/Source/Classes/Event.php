<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Event\Source\Classes;

final class Event{
    
    private $label;
    
    private $owner;
    
    public $action;
    
    private $triggers = array();
    
    public function __construct(\Closure $action, $label) {
        
        $this->action = $action;
        
        $this->label = $label;
        
        $this->triggers['before'] = array();
        
        $this->triggers['after'] = array();
        
    }
    
    public function bindTo($className){
        
        $this->owner = $className;
        
        return $this;
        
    }
    
    public function before(array $methods){
        
        $this->triggers['before'] = $methods;
        
        return $this;
        
    }
    
    public function after(array $methods){
        
        $this->triggers['after'] = $methods;
        
        return $this;
        
    }
    
    public function getLabel(){
        
        return $this->label;
        
    }
    
    public function getOwner(){
        
        return $this->owner;
        
    }
    
    public function getTriggers(){
        
        return $this->triggers;
        
    }

}
