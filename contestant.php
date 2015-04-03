<?php
class Contestant 
{
    private $name;
    private $health;
    private $damage;
    private $attacks;
    private $dodge;
    private $critical;
    private $initiative;
    private $victory = 0;
    private $failure = 0;
    
    public function __construct($data) 
    {
    	foreach ($data as $key=> $value)
    	{
    		$key = strtolower($key);
    		$this->{$key} = $value;
    	}
    }
    
    private function __set($key,$value) 
    {
        $this->$key = $value;
    }
    
    private function __get($key) 
    {
        return $this->$key;
    }
}