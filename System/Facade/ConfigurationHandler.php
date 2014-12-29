<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <all@lapp.com>
 */

namespace Facade;
use Handlers\Configuration\Source\Interfaces;
use Handlers\Configuration\Source\Classes\Configuration;
use Handlers\Loader\Source\Interfaces\ILoader;
use Handlers\Loader\Source\Classes\Loader;

class ConfigurationHandler implements Interfaces\Configurable {
    /**
     * array of configuration
     * @var array
     */
    private $configuration;
    /**
     * holds the path to the main system configuration folder
     * @var string
     */
    private $SYSTEM_CONFIGURATION_PATH; 
    /**
     * holds the path to the main system configuration folder
     * @var string
     */
    private $APPLICATION_CONFIGURATION_PATH; 
    /**
     * holds the server document root
     * @var string
     */
    private $documentRoot;
    /**
     * holds a loader instance
     * @var \Handlers\Loader\Source\Interfaces\ILoader 
     */
    private $loader;
    /**
     * constant of the default application configuration path
     */
    const DEFAULT_APPLICATION_CONFIGURATION_PATH = '/Application/Configuration';
    /**
     * constant of the default application configuration path
     */
    const DEFAULT_SYSTEM_CONFIGURATION_PATH = '/System/Configuration';
    /**
     * class constructor
     * @param \Handlers\Loader\Source\Interfaces\ILoader $loader
     * @param array $configuration
     * @param string $applicationConfigurationPath
     * @param string $systemConfigurationPath
     * @param string $documentRoot
     */
    public function __construct(ILoader $loader = NULL, array $configuration = array(), $applicationConfigurationPath = NULL, $systemConfigurationPath = NULL, $documentRoot = NULL) {
        
        if(is_null($documentRoot)){
            
            $documentRoot = filter_input(INPUT_SERVER, 'DOCUMENT_ROOT');
            
        }
        
        $this->documentRoot = $documentRoot;
        
        if(is_null($applicationConfigurationPath)){
            
            $applicationConfigurationPath = $this->documentRoot . DOCUMENT_SEPARATOR . APPNAME . self::DEFAULT_APPLICATION_CONFIGURATION_PATH;
            
        }
        
        if(is_null($systemConfigurationPath)){
            
            $systemConfigurationPath = $this->documentRoot . DOCUMENT_SEPARATOR . APPNAME . self::DEFAULT_SYSTEM_CONFIGURATION_PATH;
            
        }
        
        if(is_null($loader)){
            
            $loader = new Loader;
            
        }
        
        $this->configuration = $configuration;
        
        $this->APPLICATION_CONFIGURATION_PATH = $applicationConfigurationPath;
        
        $this->SYSTEM_CONFIGURATION_PATH = $systemConfigurationPath;
        
        $this->loader = $loader;
        
    }
    /**
     * reset the configuration array with a new value
     * @param \Handlers\Configuration\Source\Interfaces\IConfiguration $configuration
     * @param string $nodeName
     */
    public function reload(Interfaces\IConfiguration $configuration, $nodeName) {
        
        $this->configuration = array();
        
        $this->configuration[$nodeName] = $configuration;
        
    }
    /**
     * reads the configuration and loads them into the configuration array
     * @param string $fullPath
     * @param Boolean $isDirectory
     */
    public function loadConfiguration($fullPath, $isDirectory){
        //getting an array of arrays of configuration using the loader
        $configurations = $this->loader->load($fullPath, $isDirectory);
        //loop through them
        foreach($configurations as $nodeName => $configuration){
            //each configuration index will have the key as the file name and a value of configuration instance
            $this->configuration[$nodeName] = new Configuration;
            //loop through the configuration array
            foreach($configuration as $key => $value){
                //set the key and value of the configuration with the configuration instance
                $this->configuration[$nodeName]->set($key, $value);
                
            }
        }
    }
    /**
     * Alias for loadConfiguration method with system configuration folder path passed to it
     */
    public function loadSystemConfiguration(){
        
        $this->loadConfiguration($this->SYSTEM_CONFIGURATION_PATH, TRUE);
        
    }
    /**
     * Alias for loadConfiguration method with application configuration folder path passed to it
     */
    public function loadApplicationConfiguration(){
        
        $this->loadConfiguration($this->APPLICATION_CONFIGURATION_PATH, TRUE);

    }
    /**
     * returns the configuration array
     * @return array
     */
    public function getAllConfiguration(){
        
        return $this->configuration;
        
    }
    /**
     * gets a configuration instance by node name (file name)
     * @param string $nodeName
     * @return \Handlers\Configuration\Source\Interfaces\IConfiguration|boolean
     */
    public function getConfigurationNode($nodeName){
        //check if the node name exists
        if(array_key_exists($nodeName, $this->configuration)){
            //if yes return it
            return $this->configuration[$nodeName];
            
        }
        //return false otherwise
        return FALSE;
    }

}
