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
 * @subpackage Model
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Model_Product extends Mage_Core_Model_Abstract
{
    /**
     * Init Model for News
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('newsblock/product');
    }

    /**
     * Load model by multiply fields
     *
     * @param int $itemId
     * @param int $productId
     * @return Oggetto_Newsblock_Model_Product
     */
    public function loadByMultiple($itemId, $productId)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('item_id', $itemId)
            ->addFieldToFilter('product_id', $productId);
        if ($collection->count() > 0) {
            return $collection->getFirstItem();
        }
        return $this->setItemId($itemId)->setProductId($productId);
    }

    /**
     * Return collection of products for current news
     *
     * @param Oggetto_Newsblock_Model_Item $newsItem
     * @return Oggetto_Newsblock_Model_Resource_Product_Collection
     */
    public function getProducts(Oggetto_Newsblock_Model_Item $newsItem)
    {
        $collection = $this->getCollection()
            ->addFieldToFilter('item_id', $newsItem->getId());
        return $collection;
    }


    /**
     * Returned collection of related products
     *
     * @return Oggetto_Newsblock_Model_Resource_Product_Collection
     */
    public function getProductCollection(Oggetto_Newsblock_Model_Item $newsItem) {
        $productsIds = $this->getProducts($newsItem)->getColumnValues('product_id');
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addFieldToFilter('entity_id', ['in' => $productsIds])
            ->addAttributeToSelect('*');
        return $collection;
    }
}
