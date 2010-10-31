<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/json/DeployRScriptRepositoryJsonResponseParser.php';

abstract class DeployRScriptRepositoryResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case DeployRClient::OUTPUT_FORMAT_JSON:
                return new DeployRScriptRepositoryJsonResponseParser();
                break;
        }
    }
}

?>