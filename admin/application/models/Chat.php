<?php
class Application_Model_Chat extends Admin_Db {
	
	public function getAllChats($user_id) {
		
		
		//сообщения ОТ пользователя
		$sql2 = $this->db->select()
						->distinct()
						->from(array('m' => 'message'), array())
						->joinLeft(array('u' => 'user'), 'm.to_user_id = u.user_id', array('user_id' => 'u.user_id', 'image', 'fullname' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'))		
						->where('m.from_user_id = ?', $user_id)
						->order('m.date_added DESC')
						->group('m.to_user_id');
		
			
		//сообщения ДЛЯ пользователя
		$sql = $this->db->select()
						->distinct()
						->from(array('m' => 'message'), array())
						->joinLeft(array('u' => 'user'), 'm.from_user_id = u.user_id', array('user_id' => 'u.user_id', 'image', 'fullname' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'))
						->where('m.to_user_id = ?', $user_id)
						->order('m.date_added DESC')
						->group('m.from_user_id');
					
		$return = array();				
		foreach($this->db->fetchAll($sql) as $row) {
			$return[$row['user_id']] = $row;
		}
			
		foreach($this->db->fetchAll($sql2) as $row) {
			if(!isset($return[$row['user_id']])) {
				$return[$row['user_id']] = $row;
			}
		}
							
		return $return;
		
	}
	
	
	public function getMessages($user_from, $user_to, $limit = 10) {
		$sql = $this->db->select()
						->from('message', array('*'))
						->where("to_user_id = '" . (int)$user_to . "' AND from_user_id = '" . (int)$user_from . "'")
						->orWhere("to_user_id = '" . (int)$user_from . "' AND from_user_id = '" . (int)$user_to . "'")
						->order('date_added DESC')
						->limit($limit);
		
		$return = array();
		foreach($this->db->fetchAll($sql) as $row) {
			$row['description'] = $this->replaceSmiles($row['description']);
			$return[] = $row;
		}
		return array_reverse($return);
	}
	
	
	private function replaceSmiles($text) {
		
		$smiles = array(
			':)',
			':o',
			':D',
			';)',
			':p',
			':cool:',
			':rolleyes:',
			':mad:',
			':eek:',
			':confused:',
			':agree:',
			':angry:',
			':blink:',
			':cray:',
			':dance:',
			':haha:',
			':help:',
			':kissed:',
			':laugh:',
			':zzz:',
			':lol:',
			':mamba:',
			':no:',
			':nono:',
			':offtopic:',
			':(',
			':secret:',
			':stop:',
			':thanks:',
			':unsure:',
			':victory:',
			':wacko:',
			':yes:',
		);
		
		//$view = Zend_Registry::get('view');
		$baseUrl = Admin_Config::get('site_url');
		
		$images = array(
			'<img src="' . $baseUrl . 'img/smiles/1.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/2.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/3.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/4.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/5.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/6.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/7.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/8.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/9.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/10.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/11.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/12.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/13.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/14.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/15.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/16.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/17.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/18.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/19.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/20.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/21.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/22.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/23.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/24.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/25.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/26.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/27.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/28.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/29.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/30.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/31.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/32.gif" />',
			'<img src="' . $baseUrl . 'img/smiles/33.gif" />',
		);
		
		
		return str_replace($smiles, $images, $text);
	}
	
	
	public function removeMessage($message_id) {
		$this->db->delete('message', "message_id = '" . (int)$message_id . "'");
	}
	
	
	public function getMessage($message_id) {
		$sql = $this->db->select()
						  ->from('message')
						  ->where('message_id = ?', $message_id);
						  
		return $this->db->fetchRow($sql);
	}
	
	public function editMessage($message_id, $description) {
		$this->db->update('message', array('description' => $description), "message_id = '" . (int)$message_id . "'");
	}
}