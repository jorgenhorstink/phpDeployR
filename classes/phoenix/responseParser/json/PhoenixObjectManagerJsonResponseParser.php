<?php

require_once PHOENIX_DIRECTORY . '/exception/PhoenixUnauthorizedException.php';
require_once PHOENIX_DIRECTORY . '/PhoenixObject.php';

class PhoenixObjectManagerJsonResponseParser extends PhoenixObjectManagerResponseParser
{
    public function parseGetObjects($json) 
    {
        $decoded = json_decode($json, true);
   
        $robjects = array();
        if ($this->isSuccess($decoded)) {
            foreach ($decoded['deployr']['response']['objects'] as $name => $value) {
                $robjects[] = new RObject($name, $value['rclass'], $value['type']);
            }

            return $robjects;
        } else {
            throw new PhoenixResponseException('object manager - get objects - failed...');   
        }
    }
    
    public function parseGet($json, $name) 
    {
        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
   
            return new RObject(
                $name, 
                $decoded['deployr']['response']['robjects'][$name]['rclass'],
                $decoded['deployr']['response']['robjects'][$name]['type'],
                $decoded['deployr']['response']['robjects'][$name]['value']
            );
            
        } else {
            return null;
        }
    }   
    
    public function parseSave($json, $name, $descr) 
    {
        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
   
            return new PhoenixObject(
                $decoded['deployr']['response']['repository'][$name]['value'],
                $descr
            );
            
        } else {
            return null;
        }
    }    
    
    public function parseLoad($json) 
    {
        $decoded = json_decode($json, true);
   
        return $this->isSuccess($decoded);
    }
        
    public function parseDelete($json) 
    {
        $decoded = json_decode($json, true);
        
        return $this->isSuccess($decoded);
    }
    
    protected function isSuccess($decoded)
    {
        return $decoded['deployr']['response']['success'] == true;  
    }
}

?>