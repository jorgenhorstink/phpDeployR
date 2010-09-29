<?php

require_once PHOENIX_DIRECTORY . '/responseParser/PhoenixClientResponseParser.php';
require_once PHOENIX_DIRECTORY . '/transport/http/PhoenixHttpTransport.php';
require_once PHOENIX_DIRECTORY . '/impl/PhoenixHttpSession.php';

require_once PHOENIX_DIRECTORY . '/impl/PhoenixHttpObjectRepository.php';
require_once PHOENIX_DIRECTORY . '/impl/PhoenixHttpProjectRepository.php';
require_once PHOENIX_DIRECTORY . '/impl/PhoenixHttpScriptRepository.php';
require_once PHOENIX_DIRECTORY . '/impl/PhoenixHttpObjectManager.php';

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
    
    public function getObjectRepository()
    {
        return new PhoenixHttpObjectRepository($this, $this->serverUrl, $this->cookieId, $this->outputFormat);   
    }
    
    public function getProjectRepository()
    {
        return new PhoenixHttpProjectRepository($this, $this->serverUrl, $this->cookieId, $this->outputFormat);   
    }
    
    public function getScriptRepository()
    {
        return new PhoenixHttpScriptRepository($this, $this->serverUrl, $this->cookieId, $this->outputFormat);   
    }

    public function loadProject(PhoenixProject $project)
    {
        $content = $this->getDefaultContent();
        $content .= "&id=" . $project->getId();
        
        $sessionId = $this->responseParser->parseLoadProject(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/project/load',
                'POST',
                $this->getHeader(),
                $content
            )
        );

        $phoenixSession = new PhoenixHttpSession(
            $sessionId,
            $this->cookieId,
            $this->serverUrl,
            $this->outputFormat
        );
        
        $this->session->setAttribute($name, $phoenixSession->getId(), 'phoenix_sessions');
        
        return $phoenixSession;
    }

    public function executeScript(PhoenixScript $script, $preload = null, $inputs = null, $robjects = null, $files = null, $saveWorkspace = null)
    {
 
        $content  = "format=json&"; 
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
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/execute/script',
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
                $this->serverUrl . '/r/user/login',
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
                $this->serverUrl . '/r/user/logout',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
        
    }
    
    public function createSession($name)
    {
        if (!$this->session->hasAttribute($name, 'phoenix_sessions')) {
            $phoenixSession = PhoenixHttpSession::create(
                $this->cookieId,
                $this->serverUrl,
                $this->outputFormat
            );

            $this->session->setAttribute($name, $phoenixSession->getId(), 'phoenix_sessions');

        } else {
            $phoenixSession = new PhoenixHttpSession(
                $this->session->getAttribute($name, 'phoenix_sessions'),
                $this->cookieId,
                $this->serverUrl,
                $this->outputFormat
            );
        }

        return $phoenixSession;
    }
    
    public function whoAmI()
    {
        return $this->responseParser->parseWhoAmI(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/user/whoami',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }
    
    public function autoSave()
    {
        return $this->responseParser->parseAutoSave(
            PhoenixHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/user/autosave',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
    }
    
    protected function getHeader()
    {
        $header  = "Content-Type: application/x-www-form-urlencoded; charset=utf-8\n";
        $header .= "Cookie: JSESSIONID=" . $this->cookieId;
        
        return $header;
    }
    
    protected function getDefaultContent()
    {
        return "format=" . ($this->outputFormat == PhoenixClient::OUTPUT_FORMAT_JSON ? "json" : "xml");
    }
}