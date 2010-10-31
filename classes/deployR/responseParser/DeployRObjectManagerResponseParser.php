<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/json/DeployRObjectManagerJsonResponseParser.php';

abstract class DeployRObjectManagerResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case DeployRClient::OUTPUT_FORMAT_JSON:
                return new DeployRObjectManagerJsonResponseParser();
                break;
        }
    }
}

?>