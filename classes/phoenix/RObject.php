<?php

class RObject
{
    protected $name;
    protected $rclass;
    protected $type;
    protected $value;
    
    public function __construct($name, $rclass = '', $type = '', $value = '')
    {
        $this->name = $name;
        $this->rclass = $rclass;
        $this->type = $type;
        $this->value = $value;
    }
    
    public function getName()
    {
        return $this->name;   
    }
    
    public function getRClass()
    {
        return $this->rclass;
    }
    
    public function getType()
    {
        return $this->type;
    }
    
    public function getValue()
    {
        return $this->value;
    }
}

?>