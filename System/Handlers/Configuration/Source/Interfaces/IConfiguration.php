<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Configuration\Source\Interfaces;

interface IConfiguration {

    public function set($configurationVariableName, $configurationVariableValue);
    
    public function get($configurationVariableName);
    
}
