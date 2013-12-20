<?php
/*
* parent for all controller
* @author : mrjoule
*/
abstract class Initapp_Controller_Action_Abstract extends Zend_Controller_Action
{
    const SES_NAMESPACE = 'link2012';
    protected $url;
    protected $_module;
    protected $_controller;
    protected $_action;
    protected $_session = null;// Object session
   	protected $_auth = null; // authentication
   	protected $_flashmsg = null; // Helper Flashmessenger
   	protected $_redirect = null; // Helper Redirector

    public function init()
    {
        $this->_initView();

        $this->url = $this->view->url();
        $this->_controller = self::getControllerName();
        $this->_action = self::getActionName();
        $this->_module = self::getModuleName();

        $this->_session = new Zend_Session_Namespace(self::SES_NAMESPACE);
        $this->_auth = Zend_Auth::getInstance();
        $this->_flashmsg = $this->_helper->getHelper('FlashMessenger');

        //assigment view
        $this->view->controller = self::getControllerName();
        $this->view->action = self::getActionName();
        $this->view->module = self::getModuleName();
        $this->view->messages = $this->_flashmsg->getMessages();
        $this->view->session = $this->_session;
        $this->view->modules = self::myACL();

    }

    protected function redirectTo($url)
    {
        $this->_redirect($url);
    }

    /**
   	 * Before dispatching the requested controller/action
   	 */
   	public function preDispatch()
   	{
       if(self::getModuleName() == 'admin'){
           if(empty($this->_session->key)) self::redirectTo('index/');
       }

        $script = '
            var base_url = "' . $this->view->baseUrl() . '";
        ';
        $this->view->headScript()->prependScript($script, $type = 'text/javascript', $attrs = array());
   	}

    /*
     * Very simple ACL (Access Controll List) :D
     * */
    protected function myACL()
    {
        if(empty($this->_session->groupID) || !$this->_session->groupID) return false;

        $userModule = new Admin_Model_DbTable_Usermodule();
        $data = $userModule->getModuleUser($this->_session->groupID);

        $menu = array();
        if( !empty($data) ){
            foreach($data as $row){
                if( empty($row['parent']) ){
                    $menu[$row['module_id']]['name'] = $row['title'];
                    $menu[$row['module_id']]['link'] = $row['syslink'];
                    $menu[$row['module_id']]['icon'] = $row['icon'];
                    $menu[$row['module_id']]['child'] = array();

                }else{
                    if(array_key_exists( $row['parent'], $menu)){
                        $temp['name'] = $row['title'];
                        $temp['link'] = $row['syslink'];
                        array_push($menu[ $row['parent'] ]['child'], $temp);
                        unset( $temp );
                    }
                }
            }
        }

        return $menu;

    }

    #protected section
    protected function _initView()
    {
    	$view = new Initapp_Controller_Action_Helper_View($this->view);
		$this->view = $view->init();
    }

    protected function getModuleName()
   	{
   		return Zend_Controller_Front::getInstance()->getRequest()->getModuleName();
   	}

   	protected function getControllerName()
   	{
   		return Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
   	}

   	protected function getActionName()
   	{
   		return Zend_Controller_Front::getInstance()->getRequest()->getActionName();
   	}

	// Disable view dan layout
    protected final function disableView()
	{
		$this->getHelper('viewRenderer')->setNoRender(true);
		$this->getHelper('layout')->disableLayout();
	}

	// Disable view
    public final function removeView()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
    }

	// Disable layout
	protected final function disableLayout()
	{
		$this->getHelper('layout')->disableLayout();
	}

    // check ajax request
    protected final function isAjax()
    {
		if ($this->_request->isXmlHttpRequest() || isset($_GET['ajax'])) return true;
        return false;
    }

    // print array with nice styles
    protected function printArray($array)
    {
        //harus array atau object
        if(is_array($array) || is_object($array)){
            //convert ke array jika object
            if(!is_array($array)) self::objectToArray($array);

            echo '<pre>';
                echo print_r($array);
            echo '</pre>';
        }
    }

    // convert stdClass object to array
    protected function objectToArray($object) {
        $array = array();

   		if (is_object($object)) {
   			foreach ($object as $key => $value) {
   				$array[$key] = $value;
   			}
   		} else {
   			$array = $object;
   		}

   		return $array;
   	}

    protected function setCustomHeader($key)
   	{
   		$contentType = array(
   			'json' => 'application/json',
   			'js'   => 'text/javascript',
   			'html' => 'text/html',
   			'xml'  => 'text/xml',
   			'css'  => 'text/css',
   		);
   		$type = ($contentType[$key]) ? $contentType[$key] : $key;
   		$this->getResponse()->setHeader('Content-Type', $type);
   	}

    protected function random_string($length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $size = strlen($chars);

        $str = '';
        for( $i = 0; $i < $length; $i++ ) {
            $str .= $chars[ rand( 0, $size - 1 ) ];
        }

        return $str;
    }

    protected function writeLog($userID, $activity, $description = null){

        if( empty($userID) || empty($activity) ) return false;

        $log = new Default_Model_DbTable_Log();
        $log->insert( array(
            'user_id' => $userID,
            'time' => date('Y-m-d H:i:s'),
            'activity' => $activity,
            'description' => $description,
        ));
    }
}