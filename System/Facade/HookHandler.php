<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Facade;
use \Handlers\Configuration\Source\Interfaces\Configurable;
use \Handlers\Hooks\Source\Classes\HookCollection;
use \Handlers\Hooks\Source\Interfaces\Hookable;

class HookHandler implements Hookable{
    /**
     * holds an instance of ConfigurationHandler
     * @var \Handlers\Configuration\Source\Interfaces\Configurable
     */
    private $configurationHandler;
    /**
     * holds an instance of HookCollection
     * @var \Handlers\Hooks\Source\Classes\HookCollection
     */
    private $hooksCollection;
    /**
     * holds the path to the application defined hooks folder
     * @var string 
     */
    private $HOOKS_FOLDER;
    /**
     * holds the namespace of the hooks classes
     * @var String
     */
    private $HOOKS_CLASSES_NAMESPACE;
    /**
     * holds a set of hooks to be run in the current request
     * @var array
     */
    private $calledHooksCollection;
    /**
     * class constructor
     * @param \Handlers\Configuration\Source\Interfaces\Configurable $configurationHandler
     * @param \Handlers\Hooks\Source\Classes\HookCollection $hooksCollection
     * @param string $applicationHooksFolder
     * @param string $hooksClassesNamespace
     */
    public function __construct(Configurable $configurationHandler, HookCollection $hooksCollection = NULL, $applicationHooksFolder = NULL, $hooksClassesNamespace = NULL) {
        
        $this->configurationHandler = $configurationHandler;
        
        if(is_null($applicationHooksFolder)){
            
            $applicationHooksFolder = ROOT . DOCUMENT_SEPARATOR . APPNAME . DOCUMENT_SEPARATOR . "Application/Hooks/Classes";
            
        }
        
        if(is_null($hooksClassesNamespace)){
            
            $hooksClassesNamespace = "\\Hooks\\Classes\\";
            
        }
        
        if(is_null($hooksCollection)){
            
            $hooksCollection = new HookCollection;
            
        }
        
        $this->HOOKS_FOLDER = $applicationHooksFolder;
        
        $this->HOOKS_CLASSES_NAMESPACE = $hooksClassesNamespace;
        
        $this->hooksCollection = $hooksCollection;
        
        $this->calledHooksCollection = array();
    }
    /**
     * initializes the HookHandler
     * @return boolean|Nothing
     */
    public function init(){
        //reading the hook configuration in the system configuration folder
        $hooksConfiguration = $this->configurationHandler->getConfigurationNode('Hooks')->getConfigurationArray();
        //reading the enabled variable from the configuration
        $hooksEnabled = $hooksConfiguration['enabled'];
        //checking if hooks are enabled
        if(!$hooksEnabled){
            //if yes return false
            return FALSE;
            
        }
        //if the hooks are enabled, call this method
        $this->setHooksCollection();
        
    }
    /**
     * takes the current request called hooks and execute them
     * @return boolean  if the calledHooksCollection property is empty
     */
    public function run(){
        //if there are no execution called hooks
        if(empty($this->calledHooksCollection)){
            //return false
            return FALSE;
            
        }
        //if there are called execution hooks then loop though them
        foreach ($this->calledHooksCollection as $hook){
            //execute the hook
            $hook->run();
            
        }
        //reset the execution hooks to be an empty array
        $this->calledHooksCollection = array();
        
    }
    /**
     * reads the hooksCollection and checks if any of them are from type Before,
     * if yes, then copy the before hooks into the execution hooks array (<b>$calledHooksCollection</b>)
     * @param string $applicationControllerName
     * @param string $applicationControllerMethodName
     * @return \Facade\HookHandler
     */
    public function before($applicationControllerName, $applicationControllerMethodName){
        //get a set of hooks registered as type Before.
        $beforeHooks = $this->hooksCollection->getBeforeHooks();
        //loop through them
        foreach($beforeHooks as $hookedClassName => $beforeHook){
            //each hook should have a set of methods to be hooked with our hook object
            //and we are looping through these methods
            foreach($beforeHook as $hookedClassMethodName => $hookObject){
              //check if the registered HookedClass name is the same as the application controller being called
                if(ucfirst(strtolower($hookedClassName)) == ucfirst(strtolower($applicationControllerName))){
                    //check if the registered hooked method name is the same as the method being called from the application controller
                    if(ucfirst(strtolower($applicationControllerMethodName)) == ucfirst(strtolower($hookedClassMethodName))){
                        //if yes then add the hook object to the execution hook array
                        $this->calledHooksCollection[] = $hookObject;
                        
                    }
                }
            }
        }
        //return an instance of this class for method chaining
        return $this;
    }
    /**
     * reads the hooksCollection and checks if any of them are from type After,
     * if yes, then copy the after hooks into the execution hooks array (<b>$calledHooksCollection</b>)
     * @param type $applicationControllerName
     * @param type $applicationControllerMethodName
     * @return \Facade\HookHandler
     */
    public function after($applicationControllerName, $applicationControllerMethodName){
        //get a set of hooks registered as type After.
        $afterHooks = $this->hooksCollection->getAfterHooks();
        //loop through them
        foreach($afterHooks as $hookedClassName => $afterHook){
            //each hook should have a set of methods to be hooked with our hook object
            //and we are looping through these methods
            foreach($afterHook as $hookedClassMethodName => $hookObject){
                //check if the registered HookedClass name is the same as the application controller being called
                if(ucfirst(strtolower($hookedClassName)) == ucfirst(strtolower($applicationControllerName))){
                //check if the registered hooked method name is the same as the method being called from the application controller
                    if(ucfirst(strtolower($applicationControllerMethodName)) == ucfirst(strtolower($hookedClassMethodName))){
                        //if yes then add the hook object to the execution hook array
                        $this->calledHooksCollection[] = $hookObject;
                        
                    }
                }
                
            }
            
        }
        //return an instance of this class for method chaining
        return $this;
    }
    /**
     * iterates over the hook classes directory as register the hooks according to 
     * their settings
     * @param string $hooksFolderPath
     * @throws \RuntimeException
     */
    private function setHooksCollection($hooksFolderPath = NULL){
        //if the passed folder path is Null then default to the default Hooks Folder
        if(is_null($hooksFolderPath)){
            //reading the default hooks folder
            $hooksFolderPath = $this->HOOKS_FOLDER;
            
        }
        //getting a directory iterator instance
        $directoryIterator = new \DirectoryIterator($hooksFolderPath);
        //loop through the files and directories in the passed path
        foreach($directoryIterator as $fileInfo){
            //if it is it self
            if($fileInfo->isDot()){
                //do nothing and jump to the next element
                continue;
            //if the element is a directory
            }elseif($fileInfo->isDir()){
                //recursivly call this function with the current directory path
                $this->setHooksCollection($fileInfo->getRealPath());
            //default case    
            }else{
                //check if the file has the write permissions to be read 
                if($fileInfo->isReadable()){
                    //getting the path of the hookclass and removing the extension from it
                    //in php 5.3.6 or greater we can do
                    //$fileInfo->getExtension() instead of .php in the trim call
                    $hookPath = array_filter(explode('/',trim($fileInfo->getRealPath(),'.php')));
                    //get the hook class name and prepend its namespace to it
                    $hookClass = $this->HOOKS_CLASSES_NAMESPACE . array_pop($hookPath);
                    //instaciate the hook class
                    $hook = new $hookClass;
                    //initialize the hook class
                    $hook->init();
                    //add the hook to the hooksCollection
                    $this->hooksCollection->addHook($hook);
                    
                }else{
                    //the file is not readable, then throw an exception
                    throw new \RuntimeException($fileInfo->getRealPath() . ' is not readable, Permission Denied');
                
                }
            }
        }
    }

}
