<?php

require_once PHOENIX_DIRECTORY . '/exception/PhoenixUnauthorizedException.php';
require_once PHOENIX_DIRECTORY . '/PhoenixIdentity.php';

class PhoenixClientXmlResponseParser extends PhoenixClientResponseParser
{
    public function parseListScripts($xml) {
        $document = new DOMDocument();
        $document->loadXML($xml);

        $xpath = new DOMXPath($document);
        
        $response = $xpath->query('/phoenix/response')->item(0);
        $pobjects = $xpath->query('/phoenix/response/pobjects/pobject');
        
        $list = new ArrayMap();
        foreach ($pobjects as $pobject)
        {
            $list->put(
                $pobject->attributes->getNamedItem('name')->nodeValue,
                $pobject->attributes->getNamedItem('value')->nodeValue
            );
        }
        
        if ($response->attributes->getNamedItem('success')->nodeValue == 'true') {
            return $list;
        } else {
            throw new PhoenixUnauthorizedException('list scripts - failed...');   
        }
    }
    
    
    public function parseLogin($xml) {

        $document = new DOMDocument();
        $document->loadXML($xml);
        
        $xpath = new DOMXPath($document);
        
        $response = $xpath->query('/phoenix/response')->item(0);

        if ($response->attributes->getNamedItem('success')->nodeValue == 'true') {
            return $response->attributes->getNamedItem('cookie')->nodeValue;
        } else {
            throw new PhoenixUnauthorizedException('login - failed...');   
        }
    }
    
    public function parseLogout($xml) {
        $document = new DOMDocument();
        $document->loadXML($xml);
        
        $xpath = new DOMXPath($document);
        
        $response = $xpath->query('/phoenix/response')->item(0);

        if ($response->attributes->getNamedItem('success')->nodeValue != 'true') {
            throw new PhoenixUnauthorizedException('logout - failed...');   
        }
    }
    
    public function parseWhoami($xml) {
        $document = new DOMDocument();
        $document->loadXML($xml);
        
        $xpath = new DOMXPath($document);
        
        $response = $xpath->query('/phoenix/response')->item(0);

        if ($response->attributes->getNamedItem('success')->nodeValue != 'true') {
            throw new PhoenixUnauthorizedException('whoami - failed...');   
        }
        
        $identity = $xpath->query('identity', $response)->item(0);
        
        $return = new PhoenixIdentity(
            $identity->attributes->getNamedItem('username')->nodeValue,
            $identity->attributes->getNamedItem('displayname')->nodeValue
        );       
        
        return $return;
    }
    
    public function parseCreateSession($xml)
    {
        $document = new DOMDocument();
        $document->loadXML($xml);
        
        $xpath = new DOMXPath($document);
        
        $response = $xpath->query('/phoenix/response')->item(0);

        if ($response->attributes->getNamedItem('success')->nodeValue == 'true') {
            return $response->attributes->getNamedItem('session')->nodeValue;
        } else {
            throw new PhoenixResponseException('session - create - success attribute is not true');   
        } 
    }
}

?>