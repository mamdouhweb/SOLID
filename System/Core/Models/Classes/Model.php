<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Core\Models\Classes;
use Handlers\Event\Source\Abstracts\EventObserver;


class Model extends EventObserver{
    
    protected $protect = array('*');
    
    protected $obligate = array();
    
    private static $instances = array();
    
    public function __construct() {
        
        parent::__construct();
        
        self::$instances[] = $this;
        
    }
    
    public function getProtected(){
        
        return $this->protect;
        
    }
    
    public function getRequired(){
        
        return $this->obligate;
        
    }
    
    public function protect($property){
        
        $this->protect[] = $property;
        
    }
    
    public function obligate($property){
        
        $this->obligate[] = $property;
        
    }

}
