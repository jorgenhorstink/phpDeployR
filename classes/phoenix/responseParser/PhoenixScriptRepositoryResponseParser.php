<?php

require_once PHOENIX_DIRECTORY . '/responseParser/json/PhoenixScriptRepositoryJsonResponseParser.php';

abstract class PhoenixScriptRepositoryResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case PhoenixClient::OUTPUT_FORMAT_JSON:
                return new PhoenixScriptRepositoryJsonResponseParser();
                break;
        }
    }
}

?>