<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Loader\Source\Classes;
use Handlers\Loader\Source\Interfaces\ILoader;

class Loader extends \Composer\Autoload\ClassLoader implements ILoader{
    /**
     * holds an instance of DirectoryIterator
     * @var \DirectoryIterator 
     */
    private $directoryIterator;
    /**
     * an array of files in a directory
     * @var array 
     */
    private $arrayOfFilesInDirectory;
    /**
     * class constructor
     * @param \DirectoryIterator $directoryIterator
     * @param array $arrayOfFilesInDirectory
     */
    public function __construct(\DirectoryIterator $directoryIterator = NULL, array $arrayOfFilesInDirectory = array()) {
        
        $this->directoryIterator =  $directoryIterator;
        
        $this->arrayOfFilesInDirectory = $arrayOfFilesInDirectory;
        
    }
    /**
     * 
     * @param string $fullPath
     * @param boolean $isDirectory
     * @param boolean $createFileNameNodes
     * @return array
     */
    public function load($fullPath, $isDirectory = FALSE, $createFileNameNodes = TRUE) {
        
        $filesToBeLoaded = array();
        //check if the path refers to a directory
        if($isDirectory){
            //get the files in this directory
            $filesToBeLoaded = $this->getFilesInDirectory($fullPath);
        //if it does not refer to a directory    
        }elseif(!$isDirectory){
            //we need to create the nodename(file name) as index
            if($createFileNameNodes){
                //get the file name without extension
                $fileName = str_replace('.php','',array_pop(explode('/', trim($fullPath,'/'))));
                //appened the content of the file (ususally an array) to the files to be loaded array
                //with the file name as a key
                $filesToBeLoaded[$fileName] = require $fullPath;
                
            }else{
                //appened the content of the file (ususally an array) to the files to be loaded array
                //without the file name as a key
                $filesToBeLoaded = require $fullPath;
            
            }
            //return the files to be loaded array
            return $filesToBeLoaded;
            
        }
        //call the loadActual method
        return $this->loadActual($filesToBeLoaded, $createFileNameNodes);
        
    }
    /**
     * return a set of files in a directory (recursively)
     * @param string $directoryPath
     * @return array
     * @throws \RuntimeException
     */
    public function getFilesInDirectory($directoryPath){
        //create a directory iterator instance
        $directoryIterator = new \DirectoryIterator($directoryPath);
        //loop through the directory
        foreach($directoryIterator as $fileInfo){
            //if the element is it self
            if($fileInfo->isDot()){
                //fo nothing
                continue;
            //if the element is a directory     
            }elseif($fileInfo->isDir()){
                //call the function recursively with the directory path
                $this->getFilesInDirectory($fileInfo->getRealPath());
                
            }else{
                //if we have the permissions to read the file
                if($fileInfo->isReadable()){
                    //add the file to the array of files to be returned later
                    $this->arrayOfFilesInDirectory[] = $fileInfo->getRealPath();
                    
                }else{
                    //throw an exception saying we can't read the file
                    throw new \RuntimeException($fileInfo->getRealPath() . ' is not readable, Permission Denied');
                
                }
            }
        }
        //return the array of file pathes
        return $this->arrayOfFilesInDirectory;
        
    }
    /**
     * @todo add docementation
     * @param array $filesToLoad
     * @param boolean $createFileNameNode
     * @return boolean
     */
    private function loadActual(array $filesToLoad, $createFileNameNode){
        
        $tempFileResult = array();
        
        if(empty($filesToLoad)){
            
            return FALSE;
            
        }else{
            
            if($createFileNameNode){
                
                foreach($filesToLoad as $file){
                    
                    $fileName = str_replace('.php','',array_pop(explode('/', trim($file,'/'))));
                    
                    $tempFileResult[$fileName] = require $file;
                    
                }
                
            }else{
                
                foreach($filesToLoad as $file){

                    $tempFileResult[] = require $file;

                }
            }
        }
        
        return $tempFileResult;
        
    }
    /**
     * Initializes directroy iterator instance with a specific path
     * @param string $path
     */
    public function setDirectroyIterator($path){
        
        $this->directoryIterator = new \DirectoryIterator($path);
        
    }
    
    public function script($fullPath){
        
        $script = '<script content="text/javascript"';
        $script.= ' src="/assets/javascript/';
        $script.= ltrim($fullPath, '/');
        $script.= '"></script>';
        $script.= PHP_EOL;
        
        return $script;
        
    }
    
    public function css($fullPath){
        
        $css = '<link media="all" content="text/css" type="text/css" rel="stylesheet" href="/assets/css/';
        $css.= ltrim($fullPath, '/');
        $css.= '">';
        $css.= PHP_EOL;
        
        return $css;
    }
}
