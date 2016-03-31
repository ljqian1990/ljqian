<?php
require_once 'SomeClass.php';

class StubTest extends PHPUnit_Framework_TestCase
{
	public function testStub()
	{
		// Ϊ SomeClass �ഴ��׮����
		$stub = $this->getMockBuilder('SomeClass')
		->getMock();

		// ����׮����
		$stub->method('doSomething')
		->willReturn('foo');

		// ���ڵ��� $stub->doSomething() ������ 'foo'��
		$this->assertEquals('foo', $stub->doSomething());
	}
}