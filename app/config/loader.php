<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir,
        $config->application->pluginsDir,
        $config->application->providerDir,
        $config->application->helperDir,
        $config->application->servicesDir,
    ]
);

// Register some namespaces
$loader->registerNamespaces(
    [
        'Timetracker\Models'      => APP_PATH .'/models',
        'App\Forms'               => APP_PATH .'/forms/',
        'Timetracker\Security'    => APP_PATH .'/plugins',
        'Timetracker\Providers'   => APP_PATH .'/providers',
        'Timetracker\Controllers' => APP_PATH . '/controllers',
        'Dates\DTO'               => APP_PATH . '/DTO',
        'Timetracker\Services'    => APP_PATH . '/services',
        'Timetracker\Helper'      => APP_PATH .  '/helpers'
    ]
);

$loader->register();