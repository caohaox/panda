<?php

class Life_Form_Validate_RecieverName extends Zend_Validate_Abstract
{
    const NOT_FOUND = 'not_found';
 
    protected $_messageTemplates = array(
        self::NOT_FOUND => "Получатель '%value%' не найден"
    );
 
    public function isValid($value)
    {
    	$db = new Application_Model_User;
        $this->_setValue($value);
        if ($db->validateRecieverName($value)) {
            $this->_error(self::NOT_FOUND );
            return false;
        }
        return true;
    }
    
}