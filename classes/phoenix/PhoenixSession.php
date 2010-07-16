<?php

abstract class PhoenixSession
{
    abstract public function getId();
    
    abstract public function close();

    abstract public function executeCode($code, $rObjects = null, $files = null);
    
    abstract public function executeScript($rscript, $preload = null, $inputs = null, $robjects = null, $files = null, $saveWorkspace = null);
    
    abstract public function ping();

    abstract public function saveProject($project);
    
    abstract public function saveWorkspace();

    abstract public function getObject($name);
    abstract public function listObjects();
    abstract public function loadStoredObjects($id);
    abstract public function saveObject($name, $description);
}
?>