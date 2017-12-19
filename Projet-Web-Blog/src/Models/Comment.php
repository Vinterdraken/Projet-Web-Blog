<?php
	
	class Comment{
		
		private $post_id
		private $author;
		private $date;
		private $content;

		public function __construct($post_id, $author, $date, $content){
			$this->post_id = $post_id;
			$this->author = $author;
			$this->date = $date;
			$this->content = $content;
		}

		public function getPostId(){ return $this->post_id }
		public function getAuthor(){ return $this->author }
		public function getDate(){ return $this->date }
		public function getContent(){ return $this->content }

	}

?>