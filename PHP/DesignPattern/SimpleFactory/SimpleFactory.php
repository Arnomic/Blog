<?php
namespace SimpleFactory;
/**
 * 简单工厂模式
 */
interface Animal{
    public function etc($food);
}

class Dog implements Animal{
    public function etc($food)
    {
        echo __CLASS__ . $food;
    }
}

class Cat implements Animal{
    public function etc($food)
    {
        echo __CLASS__ . $food;
    }
}

class ConcreteFactory{
	public $instance = [
		'dog' => Dog::class,
		'cat' => Cat::class,
	];
    public function getInstance($type)
    {
    	if(!array_key_exists($type, $this->instance)){
    		throw new \Exception("未找到实例", 1);
    	}
       return new $this->instance[$type]();
    }
}

$handle = (new ConcreteFactory())->getInstance('cat');
$handle->etc('food');