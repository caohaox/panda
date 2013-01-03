<?php 

class Application_Model_Page extends Site_Db {	
	
	public function getPageBySeoUrl($seo_url) {
		$query = $this->db->select()
						  ->from(array('p' => 'page'))
						  ->joinLeft(array('pd' => 'page_description'), 'p.page_id = pd.page_id')
						  ->where('pd.language_id = ?', Zend_Registry::get('language_id'))
						  ->where('p.seo_url = ?', $seo_url)
						  ->where('p.status = 1');
						  
		return $this->db->fetchRow($query);
	}
	
	
	
	public function getPageById($page_id) {
		$query = $this->db->select()
						  ->from(array('p' => 'page'))
						  ->joinLeft(array('pd' => 'page_description'), 'p.page_id = pd.page_id')
						  ->where('pd.language_id = ?', Zend_Registry::get('language_id'))
						  ->where('p.status = 1')
						  ->where('p.page_id = ?', $page_id);
						  
		return $this->db->fetchRow($query);
	}
}