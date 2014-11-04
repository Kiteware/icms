<?php 
class Permissions{
 	
	private $db;

	public function __construct($database) {
	    $this->db = $database;
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

	public function has_access($userID, $pageName, $usergroupID) {
	
		$query = $this->db->prepare("SELECT * FROM `permissions` WHERE `pageName` = ? AND (`userID`= ?  OR `usergroupID` = ?)");
        $query->bindValue(1, $pageName);
		$query->bindValue(2, $userID);
		$query->bindValue(3, $usergroupID);
	
		try{

			$query->execute();
			$rows = $query->fetch(PDO::FETCH_ASSOC);

            if(!$rows) { 
                return false;
            } else {
                return true;
            }

		} catch (PDOException $e){
			die($e->getMessage());
		}

	}
    public function user_access($userID, $pageName) {
  		if(!empty($userID)){
            $query = $this->db->prepare("SELECT * FROM `permissions` WHERE `pageName` = ? AND `usergroupID` = 'user'");
            $query->bindValue(1, $pageName);
            
      		try{
    
    			$query->execute();
    			$rows = $query->fetch(PDO::FETCH_ASSOC);
    
                if(!$rows) { 
                    return false;
                } else {
                    return true;
                }
    
    		} catch (PDOException $e){
    			die($e->getMessage());
    		}
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
    
	public function add_usergroup($usergroupID, $pageName){
		$query 	= $this->db->prepare("INSERT INTO `permissions` (`usergroupID`, `pageName`) VALUES (?, ?) ");

		$query->bindValue(1, $usergroupID);
		$query->bindValue(2, $pageName);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}
    
	public function delete_usergroup($usergroupID, $pageName){
		$query 	= $this->db->prepare("DELETE FROM `permissions` WHERE `usergroupID` = ? AND `pageName` = ?");

		$query->bindValue(1, $usergroupID);
		$query->bindValue(2, $pageName);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}
    
	public function get_permission($id) {

		$query = $this->db->prepare("SELECT * FROM `permissions` WHERE `userID`= ? or `usergroupID` = ?");
		$query->bindValue(1, $id);
        $query->bindValue(2, $id);

		try{

			$query->execute();

		} catch(PDOException $e){

			die($e->getMessage());
		}
		return $query->fetch();
	}
	public function get_permissions() {

		$query = $this->db->prepare("SELECT * FROM `permissions` WHERE `userID` IS NOT NULL ORDER BY `userID` DESC");
        
		try{
			$query->execute();
		} catch(PDOException $e){
			die($e->getMessage());
		}
        return $query->fetchAll();
	}
	public function get_usergroups() {

		$query = $this->db->prepare("SELECT * FROM `permissions` WHERE `usergroupID` IS NOT NULL ORDER BY `usergroupID` ASC");
        
		try{
			$query->execute();
		} catch(PDOException $e){
			die($e->getMessage());
		}
        return $query->fetchAll();
	}
	public function delete_all_page_permissions($pageName){
		$query 	= $this->db->prepare("DELETE FROM `permissions` WHERE  `pageName` = ?");

		$query->bindValue(1, $pageName);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}
	public function delete_all_user_permissions($userID){
		$query 	= $this->db->prepare("DELETE FROM `permissions` WHERE  `userID` = ?");

		$query->bindValue(1, $userID);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}
	public function delete_all_usergroup_permissions($usergroupID){
		$query 	= $this->db->prepare("DELETE FROM `permissions` WHERE  `usergroupID` = ?");

		$query->bindValue(1, $usergroupID);

		try{
			$query->execute();
		}catch(PDOException $e){
			die($e->getMessage());
		}	
	}
    
}