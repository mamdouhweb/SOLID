<?php

/**
 * SOLID Frameword 2014
 */
namespace Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Classes;
use Handlers\Storage\Source\Interfaces\IStorage;
use Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Interfaces\Driver;
use \Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Abstracts\Connection;
/**
 * Description of Mysql
 * TODO: add support to bind blob params
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */
class Mysql extends Connection implements Driver, IStorage{
    
    private $results;
    
    private $statement;
    /**
     * temp variable to hold the results of mysqli
     * @var mysqli_result
     */
    private $mysqliResults;
    
    const MYSQLI_QUERY_ERROR = 1;
    
    const MYSQLI_CONNECTION_ERROR = 2;
    
    private static $connection;
    
    public function __construct($host, $dbname, $username, $password) {
        parent::__construct($host, $dbname, $username, $password);
        
    }
    
    public function affectedRows() {
        
        return mysqli_affected_rows(self::$connection);
        
    }

    public function execute($query, $params = array()) {
        
        $this->connect();
        
        if(!is_resource(self::$connection) || mysqli_connect_errno()){
            
            throw new \RuntimeException("There is no connection open", self::MYSQLI_CONNECTION_ERROR);
            
        }
        
        if(empty($params)){
            
            $this->results =  mysqli_query(self::$connection, $query);
            
        }else{
            
            $this->statement =  self::$connection->prepare($query);
            
            $types = $this->getParamsTypes($params);
            
            call_user_func_array('mysqli_stmt_bind_param', array_merge (array($this->statement, $types), $params)); 
            
            $this->mysqliResults = mysqli_stmt_execute($this->statement);
        }
        
        return $this;
        
    }
    
    private function getParamsTypes(array $params){
        $type = '';
        foreach($params as $param){
            if(is_numeric($param)){
                if((int)$param == $param){
                    $type .= 'i';
                }else{
                    $type .= 'd';
                }
            }else{
                $type .='s';
            }
        }
        return $type;
    }

    public function numRows() {
        
        if($this->results == false){
            
            throw new \InvalidArgumentException("numRows expects parameter 1 to be resource, boolean given", self::MYSQLI_CONNECTION_ERROR);
            
        }
        
        return mysqli_num_rows($this->mysqliResults);
        
    }

    public function result() {
        
        if($this->mysqliResults == false){
            
            throw new Exception($this->lastError(),  self::MYSQLI_QUERY_ERROR);
            
        }
        
       while($row = mysqli_fetch_array($this->mysqliResults)){
            $this->results[] = $row;
        }
        
        return $this->results;
        
    }

    public function close() {
        
        if(!is_resource(self::$connection)){
            
            throw new \RuntimeException("There is no connection open", self::MYSQLI_CONNECTION_ERROR);
            
        }
        
        $this->statement->close();
        
        self::$connection->close();
        
    }

    public function connect() {
        
        if(!is_resource(self::$connection)){
            
            if ((self::$connection = mysqli_connect($this->host,  $this->username, $this->password, $this->dbname))) {
                
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
        
        if(mysqli_connect_error()){
            return false;
        }
        return true;
        
    }

    public function getDSN() {
        
        return $this->dsn;
        
    }

    public function lastError() {
        
        if(!is_resource(self::$connection)){
            
            throw new \RuntimeException("There is no connection open", self::MYSQLI_CONNECTION_ERROR);
            
        }
        
        return mysqli_error_list(self::$connection);
        
    }

    public function getReturningVaule() {
        return null;
    }
    
    public function getLastInsertedId(){
        if(!is_resource(self::$connection)){
            
            throw new \RuntimeException("There is no connection open", self::MYSQLI_CONNECTION_ERROR);
            
        }
        
        return mysqli_insert_id(self::$connection);
    }

}
