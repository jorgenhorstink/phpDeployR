<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/DeployRProjectRepositoryResponseParser.php';

class DeployRHttpProjectRepository {
    
    protected $client;
    protected $serverUrl;
    protected $cookieId;
    protected $outputFormat;
    
    public function __construct(DeployRHttpClient $client, $serverUrl, $cookieId, $outputFormat) {
        $this->client = $client;
        $this->serverUrl = $serverUrl;
        $this->cookieId = $cookieId;
        $this->outputFormat = $outputFormat;
        
        $this->responseParser = DeployRProjectRepositoryResponseParser::get($outputFormat); 
    }
    
    public function getProjects()
    {
        return $this->responseParser->parseGetProjects(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/project/list',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );  
    }
    
    public function delete(DeployRProject $project)
    {
        $content  = $this->getDefaultContent();
        $content .= "&id=" . $project->getId();
        
        return $this->responseParser->parseProjectDelete(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/project/delete',
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