<?php

class DeployRScript
{
    protected $id;
    protected $descr;
    
    public function __construct($id, $desc = '')
    {
        $this->id = $id;
        $this->descr = $descr;
    }
    
    public function getId()
    {
        return $this->id; 
    }
    
    public function getDescr()
    {
        return $this->descr;
    }
}

?>