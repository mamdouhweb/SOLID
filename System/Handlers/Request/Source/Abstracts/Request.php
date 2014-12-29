<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <all@lapp.com>
 */

namespace Handlers\Request\Source\Abstracts;

abstract class Request {
    
    protected $connection;
    
    protected $encoding;
    
    protected $host;
    
    protected $remoteUser;
    
    public function getConnection(){
        
        return $this->connection;
        
    }
    
    public function getEncoding(){
        
        return $this->encoding;
        
    }
    
    public function getHost(){
        
        return $this->host;
        
    }
    
    public function getRemoteUser(){
        
        return $this->remoteUser;
        
    }
    
    abstract function setConnection($connectionType);
    
    abstract function setEncoding($acceptEncoding);
    
    abstract function setHost($host);
    
    abstract function setRemoteUser($remoteUserIP);
}
