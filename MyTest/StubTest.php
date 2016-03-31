<?php
require_once 'SomeClass.php';

class StubTest extends PHPUnit_Framework_TestCase
{
	public function testStub()
	{
		// 为 SomeClass 类创建桩件。
		$stub = $this->getMockBuilder('SomeClass')
		->getMock();

		// 配置桩件。
		$stub->method('doSomething')
		->willReturn('foo');

		// 现在调用 $stub->doSomething() 将返回 'foo'。
		$this->assertEquals('foo', $stub->doSomething());
	}
}