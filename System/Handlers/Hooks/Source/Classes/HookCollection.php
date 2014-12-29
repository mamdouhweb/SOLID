<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Hooks\Source\Classes;
use \Handlers\Hooks\Source\Interfaces\Hookable;

class HookCollection {
    /**
     * set of hooks from type before
     * @var array
     */
    private $hookBefore;
    /**
     * set of hooks from type after
     * @var array
     */
    private $hookAfter;
    /**
     * class constructor
     */
    public function __construct() {
        
        $this->hookAfter = array();
        
        $this->hookBefore = array();
        
    }
    /**
     * takes a hook and reads its hooked classes type then redirect to the 
     * appropriet method to handle it
     * @param \Handlers\Hooks\Source\Interfaces\Hookable $hook
     */
    public function addHook(Hookable $hook){
        //reading the hooked classes
        $hookedClasses = $hook->getHookedClasses();
        //check if there are hooked classes from type before
        if(array_key_exists('before', $hookedClasses)){
            //if yes then call addBeforeHook method
            $this->addBeforeHook($hook, $hookedClasses['before']);
            
        }
        //check if there are hooked classes from type after
        if(array_key_exists('after', $hookedClasses)){
            //if yes then call addAfterHook method
            $this->addAfterHook($hook, $hookedClasses['after']);
            
        }
        
    }
    /**
     * reads a set of hookedClasses and their methods, then append the hook to them
     * @param \Handlers\Hooks\Source\Interfaces\Hookable $hook
     * @param array $hookedClasses
     */
    public function addAfterHook(Hookable $hook, array $hookedClasses){
        //looping through the hooked classes
        foreach ($hookedClasses as $hookedClass){
            //reading the hookedMethods in the current hookedClass and looping through them
            foreach($hookedClass->getHookedMethods() as $hookedMethod){
                //appending the hook to the class method, and initializing the hookAfter array
                $this->hookAfter[$hookedClass->getName()][$hookedMethod] = $hook;   
                
            }
        }
    }
    /**
     * reads a set of hookedClasses and their methods, then append the hook to them
     * @param \Handlers\Hooks\Source\Interfaces\Hookable $hook
     * @param array $hookedClasses
     */
    public function addBeforeHook(Hookable $hook, array $hookedClasses){
        //looping through the hooked classes
        foreach ($hookedClasses as $hookedClass){
            //reading the hookedMethods in the current hookedClass and looping through them
            foreach($hookedClass->getHookedMethods() as $hookedMethod){
                //appending the hook to the class method, and initializing the hookBefore array    
                $this->hookBefore[$hookedClass->getName()][$hookedMethod] = $hook;
                
            }
        }
    }
    /**
     * returns the hookAfter array
     * @return array
     */
    public function getAfterHooks(){
        
        return $this->hookAfter;
        
    }
    /**
     * returns the hookBefore array
     * @return array
     */
    public function getBeforeHooks(){
        
        return $this->hookBefore;
        
    }
}
