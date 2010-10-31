<?php

interface DeployRSession
{
    public function getId();
    
    public function close();

    public function executeCode($code, $rObjects = null, $files = null);
    
    public function executeScript(DeployRScript $script, $preload = null, $inputs = null, $robjects = null, $files = null, $saveWorkspace = null);
    
    public function ping();

    public function saveProject($desc);
    
    public function saveWorkspace($desc);

}

?>