<?php

require_once PHOENIX_DIRECTORY . '/responseParser/PhoenixObjectManagerResponseParser.php';
require_once PHOENIX_DIRECTORY . '/RObject.php';

class PhoenixHttpObjectManager {
    
    protected $session;
    protected $serverUrl;
    protected $cookieId;
    protected $outputFormat;
    
    public function __construct(PhoenixHttpSession $session, $serverUrl, $cookieId, $outputFormat) {
        $this->session = $session;
        $this->serverUrl = $serverUrl;
        $this->cookieId = $cookieId;
        $this->outputFormat = $outputFormat;
        
        $this->responseParser = PhoenixObjectManagerResponseParser::get($outputFormat); 
    }
    
    public function getObjects()
    {
        return $this->responseParser->parseGetObjects(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/list',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );      
    }
    
    public function get(RObject $robject)
    {
        $content = $this->getDefaultContent();
        $content .= "&name=" . urlencode($robject->getName());

        return $this->responseParser->parseGet(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/get',
                'POST',
                $this->getHeader(),
                $content
            ), 
            $robject->getName()
        );  
        
    }
    
    public function delete(RObject $robject)
    {
        $content = $this->getDefaultContent();
        $content .= "&name=" . urlencode($robject->getName());

        return $this->responseParser->parseDelete(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/delete',
                'POST',
                $this->getHeader(),
                $content
            ), 
            $robject->getName()
        );  
    }
    
    public function load(PhoenixObject $object)
    {
        $content = $this->getDefaultContent();
        $content .= "&id=" . urlencode($object->getId());

        return $this->responseParser->parseSave(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/object/load',
                'POST',
                $this->getHeader(),
                $content
            )
        );           
    }
    
    public function save(RObject $robject, $descr)
    {
        $content = $this->getDefaultContent();
        $content .= "&name=" . urlencode($robject->getName());
        $content .= "&descr=" . urlencode($descr);

        return $this->responseParser->parseSave(
            PhoenixHttpTransport::getInstance()->send(
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
        $content  = "format=" . ($this->outputFormat == PhoenixClient::OUTPUT_FORMAT_JSON ? "json" : "xml") . "&"; 
        $content .= "session=" . urlencode($this->session->getId());
        return $content;
    }
}