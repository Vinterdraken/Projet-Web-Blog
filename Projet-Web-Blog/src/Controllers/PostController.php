<?php

namespace DUT\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use DUT\Models\Post;

class PostController{

	private $entityManager;
	private $commentController;

	/**
	* Cette fonction affiche tout les articles présent dans la base de données
	*/
	public function displayAllPost(Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Post');
		$comment = $entityManager->getRepository('DUT\\Models\\Comment');

		$posts = $repository->findAll();

        return new Response($app['twig']->render('Main.twig', ['articles' => $posts]));
	}


	/**
	* Cette fonction est la même que la précédente avec quelques ajout pour l'ADMIN
	* Ajouter/Editer/Supprimer un article & moderer les commentaires
	*/
	public function displayAllPostAdmin(Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Post');
		$posts = $repository->findAll();

        return new Response($app['twig']->render('Admin.twig', ['articles' => $posts]));
	}

	/**
	* Cette fonction affiche un article avec ses commentaires grâce à l'$id de l'article
	* Elle utilise un CommentCotroller pour afficher les commentaires (cf. fonction displayAllCommentBy(); )
	*/
	public function displayPostBy($id, Request $request, Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Post');

		$post = $repository->find($id);

		$commentController = new CommentController();
		$comment = $commentController->displayAllCommentBy($id, $post, $request, $app);

        return $comment;
	}

	/**
	* Cette fonction affiche un formulaire de création d'articles
	* Une fois le bouton "Valider" cliquer, le controlleur ajoute l'article dans la BD (si les 2 champs sont remplient)
	*/
	public function createPost(Request $request, Application $app){

		$entityManager = $app['em'];

		$title = $request->get('title', null);
		$date = date("Y-m-d"); //Date actuelle du serveur
		$content = $request->get('content', null);

        $url = $app['url_generator']->generate('adminHome');
        $createUrl = $app['url_generator']->generate('create');

        if (!is_null($title) && !is_null($content)) {

            $post = new Post(null, $title, $date, $content);
            
            $entityManager->persist($post);
            $entityManager->flush();

            return $app->redirect($url);
        }
        /*
        $html = '<h2>Ajouter un article</h2><form action="' . $createUrl . '" method="post">';
        $html .= '<label for="input">Titre de l\'article</label><textarea id="input_title" name="title"></textarea><br>';
        $html .= '<label for="input">Contenu de l\'article</label><textarea id="input_content" name="content"></textarea><br>';
        $html .= '<button>Valider</button></form>';
        */
        return new Response($app['twig']->render('NewArticle.twig', ['editionUrl' => $createUrl ]));
	}

	/**
	* Cette fonction supprime un article de la BD par son $id
	*/
	public function removePost($id, Application $app){

		$entityManager = $app['em'];
		$post = $entityManager->find('DUT\\Models\\Post', $id);

		$url = $app['url_generator']->generate('adminHome');

        if (!is_null($post)) {

            $entityManager->remove($post);
            $entityManager->flush();

            $commentController = new CommentController();
            $commentController->deleteAGroupOfComment($id, $app);
        }

        return $app->redirect($url);
	}

	/**
	* Cette fonction permet l'édition d'un article de la BD par son $id
	*/
	public function editPost($id, Request $request, Application $app){

		$entityManager = $app['em'];
		$post = $entityManager->find('DUT\\Models\\Post', $id);

		$adminHomeUrl = $app['url_generator']->generate('adminHome');
		$editionUrl = $app['url_generator']->generate('edit', ['id' => $post->getId()] );

		$oldId = $post->getId();
		$newTitle = $request->get('title', null);
		$newDate = date("Y-m-d"); //Date actuelle du serveur
		$newContent = $request->get('content', null);		

		if (!is_null($newTitle) && !is_null($newContent)) {			

			$post->setTitle($newTitle);
			$post->setDate($newDate);
			$post->setContent($newContent);

            $entityManager->persist($post);
            $entityManager->flush();

            return $app->redirect($adminHomeUrl);
        }
/*
		$html = '<h2>Modification de l\'article #'. $id .'</h2>';

		$html .= '<form action="' . $editionUrl . '" method="post">';
        $html .= '<label for="input">Titre de l\'article</label><textarea id="input_title" name="title">'. $post->getTitle() .'</textarea><br>';
        $html .= '<label for="input">Contenu de l\'article</label><textarea id="input_content" name="content">'. $post->getContent() .'</textarea><br>';
        $html .= '<button>Valider</button></form>';
*/
        return new Response($app['twig']->render('Form.twig', ['editionUrl' => $editionUrl , 'title' => $post->getTitle(),
            'content' => $post->getContent(), 'id' => $id]));
	}


}


?>