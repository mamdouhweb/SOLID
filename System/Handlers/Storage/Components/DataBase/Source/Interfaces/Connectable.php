<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <all@lapp.com>
 */

namespace Handlers\Storage\Components\DataBase\Source\Interfaces;


interface Connectable {
    
    public function connect();
    
    public function close();
    
}
