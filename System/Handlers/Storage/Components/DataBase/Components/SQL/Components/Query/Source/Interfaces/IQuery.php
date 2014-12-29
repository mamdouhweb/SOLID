<?php

/*
 * SOLID Framework 2014
 */

/**
 * All query concrete classes should implement this interface 
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */


namespace Handlers\Storage\Components\DataBase\Components\SQL\Components\Query\Source\Interfaces;

interface IQuery {
    
    public function select(array $params);
    
    public function from($tableName);
    
    public function join($tableName);
    
    public function leftJoin($tableName);
    
    public function rightJoin($tableName);
    
    public function fullJoin($tableName);
    
    public function on($leftSide, $rightSide, $operator = '', $bind = false);
    
    public function using($field);
    
    public function where($leftSide, $rightSide, $operator = '', $bind = where);
    
    public function groupBy($statement);
    
    public function having($leftSide, $rightSide, $operator = '', $bind = true);
    
    public function like($field, $value);
    
    public function insert(array $params);
    
    public function into($tableName = '');
    
    public function update($tableName);
    
    public function setTableName($tableName);
    
    public function set(array $params);
    
    public function andCondition($leftSide, $rightSide, $operator = '', $bind = false);
    
    public function orCondition($leftSide, $rightSide, $operator = '', $bind = false);
    
    public function xorCondition($leftSide, $rightSide, $operator = '', $bind = false);
    
    public function run();
    
    public function aAnd();
    
    public function aOr();
    
    public function aXor();
    
    public function alias($alias);
    
    public function query($query, array $params = array());
    
    public function lastQuery();
    
    public function lastBinds();
    
    public function openParentheses();
    
    public function closeParentheses();
    
    
}
