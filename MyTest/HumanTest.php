<?php
require 'Human.php';
class HumanTest extends PHPUnit_Framework_TestCase {
	protected $human;
	
	public function setUp(){
		$this->human = new Human('ljqian', 27);
		
// 		$this->assertEquals(27, $this->human->getAge());
	}
	
	public function testGetName(){
		$this->human->setName('ljqian1990');
		$this->assertEquals('ljqian1990', $this->human->getName());
	}
}