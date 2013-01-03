<?php
class Admin_Validate_EnglishAlnum extends Zend_Validate_Abstract
{
    const ENGLISH = 'english';
 
    protected $_messageTemplates = array(
        self::ENGLISH => "'%value%' содержит буквы не английского алфавита, либо запрещенные символы"
    );
 
    public function isValid($value)
    {
        $this->_setValue($value);
 		
        $pattern = '/[^a-zA-Z0-9_-]/u';
        
        if ($value != preg_match($pattern, $value)) {
            $this->_error(self::ENGLISH);
            return false;
        }
 
        return true;
    }
}