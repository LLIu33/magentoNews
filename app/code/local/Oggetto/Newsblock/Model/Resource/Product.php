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
 * @subpackage Model_Resource
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Model_Resource_Product extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Init Resource Model for News_Product
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('newsblock/product', 'item_product_id');
    }

    /**
     * Insert multiply items
     *
     * @param array $relatedProducts
     * @return void
     */
    public function insertMultiple(array $relatedProducts)
    {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $write->insertMultiple($this->getMainTable(), $relatedProducts);
    }

    /**
     * Delete Product Relatives by news
     *
     * @param int $newsId
     * @return void
     */
    public function deleteByNewsId(int $newsId)
    {
        $write = Mage::getSingleton('core/resource')->getConnection('core_write');
        $write->delete($this->getMainTable(), ['item_id', $newsId]);
    }

    /**
     * Return collection of products for current news
     *
     * @param Oggetto_Newsblock_Model_Item $newsItem
     * @return Oggetto_Newsblock_Model_Resource_Product_Collection
     */
    public function getProductRelatives(Oggetto_Newsblock_Model_Item $newsItem)
    {
        $collection = Mage::getResourceModel('newsblock/product_collection')
            ->addFieldToFilter('item_id', $newsItem->getId());
        return $collection;
    }

    /**
     * Returned collection of related products
     *
     * @param Oggetto_Newsblock_Model_Item $newsItem
     * @return Oggetto_Newsblock_Model_Resource_Product_Collection
     */
    public function getProductCollection(Oggetto_Newsblock_Model_Item $newsItem)
    {
        $relativeTable = $this->getMainTable();
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->joinTable(
                array('rel_table' => $relativeTable),
                'product_id = entity_id',
                array('rel_table.item_id' => 'item_id', 'rel_table.position' => 'position')
            )
            ->addFieldToFilter('rel_table.item_id', $newsItem->getId())
            ->setOrder('rel_table.position', 'ASC');

        Mage::getSingleton('catalog/layer')->prepareProductCollection($collection);
        return $collection;
    }
}
