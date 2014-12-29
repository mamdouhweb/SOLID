<?php

namespace Handlers\Session\Classes;

/**
 * SOLID Frameword 2014
 */

/**
 * Description of Cookie
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */
class Cookie {
    
    static $locked;
    
    public function __construct() {
        if(empty($_COOKIE['data'])){
            $_COOKIE['data'] = array();
        }
        if(self::$locked != true){
            self::$locked = false;
        }
    }
    
    public function get($key){
        $value = isset($_COOKIE['data'][$key])?$_COOKIE['data'][$key]:false;
        return $value;
    }
    
    public function write($key, $value){
        if(!self::$locked){
            self::$locked = true;
            $_COOKIE['data'][$key] = $value;
            self::$locked = false;
        }else{
            $this->write($key, $value);
        }
    }
}
