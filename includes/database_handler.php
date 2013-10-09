<?php 
	class dbhandler {
		const host = 'localhost';
		const dbname = 'ErrorTerminalDB';
		const userstabname = 'Users';
		const keystabname = 'Keys';
		const username = 'root';
		const password = 'rachit';

		private $conn = null;

		public function __construct() {
			try {
				$connectionString = sprintf("mysql:host=%s;dbname=%s", dbhandler::host, dbhandler::dbname);
				$this->conn = new PDO($connectionString, dbhandler::username, dbhandler::password);
			}
			catch(PDOException $e) {
				die("Could not connect: " . $e->getMessage());
			}
		}

		public function __destruct() {
			$this->conn = null;
		}

		public function create_new_user($name, $email) { 

			$insert_users = "INSERT INTO `" . dbhandler::userstabname . "`(`name`, `email`) VALUES ('"
			                . $name . "', '" . $email . "')" ;
			$get_last_row_query = "SELECT `id` FROM `" . dbhandler::userstabname . "` ORDER BY `id` DESC LIMIT 1" ;

			//Inserting a new row in Users table
			$this->conn->exec($insert_users) ;

			//Retreiving the id of the newly entered row
			$q = $this->conn->prepare($get_last_row_query) ;
			$q->setFetchMode(PDO::FETCH_ASSOC) ;
			$q->execute() ;
			$r = $q->fetch() ;
			$id = $r['id'] ;
			
			//Generating and inserting the keys in the Keys table

			$key = openssl_pkey_new(array(
				'private_key_bits' => 512,
				'private_key_type' => OPENSSL_KEYTYPE_RSA
				)) ;

			openssl_pkey_export($key, $private_key) ;

			$public_key = openssl_pkey_get_details($key);
			$public_key = $public_key["key"];
			
			$data = 'Error ocurred at line 64' ;

			openssl_public_encrypt($data, $encrypted, $public_key) ;
			openssl_private_decrypt($encrypted, $decrypted, $private_key) ;

			$insert_keys = "INSERT INTO `" . dbhandler::keystabname . "`(`user_id`, `public_key`, `private_key`) VALUES (" .
			                $id . ", '" . $public_key . "', '" . $private_key . "')" ;
			$this->conn->exec($insert_keys) ;

			echo "<p> Registration Successful </p>" ;
			echo "<p> Your Public Key is:<br/>" . $public_key . "<br/>Remember this always!</p>" ;
		}
	}
?>