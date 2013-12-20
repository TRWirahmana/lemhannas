<?php
/**
 * Helper Image Url
 */
class Zend_View_Helper_ImageUrl extends Zend_View_Helper_Abstract
{
	private $_base;
	
	public function __construct() 
	{
		$this->_base = Zend_Controller_Front::getInstance()->getBaseUrl() . '/images/';
	}
	
	public function imageUrl($str = '')
	{
		return $this->_base . $str;
	}
}