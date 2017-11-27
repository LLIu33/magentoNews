<?php
/**
 * Oggetto Web extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the Oggetto Api module to newer versions in the future.
 * If you wish to customize the Oggetto Api module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto api model
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @subpackage Adminhtml_NewsblockController
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
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
            $postData = $this->getRequest()->getParams();


            if (!$block->getId()) {
                Mage::getSingleton('adminhtml/session')
                    ->addError('Cannot save the news');
            }

            if (!empty($_FILES['image']['name'] )) {
                $path = Mage::getBaseDir('media') . DS . 'Newsblock' . DS;
                $uploader = new Varien_File_Uploader('image');
                $uploader->setAllowedExtensions(array('jpg', 'png', 'gif'));
                $uploader->setAllowRenameFiles(false);
                $uploader->setFilesDispersion(false);
                $destFile = $path . $_FILES['image']['name'];
                $filename = $uploader->getNewFileName($destFile);
                $uploader->save($path, $filename);
                $postData['image'] = 'Newsblock' . DS . $filename;
            } else {
                if (isset($postData['image']['delete']) && $postData['image']['delete'] == 1) {
                    if ($postData['image']['value'] != '') {
                        unlink(Mage::getBaseDir('media') . DS . $postData['image']['value']);
                    }
                    $postData['image'] = '';
                } else {
                    unset($postData['image']);
                }
            }

            $block->setData($postData);

            if ((!$block->getId() || $block->isObjectNew()) && !$block->getCreatedAt()) {
                $block->setCreatedAt($currentTime);
            }
            $block->setUpdatedAt($currentTime);
            $block->save();

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
