<?php
class VulnerableController extends Default_Library_Controller_Action_Abstract
{
	public function indexAction()
	{
        //$modelCompany = new Admin_Model_DbTable_Company();
        //$this->view->companies = $modelCompany->getAll();
	}

    public function datatableAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //init Model Penyalur
        $model = new Default_Model_Entities_Vulnerable();
        // Param
        $sEcho = intval( $this->_getParam( 'sEcho' ) );
        $sSearch = $this->_getParam( 'sSearch' );
        // Paging
        $offset = $this->_getParam( 'iDisplayStart' );
        $limit = $this->_getParam( 'iDisplayLength' );
        // Sort Order
        $sortColumn = $this->_getParam( 'iSortCol_0' );
        $order = $this->_getParam( 'sSortDir_0' );
        //custom filter
        $filter = $this->_getParam( 'filter' );

        //get rows
        $jsonString = $model->datatablesJSONApi($sEcho, $limit, $offset,
            $sortColumn, $order,
            $filter, $sSearch);

        echo  $jsonString;
    }
}