<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/DeployRClientResponseParser.php';
require_once DEPLOYR_DIRECTORY . '/transport/http/DeployRHttpTransport.php';
require_once DEPLOYR_DIRECTORY . '/impl/DeployRHttpSession.php';

require_once DEPLOYR_DIRECTORY . '/impl/DeployRHttpObjectRepository.php';
require_once DEPLOYR_DIRECTORY . '/impl/DeployRHttpProjectRepository.php';
require_once DEPLOYR_DIRECTORY . '/impl/DeployRHttpScriptRepository.php';
require_once DEPLOYR_DIRECTORY . '/impl/DeployRHttpObjectManager.php';

class DeployRHttpClient extends DeployRClient
{
    protected $serverUrl;
    protected $session;
    
    protected $outputFormat;
    protected $httpTransport;
    protected $cookieId;
    
    public function __construct($serverUrl, Session $session, $outputFormat = DeployRClient::OUTPUT_FORMAT_JSON)
    {
        $this->serverUrl = $serverUrl;
        $this->session = $session;

        $this->responseParser = DeployRClientResponseParser::get($outputFormat);
        
        $this->outputFormat = $outputFormat; 

        $this->cookieId = $this->session->getAttribute('cookie_id', 'deployr');
    }
    
    public function open()
    {
        $lastTimeOfActivity = $this->session->getAttribute('last_time_of_activity', 'phoenix');
        // The server kills the session after 30 minutes.
        if ((time() - $lastTimeOfActivity) > 1800) {
            $this->cookieId = null;   
        }
    }
    
    public function close()
    {
        $this->session->setAttribute('last_time_of_activity', time());        
    }

    public function getObjectRepository()
    {
        return new DeployRHttpObjectRepository($this, $this->serverUrl, $this->cookieId, $this->outputFormat);   
    }
    
    public function getProjectRepository()
    {
        return new DeployRHttpProjectRepository($this, $this->serverUrl, $this->cookieId, $this->outputFormat);   
    }
    
    public function getScriptRepository()
    {
        return new DeployRHttpScriptRepository($this, $this->serverUrl, $this->cookieId, $this->outputFormat);   
    }

    public function loadProject(DeployRProject $project)
    {
        $content = $this->getDefaultContent();
        $content .= "&id=" . $project->getId();
        
        $sessionId = $this->responseParser->parseLoadProject(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/project/load',
                'POST',
                $this->getHeader(),
                $content
            )
        );

        $deployRSession = new DeployRHttpSession(
            $sessionId,
            $this->cookieId,
            $this->serverUrl,
            $this->outputFormat
        );
        
        $this->session->setAttribute($name, $deployRSession->getId(), 'deployr_sessions');
        
        return $deployRSession;
    }

    public function executeScript(DeployRScript $script, $preload = null, $inputs = null, $robjects = null, $files = null, $saveWorkspace = null)
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
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/session/execute/script',
                'POST',
                $this->getHeader(),
                $content
            )
        );
    }
    
    // Currently only DeployRBasicAuthentication is supported, so keep it simple
    public function login(DeployRAuthentication $authentication)
    {
        if ($this->cookieId == null) {
    
            $header  = "Content-Type: application/x-www-form-urlencoded; charset=utf-8\n";
            
            $content  = $this->getDefaultContent();
            $content .= "&username=" . $authentication->getUsername();
            $content .= "&password=" . $authentication->getPassword();
    
            $this->cookieId = $this->responseParser->parseLogin(
                DeployRHttpTransport::getInstance()->send(
                    $this->serverUrl . '/r/user/login',
                    'POST',
                    $header,
                    $content
                )
            );
            
            $this->session->setAttribute('cookie_id', $this->cookieId, 'deployr');
        }
    }
    
    public function logout()
    {
        $this->session->removeNamespace('deployr');
        $this->session->removeNamespace('deployr_sessions');
        
        return $this->responseParser->parseLogout(
            DeployRHttpTransport::getInstance()->send(
                $this->serverUrl . '/r/user/logout',
                'POST',
                $this->getHeader(),
                $this->getDefaultContent()
            )
        );
        
    }
    
    public function createSession($name)
    {
        if (!$this->session->hasAttribute($name, 'deployr_sessions')) {
            $deployRSession = DeployRHttpSession::create(
                $this->cookieId,
                $this->serverUrl,
                $this->outputFormat
            );

            $this->session->setAttribute($name, $deployRSession->getId(), 'deployr_sessions');

        } else {
            $deployRSession = new DeployRHttpSession(
                $this->session->getAttribute($name, 'deployr_sessions'),
                $this->cookieId,
                $this->serverUrl,
                $this->outputFormat
            );
        }

        return $deployRSession;
    }
    
    public function whoAmI()
    {
        return $this->responseParser->parseWhoAmI(
            DeployRHttpTransport::getInstance()->send(
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
            DeployRHttpTransport::getInstance()->send(
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
        return "format=" . ($this->outputFormat == DeployRClient::OUTPUT_FORMAT_JSON ? "json" : "xml");
    }
}