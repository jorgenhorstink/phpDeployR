<?php

require_once PHOENIX_DIRECTORY . '/responseParser/json/PhoenixObjectManagerJsonResponseParser.php';

abstract class PhoenixObjectManagerResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case PhoenixClient::OUTPUT_FORMAT_JSON:
                return new PhoenixObjectManagerJsonResponseParser();
                break;
        }
    }
}

?>