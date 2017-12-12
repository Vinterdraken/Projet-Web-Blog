<?php

require __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

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

$app->get('/persons', function () use ($app) {
    $entityManager = $app['em'];
    $repository = $entityManager->getRepository('DUT\\Models\\Person');
});

/**
 * ROUTES
 */
$app->get('/', 'DUT\\Controllers\\ItemsController::listAction')
    ->bind('home');

$app->get('/create', 'DUT\\Controllers\\ItemsController::createAction');
$app->post('/create', 'DUT\\Controllers\\ItemsController::createAction');

$app->get('/remove/{index}', 'DUT\\Controllers\\ItemsController::deleteAction');

$app['debug'] = true;
$app->run();
