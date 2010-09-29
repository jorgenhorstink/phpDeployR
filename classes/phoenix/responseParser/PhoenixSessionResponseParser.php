<?php

require_once PHOENIX_DIRECTORY . '/responseParser/json/PhoenixSessionJsonResponseParser.php';

abstract class PhoenixSessionResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case PhoenixClient::OUTPUT_FORMAT_JSON:
                return new PhoenixSessionJsonResponseParser();
                break;
        }
    }
}

?>