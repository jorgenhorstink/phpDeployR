<?php

require_once PHOENIX_DIRECTORY . '/impl/PhoenixHttpClient.php';

abstract class PhoenixClient
{
    const OUTPUT_FORMAT_XML = 1;
    const OUTPUT_FORMAT_JSON = 2;

    /**
     * @param $url Location of the Phoenix Server
     * @param $cookieId Cookie ID of the Phoenix HTTP Session. This parameter is needed if you are creating the client for the second time and don't want to execute a real login again
     * @param $outputFormat Optional parameter for specifying the outputFormat for this client. By default, the output is in JSON.
     */
    public static function createHttpClient($url, Session $session, $outputFormat = PhoenixClient::OUTPUT_FORMAT_JSON) {
        return new PhoenixHttpClient($url, $session, $outputFormat);
    }

    public public function getFormat()
    {
        return $this->outputFormat;
    }
    
    abstract public function isAuthenticated();

    abstract public function login(PhoenixAuthentication $authentication);

    abstract public function logout();

    abstract public function whoAmI();

    abstract public function createSession($name);

    abstract public function loadProject(PhoenixProject $project);

    abstract protected function getHeader();
    
    abstract protected function getDefaultContent();
}

?>