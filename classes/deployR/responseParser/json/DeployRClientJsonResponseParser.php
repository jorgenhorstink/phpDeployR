<?php

require_once DEPLOYR_DIRECTORY . '/exception/DeployRUnauthorizedException.php';
require_once DEPLOYR_DIRECTORY . '/DeployRIdentity.php';

class DeployRClientJsonResponseParser extends DeployRClientResponseParser
{    
    public function parseLoadProject($json) 
    {
        $decoded = json_decode($json, true);
        
        if ($this->isSuccess($decoded)) {
            return $decoded['deployr']['response']['session'];
        } else {
            throw new DeployRResponseException('client - load project - failed...')   ;
        }
    }

    public function parseLogin($json) 
    {
        $decoded = json_decode($json, true);

        if (!$this->isSuccess($decoded)) {
            throw new DeployRResponseException('login - failed...');   
        }
        
        return $decoded['deployr']['response']['cookie'];   
    }
    
    public function parseLogout($json) 
    {
        $decoded = json_decode($json, true);
   
        if (!$this->isSuccess($decoded)) {
            throw new DeployRResponseException('logout - failed...');   
        }
    }
    
    public function parseWhoami($json) 
    {
        $decoded = json_decode($json, true);
   
        if (!$this->isSuccess($decoded)) {
            throw new DeployRResponseException('whoami - failed...');   
        }

        return new DeployRIdentity(
            $decoded['deployr']['response']['identity']['username'],
            $decoded['deployr']['response']['identity']['displayname']
        );
    }
    
    public function parseAutosave($json) 
    {
        $decoded = json_decode($json, true);
        if ($this->isSuccess($decoded)) {
            return true;   
        } else {
            return false;
        }
    }
    
    protected function isSuccess($decoded)
    {
        return $decoded['deployr']['response']['success'] == true;  
    }
}

?>