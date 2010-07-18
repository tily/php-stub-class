<?php

$dirname = dirname(__FILE__);
ini_set('include_path', ini_get('include_path') . ":$dirname/../");

require_once 'PHPUnit/Framework.php';
require_once 'PHPStubClass.php';

// fixture
class SomeClass {
    public function oneMethod()     {}
    public function anotherMethod() {}
}
function methodJustForTest() {}

// test body
class PHPStubClass_GeneralTest extends PHPUnit_Framework_TestCase
{
    public $stubClass;
    public $stubs;

    public function setUp() {
        $this->stubs = array(
            'oneMethod'     => 'ret value of oneMethod',
            'anotherMethod' => 'ret value of anotherMethod'
        );
        $this->stubClass = new PHPStubClass('SomeClass', $this->stubs);
    }

    public function testStubClass() {
        PHPStubClass::stubClass('SomeClass', array(
            'oneMethod' =>     'oneMethod from testMockClass()',
            'anotherMethod' => 'anotherMethod from testMockClass()'
        ));
        $obj = new SomeClass();
        $this->assertEquals($obj->oneMethod(),     'oneMethod from testMockClass()');
        $this->assertEquals($obj->anotherMethod(), 'anotherMethod from testMockClass()');
    }

    public function testOverride() {
        $this->stubClass->override();
        $obj = new SomeClass();
        $this->assertEquals($obj->oneMethod(),     'ret value of oneMethod');
        $this->assertEquals($obj->anotherMethod(), 'ret value of anotherMethod');
    }

    public function testGetStubCode() {
        $rf = new StdClass(); $rf->name = 'oneMethod';
        $ret = $this->stubClass->getStubCode($rf);
        $expect = "return unserialize('" . serialize($this->stubClass->stubs[$rf->name]) . "');";
        $this->assertEquals($ret, $expect);

        $rf->name = 'methodThatDoesNotExistInStubs';
        $ret = $this->stubClass->getStubCode($rf);
        $expect = 'return null;';
        $this->assertEquals($ret, $expect);
    }

    public function testGetSignature() {
        $param1 = new stdClass(); $param1->name = 'oneParam';
        $param2 = new stdClass(); $param2->name = 'thenAnother';
        $params = array($param1, $param2);

        $rf = $this->getMock('ReflectionFunction', array('getParameters'), array('methodJustForTest'));
        $rf->expects($this->once())
           ->method('getParameters')
           ->will($this->returnValue($params));

        $ret = $this->stubClass->getSignature($rf);
        $this->assertEquals($ret, '$oneParam,$thenAnother');
    }

    public function testGetSignatureWhenNoParameters() {
        $rf = $this->getMock('ReflectionFunction', array('getParameters'), array('methodJustForTest'));
        $rf->expects($this->once())
            ->method('getParameters')
            ->will($this->returnValue(array()));

        $ret = $this->stubClass->getSignature($rf);
        $this->assertEquals($ret, '');
    }
}

