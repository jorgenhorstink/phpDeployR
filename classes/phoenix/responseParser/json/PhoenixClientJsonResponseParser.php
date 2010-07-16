<?php

require_once PHOENIX_DIRECTORY . '/exception/PhoenixUnauthorizedException.php';
require_once PHOENIX_DIRECTORY . '/PhoenixIdentity.php';

class PhoenixClientJsonResponseParser extends PhoenixClientResponseParser
{
    public function parseListScripts($json) {
        $decoded = json_decode($json, true);
   
        if ($decoded['phoenix']['response']['success'] != 1) {
            throw new PhoenixResponseException('client - listScripts - failed...');   
        }
        
        $map = new ArrayMap();
        foreach ($decoded['phoenix']['response']['pobjects'] as $name => $script) {
            $map->put($name, $script['value']);
        }
        
        return $map;
    }

    public function parseLogin($json) {
        $decoded = json_decode($json, true);
   
        if ($decoded['phoenix']['response']['success'] != 1) {
            throw new PhoenixResponseException('login - failed...');   
        }
        
        return $decoded['phoenix']['response']['cookie'];   
    }
    
    public function parseLogout($json) {
        $decoded = json_decode($json, true);
   
        if ($decoded['phoenix']['response']['success'] != 1) {
            throw new PhoenixResponseException('logout - failed...');   
        }
    }
    
    public function parseWhoami($json) {
        $decoded = json_decode($json, true);
   
        if ($decoded['phoenix']['response']['success'] != 1) {
            throw new PhoenixResponseException('whoami - failed...');   
        }

        return new PhoenixIdentity(
            $decoded['phoenix']['response']['identity']['username'],
            $decoded['phoenix']['response']['identity']['displayname']
        );
    }
    
    public function parseCreateSession($json)
    {
        $decoded = json_decode($json, true);
        
        if ($decoded['phoenix']['response']['success'] != 1) {
            throw new PhoenixResponseException('client - createSession - failed...');   
        }
        
        return $decoded['phoenix']['response']['session'];
    }
}

?>