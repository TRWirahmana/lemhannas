<?php
class Default_Model_DbTable_Itemprice extends Admin_Library_Model_DbTable
{
    protected $_name = 'price_items';
    protected $_tableName = 'price_items';
    protected $_primary = 'id';
    protected $_columns = array(
        'id' => 'id',
        'price' => 'price',
        'unit' => 'unit',
        'item_id' => 'item_id',
    );

    public function getAll()
    {
        $select = $this->select()->from($this->_name);
        return $this->fetchAll($select)->toArray();
    }

    public function getById($priceID)
    {
        $query = $this->select()->setIntegrityCheck(FALSE)
            ->from(array('price' => $this->_tableName), array('id', 'price', 'unit') )
            ->join(array('item' => 'items'), 'item.id = price.item_id', array('item.item_name'))
        ;
        //return $this->fetchAll($select)->toArray();
    }

    public function listData($limit = false, $offset, $search = '', $sortColumn, $order, $isCount = false)
    {
        $query = $this->select()->setIntegrityCheck(FALSE)
            ->from(array('price' => $this->_tableName), array('id', 'price', 'unit') )
            ->join(array('item' => 'items'), 'item.id = price.item_id', array('item.item_name'))
        ;

        if($search != ''){
            $query->where("price.price LIKE '%$search%' ")
                    ->orWhere("price.unit LIKE '%$search%' ")
                    ->orWhere("item.item_name LIKE '%$search%' ")
            ;
        }

        if(($order == 'asc') || ($order == 'undefined')){
           switch($sortColumn){
                case 0 : $string = 'item.item_name ASC'; break;
                case 1 : $string = 'price.price ASC'; break;
            }
        }else if($order = 'desc'){
           switch($sortColumn){
               case 0 : $string = 'item.item_name DESC'; break;
               case 1 : $string = 'price.price DESC'; break;
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