<?php

require_once PHOENIX_DIRECTORY . '/responseParser/json/PhoenixObjectRepositoryJsonResponseParser.php';

abstract class PhoenixObjectRepositoryResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case PhoenixClient::OUTPUT_FORMAT_JSON:
                return new PhoenixObjectRepositoryJsonResponseParser();
                break;
        }
    }
}

?>