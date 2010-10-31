<?php

require_once DEPLOYR_DIRECTORY . '/exception/DeployRUnauthorizedException.php';
require_once DEPLOYR_DIRECTORY . '/DeployRProject.php';

class DeployRProjectRepositoryJsonResponseParser extends DeployRProjectRepositoryResponseParser
{
    public function parseGetProjects($json) 
    {
        $decoded = json_decode($json, true);
        
        $projects = array();
        if ($this->isSuccess($decoded)) {
            foreach ($decoded['deployr']['response']['projects'] as $descr => $value) {
                $projects[] = new DeployRProject($value['value'], $descr);
            }

            return $projects;
        } else {
            throw new DeployRResponseException('project repository - get projects - failed...');   
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