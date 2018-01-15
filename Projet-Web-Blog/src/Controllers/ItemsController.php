<?php

namespace DUT\Controllers;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;

class ItemsController {


    public function __construct() {

    }

    public function listAction(Application $app) {


        /** @var EntityManager $entityManager */
        //$entityManager = $app['em'];

        //$repository = $entityManager->getRepository('DUT\\Models\\Items');
        //$table = $repository->findAll();

        return new Response($app['twig']->render('Main.twig'));
    }

    public function adminAction(Application $app) {


        /** @var EntityManager $entityManager */
        //$entityManager = $app['em'];

        //$repository = $entityManager->getRepository('DUT\\Models\\Items');
        //$table = $repository->findAll();

        return new Response($app['twig']->render('Admin.twig'));
    }

    public function deleteAction($id ,Application $app) {

        /** @var EntityManager $entityManager */
        //$personToRemove = $entityManager->find('DUT\\Models\\Person,2 ,bonsoir',$id);

        //$entityManager->remove($personToRemove);
        $entityManager->flush();

        $url = $app['url_generator']->generate('home');

        return $app->redirect($url);
    }
   /* public function insertAction(Request $request, Application $app){



        $entityManager = $app['em'];
        $name = $request->get('name', null);
        $itemName = new Items($name);

        $entityManager->persist($itemName);
        $entityManager->flush();

        $url = $app['url_generator']->generate('home');
        return $app->redirect($url);
    }*/
}
