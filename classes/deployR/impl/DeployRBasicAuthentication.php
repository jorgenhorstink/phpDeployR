<?php

require_once DEPLOYR_DIRECTORY . '/DeployRAuthentication.php';

class DeployRBasicAuthentication extends DeployRAuthentication
{   
    protected $server;
    protected $format;
    protected $transport;
    
    protected $responseParser;
    
    protected $authenticationToken;

    public function __construct($username, $password)
    {
        $this->username = $username;
        $this->password = sha1($password);
    }
    
    public function getUsername()
    {
        return $this->username;   
    }
    
    public function getPassword()
    {
        return $this->password;
    }
}

?>