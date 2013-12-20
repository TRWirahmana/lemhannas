<?php
class IndexController extends Default_Library_Controller_Action_Abstract
{
	public function indexAction()
	{
        //$modelCompany = new Admin_Model_DbTable_Company();
        //$this->view->companies = $modelCompany->getAll();
	}

    public function loginAction()
    {
        parent::disableView();

        if( $this->_request->isPost() ){
            $model = new Default_Model_DbTable_User();

            $username = $this->_request->getPost('username');
            $password = $this->_request->getPost('password');
            $company_code = $this->_request->getPost('company_code');

            $data = $model->getBy( array(
                'username' => $username,
                'password'=>sha1($password),
                'company_code'=>$company_code,
            ));

            if( !empty( $data ) ){
                $this->_session->key = sha1(time() . $username . $password );
                $this->_session->userID = $data['id'];
                $this->_session->companyID = $company_code;
                $this->_session->groupID = $data['group_id'];
                $this->_session->user = $data['name'];
                $this->_session->isAdmin = $data['group_id'] == 1 ? true : false;

                parent::writeLog($data['id'], 'login', 'mengakses sistem dengan akun ' . $data['name']);

                //redirect to admin page
                $this->redirectTo('admin/index/');
            } else {
                $this->redirectTo('index/');
            }
        }else{
            $this->redirectTo('index/');
        }
    }

    public function logoutAction()
    {
        parent::disableView();
        parent::writeLog($this->_session->userID, 'logout');

        Zend_Session::destroy( true );

        $this->redirectTo('index/');
    }
}