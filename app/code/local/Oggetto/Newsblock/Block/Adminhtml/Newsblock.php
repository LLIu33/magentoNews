<?php

class Oggetto_Newsblock_Block_Adminhtml_Newsblock
    extends Mage_Adminhtml_Block_Widget_Grid_Container
{

    public function _construct()
    {
        $this->_controller = 'adminhtml_newsblock';
        $this->_blockGroup = 'newsblock';
        $this->_headerText = Mage::helper('newsblock')->__('News');
        $this->_addButtonLabel = Mage::helper('newsblock')->__('Add News');
        parent::_construct();
    }

}

