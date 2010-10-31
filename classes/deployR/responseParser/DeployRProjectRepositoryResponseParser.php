<?php

require_once DEPLOYR_DIRECTORY . '/responseParser/json/DeployRProjectRepositoryJsonResponseParser.php';

abstract class DeployRProjectRepositoryResponseParser
{
    public static function get($format)
    {
        switch ($format)
        {
            case DeployRClient::OUTPUT_FORMAT_JSON:
                return new DeployRProjectRepositoryJsonResponseParser();
                break;
        }
    }
}

?>