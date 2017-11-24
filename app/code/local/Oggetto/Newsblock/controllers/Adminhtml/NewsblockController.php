<?php
class Oggetto_Newsblock_Adminhtml_NewsblockController
    extends Mage_Adminhtml_Controller_Action
{
    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('newsblock/adminhtml_newsblock')
        );
        $this->renderLayout();
    }
}
