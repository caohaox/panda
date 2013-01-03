<?php

class Site_Form_Validate_Email extends Zend_Validate_Abstract
{
    const IDENTICAL = 'identical';
 
    protected $_messageTemplates = array(
        self::IDENTICAL => "Почтовый адрес '%value%' уже используется"
    );
 
    public function isValid($value)
    {
    	$db = new Application_Model_User;
        $this->_setValue($value);
        if ($db->emailValidate($value)) {
            $this->_error(self::IDENTICAL);
            return false;
        }
        return true;
    }
    
}