<?php

class Site_Form_Validate_Password extends Zend_Validate_Abstract
{
    const NOT_IDENTICAL = 'not_identical';
 
    protected $_messageTemplates = array(
        self::NOT_IDENTICAL => "Пароль введен не верно"
    );
 
    public function isValid($value)
    {
    	$view = Zend_Registry::get('view');
    	$db = new Application_Model_User;
        $this->_setValue($value);
        if (!$db->passwordValidate($value, $view->user->user_id)) {
            $this->_error(self::NOT_IDENTICAL);
            return false;
        }
        return true;
    }
    
}