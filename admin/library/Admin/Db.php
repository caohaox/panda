<?php 
class Admin_Db {
	
	protected $db;
	
	public function __construct() {
		$this->db = Zend_Registry::get('db');
	}
}