<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <all@lapp.com>
 */

namespace Handlers\Request\Source\Interfaces;

interface IRequestFactory {
    public function createRequest($requestType = NULL);
}
