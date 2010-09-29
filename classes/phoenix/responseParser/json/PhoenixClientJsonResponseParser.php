<?php

require_once PHOENIX_DIRECTORY . '/exception/PhoenixUnauthorizedException.php';
require_once PHOENIX_DIRECTORY . '/PhoenixIdentity.php';

class PhoenixClientJsonResponseParser extends PhoenixClientResponseParser
{    
    public function parseLoadProject($json) 
    {
        $decoded = json_decode($json, true);
        
        if ($this->isSuccess($decoded)) {
            return $decoded['deployr']['response']['session'];
        } else {
            throw new PhoenixResponseException('client - load project - failed...')   ;
        }
    }

    public function parseLogin($json) 
    {
        $decoded = json_decode($json, true);

        if (!$this->isSuccess($decoded)) {
            throw new PhoenixResponseException('login - failed...');   
        }
        
        return $decoded['deployr']['response']['cookie'];   
    }
    
    public function parseLogout($json) 
    {
        $decoded = json_decode($json, true);
   
        if (!$this->isSuccess($decoded)) {
            throw new PhoenixResponseException('logout - failed...');   
        }
    }
    
    public function parseWhoami($json) 
    {
        $decoded = json_decode($json, true);
   
        if (!$this->isSuccess($decoded)) {
            throw new PhoenixResponseException('whoami - failed...');   
        }

        return new PhoenixIdentity(
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