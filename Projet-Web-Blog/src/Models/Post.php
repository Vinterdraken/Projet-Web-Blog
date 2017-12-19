<?php

	class Post{

		private $author;
		private $date;
		private $content;

		public function __construct($author, $date, $content){
			$this->author = $author;
			$this->date = $date;
			$this->content = $content;
		}

		public function getAuthor(){ return $this->author }
		public function getDate(){ return $this->date }
		public function getContent(){ return $this->content }

	}
	
?>