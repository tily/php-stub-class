<?php
$dirname = dirname(__FILE__);
ini_set('include_path', ini_get('include_path') . ":$dirname/..");
require_once 'PHPStubClass.php';

class SomeClass {
    public function someMethod() {
        return 'this method will be overrided';
    }
}

PHPStubClass::stubClass('SomeClass', array(
    'someMethod' => 'override value'
));

class SomeClassTest extends PHPUnit_Framework_TestCase {
    private $obj;
    public function setup() {
        $this->obj = new SomeClass();
    }
    public function testSomeMethod() {
        $ret = $this->obj->someMethod();
        $this->assertEquals($ret, 'override value');
    }
}

