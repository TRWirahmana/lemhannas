<?php
/**
 * Created by PhpStorm.
 * User: Sangkuriang
 * Date: 12/20/13
 * Time: 1:23 PM
 */

class MacAddressLogController extends Default_Library_Controller_Action_Abstract
{

    public function indexAction()
    {

    }

    public function datatableAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        //init
        $model = new Default_Model_Entities_MacAddressLog();
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
        $start_date = $this->_getParam( 'start_date' );
        $end_date = $this->_getParam( 'end_date' );

        //get rows
        $jsonString = $model->datatablesJSONApi($sEcho, $limit, $offset,
            $sortColumn, $order, $sSearch, $start_date, $end_date);

        echo  $jsonString;
    }

    public function datatablesAction()
    {
        $this->getHelper('viewRenderer')->setNoRender(true);
        $this->getHelper('layout')->disableLayout();

        $model = new Default_Model_DbTable_MacAddressLog();


        $data = $model->MacAddressdata();

//         var_dump($data);exit;

        $sEcho          = $this->_getParam('sEcho');
        $iDisplayStart  = $this->_getParam("iDisplayStart");
        $iDisplayLength = $this->_getParam('iDisplayLength');

        $count1 = count($data);

        $data = array_splice($data, $iDisplayStart, $iDisplayLength);

        return $this->_helper->json->sendJson(array(
            "sEcho"                => $sEcho,
            "aaData"               => $data,
            "iTotalRecords"        => $count1,
            "iTotalDisplayRecords" => $count1
        ));
    }
}
