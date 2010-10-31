<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/DeployRSessionResponseParser.php';
require_once DEPLOYR_DIRECTORY . '/exception/DeployRResponseException.php';
require_once DEPLOYR_DIRECTORY . '/DeployRExecution.php';

require_once COLLECTION_DIRECTORY . '/ArrayMap.php';

class DeployRSessionJsonResponseParser extends DeployRSessionResponseParser
{
    
    public function parseCreateSession($json)
    {
        $decoded = json_decode($json, true);
        
        if (!$this->isSuccess($decoded)) {
            throw new DeployRResponseException('client - createSession - failed...');   
        }
        
        return $decoded['deployr']['response']['session'];
    }
    
    public function parseGetObject($json, $name)
    {        
        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
            
            return $decoded['deployr']['response']['robjects'][$name];
            
        } else {
            throw new DeployRResponseException('session - get object - success attribute is not true');   
        }
    }
    
    public function parseListObjects($json)
    {
        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
            
            $map = new ArrayMap();
            foreach ($decoded['deployr']['response']['robjects'] as $name => $robject)
            {
                $map->put($name, $robject);
            }
            
            return $map;
            
        } else {
            throw new DeployRResponseException('session - get object - success attribute is not true');   
        }
    }
    
    public function parseExecuteScript($json)
    {
        return $this->parseExecuteCode($json);
    }
    
    public function parseExecuteCode($json)
    {
        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
            $files = new ArrayMap();
            
            if (isset($decoded['deployr']['response']['files'])) {
                foreach ($decoded['deployr']['response']['files'] as $name => $file) {
                    $files->put($name, $file['value']);
                }
            }
            
            return new DeployRExecution(
                $decoded['deployr']['response']['robjects'],
                $files
            );
        } else {
            throw new DeployRResponseException('session - execute code - success attribute is not true');   
        }
    }
    
    public function parseGetOutput($json)
    {
        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
            return $decoded['deployr']['response']['console'];
        } else {
            throw new DeployRResponseException('session - get output - success attribute is not true');   
        }
    }
    
    public function parseGetHistory($json)
    {
        $decoded = json_decode($json, true);

        if ($this->isSuccess($decoded)) {
            return $decoded['deployr']['response']['history'];
        } else {
            throw new DeployRResponseException('session - get history - success attribute is not true');   
        }
    }
    
    public function parseSaveProject($json, $descr)
    {
        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
            return $decoded['deployr']['response']['projects'][$descr]['value'];
        } else {
            throw new DeployRResponseException('session - save project - success attribute is not true');   
        }
    }
    
    public function parseSaveWorkspace($json, $descr)
    {
        $decoded = json_decode($json, true);
   
        if ($this->isSuccess($decoded)) {
            return $decoded['deployr']['response']['repository'][$descr]['value'];
        } else {
            throw new DeployRResponseException('session - save workspace - success attribute is not true');   
        }
    }
    
    public function parsePing($json)
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