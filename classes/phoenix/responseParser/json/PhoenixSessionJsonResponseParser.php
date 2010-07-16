<?php

require_once PHOENIX_DIRECTORY . '/responseParser/PhoenixSessionResponseParser.php';
require_once PHOENIX_DIRECTORY . '/exception/PhoenixResponseException.php';
require_once PHOENIX_DIRECTORY . '/PhoenixExecution.php';

require_once COLLECTION_DIRECTORY . '/ArrayMap.php';

class PhoenixSessionJsonResponseParser extends PhoenixSessionResponseParser
{
    public function parseExecuteScript($json)
    {
        return $this->parseExecuteCode($json);
    }
    
    public function parseExecuteCode($json)
    {
        $decoded = json_decode($json, true);
   
        if ($decoded['phoenix']['response']['success'] == 1) {
            $files = new ArrayMap();
            
            if (isset($decoded['phoenix']['response']['files'])) {
                foreach ($decoded['phoenix']['response']['files'] as $name => $file) {
                    $files->put($name, $file['value']);
                }
            }
            
            return new PhoenixExecution(
                $decoded['phoenix']['response']['robjects'],
                $files
            );
        } else {
            throw new PhoenixResponseException('session - execute code - success attribute is not true');   
        }
    }
    
}

?>