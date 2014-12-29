<?php

/*
 * SOLID Framwork 2014
 */
$start = microtime(TRUE);

//------------------------------Main Application Definitions-------------------------//
define('APPNAME', '');
define('DOCUMENT_SEPARATOR', '/');
define('ROOT', filter_input(INPUT_SERVER, 'DOCUMENT_ROOT'));
//-----------------------------------------------------------------------------------//

//-----------------------Calling the autoload file-----------------------------------//

require_once ROOT . DOCUMENT_SEPARATOR . APPNAME .'/vendor/autoload.php';
//-----------------------------------------------------------------------------------//

//-----------------------Setting up the error hanlder--------------------------------//
$errorHandler = new \Whoops\Handler\PrettyPageHandler;

$errorHandler->setEditor("sublime");

$errorHandler->setPageTitle("Well, having an error is better than nothing");

$whoops = new \Whoops\Run;

$whoops->allowQuit(false);

$whoops->pushHandler($errorHandler);

$whoops->register();
//-----------------------------------------------------------------------------------//




//----------------------------Setting up the configuration handler-------------------//
$ConfigurationHandler = new \Facade\ConfigurationHandler();

$ConfigurationHandler->loadSystemConfiguration();
//-----------------------------------------------------------------------------------//


//-----------------------------------------------------------------------------------//
$HooksHandler = new \Facade\HookHandler($ConfigurationHandler);

$HooksHandler->init();
//-----------------------------------------------------------------------------------//


//----------------------------Setting up the Session Handler-------------------------//

$SessionHandler = new \Facade\SessionHandler($ConfigurationHandler);

//-----------------------------------------------------------------------------------//

//----------------------------Setting up the Request Handler-------------------------//
$RequestHandler = new \Facade\RequestHandler($HooksHandler);

$RoutesConfiguration = $ConfigurationHandler->getConfigurationNode('Routes');

require_once $RoutesConfiguration->get('path');

$RequestHandler->listen();
//-----------------------------------------------------------------------------------//

//testing session
