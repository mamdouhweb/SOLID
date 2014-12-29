<?php

/*
 * SOLID Framework 2014
 */

/**
 * @package SOLID 0.1
 * @author <all@lapp.com>
 */

namespace Core\Views\Abstracts;
use Core\Views\Classes\HtmlLoader;
use Handlers\Loader\Source\Classes\Loader as Assets;

abstract class View {
    
    protected $data = array();
    
    protected $assets;
    
    private $templates = array();
    
    private $templatePath;
    
    const TEMPLATES_FOLDER = 'Application/Templates';

    public function __construct() {
        $this->assets = new Assets;
        $this->templatePath = ROOT . DOCUMENT_SEPARATOR . APPNAME . self::TEMPLATES_FOLDER . DOCUMENT_SEPARATOR ;
        
    }
    
    public function __get($name) {
        
        return $this->data[$name];
        
    }
    
    public function __set($name, $value) {
        
        $this->data[$name] = $value;
        
    }
    
    protected function load($templateName){
        
        $className = array_pop(explode('\\', get_class($this)));
        
        $templateName = $this->templatePath . $className . DOCUMENT_SEPARATOR . $templateName;
        
        $this->loadActual($templateName);
        
        return $this;
        
    }
    
    private function loadActual($templatePath){
        
        ob_start();
        
        include $templatePath;
        
        $this->templates[] = ob_get_clean();
        
    }
    
    
    protected function render(){
        $this->done();
        ob_start();
        foreach($this->templates as $template){
            
            echo $template;
            
        }
        
        return ob_end_flush();
        
    }
    
    protected function with(array $data){
        
        foreach($data as $key => $value){
            
            $this->$key = $value;
            
        }
        
        return $this;
        
    }
    
    abstract function done();
    
}
