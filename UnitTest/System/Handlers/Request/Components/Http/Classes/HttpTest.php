<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Handlers\Request\Components\Http\Classes;

class HttpTest extends \PHPUnit_Framework_TestCase {

    public $http;

    const POST_CLASS_NAMESPACE = "\\Handlers\\Request\\Components\\Http\\Classes\\Post";

    public function setUp() {

        $method = $this->getMock(self::POST_CLASS_NAMESPACE);

        $headerData = $headerData = array('QUERY_STRING' => 'test=test', 'HTTP_CONNECTION' => 'keep_alive',
            'HTTP_ACCEPT_ENCODING' => 'gzip', 'HTTP_HOST' => '10.1.1.1', 'REMOTE_ADDRESS' => '1.1.1.1');

        $this->http = new \Handlers\Request\Components\Http\Classes\Http($method, $headerData);
    }

    public function testInitializeRequest() {

        $this->assertNull($this->http->InitializeRequest());
    }

    public function testHeaderDataExists() {

        $this->assertObjectHasAttribute('headerData', $this->http);
    }

    public function testHeaderDataBeenDeletedAfterInitializeRequest() {

        $this->http->InitializeRequest();

        $expected = $this->getObjectAttribute($this->http, 'headerData');

        $this->assertNull($expected);
    }

    public function testSetConnection() {

        $this->http->setConnection('someType');

        $this->assertEquals('someType', $this->getObjectAttribute($this->http, 'connection'));
    }

    public function testSetEncoding() {

        $this->http->setEncoding('someType, someEnc');

        $this->assertEquals(array('someType', 'someEnc'), $this->getObjectAttribute($this->http, 'encoding'));
    }

    public function testSetHost() {

        $this->http->setHost('someHost');

        $this->assertEquals('someHost', $this->getObjectAttribute($this->http, 'host'));
    }

    public function testSetRemoteUser() {

        $this->http->setRemoteUser('someIP');

        $this->assertEquals('someIP', $this->getObjectAttribute($this->http, 'remoteUser'));
    }

}
