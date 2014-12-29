<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Storage\Components\DataBase\Components\SQL\Components\Query\Source\Classes;
use Handlers\Storage\Components\DataBase\Components\SQL\Components\Query\Source\Interfaces\IQuery;
use Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Classes\Postgres;

class PostgresQuery implements IQuery{
    
    private $dbh;
    
    private $sqlString;
    
    private $tableName;
    
    private $binds;
    
    private $lastBinds;
    
    private $lastQuery;
    
    const POSTGRES_FORMAT_ERROR = 3;

    const TABLE_NAME = '**tableNameToParse';
    
    private $whereStarted;
    
    private $havingStarted;
    
    private $schema = '';
    
    public function __construct(Postgres $dbh) {
        
        $this->dbh = $dbh;
        
        $this->sqlString = "";
        
        $this->binds = array();
        
        $this->whereStarted = false;
        
        $this->havingStarted = false;
        
    }
    
    public function andCondition($leftSide, $rightSide, $operator = '', $bind = true) {
        
        $this->trimAndCheckSQLString("and condition");
        
        if($bind){
            
            $this->binds[] = $rightSide;
            
            $rightSide = '$' . (count($this->binds));
            
        }
        
        $condition = trim($leftSide) . ' ' . trim($operator) . ' ' . $rightSide;
        
        $this->sqlString = $this->sqlString . ' AND ' . $condition;
        
        return $this;
        
    }

    public function from($tableName) {
        
        $this->trimAndCheckSQLString("from statement");
        
        $tableName = empty($this->tableName)? $this->schema.$tableName: $this->tableName;
        
        $this->sqlString = $this->sqlString . ' FROM ' . $tableName;
        
        return $this;
        
    }

    public function fullJoin($tableName) {
        
       $this->trimAndCheckSQLString("full join statement");
        
        $this->sqlString = $this->sqlString . ' FULL JOIN ' .  $this->schema.$tableName;
        
        return $this;
        
    }

    public function run() {
        
        $this->dbh->execute($this->sqlString, $this->binds);
        
        $this->lastBinds = $this->binds;
        
        $this->lastQuery = $this->sqlString;
        
        $this->binds = array();
        
        $this->sqlString = '';
        
        return $this->dbh;
        
    }

    public function groupBy($statement) {
        
        $this->trimAndCheckSQLString("group by statement");
        
        $this->sqlString = $this->sqlString . ' GROUP BY ' . $statement;
        
        return $this;
        
    }
    
    public function orderBy($statement) {
        
        $this->trimAndCheckSQLString("order by statement");
        
        $this->sqlString = $this->sqlString . ' ORDER BY ' . $statement;
        
        return $this;
        
    }

    public function having($leftSide, $rightSide, $operator = '', $bind = true) {
        
        $this->trimAndCheckSQLString("having statement");
        
        if($bind){
            
            $this->binds[] = $rightSide;
            
            $rightSide = '$' . (count($this->binds));
            
        }
        
        $havingClause = ' ';
        
        if(!$this->havingStarted){
            
            $this->havingStarted = TRUE;
            
            $havingClause = ' HAVING ';
            
        }
        
        $condition = trim($leftSide) . ' ' . trim($operator) . ' ' . $rightSide;
        
        $this->sqlString = $this->sqlString . $havingClause . $condition;
        
        return $this;
        
    }

    public function insert(array $params) {
        
        if(empty($params)){
            
            throw new \InvalidArgumentException("Insert params should not be an empty array", self::POSTGRES_FORMAT_ERROR);
        
        }
        
        $fields = array();
        
        $values = array();
        
        foreach($params as $field => $value){
            
            $fields[] = $field;
            
            $this->binds[] = $value;
            
            $values[] = '$'.(count($this->binds));
            
        }
        
        $tableName = empty($this->tableName)?self::TABLE_NAME: $this->tableName;
        
        $fieldsString = trim(implode(',', $fields),',');
        
        $valuesString = trim(implode(',', $values),',');
        
        $this->sqlString = 'INSERT INTO ' . $tableName . '(' . $fieldsString . ')' . ' VALUES' . '(' .$valuesString . ')';
        
        return $this;
        
    }

    public function into($tableName = '') {
        
        $this->trimAndCheckSQLString("into statement");
        
        $tableName = empty($tableName)?$this->tableName:  $this->schema.$tableName;
        
        $this->sqlString = str_replace(self::TABLE_NAME, $tableName, $this->sqlString);
        
        return $this;
        
    }

    public function join($tableName) {
        
        $this->trimAndCheckSQLString("join statement");
        
        $this->sqlString = $this->sqlString . " JOIN " .  $this->schema.$tableName;
        
        return $this;
        
    }

    public function leftJoin($tableName) {
        
        $this->trimAndCheckSQLString("left join statement");
        
        $this->sqlString = $this->sqlString . " LEFT JOIN " .  $this->schema.$tableName;
        
        return $this;
        
    }

    public function like($field, $value) {
        
        $this->trimAndCheckSQLString("like statement");
        
        $this->binds[] = '%' . $value . '%';
        
        $value = '$' . (count($this->binds));
        
        $whereClause = ' ';
        
        if(!$this->whereStarted){
            
            $this->whereStarted = TRUE;
            
            $whereClause = ' WHERE ';
            
        }
        
        $condition = $whereClause . trim($field) . ' LIKE ' . $value;
        
        $this->sqlString = $this->sqlString . $condition;
        
        return $this;
        
    }

    public function on($leftSide, $rightSide, $operator = '', $bind = true) {
        
        $this->trimAndCheckSQLString("on condition");
        
        if($bind){
            
            $this->binds[] = $rightSide;
            
            $rightSide = '$' . (count($this->binds));
            
        }
        
        $condition = trim($leftSide) . ' ' . trim($operator) . ' ' . $rightSide;
        
        $this->sqlString = $this->sqlString . ' ON ' . $condition;
        
        return $this;
        
        
    }

    public function orCondition($leftSide, $rightSide, $operator = '', $bind = true) {
        
        
        $this->trimAndCheckSQLString("or condition");
        
        if($bind){
            
            $this->binds[] = $rightSide;
            
            $rightSide = '$' . (count($this->binds));
            
        }
        
        $condition = trim($leftSide) . ' ' . trim($operator) . ' ' . $rightSide;
        
        $this->sqlString = $this->sqlString . ' OR ' . $condition;
        
        return $this;
        
    }

    public function rightJoin($tableName) {
        
        $this->trimAndCheckSQLString("right join statement");
        
        $this->sqlString = $this->sqlString . ' RIGHT JOIN ' .  $this->schema.$tableName;
        
        return $this;
        
    }

    public function select(array $params) {
        
        if(empty($params)){
            
            throw new \InvalidArgumentException("Select params should not be an empty array", self::POSTGRES_FORMAT_ERROR);
        
        }
        
        $fieldsString = trim(implode(',', $params),',');
        
        $fromClause = empty($this->tableName)?'': ' FROM ' . $this->tableName;
        
        $this->sqlString = 'SELECT ' . $fieldsString . $fromClause;
        
        return $this;
        
    }

    public function set(array $params) {
        
        if(empty($params)){
            
            throw new \InvalidArgumentException("set params should not be an empty array", self::POSTGRES_FORMAT_ERROR);
        
        }
        
        $this->trimAndCheckSQLString("set statement");
        
        $setString = ' SET ';
        
        foreach($params as $array){
            
            $bindFlag = isset($array['bind'])?$array['bind']:TRUE;
            
            if($bindFlag){
                
                $this->binds[] = $array['value'];
                
                $setString .= $array['field'] . '=' . '$' . (count($this->binds)) . ', ';
                
            }else{
                
                $setString .= $array['field'] . '=' . $array['value'] . ', ';
                
            }
            
        }
        
        $this->sqlString .= rtrim($setString,"\ ..,");
        
        return $this;
    }

    public function setTableName($tableName) {
        
        $this->tableName = $this->schema . $tableName;
        
        return $this;
    }

    public function update($tableName) {
        
        $this->sqlString = 'UPDATE ' .  $this->schema.$tableName;
        
        return $this;
    }

    public function using($field) {
        
        $this->trimAndCheckSQLString("using statement");
        
        $this->sqlString = $this->sqlString . ' USING(' . $field . ')';
        
        return $this;
        
    }

    public function where($leftSide, $rightSide, $operator = '', $bind = true) {
        
        $this->trimAndCheckSQLString("where statement");
        
        if($bind){
            
            $this->binds[] = $rightSide;
            
            $rightSide = '$' . (count($this->binds));
            
        }
        
        $condition = trim($leftSide) . ' ' . trim($operator) . ' ' . $rightSide;
        
        $whereClause = ' ';
        
        if(!$this->whereStarted){
            
            $this->whereStarted = TRUE;
            
            $whereClause = ' WHERE ';
            
        }
        
        $this->sqlString = $this->sqlString . $whereClause . $condition;
        
        return $this;
        
    }

    public function xorCondition($leftSide, $rightSide, $operator = '', $bind = true) {
        
        $this->trimAndCheckSQLString("xor condition");
        
        if($bind){
            
            $this->binds[] = $rightSide;
            
            $rightSide = '$' . (count($this->binds));
            
        }
        
        $condition = trim($leftSide) . ' ' . trim($operator) . ' ' . $rightSide;
        
        $this->sqlString = $this->sqlString . ' XOR ' . $condition;
        
        return $this;
        
    }
    
    public function aAnd(){
        
        $this->trimAndCheckSQLString("aritificial and");
        
        $this->sqlString = $this->sqlString . ' AND ';
        
        return $this;
    }
    
    public function aOr(){
        
        $this->trimAndCheckSQLString("aritificial or");
        
        $this->sqlString = $this->sqlString . ' OR ';
        
        return $this;
    }
    
    public function aXor(){
        
        $this->trimAndCheckSQLString("aritificial xor");
        
        $this->sqlString = $this->sqlString . ' XOR ';
        
        return $this;
    }
    
    public function alias($alias){
        
        $this->trimAndCheckSQLString("as keyword");
        
        $this->sqlString = $this->sqlString . ' AS ' . $alias;
        
        return $this;
    }
    
    public function query($query, array $params = array()){
        
        $this->sqlString = $query;
        
        $this->binds = $params;
        
        return $this;
        
    }
    
    public function lastQuery(){
        
        return $this->lastQuery;
        
    }
    
    public function lastBinds(){
        
        return $this->lastBinds;
        
    }
    
    public function openParentheses(){
        
        $this->trimAndCheckSQLString("open parentheses");
        
        $this->sqlString = $this->sqlString . ' ( ';
        
        return $this;
    }
    
    public function closeParentheses(){
        
        $this->trimAndCheckSQLString("close parentheses");
        
        $this->sqlString = $this->sqlString . ' ) ';
        
        return $this;
        
    }
    
    public function openCase(){
        
        $this->trimAndCheckSQLString("open case");
        
        $this->sqlString = $this->sqlString . ' CASE ';
        
        return $this;
    }
    
    public function when($leftSide, $rightSide, $operator = '', $bind = true, $wrapWithParentheses = true){
        
        $this->trimAndCheckSQLString("when statement");
        
        if($bind){
            
            $this->binds[] = $rightSide;
            
            $rightSide = '$' . (count($this->binds));
            
        }
        
        $condition = $wrapWithParentheses?'(':'' . trim($leftSide) . ' ' . trim($operator) . ' ' . $rightSide . $wrapWithParentheses?')':'';
        
        $this->sqlString = $this->sqlString . ' WHEN ' . $condition;
        
        return $this;
    }
    
    public function then($leftSide, $rightSide, $operator = '', $bind = true, $wrapWithParentheses = true){
        
        $this->trimAndCheckSQLString("then statement");
        
        if($bind){
            
            $this->binds[] = $rightSide;
            
            $rightSide = '$' . (count($this->binds));
            
        }
        
        $condition = $wrapWithParentheses?'(':'' . trim($leftSide) . ' ' . trim($operator) . ' ' . $rightSide . $wrapWithParentheses?')':'';
        
        $this->sqlString = $this->sqlString . ' THEN ' . $condition;
        
        return $this;
    }
    
    public function endCase(){
        
        $this->trimAndCheckSQLString("end case");
        
        $this->sqlString = $this->sqlString . ' END ';
        
        return $this;
        
    }
    
    private function trimAndCheckSQLString($functionName){
        
        $this->sqlString = trim($this->sqlString);  
        
        if(empty($this->sqlString)){
            
            throw new \LogicException($functionName . " should be appended, you are starting with it", self::POSTGRES_FORMAT_ERROR);
        
        }
        
    }
    
    public function startTransaction(){
        $this->dbh->execute('BEGIN;');
    }
    
    public function commit(){
        $this->dbh->execute('COMMIT;');
    }
    
    public function rollback(){
        $this->dbh->execute('ROLLBACK;');
    }
    
    public function setSchema($schema){
        
        $this->schema = $schema;
        
        return $this;
    }

}
