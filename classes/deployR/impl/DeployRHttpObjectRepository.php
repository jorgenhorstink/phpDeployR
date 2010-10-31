<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/DeployRObjectRepositoryResponseParser.php';

class DeployRHttpObjectRepository {
    
    protected $client;
    protected $serverUrl;
    protected $cookieId;
    protected $outputFormat;
    
    public function __construct(DeployRHttpClient $client, $serverUrl, $cookieId, $outputFormat) {
        $this->client = $client;
        $this->serverUrl = $serverUrl;
        $this->cookieId = $cookieId;
        $this->outputFormat = $outputFormat;
        
        $this->responseParser = DeployRObjectRepositoryResponseParser::get($outputFormat); 
    }
    
    public function getObjects()
    {
        return $this->responseParser->parseGetObjects(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/repository/object/list',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }
    
    public function delete($id)
    {
        $content  = $this->getDefaultContent();
        $content .= "&id=" . $id;
        
        $this->responseParser->parseDelete(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/repository/object/delete',
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
        return "format=" . ($this->outputFormat == DeployRClient::OUTPUT_FORMAT_JSON ? "json" : "xml");
    }
}