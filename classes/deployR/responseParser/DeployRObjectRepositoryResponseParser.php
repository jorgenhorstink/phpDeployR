<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/json/DeployRObjectRepositoryJsonResponseParser.php';

abstract class DeployRObjectRepositoryResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case DeployRClient::OUTPUT_FORMAT_JSON:
                return new DeployRObjectRepositoryJsonResponseParser();
                break;
        }
    }
}

?>