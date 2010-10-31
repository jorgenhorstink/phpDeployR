<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/json/DeployRSessionJsonResponseParser.php';

abstract class DeployRSessionResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case DeployRClient::OUTPUT_FORMAT_JSON:
                return new DeployRSessionJsonResponseParser();
                break;
        }
    }
}

?>