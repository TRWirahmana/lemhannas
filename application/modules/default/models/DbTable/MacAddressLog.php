<?php
class Default_Model_DbTable_MacAddressLog extends Zend_Db_Table_Abstract
{
    protected $_name = 'public.NetworkClients';
    protected $_tableName = 'public.NetworkClients';
    protected $_primary = 'MacAddress';

    public function listTable($limit = false, $offset, $search = '', $sortColumn, $order, $isCount = false, $start_date = null, $end_date = null)
    {
        $query = $this->select()
            ->setIntegrityCheck(false)
            ->from(array("macaddress" => $this->_tableName), array(
                'macaddress.MacAddress',
                'macaddress.Hostname',
                'macaddress.OperatingSystem',
                'macaddress.Received',
                'macaddress.Status'
            ));

        if($start_date != null && $end_date != null){
            $query->where('macaddress."RegisteredTimestamp"  > ?', $start_date )
                ->where('macaddress."RegisteredTimestamp" < ?', $end_date)
            ;
        }

        if($search != ''){
            $query->where('macaddress."MacAddress" = ' . $search )
                ->orWhere('macaddress."Hostname" like ' . "'" . '%' . $search . '%' . "'")
                ->orWhere('macaddress."OperatingSystem" like ' . "'" . '%' . $search . '%' . "'")
               ;
        }

        if(($order == 'asc') || ($order == 'undefined')){
            switch($sortColumn){
                case 1 : $string = 'macaddress.MacAddress ASC'; break;
                case 2 : $string = 'macaddress.HostName ASC'; break;
                case 3 : $string = 'macaddress.OperatingSystem ASC'; break;
                default : $string = 'macaddress.MacAddress ASC'; break;
            }
        }else if($order = 'desc'){
            switch($sortColumn){
                case 1 : $string = 'macaddress.MacAddress ASC'; break;
                case 2 : $string = 'macaddress.HostName ASC'; break;
                case 3 : $string = 'macaddress.OperatingSystem ASC'; break;
                default : $string = 'macaddress.MacAddress ASC'; break;
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

    public function MacAddressdata()
    {
        $query = $this->select()
                ->setIntegrityCheck(false)
                ->from(array("macaddress" => $this->_tableName), array(
                'macaddress.MacAddress',
                'macaddress.Hostname',
                'macaddress.OperatingSystem'
            ));


        return $this->fetchAll($query)->toArray();
    }
}