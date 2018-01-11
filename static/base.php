<?php
class base
{	
	public static function getInstance()
	{
		if (!static::$_self) {
			static::$_self = new static(); 
		}
		return static::$_self;
	}
}

class A extends base
{
	protected static $_self;	
}

class B extends base
{
	protected static $_self;
}

$a = A::getInstance();
$b = B::getInstance();
echo get_class($a);//A
echo "<br>";
echo get_class($b);//B
echo "<br>";



class sclass
{
    public static function test()
    {
        echo '1';
    }
}

echo "<br>";
echo "<br>";
echo "<br>";
echo "<br>";
$s = new sclass();
$s::test();