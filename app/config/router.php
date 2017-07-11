<?php

//$router = $di->getRouter();
use Phalcon\Mvc\Router;
$router = new Router();

$router->add('/mo/:telco', array(
    'controller' => 'mo',
    'action' => ':telco'
));

$router->add('/dr/:telco', array(
    'controller' => 'dr',
    'action' => ':telco'
));

$router->handle();
