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
require_once PHOENIX_DIRECTORY . '/impl/PhoenixBasicAuthentication.php';

require_once SESSION_DIRECTORY . '/WebSession.php';
require_once SESSION_DIRECTORY . '/SessionFactory.php';

// Added to .gitignore, contains the USERNAME, PASSWORD and PHOENIX_URL constants
require_once 'conf/conf.php';

SessionFactory::setInstance(WebSession::getInstance());
$session = SessionFactory::getInstance();

// Just for testing.
$session->removeNamespace('phoenix');
$session->removeNamespace('phoenix_sessions');


try {
    // Injects a Session object to be able bind the current logged in user to the Client.
    $client = PhoenixClient::createHttpClient(PHOENIX_URL, $session);

    if (!$client->isAuthenticated()) {
        $client->login(
            new PhoenixBasicAuthentication(
                USERNAME,
                PASSWORD
            )
        );
    }

    
    // Creates a new session if mySession does not exist yet, otherwise use the
    // Session with name mySession.
    /*
    $pSession = $client->createSession('mySession');

    $phoenixExecution = $pSession->executeCode("print(rnorm(100))");

    $phoenixExecution = $pSession->executeScript('average', null, '
        {
            "numbers": {
                "type" : "vector",
                "value" : [4,5,6]
            }
        }
    ', 'average');
    
    $phoenixExecution = $pSession->executeCode(
        "myVector <- rnorm(100); png(\"myplot.png\"); plot(myVector); dev.off();",
        "myVector",
        "myplot.png"
    );

    var_dump($pSession->getHistory());
    */
    
    
    //var_dump($client->getObjectRepository()->listObjects());
    echo "<plaintext>";
    //var_dump($client->getProjectRepository()->getProjects());
    
    $pSession = $client->createSession('mySession');
    
    $phoenixExecution = $pSession->executeCode("n <- rnorm(100)");
    
    var_dump($pSession->getObjectManager()->get(new RObject("n")));

    var_dump($pSession->getObjectManager()->delete(new RObject("n")));

    var_dump($pSession->getObjectManager()->get(new RObject("n")));
    
    /*
    var_dump($client->getObjectRepository()->getObjects());
    die();
    
    $pSession = $client->loadProject(new PhoenixProject("PROJ-2679b218-b014-469f-a8ec-b3feead67922"));
    
    $phoenixExecution = $pSession->executeCode("print(rnorm(100))");
    
    var_dump($pSession->getHistory());
    */
} catch (PhoenixUnauthorizedException $e) {
    echo $e->getMessage();
} catch (PhoenixNotFoundException $e) {
    echo $e->getMessage();   
} catch (Exception $e) {
    echo $e->getMessage();   
}

?>