<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Core\Mappers\Classes;
use Core\Mappers\Interfaces\IMapper;

abstract class DatabaseMapper implements IMapper{
    
    private $storageHandler;
    
    protected $queryHandler;
    
    protected $databaseHandler;

    public function __construct($storageName, \Facade\StorageHandler $storageHandler = NULL) {
        
        if(is_null($storageHandler)){
            
            $storageHandler = new \Facade\StorageHandler();
            
        }
        
        $this->storageHandler = $storageHandler;
        
        $this->queryHandler = $this->storageHandler->getDatabaseObject($storageName);
        
    }
    
}
