<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Configuration\Source\Classes;
use Handlers\Configuration\Source\Interfaces\IConfiguration;

class Configuration implements IConfiguration{
    /**
     * array of configurations
     * @var array
     */
    private $configuration;
    /**
     * class constructor
     * @param array $configuration
     */
    public function __construct(array $configuration = array()) {
    
        $this->configuration = $configuration;
        
    }
    /**
     * return the configuration value of the passed configuration variable name
     * @param type $configurationVariableName
     * @return boolean|string
     */
    public function get($configurationVariableName) {
        
        if(array_key_exists($configurationVariableName, $this->configuration)){
            
            return $this->configuration[$configurationVariableName];
            
        }
        
        return FALSE;
        
    }
    /**
     * append a configuration to the configuration array
     * @param string $configurationVariableName
     * @param string $configurationVariableValue
     */
    public function set($configurationVariableName, $configurationVariableValue) {
        
        $this->configuration[$configurationVariableName] = $configurationVariableValue;
        
    }
    /**
     * return the array of configurations
     * @return array
     */
    public function getConfigurationArray(){
        
        return $this->configuration;
        
    }

}
