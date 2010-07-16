<?php

/**
 * @author Jorgen Horstink <mail@jorgenhorstink.nl>
 * @copyright Copyright (c) 2010, Jorgen Horstink
 * @licency Apache License, Version 2.0
 */

define('PHOENIX_DIRECTORY', 'classes/phoenix');
define('SESSION_DIRECTORY', 'classes/session');
define('COLLECTION_DIRECTORY', 'classes/collection');

set_include_path(get_include_path() . PATH_SEPARATOR . '/');

//require_once PHOENIX_DIRECTORY . '/PhoenixSession.php';
require_once PHOENIX_DIRECTORY . '/PhoenixClient.php';
require_once PHOENIX_DIRECTORY . '/PhoenixBasicAuthentication.php';

require_once SESSION_DIRECTORY . '/WebSession.php';
require_once SESSION_DIRECTORY . '/SessionFactory.php';

// Added to .gitignore, contains the USERNAME, PASSWORD and PHOENIX_URL constants
require_once 'conf/conf.php';


SessionFactory::setInstance(WebSession::getInstance());
$session = SessionFactory::getInstance();

// Just for testing.
unset($_SESSION);

try {
    $client = PhoenixClient::createHttpClient(PHOENIX_URL, $session);

    if (!$client->isAuthenticated()) {
        $client->login(
            new PhoenixBasicAuthentication(
                USERNAME,
                PASSWORD
            )
        );
    }
    
    
    var_dump($client->whoAmI());

    $pSession = $client->createSession('mySession');

    $scripts = $client->listScripts();

    foreach ($scripts as $name => $id)
    {
        echo $name . ', ' . $id . '<br>';
    }
 
    $phoenixExecution = $pSession->executeScript('jorgen', null, '
        {
            "bla": {
                "type" : "vector",
                "value" : [4,5,6]
            }
        }
    ', 'gemiddeld');
    
    $robjects = $phoenixExecution->getRObjects();
    echo $robjects['gemiddeld']['value'] . '<br>';
    
    $phoenixExecution = $pSession->executeCode('png("myPlot.png"); x <- runif(20); y <- runif( 20); plot(x,y); dev.off();', 'x, y', 'myPlot.png');
    echo '<img src="' . $phoenixExecution->getFiles()->get('myPlot.png') . '">';
    
} catch (PhoenixUnauthorizedException $e) {
    echo $e->getMessage();
} catch (PhoenixNotFoundException $e) {
    echo $e->getMessage();   
} catch (Exception $e) {
    echo $e->getMessage();   
}

?>