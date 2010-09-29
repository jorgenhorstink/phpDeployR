<?php

require_once PHOENIX_DIRECTORY . '/responseParser/json/PhoenixProjectRepositoryJsonResponseParser.php';

abstract class PhoenixProjectRepositoryResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case PhoenixClient::OUTPUT_FORMAT_JSON:
                return new PhoenixProjectRepositoryJsonResponseParser();
                break;
        }
    }
}

?>