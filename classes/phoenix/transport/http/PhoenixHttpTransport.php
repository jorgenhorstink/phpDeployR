<?php

require_once PHOENIX_DIRECTORY . '/exception/PhoenixBadGatewayException.php';
require_once PHOENIX_DIRECTORY . '/exception/PhoenixBadRequestException.php';
require_once PHOENIX_DIRECTORY . '/exception/PhoenixForbiddenException.php';
require_once PHOENIX_DIRECTORY . '/exception/PhoenixInternalServerErrorException.php';
require_once PHOENIX_DIRECTORY . '/exception/PhoenixNotAcceptableException.php';
require_once PHOENIX_DIRECTORY . '/exception/PhoenixNotFoundException.php';
require_once PHOENIX_DIRECTORY . '/exception/PhoenixServiceUnavailableException.php';
require_once PHOENIX_DIRECTORY . '/exception/PhoenixUnauthorizedException.php';

class PhoenixHttpTransport
{
    protected static $instance;
    
    protected function __construct() {}
    
    public static function getInstance()
    {
        if (self::$instance == null) {
            self::$instance = new PhoenixHttpTransport();
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
            throw new PhoenixBadRequestException('The request was invalid.  An accompanying error message will explain why. ');   
        } elseif (preg_match('/401/', $httpCode)) {
            throw new PhoenixUnauthorizedException('Authentication credentials were missing or incorrect. ');   
        } elseif (preg_match('/403/', $httpCode)) {
            throw new PhoenixForbiddenException('The request is understood, but it has been refused.  ');   
        } elseif (preg_match('/404/', $httpCode)) {
            throw new PhoenixNotFoundException('The URI requested is invalid or the resource requested, such as a user, does not exists. ');   
        } elseif (preg_match('/409/', $httpCode)) {
            throw new PhoenixNotAcceptableException('Returned by the Search API when an invalid format is specified in the request. ');   
        } elseif (preg_match('/500/', $httpCode)) {
            throw new PhoenixInternalServerErrorException('Something is broken.  Please post to the group so the Revolution Computing support site so the team can investigate. ');   
        } elseif (preg_match('/502/', $httpCode)) {
            throw new PhoenixBadGatewayException('Phoenix server is down or being upgraded. ');   
        } elseif (preg_match('/503/', $httpCode)) {
            throw new PhoenixServiceUnavailableException('The Phoenix server is up, but overloaded with requests. Try again later.');   
        }
        
        return $content;
    }
}

?>