<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Facade;
use Handlers\Storage\Components\DataBase\Source\Factories\DatabaseFactory;
use Handlers\Configuration\Source\Interfaces\IConfiguration;

class StorageHandler {
    
    private $configurationHandler;
    
    private $databaseFactory;
    
    private $configuration;
    
    const DATABASE_CONFIGURATION_PATH = "Application/Configuration/Database";
    
    public function __construct(ConfigurationHandler $configurationHandler = NULL) {
        
        if(is_null($configurationHandler)){
            
            $configurationHandler = new ConfigurationHandler();
            
        }
        
        $this->configurationHandler = $configurationHandler;
        
    }
    
    public function getDatabaseObject($name){
        
        $this->readDatabaseConfiguration($name);
        
        if(!($this->configuration instanceof IConfiguration)){
            
            throw new \LogicException("Configuration not found for database " . $name);
        
        }
        
        $host = $this->configuration->get('hostName');
        $dbname = $this->configuration->get('databaseName');
        $username = $this->configuration->get('username');
        $password = $this->configuration->get('password');
        $port = $this->configuration->get('port');
        
        $this->databaseFactory = new DatabaseFactory($host, $dbname, $username, $password, $port);
        
        return $this->databaseFactory->createStorage($name);
        
    }
    
    private function readDatabaseConfiguration($name){
        
        $fileName = ucfirst(strtolower($name));
        
        $fullPath = ROOT . DOCUMENT_SEPARATOR . APPNAME . DOCUMENT_SEPARATOR . self::DATABASE_CONFIGURATION_PATH;
        
        $this->configurationHandler->loadConfiguration($fullPath, TRUE);
        
        $this->configuration = $this->configurationHandler->getConfigurationNode($fileName);
        
    }
    
}
