<?php

class DeployRIdentity
{
    protected $username;
    protected $displayName;
    
    public function __construct($username, $displayName)
    {
        $this->username    = $username;
        $this->displayName = $displayName;
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    public function getDisplayName()
    {
        return $this->displayName;
    }
}