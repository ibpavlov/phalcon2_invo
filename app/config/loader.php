<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
	array(
		APP_PATH . $config->application->controllersDir,
		APP_PATH . $config->application->pluginsDir,
		APP_PATH . $config->application->libraryDir,
		APP_PATH . $config->application->modelsDir,
		APP_PATH . $config->application->formsDir,
	)
)->registerNamespaces(array(
		'App\Models'      => APP_PATH . $config->application->modelsDir,
		'App\Controllers' => APP_PATH . $config->application->controllersDir,
		'App\Forms'       => APP_PATH . $config->application->formsDir,
		'App\Plugins'     => APP_PATH . $config->application->pluginsDir,
		'App\Libary'      => APP_PATH . $config->application->libraryDir,
))->register();

//require APP_PATH . "/vendor/autoload.php";