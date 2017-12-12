<?php

namespace DUT\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use DUT\Services\SessionStorage;

class ItemsController {

    protected $storage;

    public function __construct() {
        $this->storage = new SessionStorage();
    }

    public function listAction() {
        $storage = new SessionStorage();
        $html = '<h2>Home</h2>';
        $html .= '<a href="create">Ajouter</a>';
        $html .= '<ul>';

        foreach ($storage->getElements() as $index => $value) {
            $html .= '<li>' . $value . ' <a href="remove/' . $index . '">Suppr.</a></li>';
        }

        $html .= '</li>';

        return new Response($html);
    }

    public function createAction(Request $request, Application $app) {
        $name = $request->get('name', null);
        $url = $app['url_generator']->generate('home');

        if (!is_null($name)) {
            $this->storage->addElement($name);

            return $app->redirect($url);
        }

        $html = '<h2>Ajouter</h2><form action="create" method="post">';
        $html .= '<label for="input">Nom</label><input id="input" type="text" name="name">';
        $html .= '<button>Valider</button></form>';

        return new Response($html);
    }

    public function deleteAction($index, Application $app) {
        $this->storage->removeElement($index);

        $url = $app['url_generator']->generate('home');

        return $app->redirect($url);
    }
}
