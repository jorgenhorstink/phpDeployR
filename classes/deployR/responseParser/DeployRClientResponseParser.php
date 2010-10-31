<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/json/DeployRClientJsonResponseParser.php';

abstract class DeployRClientResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case DeployRClient::OUTPUT_FORMAT_JSON:
                return new DeployRClientJsonResponseParser();
                break;
        }
    }
}

?>