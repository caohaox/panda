<?php
class Application_Model_Page extends Admin_Db {
	
	
	public function addPage($data = array()) {

		$this->db->insert('page', array(
			'seo_url'		=> $data['seo_url'],
			'template_id'	=> $data['template_id'],
			'status'		=> $data['status'],
		));
		
		$page_id = $this->db->lastInsertId();

		foreach($data['language'] as $language_id => $value) {
			
			$this->db->insert('page_description', array(
				'title'				=> $value['title'],
				'meta_description'	=> $value['meta_description'],
				'meta_keywords'		=> $value['meta_keywords'],
				'description'		=> $value['description'],
				'page_id'			=> $page_id,
				'language_id'		=> $language_id,
			));
		}
	}
	
	
	public function getPage($page_id) {
		
		$sql = $this->db->select()
						->from(array('p' => 'page'))
						->where('p.page_id = ?', $page_id);

		$data = $this->db->fetchRow($sql);
		
		$sql2 = $this->db->select()
						 ->from(array('pd' => 'page_description'))
						 ->where('pd.page_id = ?', $page_id);
		
		foreach($this->db->fetchAll($sql2) as $row) {
			$data['language'][$row['language_id']] = $row;
		}
				
		return $data;
	}
	
	
	public function editPage($data = array()) {
		
		$where = 'page_id = ' . (int)$data['page_id'];
		$this->db->update('page', array(
			'seo_url'		=> $data['seo_url'],
			'template_id'	=> $data['template_id'],
			'status'		=> $data['status'],
		), $where);
		
		$this->db->delete('page_description', "page_id = '" . (int)$data['page_id'] . "'");
		foreach($data['language'] as $language_id => $value) {
			
			$this->db->insert('page_description', array(
				'title'				=> $value['title'],
				'meta_description'	=> $value['meta_description'],
				'meta_keywords'		=> $value['meta_keywords'],
				'description'		=> $value['description'],
				'page_id'			=> $data['page_id'],
				'language_id'		=> $language_id,
			));
		}
		
		return true;
	}
	
	
	/**
	 * used by Zend_Pagination
	 * @return Zend_Db_Select object
	 */
	public function getPages() {
		
		$query = $this->db->select()
						  ->from(array('p' => 'page'))
						  ->joinLeft(array('pd' => 'page_description'), 'p.page_id = pd.page_id')
						  ->order('p.page_id DESC')
						  ->where('pd.language_id = ?', Zend_Registry::get('language_id'));
						  
		return $query;
	}
	
	
	/**
	 * used by Zend_Pagination
	 * @return Zend_Db_Select object
	 */
	public function getTotalPages() {
		$query = $this->db->select()
						  ->from('page', array(Zend_Paginator_Adapter_DbSelect::ROW_COUNT_COLUMN => 'COUNT(*)'));
						  
		return $query;
	}
	
	
	public function removePage($page_id) {
		
		$this->db->delete('page', 'page_id = ' . (int)$page_id);
	}
}