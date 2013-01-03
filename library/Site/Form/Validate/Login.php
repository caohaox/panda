<?php

class Life_Form_Validate_Login extends Zend_Validate_Abstract
{
    const IDENTICAL = 'identical';
 
    protected $_messageTemplates = array(
        self::IDENTICAL => "Логин '%value%' уже используется"
    );
 
    public function isValid($value)
    {
    	$db = new Application_Model_User;
        $this->_setValue($value);
        if ($db->loginValidate($value)) {
            $this->_error(self::IDENTICAL);
            return false;
        }
        return true;
    }
}