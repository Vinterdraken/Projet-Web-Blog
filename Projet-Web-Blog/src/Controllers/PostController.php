<?php

namespace DUT\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use DUT\Controllers\CommentController;
use DUT\Models\Post;

class PostController{

	private $entityManager;
	private $comments;

	public function displayAllPost(Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Post');

		$addPostUrl = $app['url_generator']->generate('create');				

		$html = '<h2>Affichage de tout les articles</h2>';

		$posts = $repository->findAll();

		foreach ($posts as $post ) {

			$editPostUrl = $app['url_generator']->generate('edit', ['id' => $post->getId()] );
			$removePostUrl = $app['url_generator']->generate('remove', ['id' => $post->getId()] );
			$displayPostByUrl = $app['url_generator']->generate('post', ['id' => $post->getId()] );

			$html .= '<h3><a href="'. $displayPostByUrl .'">'.  $post->getTitle() .'</a></h3>';
			$html .= '<h5> Dernière modification le '. $post->getDate() .'</h5>';
			$html .= '<p>'. $post->getContent() .'</p>';

		}

		return new Response($html);
	}

	public function displayAllPostAdmin(Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Post');

		$addPostUrl = $app['url_generator']->generate('create');				
		$moderateCommentUrl = $app['url_generator']->generate('moderateComment');

		$html = '<h2>Affichage de tout les articles</h2>';

		$html .= '<br><a href="'. $moderateCommentUrl .'"> Moderer les commentaires </a>';
		$html .= '<br><br><a href="' . $addPostUrl . '">Ajouter un article</a>';

		$posts = $repository->findAll();

		foreach ($posts as $post ) {

			$editPostUrl = $app['url_generator']->generate('edit', ['id' => $post->getId()] );
			$removePostUrl = $app['url_generator']->generate('remove', ['id' => $post->getId()] );
			$displayPostByUrl = $app['url_generator']->generate('post', ['id' => $post->getId()] );

			$html .= '<h3><a href="'. $displayPostByUrl .'">'.  $post->getTitle() .'</a></h3>';
			$html .= '<h5> Dernière modification le '. $post->getDate() .'</h5>';
			$html .= '<p>'. $post->getContent() .'</p>';

			$html .= '<a href="' . $removePostUrl . '">Supprimer</a><br>';
			$html .= '<a href="' . $editPostUrl . '">Editer</a>';

		}

		return new Response($html);
	}

	public function displayPostBy($id, Request $request, Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Post');

		$post = $repository->find($id);
		
		$html = '<h2>Affichage de l\'article #'. $id .'</h2>';
		$html .= '<h3>#'. $post->getId() .' '. $post->getTitle() .'</h3>';
		$html .= '<h5>Dernière modification le '. $post->getDate() .'</h5>';
		$html .= '<p>'. $post->getContent() .'</p>';

		$comments = new CommentController();
		$html .= $comments->displayAllCommentBy($id, $request, $app);

		return new Response($html);
	}

	public function createPost(Request $request, Application $app){

		$entityManager = $app['em'];

		$title = $request->get('title', null);
		$date = date("Y-m-d"); //Date actuelle du serveur
		$content = $request->get('content', null);

        $url = $app['url_generator']->generate('adminHome');
        $createUrl = $app['url_generator']->generate('create');

        if (!is_null($title) && !is_null($content)) {

            $post = new Post($title, $date, $content);
            
            $entityManager->persist($post);
            $entityManager->flush();

            return $app->redirect($url);
        }

        $html = '<h2>Ajouter un article</h2><form action="' . $createUrl . '" method="post">';
        $html .= '<label for="input">Titre de l\'article</label><textarea id="input_title" name="title"></textarea><br>';
        $html .= '<label for="input">Contenu de l\'article</label><textarea id="input_content" name="content"></textarea><br>';
        $html .= '<button>Valider</button></form>';

        return new Response($html);
	}

	public function removePost($id, Application $app){

		$entityManager = $app['em'];
		$post = $entityManager->find('DUT\\Models\\Post', $id);

		$url = $app['url_generator']->generate('adminHome');

        if (!is_null($post)) {
            $entityManager->remove($post);
            $entityManager->flush();
        }

        return $app->redirect($url);
	}

	public function editPost($id, Request $request, Application $app){

		$entityManager = $app['em'];
		$post = $entityManager->find('DUT\\Models\\Post', $id);

		$url = $app['url_generator']->generate('post', ['id' => $post->getId()] );
		$editionUrl = $app['url_generator']->generate('edit', ['id' => $post->getId()] );

		$oldId = $post->getId();
		$newTitle = $request->get('title', null);
		$newDate = date("Y-m-d"); //Date actuelle du serveur
		$newContent = $request->get('content', null);

		$this->removePost($oldId, $app);

		if (!is_null($newTitle) && !is_null($newContent)) {

            $entityManager->persist(new Post($oldId, $newTitle, $newDate, $newContent));
            $entityManager->flush();

            return $app->redirect($url);
        }

		$html = '<h2>Modification de l\'article #'. $id .'</h2>';

		$html .= '<form action="' . $editionUrl . '" method="post">';
        $html .= '<label for="input">Titre de l\'article</label><textarea id="input_title" name="title">'. $post->getTitle() .'</textarea><br>';
        $html .= '<label for="input">Contenu de l\'article</label><textarea id="input_content" name="content">'. $post->getContent() .'</textarea><br>';
        $html .= '<button>Valider</button></form>';

        return new Response($html);
	}
}


?>