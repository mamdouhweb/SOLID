<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Request\Components\Http\Classes;

class GetTest extends \PHPUnit_Framework_TestCase {

    public $get;
    public $http;

    const HTTP_CLASS_NAMESPACE = "\\Handlers\\Request\\Components\\Http\\Classes\\Http";
    const GET_CLASS_NAMESPACE = "\\Handlers\\Request\\Components\\Http\\Classes\\Get";

    public function setUp() {

        $this->get = new \Handlers\Request\Components\Http\Classes\Get;

        $this->http = $this->getMockBuilder(self::HTTP_CLASS_NAMESPACE)
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testDissectThrowsErrorWhenSecondArgumentIsNotArray() {

        $this->get->dissect($this->http, 'somedata');
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testDissectThrowsErrorWhenFirstArgumentIsNotHttpObject() {

        $this->get->dissect('someData', array());
    }

    public function testDissect() {

        $this->assertNull($this->get->dissect($this->http, array()));
    }

}
