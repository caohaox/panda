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
        $this->bootstrap('db');
		$db = $this->getResource('db');
		Zend_Registry::set('db', $db);
		
        Zend_Db_Table_Abstract::setDefaultAdapter($db);

		$config = array(

		    'name'           => 'sessions',
		    'primary'        => 'session_id',
		    'modifiedColumn' => 'modified',
		    'dataColumn'     => 'data',
		    'lifetimeColumn' => 'lifetime'
		
		);
		
		Zend_Session::setOptions(array('cookie_lifetime' => 1209600, 'gc_maxlifetime'  => 1209600, 'remember_me_seconds' => 864000));
		Zend_Session::setSaveHandler(new Zend_Session_SaveHandler_DbTable($config));
		Zend_Session::start();
    }
    
    
    protected function _initRoutes() {		
		$routesConfig = new Zend_Config_Ini(APPLICATION_PATH . '/configs/routes.ini', 'production');
		$router = new Zend_Controller_Router_Rewrite();
		$router->addConfig($routesConfig, 'routes');
				
		$controller = Zend_Controller_Front::getInstance();
		$controller->setRouter($router);
	}

	
	
    public function _initPlugins() {
    	$frontController = Zend_Controller_Front::getInstance();
       
        $frontController->registerPlugin(new Application_Plugin_Pages());
        $frontController->registerPlugin(new Application_Plugin_Auth());
        $frontController->registerPlugin(new Application_Plugin_Acl());
    }
	
	
	protected function _initLocale() {
		
		$session = new Zend_Session_Namespace('site');
		$language = array();
		
		if($session->language) {
			$language = $session->language;
		}elseif(isset($_COOKIE['language_id'])) {
			$db = Zend_Registry::get('db');
			$query = $db->select()
						   ->from('language')
						   ->where('language_id = ?', $_COOKIE['language_id']);
			
			$language = $db->fetchRow($query);			   
		}
		
		if(!$language) {
			$default_language_id = Site_Config::get('default_language_id');
			
			$db = Zend_Registry::get('db');
			$query = $db->select()
						   ->from('language')
						   ->where('language_id = ?', $default_language_id);
			
			$language = $db->fetchRow($query);	
		}
		
		Zend_Registry::set('language_id', $language['language_id']);
		
	
			
		$session->language = $language;
		setcookie('language_id', $language['language_id'], time() + 60 * 60 * 24 * 30, '/', $_SERVER['HTTP_HOST']);
		
		$locale = new Zend_Locale($language['locale']);
		Zend_Registry::set('Zend_Locale', $locale);
        setlocale(LC_ALL, $language['locale'], $language['code']);
		
        $translator = new Zend_Translate(
	        array(
	        	'adapter' => 'array',
	        	'content' => APPLICATION_PATH . '/../resources/languages',
	        	'locale' => $language['code'],
	        	'scan' => Zend_Translate::LOCALE_DIRECTORY
	        )
        );
        
        Zend_Registry::set('translate', $translator);

        Zend_Validate_Abstract::setDefaultTranslator($translator);
    }

    public function _initCache() {
    	$frontendOptions = array(
   			'lifetime' => 7200,
   			'automatic_serialization' => true
		);
		$backendOptions = array(
    		'cache_dir' => '../cache/'
		);
    	$cache = Zend_Cache::factory(
    						'Core',
                            'File',
                            $frontendOptions,
                            $backendOptions);
                  
		Zend_Registry::set('cache', $cache);
    }
    
    
    protected function _initMes() {
    	Zend_Controller_Action_HelperBroker::addHelper(new Zend_Controller_Action_Helper_FlashMessenger());
    } 
    
    protected function _initConstants() {
    	//ид опции региона проживания у мужчин
    	Zend_Registry::set('region_man', 21);
    	
    	 //ид опции региона проживания у мужчин
    	Zend_Registry::set('region_woman', 20);
    	
    	//ид опции возраста
    	Zend_Registry::set('partner_age', 4);
    	
    	//ид опции роста
    	Zend_Registry::set('partner_height', 36);
    	
    	//ид статьи с информацией
    	Zend_Registry::set('notification_area', 6);
    	
    	//телосложение
    	Zend_Registry::set('body', 8);
    	
    	//цвет глаз
    	Zend_Registry::set('eyes', 9);
    	
    	//цвет волос
    	Zend_Registry::set('hair', 10);
    	
    	//курение
    	Zend_Registry::set('smoking', 1);
    	
    	//алкоголь
    	Zend_Registry::set('alcohol', 11);
    	
    	//образование
    	Zend_Registry::set('education', 12);
    	
    	//специальность
    	Zend_Registry::set('speciality', 13);
    	
    	//сфера деятельности
    	Zend_Registry::set('sfera', 19);
    }
}

