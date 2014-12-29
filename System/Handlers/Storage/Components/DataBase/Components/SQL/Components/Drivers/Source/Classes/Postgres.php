<?php

/*
 * SOLID Framework 2014
 */

/**
 * Postgres Database Driver
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Classes;
use Handlers\Storage\Source\Interfaces\IStorage;
use Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Interfaces\Driver;
use \Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Abstracts\Connection;

class Postgres extends Connection implements Driver, IStorage{
    
    private $results;
    
    const PG_QUERY_ERROR = 1;
    
    const PG_CONNECTION_ERROR = 2;
    
    private static $connection;

    public function __construct($host, $dbname, $username, $password, $port) {
        
        parent::__construct($host, $dbname, $username, $password, $port);
        
        $this->dsn = "host=" . $this->host . " dbname=". $this->dbname. " user=" . $this->username . " password=" . $this->password . " port=" . $this->port;
        
        $this->results = false;
                
    }
    
    public function affectedRows() {
        
        return pg_affected_rows($this->results);
        
    }

    public function execute($query, $params = array()) {
        
        $this->connect();
        
        if(!is_resource(self::$connection)){
            
            throw new \RuntimeException("There is no connection open", self::PG_CONNECTION_ERROR);
            
        }
        
        if(empty($params)){
            
            $this->results =  pg_query(self::$connection, $query);
            
        }else{
            
            $this->results =  pg_query_params(self::$connection, $query, $params);
            
        }
        
        return $this;
        
    }
    /**
     * @deprecated since version 0.2
     */
    public function getReturningVaule() {
        
        if($this->results == false){
            
            throw new Exception($this->lastError(),  self::PG_QUERY_ERROR);
            
        }
        
        return pg_fetch_all($this->results);
        
    }

    public function numRows() {
        
        if($this->results == false){
            
            throw new \InvalidArgumentException("numRows expects parameter 1 to be resource, boolean given", self::PG_CONNECTION_ERROR);
            
        }
        
        return pg_num_rows($this->results);
        
    }

    public function result() {
        
        if($this->results == false){
            
            throw new Exception($this->lastError(),  self::PG_QUERY_ERROR);
            
        }
        
        $result =  pg_fetch_all($this->results);
        
        return $result;
        
    }

    public function close() {
        
        if(!is_resource(self::$connection)){
            
            throw new \RuntimeException("There is no connection open", self::PG_CONNECTION_ERROR);
            
        }
        
        pg_close(self::$connection);
        
    }

    public function connect() {
        
        if(!is_resource(self::$connection)){
            
            if ((self::$connection = pg_connect($this->dsn))) {
                
                return TRUE;
            
            }
            
            return FALSE;
            
        }
        
        return TRUE;
        
    }

    public function connectionStatus() {
        
        if(!is_resource(self::$connection)){
            
            throw new \RuntimeException("There is no connection open", self::PG_CONNECTION_ERROR);
            
        }
        
        return pg_connection_status(self::$connection);
        
    }

    public function getDSN() {
        
        return $this->dsn;
        
    }

    public function lastError() {
        
        if(!is_resource(self::$connection)){
            
            throw new \RuntimeException("There is no connection open", self::PG_CONNECTION_ERROR);
            
        }
        
        return pg_last_error(self::$connection);
        
    }
}
