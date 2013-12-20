<?php
/**
 * Created by PhpStorm.
 * User: Sangkuriang
 * Date: 12/20/13
 * Time: 1:19 PM
 */

class Default_Model_DbTable_NetworkClients extends Zend_Db_Table_Abstract
{
    protected $_name = 'public.NetworkClients';
    protected $_tableName = 'public.NetworkClients';
    protected $_primary = 'MacAddress';


}