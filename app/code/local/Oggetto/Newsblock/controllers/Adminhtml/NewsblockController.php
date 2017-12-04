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
 * the Oggetto Newsblock module to newer versions in the future.
 * If you wish to customize the Oggetto Newsblock module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto Newsblock
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @subpackage NewsblockController
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
        $this->renderLayout();
    }

    /**
     * Render Products
     *
     * @return void
     */
    public function productsAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Render Grid for Products
     *
     * @return void
     */
    public function productsgridAction()
    {
        $this->loadLayout();
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
        $newsObject = (array)Mage::getSingleton('adminhtml/session')
            ->getBlockObject(true);

        if (count($newsObject)) {
            Mage::registry('newsblock_item')->setData($newsObject);
        }
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Save or delete news image
     *
     * @param array $data
     * @return array
     */
    protected function processImage(array $data)
    {
        $imageFolder = 'newsblock';
        if (!empty($_FILES['image']['name'] )) {
            $path = Mage::getBaseDir('media') . DS . $imageFolder . DS;
            $uploader = Mage::getModel('core/file_uploader', 'image');
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'png', 'gif']);
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $filename = $uploader->getNewFileName($path . $_FILES['image']['name']);
            $uploader->save($path, $filename);
            $data['image'] = $imageFolder . DS . $filename;
        } else {
            if (isset($data['image']['delete']) && $data['image']['delete'] == true) {
                if ($data['image']['value']) {
                    unlink(Mage::getBaseDir('media') . DS . $data['image']['value']);
                }
                $data['image'] = '';
            } else {
                unset($data['image']);
            }
        }
        return $data;
    }

    /**
     * Add/update related products
     *
     * @param int $itemId
     * @param int $productId
     * @param int $position
     * @return void
     */
    protected function _saveRelatedProduct($itemId, $productId, $position)
    {
        try {
            $relatedProduct = Mage::getModel('newsblock/product')->loadByMultiple($itemId, $productId);
            $relatedProduct->setPosition($position);
            $relatedProduct->save();
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
    }

    /**
     * Delete unchecked related products
     *
     * @param Oggetto_Newsblock_Model_Item  $news
     * @param array                         $selectedProducts
     * @return void
     */
    protected function _cleanUpRelatedProducts($news, $selectedProducts)
    {
        try {
            $relatedProducts = Mage::getModel('newsblock/product')->getProducts($news);
            $relatedProducts->addFieldToFilter('product_id', ['nin' => array_keys($selectedProducts)]);
            foreach ($relatedProducts as $product) {
                $product->delete();
            }
        } catch (Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
        }
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
            $news = Mage::getModel('newsblock/item')->load($id);
            $data = $this->getRequest()->getParams();
            $data = $this->processImage($data);

            $links = $this->getRequest()->getPost('links', []);
            if (array_key_exists('products', $links)) {
                $selectedProducts = Mage::helper('adminhtml/js')->decodeGridSerializedInput($links['products']);
                foreach ($selectedProducts as $productId => $productData) {
                    $this->_saveRelatedProduct($id, $productId, $productData['position']);
                }
                $this->_cleanUpRelatedProducts($news, $selectedProducts);
            }

            $news->setData($data);
            if (!$news->getId()) {
                $news->setCreatedAt($currentTime);
            }
            $news->setUpdatedAt($currentTime);
            $news->save();

            if (!$news->getId()) {
                Mage::getSingleton('adminhtml/session')
                    ->addError(Mage::helper('newsblock')->__('Cannot save the news'));
            }

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')
                ->setBlockObject($news->getUserData());
            return  $this->_redirect(
                '*/*/edit',
                ['item_id' => $this->getRequest()->getParam('item_id')]
            );
        }

        Mage::getSingleton('adminhtml/session')
            ->addSuccess(Mage::helper('newsblock')->__('News has been successfully saved'));

        return $this->_redirect(
            '*/*/' . $this->getRequest()->getParam('back', 'index'),
            ['item_id' => $news->getId()]
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
        try {
            $news = Mage::getModel('newsblock/item')
                ->setId($this->getRequest()->getParam('item_id'))
                ->delete();
            if ($news->getId()) {
                Mage::getSingleton('adminhtml/session')
                    ->addSuccess(Mage::helper('newsblock')->__('News has been successfully deleted !'));
            }

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            Mage::getSingleton('adminhtml/session')
                ->setBlockObject($news->getUserData());
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
            $newss = Mage::getModel('newsblock/item')
                ->getCollection()
                ->addFieldToFilter('item_id', ['in' => $statuses['massaction']]);
            foreach ($newss as $news) {
                $news->setItemStatus($statuses['item_status'])->save();
            }
        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')
            ->addSuccess(Mage::helper('newsblock')->__('News has been updated!'));

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
        $newss = $this->getRequest()->getParams();
        try {
            $news = Mage::getModel('newsblock/item');
            foreach ($newss['massaction'] as $id) {
                $news->setId($id)->delete();
            }

        } catch (Exception $e) {
            Mage::logException($e);
            Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            return $this->_redirect('*/*/');
        }
        Mage::getSingleton('adminhtml/session')
            ->addSuccess(Mage::helper('newsblock')->__('News has been deleted!'));

        return $this->_redirect('*/*/');
    }
}
