<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Request\Components\Http\Factories;
use Handlers\Request\Components\Http\Classes;
use Handlers\Request\Source\Interfaces\IRequestFactory;

class HttpFactory implements IRequestFactory{
    
    private $requestType;
    
    const HTTP_CLASSES_PATH = "Handlers\\Request\\Components\Http\\Classes\\";
    
    public function __construct($requestType = NULL) {
        
        $this->requestType = $requestType;
        
    }

    public function createRequest($requestType = NULL) {
        
        $request = is_null($requestType)?$this->requestType:$requestType;
        
        $requestClassName = self::HTTP_CLASSES_PATH . ucfirst(strtolower($request));
        
        return new $requestClassName;
    }

}
