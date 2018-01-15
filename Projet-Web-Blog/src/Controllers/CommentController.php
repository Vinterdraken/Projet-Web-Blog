<?php

namespace DUT\Controllers;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Silex\Application;
use DUT\Models\Comment;

class CommentController{

	private $entityManager;

	/**
	* Cette fonction affiche tout les commentaires d'un article
	* $postId l'id de l'article concerné
	*/
	public function displayAllCommentBy($postId, Request $request, Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Comment');
 		
		$comments = $repository->findAll();

		$html = "<br>";

		foreach ($comments as $comment) {
			
			if($comment->getPostId() == $postId && $comment->getVerification() == "true"){
				
				$html .= '<h4> Commenté le '. $comment->getDate() .' par '. $comment->getAuthor() .'</h4>';
				$html .= '<p>'. $comment->getContent() .'</p>';
				
			}
		}

		$author = $request->get('author', null);	
		$content = $request->get('content', null);

		$postByUrl = $app['url_generator']->generate('post', ['id' => $postId] );

 		if (!is_null($author) && !is_null($content)) {
			$this->createComment($request, $app, $postId, $author, $content, $postByUrl);
		}

		$html .= '<br><h2>Ajouter un Commentaire</h2><form action="' . $postByUrl . '" method="post">';
        $html .= '<label for="input">Nom/Pseudo</label><textarea id="input_title" name="author"></textarea><br>';
        $html .= '<label for="input">Commentaire</label><textarea id="input_content" name="content"></textarea><br>';
        $html .= '<button>Valider</button></form>';

		
		return $html;
	}

	/**
	* Cette fonction créé un commentaire dans la base de données
	* $postId, $author, $content sont des variables qui servent à créer le commentaire
	* $postByUrl permet la redirection sur la page d'affichage de l'article commenter (cf. fonction displayPostBy)
	*/
	public function createComment(Request $request, Application $app, $postId, $author, $content, $postByUrl){

		$entityManager = $app['em'];

		$html = "";

		$date = date("Y-m-d"); //Date actuelle du serveur	
        $comment = new Comment($postId, $author, $date, $content);
        
        $entityManager->persist($comment);
        $entityManager->flush();
        
        return $app->redirect($postByUrl);         
	}
	
	/**
	* Cette fonction affiche tout les commentaires non vérifés et permet à l'admin de supprimer ou approuver le commentaire
	*/
	public function displayCommentForModeration(Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Comment');

		$comments = $repository->findAll();
		
		$html = "<br>";

		$returnHomeUrl = $app['url_generator']->generate('adminHome');

		foreach ($comments as $comment) {
			
			if($comment->getVerification() == "false"){

				$deleteCommentUrl = $app['url_generator']->generate('deleteComment', ['id' => $comment->getId()] );
				$approveCommentUrl = $app['url_generator']->generate('approveComment', ['id' => $comment->getId()] );
				
				
				$html .= '<p> Commenté le <b>'. $comment->getDate() .'</b> par <b>'. $comment->getAuthor() .'</b> concernant l\'article n° <b>'. $comment->getPostId() .'</b></p>';
				$html .= '<label for="input">Commentaire: </label><textarea id="input_content" name="content">'. $comment->getContent() .'</textarea><br>';
				//$html .= '<p>'. $comment->getContent() .'</p>';

				$html .= '<a href="'. $approveCommentUrl .'">Approuver</a><br>';
				$html .= '<a href="'. $deleteCommentUrl .'">Supprimer</a><br>';		
				
			}
		}

		$html .= '<br><a href="'. $returnHomeUrl .'">Retour à la page d\'accueil</a>';

		return new Response($html);
	}

	/**
	* Cette fonction permet d'approuver un commentaire 
	* $id l'id du commentaire
	*/
	public function approveComment($id, Application $app){

		$entityManager = $app['em'];
		$comment = $entityManager->find('DUT\\Models\\Comment', $id);

		$comment->setVerification('true');

		$url = $app['url_generator']->generate('moderateComment');

        if (!is_null($comment)) {
            $entityManager->persist($comment);
            $entityManager->flush();
        }

        return $app->redirect($url);
	}

	/**
	* Cette fonction permet de supprimer un commentaire 
	* $id l'id du commentaire
	*/
	public function deleteComment($id, Application $app){

		$entityManager = $app['em'];
		$comment = $entityManager->find('DUT\\Models\\Comment', $id);

		$url = $app['url_generator']->generate('moderateComment');

        if (!is_null($comment)) {
            $entityManager->remove($comment);
            $entityManager->flush();
        }

        return $app->redirect($url);
	}

	/**
	* Cette fonction permet de supprimer les commentaires d'un article, notament quand l'article est supprimer
	* $postId l'id de l'article supprimer
	*/
	public function deleteAGroupOfComment($postId, Application $app){

		$entityManager = $app['em'];
		$repository = $entityManager->getRepository('DUT\\Models\\Comment');

		$groupOfComments = $repository->findAll();		

		$url = $app['url_generator']->generate('adminHome');

		foreach ($groupOfComments as $comment) {

			if ($comment->getPostId() == $postId) {
		            $entityManager->remove($comment);
		            $entityManager->flush();
			}
		}

        return $app->redirect($url);
	}

}

?>