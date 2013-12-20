<?php
class Default_Model_DbTable_Usergroup extends Admin_Library_Model_DbTable
{
    protected $_name = 'user_groups';
    protected $_tableName = 'user_groups';
    protected $_primary = 'id';
    protected $_columns = array(
        'id' => 'id',
        'name' => 'name',
    );

    public function getAll(){
        $select = $this->select()->from($this->_name);
        return $this->fetchAll($select)->toArray();
    }

    public function listData($limit = false, $offset, $search = '', $sortColumn, $order, $isCount = false)
    {
        $query = $this->select()->setIntegrityCheck(FALSE)
            ->from( array( 'user' => $this->_tableName ), array('*') )
        ;

        if($search != ''){
            $query->where("user.username LIKE '%$search%' ");
        }

        if(($order == 'asc') || ($order == 'undefined')){
           switch($sortColumn){
               case 0 : $string = 'user.name ASC'; break;
               case 1 : $string = 'company.name ASC'; break;
            }
        }else if($order = 'desc'){
           switch($sortColumn){
               case 0 : $string = 'user.name DESC'; break;
               case 1 : $string = 'company.name DESC'; break;
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