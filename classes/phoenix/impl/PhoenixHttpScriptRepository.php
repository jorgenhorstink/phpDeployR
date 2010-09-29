<?php

require_once PHOENIX_DIRECTORY . '/responseParser/PhoenixScriptRepositoryResponseParser.php';

class PhoenixHttpScriptRepository {
    
    protected $client;
    protected $serverUrl;
    protected $cookieId;
    protected $outputFormat;
    
    public function __construct(PhoenixHttpClient $client, $serverUrl, $cookieId, $outputFormat) {
        $this->client = $client;
        $this->serverUrl = $serverUrl;
        $this->cookieId = $cookieId;
        $this->outputFormat = $outputFormat;
        
        $this->responseParser = PhoenixScriptRepositoryResponseParser::get($outputFormat); 
    }
    
    public function getScripts()
    {
        return $this->responseParser->parseGetScripts(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/script/list',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
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
        return "format=" . ($this->outputFormat == PhoenixClient::OUTPUT_FORMAT_JSON ? "json" : "xml");
    }
}