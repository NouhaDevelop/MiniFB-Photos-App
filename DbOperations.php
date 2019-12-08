<?php
	
	class DbOperations{

		private $con;

		function __construct(){

			require_once dirname(__FILE__).'/DbConnect.php';
			$db = new DbConnect();

			$this->con = $db->connect();
		}

		/*CRUD -> C -> CREATE*/
		public function createUser($email, $passwd){

			if($this->isUserExist($email)){
				return 0;
			}else{

			$password = md5($passwd);
			$stmt = $this->con->prepare("INSERT INTO `users` (`id`, `email`, `password`) VALUES (NULL, ?, ?);");
			$stmt->bind_param("ss", $email, $password);
			
			if($stmt->execute()){
				return true;
			}else{
				return false;
			}
		}
	}

		//Check for user email & password
		public function userLogin($email, $pass){
			$password = md5($pass);
			$stmt = $this->con->prepare("SELECT id FROM users WHERE email=? AND password=?");
			$stmt->bind_param("ss", $email, $password);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}

		//Get user details by posting user email

		public function getUserByEmail($email){
			$stmt = $this->con->prepare("SELECT * FROM users WHERE email=?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			return $stmt->get_result()->fetch_assoc();
		}

		//Check if user already exist

		public function isUserExist($email){

			$stmt = $this->con->prepare("SELECT id FROM users WHERE email=?");
			$stmt->bind_param("s", $email);
			$stmt->execute();
			$stmt->store_result();
			return $stmt->num_rows > 0;
		}


	}