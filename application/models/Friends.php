<?php 

class Application_Model_Friends extends Site_Db {	
	

	public function findFriend($name, $user_id) {
		$sql = $this->db->select()
						->distinct()
						->from(array('f' => 'friends'), array())
						->joinLeft(array('u1' => 'user'), 'f.friend1 = u1.user_id', array('user1' => 'u1.user_id', 'fullname1' => 'CONCAT(u1.name, " ", u1.lastname)', 'fullname1_eng' => 'CONCAT(u1.name_eng, " ", u1.lastname_eng)'))
						->joinLeft(array('u2' => 'user'), 'f.friend2 = u2.user_id', array('user2' => 'u2.user_id', 'fullname2' => 'CONCAT(u2.name, " ", u2.lastname)', 'fullname2_eng' => 'CONCAT(u2.name_eng, " ", u2.lastname_eng)'))
						->where('f.friend1_accepted = 1')
						->where('f.friend2_accepted = 1')
						->where("f.friend1 = '" . (int)$user_id . "' AND CONCAT(LOWER(u2.name), ' ', LOWER(u2.lastname), ' ', LOWER(u2.name_eng), ' ', LOWER(u2.lastname_eng)) LIKE " . $this->db->quote('%' . mb_strtolower($name, 'UTF-8') . '%'))
						->orWhere("f.friend2 = '" . (int)$user_id . "' AND CONCAT(LOWER(u1.name), ' ', LOWER(u1.lastname), ' ', LOWER(u1.name_eng), ' ', LOWER(u1.lastname_eng)) LIKE " . $this->db->quote('%' . mb_strtolower($name, 'UTF-8') . '%'));

		$return = array();
		foreach($this->db->fetchAll($sql) as $row) {
			if($row['user1'] == $user_id) {
				$return[] = array(
					'user_id'	=> $row['user2'],
					'fullname'	=> $row['fullname2'],
				);
			}elseif($row['user2'] == $user_id) {
				$return[] = array(
					'user_id'	=> $row['user1'],
					'fullname'	=> $row['fullname1'],
				);
			}
		}
		return $return;
	}
	
	public function checkFriends($friend1, $friend2) {
		$sql = $this->db->select()
						->from(array('f' => 'friends'))
						->where('f.friend1_accepted = 1')
						->where('f.friend2_accepted = 1')
						->where("friend1 = '" . (int)$friend1 . "' AND friend2 = '" . (int)$friend2 . "'")
						->orWhere("friend2 = '" . (int)$friend1 . "' AND friend1 = '" . (int)$friend2 . "'");
		
		$result = $this->db->fetchAll($sql);
		if(count($result)) return true;
						
		return false;
	}
	
	//friend1 => пользователь от имени кого смотрим
	public function getFriendshipStatus($friend1, $friend2) {
		$sql = $this->db->select()
						->from(array('f' => 'friends'))
						->where("friend1 = '" . (int)$friend1 . "' AND friend2 = '" . (int)$friend2 . "'")
						->orWhere("friend2 = '" . (int)$friend1 . "' AND friend1 = '" . (int)$friend2 . "'");
		
		$result = $this->db->fetchRow($sql);
		
		if(!$result) return false;

		if(!$result['friend2_accepted'] && ($friend1 == $result['friend1'])) {
			return 'notConfirmed';
		}
		
		if(!$result['friend2_accepted'] && ($friend1 == $result['friend2'])) {
			return 'notConfirmedByYou';
		}
		
		return true;
	}
	
	public function getFriends($user_id) {
		$sql = $this->db->select()
						->distinct()
						->from(array('f' => 'friends'), array())
						->joinLeft(array('u1' => 'user'), 'f.friend1 = u1.user_id', array('user1' => 'u1.user_id', 'image1' => 'u1.image', 'fullname1' => 'CONCAT(u1.name, " ", u1.lastname)', 'fullname1_eng' => 'CONCAT(u1.name_eng, " ", u1.lastname_eng)'))
						->joinLeft(array('u2' => 'user'), 'f.friend2 = u2.user_id', array('user2' => 'u2.user_id', 'image2' => 'u2.image', 'fullname2' => 'CONCAT(u2.name, " ", u2.lastname)', 'fullname2_eng' => 'CONCAT(u2.name_eng, " ", u2.lastname_eng)'))
						->where('f.friend1_accepted = 1')
						->where('f.friend2_accepted = 1')
						->where("f.friend1 = '" . (int)$user_id . "' OR f.friend2 = '" . (int)$user_id . "'");

		$return = array();
		foreach($this->db->fetchAll($sql) as $row) {
			if($row['user1'] == $user_id) {
				$return[] = array(
					'user_id'	=> $row['user2'],
					'image'		=> $row['image2'],
					'fullname'	=> $row['fullname2'],
				);
			}elseif($row['user2'] == $user_id) {
				$return[] = array(
					'user_id'	=> $row['user1'],
					'image'		=> $row['image1'],
					'fullname'	=> $row['fullname1'],
				);
			}
		}
		return $return;
	}
	
	public function getRequests($user_id) {
		$sql = $this->db->select()
						->distinct()
						->from(array('f' => 'friends'))
						->joinLeft(array('u1' => 'user'), 'f.friend1 = u1.user_id', array('u1.*', 'fullname' => 'CONCAT(u1.name, " ", u1.lastname)'))
						->where("f.friend2 = '" . (int)$user_id . "'")
						->where('f.friend1_accepted = 1')
						->where('f.friend2_accepted = 0');

		$return = array();				
		foreach($this->db->fetchAll($sql) as $row) {
			unset($row['password']);
			$return[] = $row;
		}
		return $return;
	}
	
	public function getTotalRequests($user_id) {
		$sql = $this->db->select()
						->distinct()
						->from(array('f' => 'friends'), array('count' => 'COUNT(*)'))
						->where("f.friend2 = '" . (int)$user_id . "'")
						->where('f.friend1_accepted = 1')
						->where('f.friend2_accepted = 0');

		$return = $this->db->fetchRow($sql);
		return (int)$return['count'];
	}
	
	//friend1 - инициатор!!!
	public function setFriendship($friend_1, $friend_2) {

		if(!$this->getFriendshipStatus($friend_1, $friend_2) && ($friend_1 != $friend_2)) {
			$this->db->insert('friends', array(
											'friend1' 	=> $friend_1,
											'friend2' 	=> $friend_2,
											'friend1_accepted' 	=> 1,
										));
				
			return true;							
		}
		return false;
	}
	

	public function acceptFriendship($user_id, $partner_id) {
		
		if($this->getFriendshipStatus($user_id, $partner_id) == 'notConfirmedByYou') {
			$this->db->update('friends', array('friend2_accepted' 	=> 1), "friend1 = '" . (int)$partner_id . "' AND friend2 = '" . (int)$user_id . "'");
		}
	}
	
	public function declineFriendship($user_id, $partner_id) {
		
		if($this->getFriendshipStatus($user_id, $partner_id) == 'notConfirmed') {
			$this->db->delete('friends', "friend1 = '" . (int)$partner_id . "' AND friend2 = '" . (int)$user_id . "'");
		}
	}
}