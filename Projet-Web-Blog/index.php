<?php

require __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
// use DUT\Models\Items;


$app = new Silex\Application();
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['connection'] = [
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'dbname' => 'dut'
];

$app['doctrine_config'] = Setup::createYAMLMetadataConfiguration([__DIR__ . '/config'], true);

$app['em'] = function ($app) {
    return EntityManager::create($app['connection'], $app['doctrine_config']);
};

//web/index.php
$app->register(new Silex\Provider\TwigServiceProvider(), [
    'twig.path' => __DIR__.'/View',
]);


$app->get('/items', function () use ($app) {
  return;
});

/**
 * ROUTES
 */

$app->get('/admin', 'DUT\\Controllers\\ItemsController::adminAction')
    ->bind('home');


$app->get('/main', 'DUT\\Controllers\\ItemsController::adminAction')
    ->bind('home');

$app->post('/create', 'DUT\\Controllers\\ItemsController::insertAction')
    ->bind('create');


$app->get('/remove/{index}', 'DUT\\Controllers\\ItemsController::deleteAction');


//web/index.php


$app['debug'] = true;
$app->run();
