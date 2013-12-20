<?php
class Default_Model_DbTable_Supplier extends Admin_Library_Model_DbTable
{
    protected $_name = 'suppliers';
    protected $_tableName = 'suppliers';
    protected $_primary = 'id';
    protected $_columns = array(
        'id' => 'id',
        'name' => 'name',
        'address' => 'address',
        'email' => 'email',
        'phone' => 'phone',
    );

    public function getAll()
    {
        $select = $this->select()->from($this->_name);
        return $this->fetchAll($select)->toArray();
    }

    public function listData($limit = false, $offset, $search = '', $sortColumn, $order, $isCount = false)
    {
        $query = $this->select()->setIntegrityCheck(FALSE)
            ->from( array( 'supplier' => $this->_tableName ), array('*') )
        ;

        if($search != ''){
            $query->where("supplier.name LIKE '%$search%' ");
        }

        if(($order == 'asc') || ($order == 'undefined')){
           switch($sortColumn){
                case 0 : $string = 'supplier.name ASC'; break;
                case 1 : $string = 'supplier.address ASC'; break;
            }
        }else if($order = 'desc'){
           switch($sortColumn){
                case 0 : $string = 'supplier.name DESC'; break;
                case 1 : $string = 'supplier.address DESC'; break;
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