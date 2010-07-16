<?php

require_once PHOENIX_DIRECTORY . '/responseParser/json/PhoenixSessionJsonResponseParser.php';
require_once PHOENIX_DIRECTORY . '/responseParser/xml/PhoenixSessionXmlResponseParser.php';

abstract class PhoenixSessionResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case PhoenixClient::OUTPUT_FORMAT_JSON:
                return new PhoenixSessionJsonResponseParser();
                break;
            case PhoenixClient::OUTPUT_FORMAT_XML:
                return new PhoenixSessionXmlResponseParser();
                break;
        }
    }
}

?>