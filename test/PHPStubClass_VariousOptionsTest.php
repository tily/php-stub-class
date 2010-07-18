<?php

$dirname = dirname(__FILE__);
ini_set('include_path', ini_get('include_path') . ":$dirname/../");

require_once 'PHPUnit/Framework.php';
require_once 'PHPStubClass.php';

// TODO: return objects like stdClass and stub nonexistent method

// fixture
class OtherClass {
  public        function oneMethod()           {}
  public        function anotherMethod()       {}
  private       function thisIsPrivateMethod() {}
  public final  function thisIsFinalMethod()   {}
  public static function thisIsStaticMethod()  {}
}

// test body
class PHPStubClass_VariousOptionsTest extends PHPUnit_Framework_TestCase
{
    public function testStubClassThatDoesNotExist() {
        try {
        PHPStubClass::stubClass('ClassThatDoesNotExist', array());
        } catch(Exception $e) {
            $this->assertEquals($e->getMessage(), 'ClassThatDoesNotExist not exist.');
            return;
        }
        $this->fail('Exception not happened.');
    }

    public function testStubClassTwice() {
        PHPStubClass::stubClass('OtherClass', array(
            'oneMethod' =>     'first time oneMethod',
            'anotherMethod' => 'first time anotherMethod'
        ));
        PHPStubClass::stubClass('OtherClass', array(
            'oneMethod' =>     'second time oneMethod',
            'anotherMethod' => 'second time anotherMethod'
        ));
        $obj = new OtherClass();
        $this->assertEquals($obj->oneMethod(),     'second time oneMethod');
        $this->assertEquals($obj->anotherMethod(), 'second time anotherMethod');
    }

    public function testStubClassWithEmptyStubArray() {
        PHPStubClass::stubClass('OtherClass', array());
        $obj = new OtherClass();
        $this->assertEquals($obj->oneMethod(),     null);
        $this->assertEquals($obj->anotherMethod(), null);
    }

    public function testStubClassWhenStubStaticMethod() {
        PHPStubClass::stubClass('OtherClass', array('thisIsStaticMethod' => 'ret value of static method'));
        $obj = new OtherClass();
        $this->assertEquals(OtherClass::thisIsStaticMethod(), 'ret value of static method');
    }

    public function testStubClassWhenStubPrivateMethod() {
        PHPStubClass::stubClass('OtherClass', array('thisIsPrivateMethod' => 'ret value of private method'));
        $obj = new OtherClass();
        $this->assertEquals($obj->thisIsPrivateMethod(), 'ret value of private method');
    }

    public function testStubClassWhenStubFinalMethod() {
        PHPStubClass::stubClass('OtherClass', array('thisIsFinalMethod' => 'ret value of final method'));
        $obj = new OtherClass();
        $this->assertEquals($obj->thisIsFinalMethod(), 'ret value of final method');
    }

    public function testStubClassWithObjects() {
        eval("class OneMoreClass {
            public function testMethod() { return 'hello from testMethod'; }
        }");
        PHPStubClass::stubClass('OtherClass', array('oneMethod' => new OneMoreClass));
        $obj = new OtherClass();
        $oneMoreObj = $obj->oneMethod();
        $this->assertEquals($oneMoreObj->testMethod(), 'hello from testMethod');
    }
}

