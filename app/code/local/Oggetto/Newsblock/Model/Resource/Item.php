<?php
class Oggetto_Newsblock_Model_Resource_Item extends Mage_Core_Model_Resource_Db_Abstract
{
    public function _construct()
    {
        $this->_init('newsblock/item', 'item_id');
    }
}
