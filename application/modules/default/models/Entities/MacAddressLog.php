<?php
class Default_Model_Entities_MacAddressLog
{
    public function datatablesJSONApi($sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $search = '', $start_date = null, $end_date = null)
    {
        $tableNews = new Default_Model_DbTable_MacAddressLog();
        // $list = new Cms_Model_DbTable_List();

        $data = $tableNews->listTable($limit, $offset, $sortColumn, $order, $search, $count = false, $start_date, $end_date);

        $queryrowsCount = $tableNews->listTable($limit, $offset, $sortColumn, $order, $search, $count = true, $start_date, $end_date);

        $jsonString = array();

        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            $i = 1;
            foreach($data as $dat)
            {
                $temp['no'] = $i++;
                $temp['mac'] = $dat['MacAddress'];
                $temp['hostname'] = $dat['Hostname'];
                $temp['os'] = $dat['OperatingSystem'];
                $temp['bandwith'] = $dat['Received'];
                $temp['status'] = $dat['Status'];

                $totalAll = intval($dat['MacAddress']);

                array_push($jsonString, array_values($temp));
            }
        }

        $jsonArray = array();
        $jsonArray[ 'sEcho' ] = $sEcho;
        $jsonArray[ 'iTotalRecords' ] = $queryrowsCount;
        $jsonArray[ 'iTotalAllValue' ] = $totalAll;
        $jsonArray[ 'iTotalDisplayRecords' ] = $queryrowsCount;
        $jsonArray[ 'aaData' ] = $jsonString;

        return json_encode( $jsonArray );
    }
}