<?php

require_once DEPLOYR_DIRECTORY . '/impl/DeployRHttpClient.php';

abstract class DeployRClient
{
    const OUTPUT_FORMAT_XML = 1;
    const OUTPUT_FORMAT_JSON = 2;

    /**
     * @param $url Location of the DeployR Server
     * @param $cookieId Cookie ID of the DeployR HTTP Session. This parameter is needed if you are creating the client for the second time and don't want to execute a real login again
     * @param $outputFormat Optional parameter for specifying the outputFormat for this client. By default, the output is in JSON.
     */
    public static function createHttpClient($url, Session $session, $outputFormat = DeployRClient::OUTPUT_FORMAT_JSON) {
        return new DeployRHttpClient($url, $session, $outputFormat);
    }

    public public function getFormat()
    {
        return $this->outputFormat;
    }
    
    abstract public function open();
    
    abstract public function close();
    
    abstract public function login(DeployRAuthentication $authentication);

    abstract public function logout();

    abstract public function whoAmI();

    abstract public function createSession($name);

    abstract public function loadProject(DeployRProject $project);

    abstract protected function getHeader();
    
    abstract protected function getDefaultContent();
}

?>