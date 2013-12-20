<?php
class Default_Model_DbTable_User extends Admin_Library_Model_DbTable
{
    protected $_name = 'users';
    protected $_tableName = 'users';
    protected $_primary = 'id';
    protected $_columns = array(
        'id' => 'id',
        'company_code' => 'company_code',
        'name' => 'name',
        'email' => 'email',
        'phone' => 'phone',
        'username' => 'username',
        'password' => 'password',
        'group_id' => 'group_id',
    );

    public function getAll(){
        $select = $this->select()->from($this->_name);
        return $this->fetchAll($select)->toArray();
    }

    public function listData($limit = false, $offset, $search = '', $sortColumn, $order, $isCount = false)
    {
        $query = $this->select()->setIntegrityCheck(FALSE)
            ->from( array( 'user' => $this->_tableName ), array('*') )
            ->join( array( 'company' => 'companies' ), 'company.register_code = user.company_code', array('company.name as company'))
            ->joinLeft( array( 'group' => 'user_groups' ), 'group.id = user.group_id', array('group.name as group'))
        ;

        if($search != ''){
            $query->where("user.username LIKE '%$search%' ")
                    ->orWhere("user.name LIKE '%$search%' ")
                    ->orWhere("company.name LIKE '%$search%' ")
                    ->orWhere("group.name LIKE '%$search%' ")
            ;
        }

        if(($order == 'asc') || ($order == 'undefined')){
           switch($sortColumn){
               case 0 : $string = 'user.name ASC'; break;
               case 1 : $string = 'company.name ASC'; break;
               case 2 : $string = 'group.name ASC'; break;
            }
        }else if($order = 'desc'){
           switch($sortColumn){
               case 0 : $string = 'user.name DESC'; break;
               case 1 : $string = 'company.name DESC'; break;
               case 2 : $string = 'group.name DESC'; break;
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