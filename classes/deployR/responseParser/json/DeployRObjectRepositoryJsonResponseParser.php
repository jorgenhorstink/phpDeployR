<?php

require_once DEPLOYR_DIRECTORY . '/exception/DeployRUnauthorizedException.php';

require_once DEPLOYR_DIRECTORY . '/DeployRObject.php';

class DeployRObjectRepositoryJsonResponseParser extends DeployRObjectRepositoryResponseParser
{
    public function parseGetObjects($json) {

        $decoded = json_decode($json, true);
   
        $objects = array();
        if ($this->isSuccess($decoded)) {
            foreach ($decoded['deployr']['response']['objects'] as $descr => $value) {
                $objects[] = new DeployRObject($value['value'], $descr, $url);
            }

            return $objects;
        } else {
            throw new DeployRResponseException('object repository - get objects - failed...');   
        }
    }
    
    public function parseDelete($json) {

        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
            return true;
        } else {
            throw false;
        }
    }
    
    protected function isSuccess($decoded)
    {
        return $decoded['deployr']['response']['success'] == true;  
    }
}

?>