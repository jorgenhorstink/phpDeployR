<?php

require_once PHOENIX_DIRECTORY . '/responseParser/PhoenixClientResponseParser.php';
require_once PHOENIX_DIRECTORY . '/transport/http/PhoenixHttpTransport.php';
require_once PHOENIX_DIRECTORY . '/PhoenixHttpSession.php';

class PhoenixHttpClient extends PhoenixClient
{
    protected $serverUrl;
    protected $session;
    
    protected $outputFormat;
    protected $httpTransport;
    protected $cookieId;
    
    public function __construct($serverUrl, Session $session, $outputFormat = PhoenixClient::OUTPUT_FORMAT_JSON)
    {
        $this->serverUrl = $serverUrl;
        $this->session = $session;
        
        $this->responseParser = PhoenixClientResponseParser::get($outputFormat);
        
        $this->outputFormat = $outputFormat; 

        $this->cookieId = $this->session->getAttribute('cookie_id', 'phoenix');
    }

    public function isAuthenticated()
    {
        return $this->cookieId != null;   
    }
    
    public function listScripts()
    {
        return $this->responseParser->parseListScripts(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/script/list',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );        
    }
    
    public function loadProject(PhoenixProject $project)
    {
        $content = $this->getDefaultContent();
        $content .= "&id=" . $project->getId();
        
        return $this->responseParser->parseWhoAmI(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/project/load',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }

    // Currently only PhoenixBasicAuthentication is supported, so keep it simple
    public function login(PhoenixAuthentication $authentication)
    {
        $header  = "Content-Type: application/x-www-form-urlencoded; charset=utf-8\n";
        
        $content  = $this->getDefaultContent();
        $content .= "&username=" . $authentication->getUsername();
        $content .= "&password=" . $authentication->getPassword();

        
        $this->cookieId = $this->responseParser->parseLogin(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/login',
                'POST',
                $header,
                $content
            )
        );
        
        $this->session->setAttribute('cookie_id', $this->cookieId, 'phoenix');
    }
    
    public function logout()
    {
        $this->session->removeNamespace('phoenix');
        $this->session->removeNamespace('phoenix_sessions');
        
        return $this->responseParser->parseLogout(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/logout',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
        
    }
    
    public function createSession($name)
    {
        if (!$this->session->hasAttribute($name, 'phoenix_sessions')) {            
            $sessionId = $this->responseParser->parseCreateSession(
                PhoenixHttpTransport::getInstance()->send(
                    $this->serverUrl . '/r/session/create',
                    'POST',
                    $this->getHeader(),
                    $this->getDefaultContent()
                )
            );
            
            $this->session->setAttribute($name, $sessionId, 'phoenix_sessions');
        }
        
        return new PhoenixHttpSession(
            $this->session->getAttribute($name, 'phoenix_sessions'),
            $this->cookieId,
            $this->serverUrl,
            $this->outputFormat
        );
    }
    
    public function whoAmI()
    {
        return $this->responseParser->parseWhoAmI(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/whoami',
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