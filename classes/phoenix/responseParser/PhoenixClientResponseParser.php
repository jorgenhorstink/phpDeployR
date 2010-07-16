<?php

require_once PHOENIX_DIRECTORY . '/responseParser/json/PhoenixClientJsonResponseParser.php';
require_once PHOENIX_DIRECTORY . '/responseParser/xml/PhoenixClientXmlResponseParser.php';

abstract class PhoenixClientResponseParser
{
    abstract public function parseLogin($response);  
    
    public static function get($format)
    {
        switch ($format)
        {
            case PhoenixClient::OUTPUT_FORMAT_JSON:
                return new PhoenixClientJsonResponseParser();
                break;
            case PhoenixClient::OUTPUT_FORMAT_XML:
                return new PhoenixClientXmlResponseParser();
                break;
        }
    }
}

?>