<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Hooks\Source\Interfaces;

interface Hookable {
    public function run();
    public function init();
}
