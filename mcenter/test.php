<?php 
class Test
{
    public function setName($name)
    {
        $this->name = $name;
    }
    
    public function getName()
    {
        return $this->name;
    }
}

$test = new Test();
$test->setName('ljqian');
echo $test->getName();