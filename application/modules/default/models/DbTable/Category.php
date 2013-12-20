<?php
class Default_Model_DbTable_Category extends Admin_Library_Model_DbTable
{
    protected $_name = 'categories';
    protected $_tableName = 'categories';
    protected $_primary = 'id';
    protected $_columns = array(
        'id' => 'id',
        'category_name' => 'category_name',
        'category_parent' => 'category_parent',
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
            ->from( array( 'category' => $this->_tableName ), array('*') )
        ;

        if($search != ''){
            $query->where("category.category_name LIKE '%$search%' ");
        }

        if(($order == 'asc') || ($order == 'undefined')){
           switch($sortColumn){
                case 0 : $string = 'category.category_name ASC'; break;
            }
        }else if($order = 'desc'){
           switch($sortColumn){
                case 0 : $string = 'category.category_name DESC'; break;
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