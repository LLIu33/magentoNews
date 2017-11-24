<?php
class Oggetto_Newsblock_Item_List extends Mage_Core_Block_Template
{
    public function getBlocks()
    {
        return Mage::getModel('newsblock/item')->getCollection()
            ->addFieldToFilter(
                'item_status',
                array('eq' => Oggetto_Newsblock_Model_Source_Status::ENABLED)
            );
    }
}
