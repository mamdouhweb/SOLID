<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Request\Components\Http\Classes;

class PostTest extends \PHPUnit_Framework_TestCase {

    public $post;
    public $http;

    const HTTP_CLASS_NAMESPACE = "\\Handlers\\Request\\Components\\Http\\Classes\\Http";
    const POST_CLASS_NAMESPACE = "\\Handlers\\Request\\Components\\Http\\Classes\\Post";

    public function setUp() {

        $this->post = new \Handlers\Request\Components\Http\Classes\Post;

        $this->http = $this->getMockBuilder(self::HTTP_CLASS_NAMESPACE)
                ->disableOriginalConstructor()
                ->getMock();
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testDissectThrowsErrorWhenSecondArgumentIsNotArray() {

        $this->post->dissect($this->http, 'somedata');
    }

    /**
     * @expectedException PHPUnit_Framework_Error
     */
    public function testDissectThrowsErrorWhenFirstArgumentIsNotHttpObject() {

        $this->post->dissect('someData', array());
    }

    public function testDissect() {

        $this->assertNull($this->post->dissect($this->http, array()));
    }

}
