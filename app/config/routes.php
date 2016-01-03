<?php

use Phalcon\Mvc\Router;

// Create the router
$router = new Router();
$router->setDefaultNamespace('App\Controllers');
$router->removeExtraSlashes(true);

$router->add(
		"/",
		array(
				'namespace'  => 'App\Controllers',
				"controller" => "index",
				"action"     => "index"
		)
);

$router->add(
		'/api/(v1|v2)/about',
		array(
				'namespace'  => 'App\Controllers\Api\V1',
				'controller' => 'about',
				'version'    => 1,
		)
);

$router->notFound(
		array(
				"controller" => "index",
				"action"     => "route404"
		)
);

$router->handle();