<?php
class Default_Model_DbTable_Vulnerable extends Zend_Db_Table_Abstract
    //Default_Library_Model_DbTable
{
    protected $_name = 'VulnerabilityLogs';
    protected $_tableName = 'VulnerabilityLogs';
    protected $_primary = 'Id';

    public function listTable($limit = false, $offset, $search = '', $sortColumn, $order, $isCount = false)
    {
        $query = $this->select()->setIntegrityCheck(FALSE)
                      ->from(array('vl' => $this->_tableName), array('vl.Id', 'vl.VulnerabilityId'))
                        ->joinLeft(array('vt' => 'VulnerabilityTypes'), 'vl."VulnerabilityId" = vt."Id"',
                                array('vt.Name'))
                        ->joinLeft(array('nc' => 'NetworkClients'), 'vl."MacAddress" = nc."MacAddress"',
                                array('nc.Hostname', 'nc.OperatingSystem', 'nc.Received'))
        ;

        if($search != ''){
            $query->where('vt."Name" like ' . "'" . '%' . $search . '%' . "'")
                ->orWhere('nc."Hostname" like ' . "'" . '%' . $search . '%' . "'")
                ->orWhere('nc."OperatingSystem" like ' . "'" . '%' . $search . '%' . "'")
                ->orWhere('nc."Received" = ' . $search )
                ->orWhere('vl."VulnerabilityId" = ' . $search )
            ;
        }

        if(($order == 'asc') || ($order == 'undefined')){
            switch($sortColumn){
                case 1 : $string = 'vt.Name ASC'; break;
                case 2 : $string = 'nc.HostName ASC'; break;
                case 3 : $string = 'nc.OperatingSystem ASC'; break;
                case 4 : $string = 'nc.Received ASC'; break;
                case 5 : $string = 'vl,VulnerabilityId ASC'; break;
                default : $string = 'vt.Name ASC'; break;
            }
        }else if($order = 'desc'){
            switch($sortColumn){
                case 1 : $string = 'vt.Name DESC'; break;
                case 2 : $string = 'nc.HostName DESC'; break;
                case 3 : $string = 'nc.OperatingSystem DESC'; break;
                case 4 : $string = 'nc.Received DESC'; break;
                case 5 : $string = 'vl,VulnerabilityId DESC'; break;
                default : $string = 'vt.Name DESC'; break;
            }
        }

        $query->order($string);

        if(!$isCount){
            $query->limit( $limit, $offset );

            $result = $this->fetchAll($query);
            if(empty($result)) return null;

            return $result->toArray();

        } else {
            $result = $this->fetchAll($query);
            if(empty($result)) return null;
            return count($result);
        }
    }
}