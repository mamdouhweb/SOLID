<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Hooks\Source\Classes;

class HookedClass {
    /**
     * the hooked controller name
     * @var string
     */
    private $name;
    /**
     * the hooked controller methods names
     * @var array
     */
    private $methods;
    /**
     * class constructor
     */
    public function __construct() {
        
        $this->methods = array();
        
    }

    /**
     * append a method to the methods array
     * @param string|array $method
     * @throws RuntimeException
     */
    public function addMethod($method){
        //checking if the method variable is array and it has indexes
        if(is_array($method) && !empty($method)){
            //loop though it
            foreach($method as $hookedClassMethod){
                //append the method to the methods array
                $this->methods[] = $hookedClassMethod;
                
            }
        //if the method variable is not array but not empty then it should be a string or any other value
        //other checks might be valid such as making sure it is a string
        }elseif(!is_array($method) && !empty($method)){
                //append the method to the methods array
                $this->methods[] = $hookedClassMethod;
            
        }else{
            //throw an exception if the passed argument does not match our conditions
            throw new RuntimeException('addMethod expect 1 argument and it should not be empty');
            
        }
    }
    /**
     * returns the methods array
     * @return array
     */
    public function getHookedMethods(){
        
        return $this->methods;
        
    }
    /**
     * returns the hooked class name
     * @return string
     */
    public function getName(){
        
        return $this->name;
        
    }
    /**
     * sets the hooked class name
     * @param string $name
     */
    public function setName($name){
        
        $this->name = $name;
        
    }
}
