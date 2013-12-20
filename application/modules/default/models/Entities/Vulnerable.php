<?php
class Default_Model_Entities_Vulnerable
{
    public function datatablesJSONApi($sEcho, $limit = 0, $offset = 0, $sortColumn = 0, $order = 'ASC',
                                      $filter = '', $search = '')
    {
        $tableNews = new Default_Model_DbTable_Vulnerable();
        // $list = new Cms_Model_DbTable_List();

        $data = $tableNews->listTable($limit, $offset, $sortColumn, $order, $filter, $search, $count = false);

        $queryrowsCount = $tableNews->listTable($limit, $offset, $sortColumn, $order, $filter, $search, $count = true);

        $jsonString = array();

        $totalAll = 0;
        if($queryrowsCount > 0)
        {
            $temp = array();
            $i = 1;
            foreach($data as $dat)
            {
                $temp['no'] = $i++;
                $temp['mac'] = $dat['Name'];
                $temp['hostname'] = $dat['Hostname'];
                $temp['os'] = $dat['OperatingSystem'];
                $temp['received'] = $dat['Received'];
                $temp['vulnerable'] = $dat['VulnerabilityId'];

                $totalAll = intval($dat['Id']);

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