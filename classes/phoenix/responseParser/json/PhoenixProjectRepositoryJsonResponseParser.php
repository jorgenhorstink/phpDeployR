<?php

require_once PHOENIX_DIRECTORY . '/exception/PhoenixUnauthorizedException.php';
require_once PHOENIX_DIRECTORY . '/PhoenixProject.php';

class PhoenixProjectRepositoryJsonResponseParser extends PhoenixProjectRepositoryResponseParser
{
    public function parseGetProjects($json) 
    {
        $decoded = json_decode($json, true);
        
        $projects = array();
        if ($this->isSuccess($decoded)) {
            foreach ($decoded['deployr']['response']['projects'] as $descr => $value) {
                $projects[] = new PhoenixProject($value['value'], $descr);
            }

            return $projects;
        } else {
            throw new PhoenixResponseException('project repository - get projects - failed...');   
        }
    }
    
    public function parseProjectDelete($json) 
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