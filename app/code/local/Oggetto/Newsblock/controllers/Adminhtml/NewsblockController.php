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


    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        $id = $this->getRequest()->getParam('item_id');
        Mage::register(
            'newsblock_item',
            Mage::getModel('newsblock/item')->load($id)
        );
        $blockObject = (array)Mage::getSingleton('adminhtml/session')
            ->getBlockObject(true);

        if(count($blockObject)) {
            Mage::registry('newsblock_item')->setData($blockObject);
        }
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock(
            'newsblock/adminhtml_newsblock_edit')
        );
        $this->renderLayout();
    }

    public function saveAction()
    {
        try {
            $id = $this->getRequest()->getParam('item_id');
            $currentTime = Mage::app()->getLocale()->date();
            $block = Mage::getModel('newsblock/item')->load($id);
            if ((!$block->getId() || $block->isObjectNew()) && !$block->getCreatedAt()) {
                $block->setCreatedAt($currentTime);
            }
            $block
                ->setData($this->getRequest()->getParams())
                ->setUpdatedAt($currentTime)
                ->save();

            if(!$block->getId()) {
                Mage::getSingleton('adminhtml/session')
                    ->addError('Cannot save the news');
            }
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')
                ->setBlockObject($block->getData());
            return  $this->_redirect(
                '*/*/edit',
                array('item_id'=>$this->getRequest()->getParam('item_id'))
            );
        }

        Mage::getSingleton('adminhtml/session')
            ->addSuccess('News was saved successfully!');

        return $this->_redirect(
            '*/*/'.$this->getRequest()->getParam('back','index'),
            array('item_id'=>$block->getId())
        );
    }

    public function deleteAction()
    {
        $block = Mage::getModel('newsblock/item')
            ->setId($this->getRequest()->getParam('item_id'))
            ->delete();
        if($block->getId()) {
            Mage::getSingleton('adminhtml/session')
                ->addSuccess('News was deleted successfully!');
        }
        $this->_redirect('*/*/');

    }

    public function massStatusAction()
    {
        $statuses = $this->getRequest()->getParams();
        try {
            $blocks= Mage::getModel('newsblock/item')
                ->getCollection()
                ->addFieldToFilter('item_id',array('in'=>$statuses['massaction']));
            foreach($blocks as $block) {
                $block->setItemStatus($statuses['item_status'])->save();
            }
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('News were updated!');

        return $this->_redirect('*/*/');

    }

    public function massDeleteAction()
    {
        $blocks = $this->getRequest()->getParams();
        try {
            $blocks= Mage::getModel('newsblock/item')
                ->getCollection()
                ->addFieldToFilter('item_id',array('in'=>$blocks['massaction']));
            foreach($blocks as $block) {
                $block->delete();
            }
        } catch(Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('Blocks were deleted!');

        return $this->_redirect('*/*/');

    }
}
