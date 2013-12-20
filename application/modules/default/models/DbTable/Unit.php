<?php
class Default_Model_DbTable_Unit extends Admin_Library_Model_DbTable
{
    protected $_name = 'ref_units';
    protected $_tableName = 'ref_units';
    protected $_primary = 'id';
    protected $_columns = array(
        'id' => 'id',
        'term' => 'term',
        'description' => 'description',
    );

    public function getAll($fields = null)
    {
        if(empty($fields)) $fields = array('*');

        $select = $this->select()->from($this->_name, $fields);
        return $this->fetchAll($select)->toArray();
    }
}