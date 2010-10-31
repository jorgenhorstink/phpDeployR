<?php

class DeployRExecution
{
    protected $robjects;
    protected $files;
    
    public function __construct($robjects, Map $files)
    {
        $this->robjects = $robjects;
        $this->files = $files;
    }
    
    public function getRObjects()
    {
        return $this->robjects;   
    }
    
    public function getFiles()
    {
        return $this->files;
    }
}

?>