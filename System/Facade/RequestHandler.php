<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Facade;
use \Handlers\Request\Components\Http\Classes\Http;
use \Handlers\Request\Components\Http\Factories\HttpFactory;
use \Handlers\Request\Source\Interfaces\IRequest;

class RequestHandler {
    /**
     * application controller path
     */
    const APPLICATION_CONTROLLER_PATH = "ApplicationControllers\\";
    /**
     * controller default method name
     */
    const MAIN_METHOD_NAME = "main";
    /**
     * holds server request array
     * @var array
     */
    private $server;
    /**
     * Http instance
     * @var Http
     */
    private $http;
    /**
     * HttpFactory instance
     * @var HttpFactory
     */
    private $methodFactory;
    /**
     * holds all the calls which are registered with either
     * <b>$RequestHandler->get()</b> OR <b>$RequestFactory->post()</b>
     * @var \ArrayIterator
     */
    private $callStack;
    /**
     * holds the PATH_INFO peices in an array
     * @var array
     */
    private $pathInfo;
    /**
     * combination between the <b>$pathInfo</b> array and <b>$http->params</b> array
     * @var array
     */
    private $methodParams;
    /**
     * holds all the maps which are registered with
     * <b>$RequestHandler->map()</b>
     * @var \ArrayIterator
     */
    private $map;
    /**
     *
     * @var \HookHandler
     */
    private $hookHandler;
    /**
     * holds the input params passed by the user
     * @var array
     */
    private static $httpParams;
    /**
     * holds the default application controller name
     * @var string
     */
    private $defaultApplicationController;

    /**
     * class constructor
     * initializes the <b>$callStack</b> and <b>$map</b> properties
     * @param array $callStack
     * @param array $map
     */
    public function __construct(HookHandler $hookHandler, array $callStack = array(), array $map = array()) {
        
        $this->callStack = new \ArrayIterator($callStack);
        
        $this->map = new \ArrayIterator($map);
        
        $this->methodFactory = new HttpFactory;
        
        $this->hookHandler = $hookHandler;
    }
    /**
     * @todo Implement page not found
     * reads all the calls and the maps that been registered and routes them to the appropreit controller
     * otherwise it shows 404 not found page <b>To be implemented.... listed in todo</b>
     */
    public function listen(){
        
        $this->server = filter_input_array(INPUT_SERVER);
        if(!empty($this->server['PATH_INFO'])){
            $this->setPathInfo($this->server['PATH_INFO']);
        }else{
            $this->setPathInfo(null,true);
        }
        $controllerName = '';
        if(empty($this->pathInfo[0])){
            $controllerName = $this->defaultApplicationController;
        }else{
            $controllerName = array_shift($this->pathInfo);
        }
        $controllerMethodName = array_shift($this->pathInfo);
        
        if($this->isMapped($controllerName) || $this->isMapped($controllerMethodName)){ 
            
            echo 'page not found'; return;
            
        }
        
        $pathControllerName = $this->getMapped(ucfirst(strtolower($controllerName)));
        
        $pathControllerMethod = $this->getMapped(ucfirst(strtolower($controllerMethodName))?:ucfirst(strtolower(self::MAIN_METHOD_NAME)));
        
        $this->methodParams = array_values($this->pathInfo);
        
        while($this->callStack->valid()){
            
            $call = $this->callStack->current();
            
            if(strtolower($call['callName']) == strtolower($this->server['REQUEST_METHOD'])){
                
                $methodName = $call['callName'].'Actual';
                
                if(ucfirst(strtolower($call['controllerName'])) == $pathControllerName && ucfirst(strtolower($call['controllerMethod'])) == $pathControllerMethod){
                    
                    $this->$methodName($pathControllerName, $pathControllerMethod);
                    
                }
            }
            
            $this->callStack->next();
        }
        
    }
    /**
     * Registers get calls into <b>$callStack</b>
     * @param string $controller
     * @param string $controllerMethod
     * @throws \InvalidArgumentException
     */
    public function get($controller, $controllerMethod = 'main'){
        
        if(!is_string($controllerMethod) || !is_string($controller)){
            
            throw new \InvalidArgumentException('RquestHandler get method expects its parameters as string');
        
        }
        
        if(empty($controller) || empty($controllerMethod)){
            
            throw new \InvalidArgumentException('RquestHandler get method expects its parameters not to be null');
            
        }
        
        $call = array('callName'=>'get','controllerName'=>$controller, 'controllerMethod'=>$controllerMethod);
        
        $this->callStack->append($call);
        
    }
    /**
     * the actual get method that creates the request and send it to the
     * requested controller
     * @param string $controller
     * @param string $controllerMethod
     */
    protected function getActual($controller, $controllerMethod = 'main'){
        
        $method = $this->methodFactory->createRequest('get');
        
        $this->initializeHttpRequest($method);
        
        $this->runController($controller, $controllerMethod, $this->methodParams);
        
    }
    /**
     * Registers post calls into <b>$callStack</b>
     * @param string $controller
     * @param string $controllerMethod
     * @throws \InvalidArgumentException
     */
    public function post($controller, $controllerMethod = 'main'){
        
        if(!is_string($controllerMethod) || !is_string($controller)){
            
            throw new \InvalidArgumentException('RquestHandler post method expects its parameters as string');
        
        }
        
        if(empty($controller) || empty($controllerMethod)){
            
            throw new \InvalidArgumentException('RquestHandler post method expects its parameters not to be null');
            
        }
        
        $call = array('callName'=>'post','controllerName'=>$controller, 'controllerMethod'=>$controllerMethod);
        
        $this->callStack->append($call);
    }
    /**
     * the actual post method that creates the request and send it to the
     * requested controller
     * @param string $controller
     * @param string $controllerMethod
     */
    protected function postActual($controller, $controllerMethod = 'main'){
        
        $method = $this->methodFactory->createRequest('post');
        
        $this->initializeHttpRequest($method);
        
        $this->runController($controller, $controllerMethod, $this->methodParams);
    }
    /**
     * Registers a mapping fake name into <b>$map</b> to be called through the URL instead of the original name
     * @param string $fake
     * @param string $original
     * @throws \InvalidArgumentException
     */
    public function map($fake, $original){
        
        if(!is_string($fake) || !is_string($original)){
            
            throw new \InvalidArgumentException('RquestHandler map method expects its parameters as string');
        
        }
        
        if(empty($fake) || empty($original)){
            
            throw new \InvalidArgumentException('RquestHandler map method expects its parameters not to be null');
            
        }
        
        $callMap = array(ucfirst(strtolower($fake)) => ucfirst(strtolower($original)));
        
        $this->map->append($callMap);
    }
    /**
     * converts the server path info into an array
     * @param string $pathInfo
     */
    private function setPathInfo($pathInfo=null, $default=false){
        if(!empty($pathInfo) && $default==false){
            $this->pathInfo = explode('/', trim($pathInfo,'/'));
        }else{
            $this->pathInfo = array();
        }
    }
    /**
     * calls the controller class and its method then invokes any passed arguments if available
     * @param string $controller
     * @param string $controllerMethod
     * @param array $params
     */
    private function runController($controller, $controllerMethod, $params){
        
        $controllerName = $controller;
        
        $controllerMethodName = $controllerMethod;
        
        $this->hookHandler->before($controllerName, $controllerMethodName)->run();
        
        $controller = self::APPLICATION_CONTROLLER_PATH . ucfirst(strtolower($controller));
        
        $controllerMethod = new \ReflectionMethod($controller,$controllerMethod);
        
        $injector = new \Auryn\Provider();
        
        $controllerObject = $injector->make($controller);
        
        $controllerMethod->invokeArgs($controllerObject, $params);
        
        $this->hookHandler->after($controllerName, $controllerMethodName)->run();
        
    }
    /**
     * Initializes <b>$http</b> property and merges the passed params with URL params
     * @param \Handlers\Request\Source\Interfaces\IRequest $method
     */
    private function initializeHttpRequest(IRequest $method){
        
        $this->http = new Http($method, $this->server);
        
        $this->http->InitializeRequest();
        
        self::$httpParams = $this->http->params;
        
        $this->methodParams = array_merge($this->methodParams, $this->http->params);
    }
    /**
     * returns the passed pathe variable either from the map array or returns the original
     * @param string $pathVariable
     * @return string
     */
    public function getMapped($pathVariable){
        
        $pathVariable = ucfirst(strtolower($pathVariable));
        
        $this->map->rewind();
        
        while($this->map->valid()){
        
            $callMap = $this->map->current();
        
            $pattern = '/^' . key($callMap) . '$/';
        
            if(isset($callMap[$pathVariable]) || preg_match($pattern, $pathVariable)){
        
                return ucfirst(strtolower(current($callMap)));
                
            }
        
            $this->map->next();
            
        }
        
        return ucfirst(strtolower($pathVariable));
        
    }
    /**
     * checks if a passed path vaiable has been mapped or not
     * @param string $pathVariable
     * @return boolean
     */
    public function isMapped($pathVariable){
        
        $pathVariable = ucfirst(strtolower($pathVariable));
        
        $this->map->rewind();
        
        while($this->map->valid()){
        
            $callMap = array_flip($this->map->current());
        
            $pattern = '/^' . $pathVariable . '$/';
        
            if(isset($callMap[$pathVariable]) || preg_match($pattern, key($callMap))){
        
                return TRUE;
                
            }
        
            $this->map->next();
            
        }
        
        return FALSE;
        
    }
    /**
     * return the user input params
     * @return array
     */
    public function getHttpParams(){
        
        return self::$httpParams;
        
    }
    public function home($defualt){
        $this->defaultApplicationController = ucfirst(strtolower($defualt));
    }
}
