<?php

class PhoenixObject
{
    protected $id;
    protected $descr;
    protected $url;
    
    public function __construct($id, $desc = '', $url = '')
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
    
    public function getUrl()
    {
        return $this->url;
    }
}

?>