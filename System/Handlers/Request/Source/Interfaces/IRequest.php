<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <all@lapp.com>
 */

namespace Handlers\Request\Source\Interfaces;
use Handlers\Request\Components\Http\Classes\Http;

interface IRequest {
    public function getParams();
    public function dissect(Http $http, array $headerData);
}
