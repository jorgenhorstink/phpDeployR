<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/DeployRObjectManagerResponseParser.php';
require_once DEPLOYR_DIRECTORY . '/DeployRRObject.php';

class DeployRHttpObjectManager {
    
    protected $session;
    protected $serverUrl;
    protected $cookieId;
    protected $outputFormat;
    
    public function __construct(DeployRHttpSession $session, $serverUrl, $cookieId, $outputFormat) {
        $this->session = $session;
        $this->serverUrl = $serverUrl;
        $this->cookieId = $cookieId;
        $this->outputFormat = $outputFormat;
        
        $this->responseParser = DeployRObjectManagerResponseParser::get($outputFormat); 
    }
    
    public function getObjects()
    {
        return $this->responseParser->parseGetObjects(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/list',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );      
    }
    
    public function get(DeployRRObject $robject)
    {
        $content = $this->getDefaultContent();
        $content .= "&name=" . urlencode($robject->getName());

        return $this->responseParser->parseGet(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/get',
                'POST',
                $this->getHeader(),
                $content
            ), 
            $robject->getName()
        );  
        
    }
    
    public function delete(DeployRRObject $robject)
    {
        $content = $this->getDefaultContent();
        $content .= "&name=" . urlencode($robject->getName());

        return $this->responseParser->parseDelete(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/delete',
                'POST',
                $this->getHeader(),
                $content
            ), 
            $robject->getName()
        );  
    }
    
    public function load(DeployRObject $object)
    {
        $content = $this->getDefaultContent();
        $content .= "&id=" . urlencode($object->getId());

        return $this->responseParser->parseSave(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/load',
                'POST',
                $this->getHeader(),
                $content
            )
        );           
    }
    
    public function save(DeployRRObject $robject, $descr)
    {
        $content = $this->getDefaultContent();
        $content .= "&name=" . urlencode($robject->getName());
        $content .= "&descr=" . urlencode($descr);

        return $this->responseParser->parseSave(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/save',
                'POST',
                $this->getHeader(),
                $content
            ), 
            $robject->getName(),
            $descr
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
        $content  = "format=" . ($this->outputFormat == DeployRClient::OUTPUT_FORMAT_JSON ? "json" : "xml") . "&"; 
        $content .= "session=" . urlencode($this->session->getId());
        return $content;
    }
}