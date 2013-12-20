<?php
class Default_Model_DbTable_Item extends Admin_Library_Model_DbTable
{
    protected $_name = 'items';
    protected $_tableName = 'items';
    protected $_primary = 'id';
    protected $_identity = 1;

    protected $_columns = array(
        'id' => 'id',
        'item_name' => 'item_name',
        'category_id' => 'category_id',
    );

    public function getAll($fields = null)
    {
        if(empty($fields)) $fields = array('*');

        $select = $this->select()->from($this->_name, $fields);
        return $this->fetchAll($select)->toArray();
    }

    public function listData($limit = false, $offset, $search = '', $sortColumn, $order, $isCount = false)
    {
        $query = $this->select()->setIntegrityCheck(FALSE)
            ->from( array( 'item' => $this->_tableName ), array('*') )
            ->join( array( 'cat' => 'categories' ), 'cat.id = item.category_id', array('cat.category_name'))
        ;

        if($search != ''){
            $query->where("item.item_name LIKE '%$search%' ");
        }

        if(($order == 'asc') || ($order == 'undefined')){
           switch($sortColumn){
                case 0 : $string = 'item.item_name ASC'; break;
                case 1 : $string = 'cat.category_name ASC'; break;
            }
        }else if($order = 'desc'){
           switch($sortColumn){
                case 0 : $string = 'item.item_name DESC'; break;
                case 1 : $string = 'cat.category_name DESC'; break;
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