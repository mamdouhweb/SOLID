<?php
/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Request\Components\Http\Classes;
use Handlers\Request\Source\Interfaces\IRequest;
use Handlers\Request\Components\Http\Classes\Http;

class Get implements IRequest{
    
    public function dissect(Http $http, array $headerData){
        
        $http->queryString = $headerData['QUERY_STRING'];
        
        $http->params = $this->getParams();
        
        $http->setConnection($headerData['HTTP_CONNECTION']);
        
        $http->setEncoding($headerData['HTTP_ACCEPT_ENCODING']);
        
        $http->setHost($headerData['HTTP_HOST']);
        
        $http->setRemoteUser($headerData['REMOTE_ADDR']);
    }

    public function getParams() {
        
        $params = filter_input_array(INPUT_GET)?:array();
        
        return $params;
        
    }
}
