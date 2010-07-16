<?php

require_once PHOENIX_DIRECTORY . '/PhoenixSession.php';
require_once PHOENIX_DIRECTORY . '/responseParser/PhoenixSessionResponseParser.php';
require_once PHOENIX_DIRECTORY . '/transport/http/PhoenixHttpTransport.php';

class PhoenixHttpSession extends PhoenixSession
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
        
        $this->responseParser = PhoenixSessionResponseParser::get($outputFormat);
    }
    
    public function getId()
    {
        return $this->sessionId;
    }

    public function close() {
        return $this->responseParser->parseClose(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/close',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }

    public function executeCode($code, $rObjects = null, $files = null) {
        //$content = $this->getDefaultContent();
 
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
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/execute/code',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }
        
    public function executeScript($rscript, $preload = null, $inputs = null, $robjects = null, $files = null, $saveWorkspace = null) {
 
        $content  = "format=json&"; 
        $content .= "session=" . urlencode($this->sessionId);
        
        $content .= "&rscript=" . urlencode($rscript);
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
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/execute/script',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }
    
    public function ping() {        
        return $this->responseParser->parsePing(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/ping',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }
    
    
    public function saveProject($project) {}
    
    public function saveWorkspace() {}

    public function getObject($name) {
        $content = $this->getDefaultContent();
        
        $content .= "&name=" . urlencode($name);
        
        return $this->responseParser->parseExecuteScript(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/get',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }
    
    public function listObjects() {
        return $this->responseParser->parseExecuteScript(
            PhoenixHttpTransport::getInstance()->send(
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
        
        return $this->responseParser->parseExecuteScript(
            PhoenixHttpTransport::getInstance()->send(
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
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/save',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }

    private function getHeader()
    {
        $header  = "Content-Type: application/x-www-form-urlencoded; charset=utf-8\n";
        $header .= "Cookie: JSESSIONID=" . $this->cookieId;
        
        return $header;
    }
    
    private function getDefaultContent()
    {
        $content  = "format=" . ($this->outputFormat == PhoenixClient::OUTPUT_FORMAT_JSON ? "json" : "xml") . "&"; 
        $content .= "session=" . urlencode($this->sessionId);
        
        return $content;
    }
    
}

?>