<?php

require_once DEPLOYR_DIRECTORY . '/exception/DeployRBadGatewayException.php';
require_once DEPLOYR_DIRECTORY . '/exception/DeployRBadRequestException.php';
require_once DEPLOYR_DIRECTORY . '/exception/DeployRForbiddenException.php';
require_once DEPLOYR_DIRECTORY . '/exception/DeployRInternalServerErrorException.php';
require_once DEPLOYR_DIRECTORY . '/exception/DeployRNotAcceptableException.php';
require_once DEPLOYR_DIRECTORY . '/exception/DeployRNotFoundException.php';
require_once DEPLOYR_DIRECTORY . '/exception/DeployRServiceUnavailableException.php';
require_once DEPLOYR_DIRECTORY . '/exception/DeployRUnauthorizedException.php';

class DeployRHttpTransport
{
    protected static $instance;
    
    protected function __construct() {}
    
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new DeployRHttpTransport();
        }

        return self::$instance;
    }
    
    public function send($url, $method, $header, $content) {
        $context = array('http' => array ( 'method' => $method,
                                        'header'=> $header,
                                        'content' => $content));
    
        $content = file_get_contents($url, 0, stream_context_create($context));         
        $httpCode = $http_response_header[0];
     
        if (preg_match('/400/', $httpCode)) {
            throw new DeployRBadRequestException('The request was invalid.  An accompanying error message will explain why. ');   
        } elseif (preg_match('/401/', $httpCode)) {
            throw new DeployRUnauthorizedException('Authentication credentials were missing or incorrect. ');   
        } elseif (preg_match('/403/', $httpCode)) {
            throw new DeployRForbiddenException('The request is understood, but it has been refused.  ');   
        } elseif (preg_match('/404/', $httpCode)) {
            throw new DeployRNotFoundException('The URI requested is invalid or the resource requested, such as a user, does not exists. ');   
        } elseif (preg_match('/409/', $httpCode)) {
            throw new DeployRNotAcceptableException('Returned by the Search API when an invalid format is specified in the request. ');   
        } elseif (preg_match('/500/', $httpCode)) {
            throw new DeployRInternalServerErrorException('Something is broken.  Please post to the group so the Revolution Computing support site so the team can investigate. ');   
        } elseif (preg_match('/502/', $httpCode)) {
            throw new DeployRBadGatewayException('DeployR server is down or being upgraded. ');   
        } elseif (preg_match('/503/', $httpCode)) {
            throw new DeployRServiceUnavailableException('The DeployR server is up, but overloaded with requests. Try again later.');   
        }
        
        return $content;
    }
}

?>