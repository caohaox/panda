<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected $view;

    public function __construct($application) {
        parent::__construct($application);

        $this->bootstrap('view');
        $view = $this->getResource('view');
        $this->view = $view;
        Zend_Registry::set('view', $view);
    }
    
    
	protected function _initDatabase() {
		$config = new Zend_Config_Ini(APPLICATION_PATH . '/configs/application.ini', 'production');
		$db = Zend_Db::factory($config->db);
		Zend_Registry::set('db', $db);
	}
	
	protected function _initPlugins() {
		$frontController = Zend_Controller_Front::getInstance();
        $frontController->registerPlugin(new Application_Plugin_Auth());
	}
	
	protected function _initLocale() {

        $session = new Zend_Session_Namespace('admin_area');
		$language = array();
		
		if($session->language) {
			$language = $session->language;
		}elseif(isset($_COOKIE['admin_language_id'])) {
			$db = Zend_Registry::get('db');
			$query = $db->select()
						   ->from('language')
						   ->where('language_id = ?', $_COOKIE['language_id']);
			
			$language = $db->fetchRow($query);			   
		}
		
		if(!$language) {
			$default_language_id = Admin_Config::get('default_language_id');
			
			$db = Zend_Registry::get('db');
			$query = $db->select()
						   ->from('language')
						   ->where('language_id = ?', $default_language_id);
			
			$language = $db->fetchRow($query);	
		}
		
		Zend_Registry::set('language_id', $language['language_id']);
		
		$session->language = $language;
		setcookie('admin_language_id', $language['language_id'], time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
		
        setlocale(LC_ALL, $language['locale'], $language['code']);
		
        $translator = new Zend_Translate(
	        array(
	        	'adapter' => 'array',
	        	'content' => APPLICATION_PATH . '/../../resources/languages',
	        	'locale' => $language['code'],
	        	'scan' => Zend_Translate::LOCALE_DIRECTORY
	        )
        );
        
        Zend_Registry::set('translate', $translator);

        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }
    
    
    protected function _initMes() {
    	Zend_Controller_Action_HelperBroker::addHelper(new Zend_Controller_Action_Helper_FlashMessenger());
    }
}