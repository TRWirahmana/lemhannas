<?php
class Default_Model_DbTable_Company extends Admin_Library_Model_DbTable
{
    protected $_name = 'companies';
    protected $_tableName = 'companies';
    protected $_primary = 'id';
    protected $_columns = array(
        'id' => 'id',
        'register_code' => 'register_code',
        'name' => 'name',
        'address' => 'address',
        'email' => 'email',
        'phone' => 'phone',
        'parent' => 'parent',
    );

    public function getAll($fields = null)
    {
        if(empty($fields)) $fields = array('*');
        $query = $this->select()->from($this->_name, $fields);
        return $this->fetchAll($query)->toArray();
    }

    public function getParent($fields = null)
    {
        if(empty($fields)) $fields = array('*');
        $query = $this->select()
                ->from(array('company'=>$this->_name), $fields)
                ->where('company.parent IS NULL')
                ->orWhere('company.parent = ""')
        ;
        return $this->fetchAll($query)->toArray();
    }

    public function listData($type = 'toko', $limit = false, $offset, $search = '', $sortColumn, $order, $isCount = false)
    {
        $query = $this->select()->setIntegrityCheck(FALSE)
            ->from( array( 'company' => $this->_tableName ), array('*') )
        ;
        if($type == 'toko'){
            //toko
            $query->where('company.parent !=""');
        }else{
            //gudang
            $query->where('company.parent IS NULL')
                  ->orWhere('company.parent = ""')
            ;
        }

        if($search != ''){
            $query->where("company.register_code LIKE '%$search%' ")
                    ->orWhere("company.name LIKE '%$search%' ")
                    ->orWhere("company.address LIKE '%$search%' ")
            ;
        }

        if(($order == 'asc') || ($order == 'undefined')){
           switch($sortColumn){
                case 0 : $string = 'company.register_code ASC'; break;
                case 1 : $string = 'company.name ASC'; break;
                case 2 : $string = 'company.address ASC'; break;
            }
        }else if($order = 'desc'){
           switch($sortColumn){
                case 0 : $string = 'company.register_code DESC'; break;
                case 1 : $string = 'company.name DESC'; break;
                case 2 : $string = 'company.address DESC'; break;
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