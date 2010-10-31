<?php

require_once DEPLOYR_DIRECTORY . '/DeployRSession.php';
require_once DEPLOYR_DIRECTORY . '/responseParser/DeployRSessionResponseParser.php';
require_once DEPLOYR_DIRECTORY . '/transport/http/DeployRHttpTransport.php';

class DeployRHttpSession implements DeployRSession
{
    protected $cookieId;
    protected $outputFormat;
    protected $serverUrl;

    protected $sessionId = null;
        
    public function __construct($sessionId, $cookieId, $serverUrl, $outputFormat) {
        $this->sessionId = $sessionId;
        $this->cookieId = $cookieId;
        $this->serverUrl = $serverUrl;
        $this->outputFormat = $outputFormat;
        
        $this->responseParser = DeployRSessionResponseParser::get($outputFormat);
    }
    
    public static function create($cookieId, $serverUrl, $outputFormat)
    {
        $header  = "Content-Type: application/x-www-form-urlencoded; charset=utf-8\n";
        $header .= "Cookie: JSESSIONID=" . $cookieId;
        
        $content  = "format=" . ($outputFormat == DeployRClient::OUTPUT_FORMAT_JSON ? "json" : "xml"); 

        $sessionId = DeployRSessionResponseParser::get($outputFormat)->parseCreateSession(
            DeployRHttpTransport::getInstance()->send(
                $serverUrl . '/r/session/create',
                'POST',
                $header,
                $content
            )
        );
        
        return new DeployRHttpSession($sessionId, $cookieId, $serverUrl, $outputFormat);
    }
    
    public function getId()
    {
        return $this->sessionId;
    }

    public function close() 
    {
        return $this->responseParser->parseClose(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/close',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }

    public function executeCode($code, $rObjects = null, $files = null) 
    {
        $content  = "format=json&"; 
        $content .= "session=" . urlencode($this->sessionId);
        
        if ($rObjects != null) {
            $content .= "&robjects=" . urlencode($rObjects);   
        }
        if ($files != null) {
            $content .= "&files=" . urlencode($files);   
        }
        
        $content .= "&code=" . urlencode($code);

        return $this->responseParser->parseExecuteCode(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/execute/code',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }
        
    public function executeScript(DeployRScript $script, $preload = null, $inputs = null, $robjects = null, $files = null, $saveWorkspace = null) 
    {
 
        $content  = "format=json&"; 
        $content .= "session=" . urlencode($this->sessionId) . '&';
        $content .= "rscript=" . urlencode($script->getId());
        
        if ($preload != null) {
            $content .= "&preload=" . urlencode($preload);
        }
        if ($inputs != null) {
            $content .= "&inputs=" . urlencode($inputs);   
        }
        if ($robjects != null) {
            $content .= "&robjects=" . urlencode($robjects);   
        }
        if ($files != null) {
            $content .= "&files=" . urlencode($files);   
        }
        if ($saveWorkspace != null) {
            $content .= "&saveworkspace=" . ($saveWorkspace ? "true" : "false");   
        }     
           
        return $this->responseParser->parseExecuteScript(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/execute/script',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }
    
    public function ping() 
    {        
        return $this->responseParser->parsePing(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/ping',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }
    
    public function getOutput()
    {
        return $this->responseParser->parseGetOutput(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/output',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }
    
    public function getHistory()
    {
        return $this->responseParser->parseGetHistory(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/history',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }

    public function saveProject($descr) 
    {
        $content = $this->getDefaultContent();
        $content .= "&descr=" . urlencode($descr);
        
        return $this->responseParser->parseSaveProject(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/project/save',
                'POST',
                $this->getHeader(),
                $content
            ),
            $descr
        );
    }
    
    public function saveWorkspace($descr) 
    {
        $content = $this->getDefaultContent();
        $content .= "&descr=" . urlencode($descr);
        
        return $this->responseParser->parseSaveWorkspace(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/workspace/save',
                'POST',
                $this->getHeader(),
                $content
            ),
            $descr
        );
    }
    
    public function getObjectManager()
    {
        return new DeployRHttpObjectManager($this, $this->serverUrl, $this->cookieId, $this->outputFormat);   
    }    
    
    
    /*
    public function getObject($name) {
        $content = $this->getDefaultContent();
        
        $content .= "&name=" . urlencode($name);
        
        return $this->responseParser->parseGetObject(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/get',
                'POST',
                $this->getHeader(),
                $content
            ),
            $name
        );
    }
    
    public function listObjects() {
        return $this->responseParser->parseListObjects(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/list',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }
    
    public function loadStoredObjects($id) {
        $content = $this->getDefaultContent();
        $content .= "&id=" . urlencode($id);
        
        return $this->responseParser->parseLoadStoredObjects(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/stored/load',
                'POST',
                $this->getHeader(),
                $content
            )
        );        
    }
    
    public function saveObject($name, $description) {
        $content = $this->getDefaultContent();
        
        $content .= "&name=" . urlencode($name);
        $content .= "&descr=" . urlencode($description);
        
        return $this->responseParser->parseExecuteScript(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/save',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }
    */

    private function getHeader()
    {
        $header  = "Content-Type: application/x-www-form-urlencoded; charset=utf-8\n";
        $header .= "Cookie: JSESSIONID=" . $this->cookieId;

        return $header;
    }
    
    private function getDefaultContent()
    {
        $content  = "format=" . ($this->outputFormat == DeployRClient::OUTPUT_FORMAT_JSON ? "json" : "xml") . "&"; 
        $content .= "session=" . urlencode($this->sessionId);
        
        return $content;
    }
    
}

?>