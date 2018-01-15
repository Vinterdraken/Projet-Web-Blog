<?php

require __DIR__ . '/vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$app = new Silex\Application();
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

$app['connection'] = [
    'driver' => 'pdo_mysql',
    'host' => 'localhost',
    'user' => 'p1602259',
    'password' => '11602259',
    'dbname' => 'p1602259'
];

$app['doctrine_config'] = Setup::createYAMLMetadataConfiguration([__DIR__ . '/config'], true);

$app['em'] = function ($app) {
    return EntityManager::create($app['connection'], $app['doctrine_config']);
};

/*
$app->get('/persons', function () use ($app) {
    $entityManager = $app['em'];
    $repository = $entityManager->getRepository('DUT\\Models\\Person');
});*/


/**
 * ROUTES
 */

///// Display all Posts for User //////
$app->get('/', 'DUT\\Controllers\\PostController::displayAllPost')
    ->bind('home');

///// Display all Posts for Admin (with Add/Edit/Delete Post & moderate Comments) //////
$app->get('/admin', 'DUT\\Controllers\\PostController::displayAllPostAdmin')
    ->bind('adminHome');

///// Post management for Admin /////
$app->get('/create', 'DUT\\Controllers\\PostController::createPost')
    ->bind('create');
$app->post('/create', 'DUT\\Controllers\\PostController::createPost');

$app->get('/edit/{id}', 'DUT\\Controllers\\PostController::editPost')
    ->bind('edit');
$app->post('/edit/{id}', 'DUT\\Controllers\\PostController::createPost');

$app->get('/remove/{id}', 'DUT\\Controllers\\PostController::removePost')
    ->bind('remove');


///// Comment Moderation for Admin /////
$app->get('/moderateComment', 'DUT\\Controllers\\CommentController::displayCommentForModeration')
    ->bind('moderateComment');

$app->get('/deleteComment/{id}', 'DUT\\Controllers\\CommentController::deleteComment')
    ->bind('deleteComment');

$app->get('/approveComment/{id}', 'DUT\\Controllers\\CommentController::approveComment')
    ->bind('approveComment');


///// Display an article and add Comments for a simple user /////
$app->get('/post/{id}', 'DUT\\Controllers\\PostController::displayPostBy')
	->bind('post'); //user
$app->post('/post/{id}', 'DUT\\Controllers\\PostController::displayPostBy');

//une page admin avec auth qui fait acceder au Ajouter/Modif/Suppr & Moderer les nouveaux commentaire

//si temps gerer les users identifié 

$app['debug'] = true;
$app->run();
