<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <all@lapp.com>
 */

namespace Handlers\Storage\Source\Abstracts;

abstract class StorageFactory {
    
    protected $host;
    protected $dbname;
    protected $username;
    protected $password;
    protected $port;
    
    public function __construct($host, $dbname, $username, $password, $port) {
        
        $this->host = $host;
        
        $this->dbname = $dbname;
        
        $this->username = $username;
        
        $this->password = $password;
        
        $this->port = $port;
        
    }


    abstract function createStorage($type);
    
}
