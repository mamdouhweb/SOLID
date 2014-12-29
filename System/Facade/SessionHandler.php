<?php

/**
 * SOLID Frameword 2014
 */
namespace Facade;
use Handlers\Configuration\Source\Interfaces\IConfiguration;
use Handlers\Session\Factories\SessionFactory;
/**
 * Description of SessionHandler
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */
class SessionHandler {
    
    private $configurationHandler;
    
    private $sessionFactory;
    
    private $configuration;
    
    const SESSION_CONFIGURATION_PATH = "Application/Configuration/Session";
    
    public function __construct(ConfigurationHandler $configurationHandler = NULL) {
        
        if(is_null($configurationHandler)){
            
            $configurationHandler = new ConfigurationHandler();
            
        }
        
        $this->configurationHandler = $configurationHandler;
        
        $this->initSession();
        
    }
    
    private function initSession(){
        $this->readSessionConfiguration();
        $sessionType = $this->configuration->get('session_type');
        $ttl = $this->configuration->get('ttl');
        $encrypt = $this->configuration->get('encrypt');
        $encryptMethod = $this->configuration->get('encryptMethod');
        $rounds = $this->configuration->get('rounds');
        $savePath = $this->configuration->get('savepath');
        $key = $this->configuration->get('key');
        $secureCookie = $this->configuration->get('secure');
        $sessionEnabled = $this->configuration->get('enabled');
        
        
        if($sessionEnabled){
            $this->sessionFactory = new SessionFactory();

            session_set_save_handler($this->sessionFactory->createSession(
                    $key, $sessionType, $encrypt, $ttl, $encryptMethod, $rounds, $savePath
                    ), true);
            session_set_cookie_params(
			$ttl,
			$savePath,
			null,
			$secureCookie,
			TRUE 
		);
            session_start();
        }
        
    }
    
    private function readSessionConfiguration(){
        
        $fullPath = ROOT . DOCUMENT_SEPARATOR . APPNAME . DOCUMENT_SEPARATOR . self::SESSION_CONFIGURATION_PATH;
        
        $this->configurationHandler->loadConfiguration($fullPath, TRUE);
        
        $this->configuration = $this->configurationHandler->getConfigurationNode('Session');
        
    }
    
    public function get($key){
        if(isset ($_SESSION[$key])){
            return $_SESSION[$key];
        }
        return FALSE;
    }
    
    public function set($key, $value){
        $_SESSION[$key] = $value;
        session_write_close();
    }
}
