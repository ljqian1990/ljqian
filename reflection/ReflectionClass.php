<?php
$class = new ReflectionClass('Schema');
$instance = $class->newInstanceArgs(['ljqian', '123456']);
var_dump($instance->supportList());



class Schema
{
    private $name = '';
    private $pwd = '';
    
    public function __construct($name, $pwd)
    {
        $this->name = $name;
        $this->pwd = $pwd;
    }
    
    public function supportList()
    {
        return [$this->name, $this->pwd];
    }
}