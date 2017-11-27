<?php
class Oggetto_Newsblock_Model_Resource_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Init Collection for News
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('newsblock/item');
    }
}
