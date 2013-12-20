<?php
/**
 * Helper Base Url
 * Append string to baseUrl
 * With additional array of params
 */
class Zend_View_Helper_BaseUrl extends Zend_View_Helper_Abstract
{
    private $_base;

    public function __construct()
    {
        $this->_base = ROOT_URL . Zend_Controller_Front::getInstance()->getBaseUrl() . '/';
    }

	public function baseUrl($str = '', $params = false)
	{
        $this->_base = Zend_Controller_Front::getInstance()->getBaseUrl() . '/';

		if (strpos($str, '#') === 0) return $str;	// # means empty
		if ($params) {
			foreach ($params as $key => $val) {
				$str .= '/' . urlencode($key) . '/' . urlencode($val);
			}
		}
		return $this->_base . $str;
	}
}