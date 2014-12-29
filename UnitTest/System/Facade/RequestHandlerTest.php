<?php

/*
 * SOLID Framwork 2014
 */

/**
 * @package SOLID 0.1
 * @author <alramadan.mamdouh@gmail.com>
 */

namespace Facade;

class RequestHandlerTest extends \PHPUnit_Framework_TestCase {

    private $RequesetHandler;
    private $hookHandler;

    public function setUp() {

        $this->hookHandler = $this->getMockBuilder('\\Facade\\HookHandler')
                ->disableOriginalConstructor()
                ->getMock();

        $this->hookHandler->expects($this->any())
                ->method('before');

        $this->RequesetHandler = new \Facade\RequestHandler($this->hookHandler);
    }

    protected function getMethod($name) {

        $class = new \ReflectionClass(get_class($this->RequesetHandler));

        $method = $class->getMethod($name);

        $method->setAccessible(true);

        return $method;
    }

    public function testGetisVoid() {

        $output = $this->RequesetHandler->get('Test', 'ts');

        $this->assertNull($output);
    }

    public function testGetAppendingToCallStackArray() {

        $output = $this->getObjectAttribute($this->RequesetHandler, 'callStack');

        $callStack = new \ArrayIterator();

        $this->RequesetHandler->get('testController', 'testMethod');

        $getArray = array(
            'callName' => 'get',
            'controllerName' => 'testController',
            'controllerMethod' => 'testMethod'
        );

        $callStack->append($getArray);

        $this->assertEquals($callStack, $output);
    }

    public function testGetAppendingToCallStackArrayWithDefaultMethodNameAsMain() {

        $output = $this->getObjectAttribute($this->RequesetHandler, 'callStack');

        $callStack = new \ArrayIterator();

        $this->RequesetHandler->get('testController');

        $getArray = array(
            'callName' => 'get',
            'controllerName' => 'testController',
            'controllerMethod' => 'main'
        );

        $callStack->append($getArray);

        $this->assertEquals($callStack, $output);
    }

    public function DataProviderFor_testGetThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed() {

        return array(
            array(12, NULL),
            array(NULL, NULL),
            array(NULL, 23),
            array(FALSE, NULL),
            array(FALSE, TRUE),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage RquestHandler get method expects its parameters as string
     * @dataProvider DataProviderFor_testGetThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed
     */
    public function testGetThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed($controllerName, $controllerMethodName) {

        $this->RequesetHandler->get($controllerName, $controllerMethodName);
    }

    public function DataProviderFor_testGetThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed() {

        return array(
            array('someName', ''),
            array('', ''),
            array('', 'hah'),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage RquestHandler get method expects its parameters not to be null
     * @dataProvider DataProviderFor_testGetThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed
     */
    public function testGetThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed($controllerName, $controllerMethodName) {

        $this->RequesetHandler->get($controllerName, $controllerMethodName);
    }

    public function testPostisVoid() {

        $output = $this->RequesetHandler->post('Test', 'ts');

        $this->assertNull($output);
    }

    public function testPostAppendingToCallStackArray() {

        $output = $this->getObjectAttribute($this->RequesetHandler, 'callStack');

        $callStack = new \ArrayIterator();

        $this->RequesetHandler->post('testController', 'testMethod');

        $getArray = array(
            'callName' => 'post',
            'controllerName' => 'testController',
            'controllerMethod' => 'testMethod'
        );

        $callStack->append($getArray);

        $this->assertEquals($callStack, $output);
    }

    public function testPostAppendingToCallStackArrayWithDefaultMethodNameAsMain() {

        $output = $this->getObjectAttribute($this->RequesetHandler, 'callStack');

        $callStack = new \ArrayIterator();

        $this->RequesetHandler->post('testController');

        $getArray = array(
            'callName' => 'post',
            'controllerName' => 'testController',
            'controllerMethod' => 'main'
        );

        $callStack->append($getArray);

        $this->assertEquals($callStack, $output);
    }

    public function DataProviderFor_testPostThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed() {

        return array(
            array(12, NULL),
            array(NULL, NULL),
            array(NULL, 23),
            array(FALSE, NULL),
            array(FALSE, TRUE),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage RquestHandler post method expects its parameters as string
     * @dataProvider DataProviderFor_testPostThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed
     */
    public function testPostThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed($controllerName, $controllerMethodName) {

        $this->RequesetHandler->post($controllerName, $controllerMethodName);
    }

    public function DataProviderFor_testPostThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed() {

        return array(
            array('someName', ''),
            array('', ''),
            array('', 'hah'),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage RquestHandler post method expects its parameters not to be null
     * @dataProvider DataProviderFor_testPostThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed
     */
    public function testPostThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed($controllerName, $controllerMethodName) {

        $this->RequesetHandler->post($controllerName, $controllerMethodName);
    }

    public function testMap() {

        $this->assertNull($this->RequesetHandler->map('fake', 'original'));
    }

    public function testMapAppendingToMapArray() {

        $this->RequesetHandler->map('fake', 'original');

        $expected = new \ArrayIterator;

        $expected->append(array('Fake' => 'Original'));

        $actual = $this->getObjectAttribute($this->RequesetHandler, 'map');

        $this->assertEquals($expected, $actual);
    }

    public function DataProviderFor_testMapThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed() {

        return array(
            array(12, NULL),
            array(NULL, NULL),
            array(NULL, 23),
            array(FALSE, NULL),
            array(FALSE, TRUE),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage RquestHandler map method expects its parameters as string
     * @dataProvider DataProviderFor_testMapThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed
     */
    public function testMapThrowsInvalidArgumentExceptionWithNotStringArgumentsPassed($fake, $original) {

        $this->RequesetHandler->map($fake, $original);
    }

    public function DataProviderFor_testMapThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed() {

        return array(
            array('someName', ''),
            array('', ''),
            array('', 'hah'),
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage RquestHandler map method expects its parameters not to be null
     * @dataProvider DataProviderFor_testMapThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed
     */
    public function testMapThrowsInvalidArgumentExceptionWithEmptyStringArgumentsPassed($fake, $original) {

        $this->RequesetHandler->map($fake, $original);
    }

    public function testPathInfo() {

        $this->assertNull($this->getMethod('setPathInfo')->invokeArgs($this->RequesetHandler, array('something')));
    }

    public function testPathtInfoAppedingToPathInfoArray() {

        $expected = array('testArg1', 'testArg2');

        $this->getMethod('setPathInfo')->invokeArgs($this->RequesetHandler, array('testArg1/testArg2/'));

        $actual = $this->getObjectAttribute($this->RequesetHandler, 'pathInfo');

        $this->assertEquals($expected, $actual);
    }

}
