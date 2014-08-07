<?php 
class Permissions{
 	
	private $db;

	public function __construct($database) {
	    $this->db = $database;
	}	
	
	public function update_permission($userID, $pageName){

		$query = $this->db->prepare("UPDATE `permissions` SET
								`pageName`	= ?
								
								WHERE `userID` 		= ? 
								");

		$query->bindValue(1, $pageName);
		$query->bindValue(2, $userID);
		
		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
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

		$allowed = array('id', 'username', 'first_name', 'last_name', 'gender', 'bio', 'email'); 
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



	public function has_access($userID, $pageName) {
	
		$query = $this->db->prepare("SELECT * FROM `permissions` WHERE `userID`= ? AND `pageName` = ?");
		$query->bindValue(1, $userID);
        $query->bindValue(2, $pageName);
	
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

	public function add_permission($userID, $pageName){
		$query 	= $this->db->prepare("INSERT INTO `permissions` (`userID`, `pageName`) VALUES (?, ?) ");

		$query->bindValue(1, $userID);
		$query->bindValue(2, $pageName);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}
	public function delete_permission($userID, $pageName){
		$query 	= $this->db->prepare("DELETE FROM `permissions` WHERE `userID` = ? AND `pageName` = ?");

		$query->bindValue(1, $userID);
		$query->bindValue(2, $pageName);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}


	public function get_permission($id) {

		$query = $this->db->prepare("SELECT * FROM `permissions` WHERE `userID`= ?");
		$query->bindValue(1, $id);

		try{

			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}
		return $query->fetch();
	}
	public function get_permissions() {

		$query = $this->db->prepare("SELECT * FROM `permissions` ORDER BY `userID` DESC");
        
		try{
			$query->execute();
		} catch(PDOException $e){
			die($e->getMessage());
		}
        return $query->fetchAll();
	}
}