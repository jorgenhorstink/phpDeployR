<?php

require_once PHOENIX_DIRECTORY . '/exception/PhoenixUnauthorizedException.php';

require_once PHOENIX_DIRECTORY . '/PhoenixObject.php';

class PhoenixObjectRepositoryJsonResponseParser extends PhoenixObjectRepositoryResponseParser
{
    public function parseGetObjects($json) {

        $decoded = json_decode($json, true);
   
        $objects = array();
        if ($this->isSuccess($decoded)) {
            foreach ($decoded['deployr']['response']['objects'] as $descr => $value) {
                $objects[] = new PhoenixObject($value['value'], $descr, $url);
            }

            return $objects;
        } else {
            throw new PhoenixResponseException('object repository - get objects - failed...');   
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