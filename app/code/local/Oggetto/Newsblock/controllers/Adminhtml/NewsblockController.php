<?php
class Oggetto_Newsblock_Adminhtml_NewsblockController
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Render Grid for News
     *
     * @return void
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->_addContent(
            $this->getLayout()->createBlock('newsblock/adminhtml_newsblock')
        );
        $this->renderLayout();
    }

    /**
     * Add News
     *
     * @return void
     */

    public function newAction()
    {
        $this->_forward('edit');
    }

    /**
     * Edit News
     *
     * @return void
     */

    public function editAction()
    {
        $id = $this->getRequest()->getParam('item_id');
        Mage::register(
            'newsblock_item',
            Mage::getModel('newsblock/item')->load($id)
        );
        $blockObject = (array)Mage::getSingleton('adminhtml/session')
            ->getBlockObject(true);

        if (count($blockObject)) {
            Mage::registry('newsblock_item')->setData($blockObject);
        }
        $this->loadLayout();
        $this->_addContent($this->getLayout()->createBlock(
            'newsblock/adminhtml_newsblock_edit')
        );
        $this->renderLayout();
    }

    public function removeFile($file)
    {
        $_helper = Mage::helper('newsblock');
        $file = $_helper->updateDirSepereator($file);
        $directory = Mage::getBaseDir('media') . DS .'Newsblock' ;
        $io = new Varien_Io_File();
        $result = $io->rmdir($directory, true);
    }

    /**
     * Save changes for News
     *
     * @return Mage_Core_Controller_Varien_Action
     *
     */
    public function saveAction()
    {
        try {
            $id = $this->getRequest()->getParam('item_id');
            $currentTime = Mage::app()->getLocale()->date();
            $block = Mage::getModel('newsblock/item')->load($id);
            $post_data = $this->getRequest()->getParams();
            if ((!$block->getId() || $block->isObjectNew()) && !$block->getCreatedAt()) {
                $block->setCreatedAt($currentTime);
            }

            if (!$block->getId()) {
                Mage::getSingleton('adminhtml/session')
                    ->addError('Cannot save the news');
            }

            if (!empty($_FILES['image']['name'] ))
            {
//                $block['image'] = $_FILES['image']['name'];
                if ($this->getRequest()->getParam("id")) {
                    if ($block->getData('image')) {
                        $io = new Varien_Io_File();
                        $io->rm(
                            Mage::getBaseDir('media') . DS . implode(DS, explode('/', $model->getData('image'))));
                    }
                }

                $path = Mage::getBaseDir('media') . DS . 'Newsblock' . DS;
                $uploader = new Varien_File_Uploader('image');
                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $destFile = $path . $_FILES['image']['name'];
                $filename = $uploader->getNewFileName($destFile);
                $uploader->save($path, $filename);

                $post_data['image'] = 'Newsblock' . DS . $filename;

//                $mediaAttribute = array (
//                    'image',
//                    'thumbnail',
//                    'small_image'
//                );
            }
            else
            {
                if (isset($post_data['image']['delete']) && $post_data['image']['delete'] == 1)
                {
                    if ($post_data['image']['value'] != '')
                        $this->removeFile($post_data['image']['value']);
                    $post_data['image'] = '';
                }
                else
                {
                    unset($post_data['image']);
                }
            }

            $block
                ->setData($post_data)
                ->setUpdatedAt($currentTime)
                ->save();

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')
                ->setBlockObject($block->getData());
            return  $this->_redirect(
                '*/*/edit',
                array('item_id' => $this->getRequest()->getParam('item_id'))
            );
        }

        Mage::getSingleton('adminhtml/session')
            ->addSuccess('News was saved successfully!');

        return $this->_redirect(
            '*/*/' . $this->getRequest()->getParam('back', 'index'),
            array('item_id' => $block->getId())
        );
    }

    /**
     * Delete News
     *
     * @return Mage_Core_Controller_Varien_Action
     *
     */

    public function deleteAction()
    {
        $block = Mage::getModel('newsblock/item')
            ->setId($this->getRequest()->getParam('item_id'))
            ->delete();
        if ($block->getId()) {
            Mage::getSingleton('adminhtml/session')
                ->addSuccess('News was deleted successfully!');
        }
        return $this->_redirect('*/*/');
    }

    /**
     * Change status for many News
     *
     * @return Mage_Core_Controller_Varien_Action
     *
     */

    public function massStatusAction()
    {
        $statuses = $this->getRequest()->getParams();
        try {
            $blocks = Mage::getModel('newsblock/item')
                ->getCollection()
                ->addFieldToFilter('item_id', array('in' => $statuses['massaction']));
            foreach ($blocks as $block) {
                $block->setItemStatus($statuses['item_status'])->save();
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('News were updated!');

        return $this->_redirect('*/*/');
    }

    /**
     * Delete many News
     *
     * @return Mage_Core_Controller_Varien_Action
     *
     */

    public function massDeleteAction()
    {
        $blocks = $this->getRequest()->getParams();
        try {
            $blocks = Mage::getModel('newsblock/item')
                ->getCollection()
                ->addFieldToFilter('item_id', array('in' => $blocks['massaction']));
            foreach ($blocks as $block) {
                $block->delete();
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')->addSuccess('Blocks were deleted!');

        return $this->_redirect('*/*/');
    }
}
