<?php

	namespace DUT\Models;

	class Post{

		private $Post_id;
		private $Post_title;
		private $Post_date;
		private $Post_content;

		public function __construct($title, $date, $content){
			//$this->Post_id = $id;		
			$this->Post_title = $title;
			$this->Post_date = $date;
			$this->Post_content = $content;
		}

		public function getId(){ return $this->Post_id; }
		public function getTitle(){ return $this->Post_title; }
		public function getDate() { return $this->Post_date; }
		public function getContent() { return $this->Post_content; }
	
		public function setTitle($title){
			$this->title = $title;
		}
		public function setDate($date){
			$this->date = $date;
		}
		public function setContent($content){
			$this->content = $content;
		}

	}
	
?>