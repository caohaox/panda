<?php
class Application_Model_Template extends Admin_Db {
	public function getTemplates() {
		
		$query = $this->db->select()
						  ->from('template');
						  
		return $this->db->fetchAll($query);
	}
}