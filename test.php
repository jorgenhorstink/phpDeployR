<?php

/**
 * @author Jorgen Horstink <mail@jorgenhorstink.nl>
 * @copyright Copyright (c) 2010, Jorgen Horstink
 * @licency Apache License, Version 2.0
 */

define('DEPLOYR_DIRECTORY', 'classes/deployR');
define('SESSION_DIRECTORY', 'classes/session');
define('COLLECTION_DIRECTORY', 'classes/collection');

set_include_path(get_include_path() . PATH_SEPARATOR . '/');

//require_once DEPLOYR_DIRECTORY . '/DeployRSession.php';
require_once DEPLOYR_DIRECTORY . '/DeployRClient.php';
require_once DEPLOYR_DIRECTORY . '/impl/DeployRBasicAuthentication.php';

require_once SESSION_DIRECTORY . '/WebSession.php';
require_once SESSION_DIRECTORY . '/SessionFactory.php';

// Added to .gitignore, contains the USERNAME, PASSWORD and DEPLOYR_URL constants
require_once 'conf/conf.php';

SessionFactory::setInstance(WebSession::getInstance());
$session = SessionFactory::getInstance();

// Just for testing.
$session->removeNamespace('deployr');
$session->removeNamespace('deployr_sessions');


try {
    // Injects a Session object to be able bind the current logged in user to the Client.
    $client = DeployRClient::createHttpClient(DEPLOYR_URL, $session);

    $client->open();
    
    // Login checks if the user is already logged in. If so, the injected session object has a valid cookieId
    $client->login(
        new DeployRBasicAuthentication(
            USERNAME,
            PASSWORD
        )
    );

    // Use a named session. If the session already exists, it reuses the old named session. Otherwise it creates a new session.
    $pSession = $client->createSession('mySession');
    
    $deployRExecution = $pSession->executeCode(
        "myVector <- rnorm(100); png(\"myplot.png\"); plot(myVector); dev.off();",
        "myVector",
        "myplot.png"
    );
    
    $file = $deployRExecution->getFiles()->get("myplot.png");
    
    echo "<img src='" . $file . "' />";
    
} catch (DeployRUnauthorizedException $e) {
    echo $e->getMessage();
} catch (DeployRNotFoundException $e) {
    echo $e->getMessage();   
} catch (Exception $e) {
    echo $e->getMessage();   
}

?>