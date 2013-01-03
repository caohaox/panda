<?php
class Application_Model_Language extends Admin_Db {
	
	//site languages, not user skills!!!
	public function getLanguages() {
		
		$sql = $this->db->select()
						->from('language');
						
		return $this->db->fetchAll($sql);
	}
	
	
	public function getAllLanguages() {
		$sql = $this->db->select()
						->from(array('al' => 'all_languages'))
						->joinLeft(array('aln' => 'all_languages_names'), 'al.all_language_id = aln.all_language_id')
						->where('aln.language_id = ?', Zend_Registry::get('language_id'));
						
		return $this->db->fetchAll($sql);
	}
	
	
	//user skills!!!
	public function getSkills() {
		$sql = $this->db->select()
						->from(array('s' => 'skills'))
						->joinLeft(array('sn' => 'skills_names'), 's.skill_id = sn.skill_id')
						->where('sn.language_id = ?', Zend_Registry::get('language_id'));
						
		return $this->db->fetchAll($sql);
	}
}