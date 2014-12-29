<?php

/*
 * SOLID Framework 2014
 */

/**
 * Each Driver should extend this class
 * Defines the blue-print of Database conection
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Abstracts;
use Handlers\Storage\Components\DataBase\Source\Interfaces\Connectable;

abstract class Connection implements Connectable{
    
    protected $host;
    
    protected $dbname;
    
    protected $username;
    
    protected $password;
    
    protected $port;
    
    protected $dsn;

    protected function __construct($host, $dbname, $username, $password, $port) {
        
        $this->host = $host;
        
        $this->dbname = $dbname;
        
        $this->username = $username;
        
        $this->password = $password;
        
        $this->port = $port;
        
    }

    abstract public function connectionStatus();
    
    abstract public function getDSN();
    
}
