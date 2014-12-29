<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Request\Components\Http\Classes;
use Handlers\Request\Source\Abstracts\Request;
use Handlers\Request\Source\Interfaces\IRequest;

class Http extends Request{
    
    public $method;
    
    public $queryString;
    
    public $params;
    
    private $headerData;
    
    public function __construct(IRequest $method , $headerData) {
        $this->method = $method;
        $this->headerData= is_array($headerData)?$headerData:array();
    }
    /**
     * setup process for the class attributes
     */
    public function InitializeRequest(){
        
        $this->method->dissect($this, $this->headerData);
        
        unset($this->headerData);
        
    }
    
    public function setConnection($connectionType) {
        
        $this->connection = trim($connectionType);
        
    }

    public function setEncoding($acceptEncoding) {
        
        $this->encoding = array_map('trim' ,explode(',', $acceptEncoding));
        
    }

    public function setHost($host) {
        
        $this->host = trim($host);
        
    }

    public function setRemoteUser($remoteUserIP) {
        
        $this->remoteUser = trim($remoteUserIP);
        
    }

}
