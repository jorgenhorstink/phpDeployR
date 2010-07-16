<?php

require_once PHOENIX_DIRECTORY . '/PhoenixHttpClient.php';

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

    public function getFormat()
    {
        return $this->outputFormat;
    }
    
    abstract function isAuthenticated();

    abstract function login(PhoenixAuthentication $authentication);

    abstract function logout();

    abstract function whoAmI();

    abstract function createSession($name);

    abstract function loadProject(PhoenixProject $project);

}

?>