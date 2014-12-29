<?php

/**
 * SOLID Frameword 2014
 */
namespace Handlers\Session\Abstracts;
/**
 * Description of Session
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */
abstract class Session {
    
    protected $key;
    
    protected $rounds;
    
    protected $sessionId;

    protected $encryptionMethod;
    
    protected $ttl;
    
    protected $encrypt;

    public function __construct($key, $encrypt = TRUE, $ttl=1800, $encryptMethod = 'BLOWFISH', $rounds = 10) {
        $this->key = $key;
        if(empty($this->key)){
            trigger_error("Session key cannot be null", E_USER_ERROR);
        }
        $this->rounds = $rounds;
        $this->encryptionMethod = $encryptMethod;
        $this->ttl = $ttl;
        $this->encrypt = $encrypt;
    }
    
    public function generateSessionId(){
        $randomHashedNumber = md5($this->key);
        $this->sessionId = sha1(bin2hex(openssl_random_pseudo_bytes(16)) . $randomHashedNumber);
    }
    
    public function encryptSession($sessionData){
        $session = array();
        switch ($this->encryptionMethod){
            case 'BLOWFISH':
            default:
                $session = $this->encrypt($sessionData);
                break;
            
        }
        return $session;
    }
    
    public function decryptSession($sessionData){
        $session = array();
        switch ($this->encryptionMethod){
            case 'BLOWFISH':
            default:
                $session = $this->decrypt($sessionData);
                break;
            
        }
        return $session;
    }
    
    protected function encrypt($data){
        $ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $encryptedString = mcrypt_encrypt(MCRYPT_BLOWFISH, $this->key, utf8_encode($data), MCRYPT_MODE_CBC, $iv);
        return $encryptedString;
    }
    
    protected function decrypt($session) {
        $ivSize = mcrypt_get_iv_size(MCRYPT_BLOWFISH, MCRYPT_MODE_CBC);
        $iv = mcrypt_create_iv($ivSize, MCRYPT_RAND);
        $decryptedString = mcrypt_decrypt(MCRYPT_BLOWFISH, $this->key, $session, MCRYPT_MODE_CBC, $iv);
        return $decryptedString;
    }
    
    protected function serializeSession($session){
        return serialize($session);
    }
    
    protected function deserializeSession($session){
        if(!empty($session)){
            return unserialize($session);
        }
    }
    
}
