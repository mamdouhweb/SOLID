<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Configuration\Source\Interfaces;
use Handlers\Configuration\Source\Interfaces\IConfiguration;

interface Configurable {
    public function reload(IConfiguration $configuration, $nodeName);
    public function getConfigurationNode($nodeName);
}
