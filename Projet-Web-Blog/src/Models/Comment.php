<?php
	
	namespace DUT\Models;
	
	class Comment{

	//Attributes

		private $Comment_id;
		private $Comment_post_id;
		private $Comment_author;
		private $Comment_date;
		private $Comment_content;
		private $Comment_verified;

	//Methods

		public function __construct($post_id, $author, $date, $content){
			$this->Comment_post_id = $post_id;
			$this->Comment_author = $author;
			$this->Comment_date = $date;
			$this->Comment_content = $content;
			$this->Comment_verified = "false";

		}

		public function getId() { return $this->Comment_id; }
		public function getPostId(){ return $this->Comment_post_id;	}
		public function getAuthor(){ return $this->Comment_author; }
		public function getDate(){ return $this->Comment_date; }
		public function getContent(){ return $this->Comment_content; }
		public function getVerification(){ return $this->Comment_verified; }
		public function setVerification($value){ $this->Comment_verified = $value; }

	}

?>