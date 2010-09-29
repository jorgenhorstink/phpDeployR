<?php

require_once PHOENIX_DIRECTORY . '/responseParser/json/PhoenixClientJsonResponseParser.php';

abstract class PhoenixClientResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case PhoenixClient::OUTPUT_FORMAT_JSON:
                return new PhoenixClientJsonResponseParser();
                break;
        }
    }
}

?>