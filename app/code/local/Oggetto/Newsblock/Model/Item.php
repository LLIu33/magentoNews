<?php
class Oggetto_Newsblock_Model_Item extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('newsblock/item');
    }
}
