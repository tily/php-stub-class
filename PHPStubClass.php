<?php
/**
 * PHPStubClass
 *
 * override existent class as stub
 *
 * @author tily (http://d.hatena.ne.jp/tily)
 * @create 2010/07/14
 * @version 0.1
 **/
class PHPStubClass {

    public $stubs;
    public $className;

    /**
     * generate stub class
     *
     * @access public
     * @param  String $className
     * @param  Array  $stubs
     * @return String $className
     **/
    public static function stubClass($className, $stubs) {
        $stubClass = new self($className, $stubs);
        $stubClass->override();
        return $className;
    }

    /**
     * __construct
     * 
     * @access public
     * @param  String $className
     * @param  Array  $stubs
     **/
    public function __construct($className, $stubs) {
        if(!function_exists('runkit_method_redefine')) {
            throw new Exception('runkit_method_redefine() not found.');
        }
        if(!class_exists($className)) {
            throw new Exception("$className not exist.");
        }
        $this->className = $className;
        $this->stubs = $stubs;
    }

    /**
     * override
     * 
     * @access public
     **/
    public function override() {
        $reflectionClass = new ReflectionClass($this->className);
        foreach($reflectionClass->getMethods() as $m) {
            $code = $this->getStubCode($m);
            $sig = $this->getSignature($m);
            runkit_method_redefine($this->className, $m->name, $sig, $code, RUNKIT_ACC_PUBLIC);
        }
    }

    /**
     * @access public
     * @param  ReflectionFunction $rf
     * @return code fragment
     **/
    public function getStubCode($rf) {
        if(array_key_exists($rf->name, $this->stubs)) {
            $value = serialize($this->stubs[$rf->name]);
            return "return unserialize('$value');";
        } else {
            return "return null;";
        }
    }

    /**
     * @access public
     * @param  ReflectionFunction $rf
     * @return signature
     **/
    public function getSignature($rf) {
        $params = array();
        foreach($rf->getParameters() as $p) {
            $params[] = '$' . $p->name;
        }
        return join(',', $params);
    }
}

