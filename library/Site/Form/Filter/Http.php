<?php
class Life_Form_Filter_Http implements Zend_Filter_Interface
{
    public function filter($value)
    { 
    	if($value) {
        	$result = str_replace(array('http://'), '', $value);
        	return 'http://' . $result;
    	}
    	return $value;
    }
}