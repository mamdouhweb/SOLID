<?php

/*
 * SOLID Framwork 2014
 */

/**
 * Each Driver should extend this class
 * Defines the blue-print of Database Drivers
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Storage\Components\DataBase\Components\SQL\Components\Drivers\Source\Interfaces;


interface Driver{

    public function affectedRows();
    
    public function execute($query, $params);
    
    public function result();
    
    public function getReturningVaule();
    
    public function numRows();
    
    public function lastError();
}
