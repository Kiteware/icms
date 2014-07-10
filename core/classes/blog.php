<?php 
class Blog{
 	
	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}	

	public function update_post($postName, $postPreview, $postContent, $postID){

		$query = $this->db->prepare("UPDATE `posts` SET
								`post_name`	= ?,
								`post_preview`		= ?,
								`post_date`		= ?
								
								WHERE `post_id` 		= ? 
								");

		$query->bindValue(1, $postName);
		$query->bindValue(2, $postPreview);
		$query->bindValue(3, $postContent);
		$query->bindValue(4, $postID);
		
		try{
			$query->execute();
			return true;
		}catch(PDOException $e){
			die($e->getMessage());
			return false;
		}	
	}

	public function change_password($user_id, $password) {

		global $bcrypt;

		/* Two create a Hash you do */
		$password_hash = $bcrypt->genHash($password);

		$query = $this->db->prepare("UPDATE `users` SET `password` = ? WHERE `id` = ?");

		$query->bindValue(1, $password_hash);
		$query->bindValue(2, $user_id);				

		try{
			$query->execute();
			return true;
		} catch(PDOException $e){
			die($e->getMessage());
		}

	}

    public function fetch_info($what, $field, $value){

		$allowed = array('id', 'username', 'first_name', 'last_name', 'gender', 'bio', 'email'); // I have only added few, but you can add more. However do not add 'password' eventhough the parameters will only be given by you and not the user, in our system.
		if (!in_array($what, $allowed, true) || !in_array($field, $allowed, true)) {
		    throw new InvalidArgumentException;
		}else{
		
			$query = $this->db->prepare("SELECT $what FROM `users` WHERE $field = ?");

			$query->bindValue(1, $value);

			try{

				$query->execute();
				
			} catch(PDOException $e){

				die($e->getMessage());
			}

			return $query->fetchColumn();
		}
	}

	public function user_exists($username) {
	
		$query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `username`= ?");
		$query->bindValue(1, $username);
	
		try{

			$query->execute();
			$rows = $query->fetchColumn();

			if($rows == 1){
				return true;
			}else{
				return false;
			}

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}
	 
	public function email_exists($email) {

		$query = $this->db->prepare("SELECT COUNT(`id`) FROM `users` WHERE `email`= ?");
		$query->bindValue(1, $email);
	
		try{

			$query->execute();
			$rows = $query->fetchColumn();

			if($rows == 1){
				return true;
			}else{
				return false;
			}

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}

	public function newBlogPost($postName, $postPreview, $postContent){

		$time 		= time();
		$ip 		= $_SERVER['REMOTE_ADDR']; // getting the users IP address
		
		$query 	= $this->db->prepare('INSERT INTO posts (post_name, post_preview, post_content, post_date) VALUES (:postName, :postPreview, :postContent, now())');

		try{
			$query->execute(array(
             ':postName' => $postName,
             ':postPreview' => $postPreview,
             ':postContent' => $postContent));

		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}


	public function login($username, $password) {

		global $bcrypt;  // Again make get the bcrypt variable, which is defined in init.php, which is included in login.php where this function is called

		$query = $this->db->prepare("SELECT `password`, `id` FROM `users` WHERE `username` = ?");
		$query->bindValue(1, $username);

		try{
			
			$query->execute();
			$data 				= $query->fetch();
			$stored_password 	= $data['password']; // stored hashed password
			$id   				= $data['id']; // id of the user to be returned if the password is verified, below.
			
			if($bcrypt->verify($password, $stored_password) === true){ // using the verify method to compare the password with the stored hashed password.
				return $id;	// returning the user's id.
			}else{
				return false;	
			}

		}catch(PDOException $e){
			die($e->getMessage());
		}
	
	}

	public function get_post($id) {

		$query = $this->db->prepare("SELECT * FROM `posts` WHERE `post_id`= ? ");
		$query->bindValue(1, $id);

		try{

			$query->execute();

			return $query->fetch();

		} catch(PDOException $e){

			die($e->getMessage());
		}

	}
	  	  	 
	public function get_posts() {

		$query = $this->db->prepare("SELECT * FROM `posts` ORDER BY `post_date` DESC");
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

		return $query->fetchAll();

	}	
	public function get_posts_fetch() {

		$query = $this->db->prepare("SELECT * FROM `posts` ORDER BY `post_date` DESC");
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

		return $query->fetch();

	}
	public function delete_posts($postID) {
		$query = $this->db->prepare('DELETE FROM posts WHERE post_id = ?');
		$query->bindValue(1, $postID);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}

		return true;
		}
}
