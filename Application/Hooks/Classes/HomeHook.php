<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Hooks\Classes;
use Handlers\Hooks\Source\Interfaces;
use Handlers\Hooks\Source\Classes\HookedClass;
use Handlers\Hooks\Source\Abstracts\Hook;

class HomeHook extends Hook implements Interfaces\Hookable{
    
    private $hookedClases;
    
    public function init(){
        
        $hookedClass = new HookedClass;
        
        $hookedClass->setName('Home');
        
        $hookedClass->addMethod(array('test','process'));
        
        $this->hookedClases['after'][] = $hookedClass;
        
    }
    
    public function run() {
        
        echo "<br />I am a run function from HomeHook<br />";
        
    }
    
    public function getHookedClasses(){
        
        return $this->hookedClases;
        
    }

}
