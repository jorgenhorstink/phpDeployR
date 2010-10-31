<?php

require_once DEPLOYR_DIRECTORY . '/exception/DeployRUnauthorizedException.php';
require_once DEPLOYR_DIRECTORY . '/DeployRScript.php';

class DeployRScriptRepositoryJsonResponseParser extends DeployRScriptRepositoryResponseParser
{
    public function parseGetScripts($json) {
        
        $decoded = json_decode($json, true);

        $scripts = array();
        if ($this->isSuccess($decoded)) {
            foreach ($decoded['deployr']['response']['scripts'] as $descr => $value) {
                $scripts[] = new DeployRScript($value['value'], $descr);
            }

            return $scripts;
        } else {
            throw new DeployRResponseException('project repository - get projects - failed...');   
        }
    }
    
    protected function isSuccess($decoded)
    {
        return $decoded['deployr']['response']['success'] == true;  
    }
}

?>