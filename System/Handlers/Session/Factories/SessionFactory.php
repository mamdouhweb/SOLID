<?php

namespace Handlers\Session\Factories;
use Handlers\Session\Classes;
/**
 * SOLID Frameword 2014
 */

/**
 * Description of SessionFactory
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */
class SessionFactory {
    private $sessionType;
    
    const SESSION_CLASS_PATH = "Handlers\\Session\\Classes\\";
    
    public function __construct($sessionType = NULL) {
        
        $this->sessionType = $sessionType;
        
    }

    public function createSession($key, $sessionType = NULL, $encrypt = TRUE, $ttl = 1800, $encryptMethod = 'BLOWFISH', $rounds = 10, $savePath = null) {
        
        $session = is_null($sessionType)?ucfirst($this->sessionType).'Session':ucfirst($sessionType).'Session';
        
        $sessionClassName = self::SESSION_CLASS_PATH . $session;
        
        return new $sessionClassName($key, $encrypt, $ttl, $encryptMethod, $rounds, $savePath);
    }
}
