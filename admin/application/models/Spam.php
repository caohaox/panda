<?php
class Application_Model_Spam extends Admin_Db {

	public function getSpams() {
		
		$query = $this->db->select()
						  ->from(array('s' => 'spam'))
						  ->joinLeft(array('m' => 'message'), 's.message_id = m.message_id', array('user_id' => 'm.from_user_id'))
						  ->joinLeft(array('u' => 'user'), 'm.from_user_id = u.user_id', array('full_name' => 'CONCAT(u.name_eng, " ", u.lastname_eng)'))
						  ->order('s.spam_id DESC');
						  
						  
		return $query;
	}
	

	public function getTotalSpams() {
		$query = $this->db->select()
						  ->from('spam', array(Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN => 'COUNT(*)'));
						  
		return $query;
	}
	
	
	public function removeSpam($spam_id) {
		
		$this->db->delete('spam', 'spam_id = ' . (int)$spam_id);
		
	}
}