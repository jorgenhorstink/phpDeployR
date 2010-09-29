<?php

require_once PHOENIX_DIRECTORY . '/exception/PhoenixUnauthorizedException.php';
require_once PHOENIX_DIRECTORY . '/PhoenixScript.php';

class PhoenixScriptRepositoryJsonResponseParser extends PhoenixScriptRepositoryResponseParser
{
    public function parseGetScripts($json) {
        
        $decoded = json_decode($json, true);

        $scripts = array();
        if ($this->isSuccess($decoded)) {
            foreach ($decoded['deployr']['response']['scripts'] as $descr => $value) {
                $scripts[] = new PhoenixScript($value['value'], $descr);
            }

            return $scripts;
        } else {
            throw new PhoenixResponseException('project repository - get projects - failed...');   
        }
    }
    
    protected function isSuccess($decoded)
    {
        return $decoded['deployr']['response']['success'] == true;  
    }
}

?>