<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <all@lapp.com>
 */

namespace Handlers\Storage\Components\DataBase\Source\Factories;
use Handlers\Storage\Source\Abstracts\StorageFactory;

class DatabaseFactory extends StorageFactory{
    
    private $databaseHandler;
    
    private $queryHandler;

    const DATABASE_CLASSES_PATH = 'Handlers\\Storage\\Components\\DataBase\\Components\\SQL\\Components\\Drivers\\Source\\Classes\\';
    
    const QUERY_CLASSES_PATH = 'Handlers\\Storage\\Components\\DataBase\\Components\\SQL\\Components\\Query\\Source\\Classes\\';
    
    const CLASS_DOES_NO_EXISTS = 101;
    
    const QUERY_CLASS_POSTFIX = "Query";

    public function __construct($host, $dbname, $username, $password, $port) {
        
        parent::__construct($host, $dbname, $username, $password, $port);
        
    }
    
    public function createStorage($type) {
        
        $databaseClass = self::DATABASE_CLASSES_PATH.ucfirst(strtolower($type));
        
        if(class_exists($databaseClass, TRUE)){
            
            $this->databaseHandler = new $databaseClass($this->host, $this->dbname, $this->username, $this->password, $this->port);
            
        }else{
            
            throw new \InvalidArgumentException("the class " . $databaseClass . " does not exists", self::CLASS_DOES_NO_EXISTS);
        
        }
        
        $queryClass = self::QUERY_CLASSES_PATH.ucfirst(strtolower($type)).self::QUERY_CLASS_POSTFIX;
        
        if(class_exists($queryClass, true)){
            
            $this->queryHandler = new $queryClass($this->databaseHandler);   
            
        }else{
            
            throw new \InvalidArgumentException("the class " . $queryClass . " does not exists", self::CLASS_DOES_NO_EXISTS);
            
        }
        
        return $this->queryHandler;
    }

}
