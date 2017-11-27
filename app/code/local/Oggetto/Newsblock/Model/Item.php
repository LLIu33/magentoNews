<?php
class Oggetto_Newsblock_Model_Item extends Mage_Core_Model_Abstract
{
    /**
     * Init Model for News
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('newsblock/item');
    }
}
