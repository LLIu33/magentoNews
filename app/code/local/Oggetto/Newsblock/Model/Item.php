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

    /**
     * Serialize product data before save
     *
     * @return Mage_Core_Model_Abstract|void
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        if (is_array($this->getData('products'))) {
            $this->setData('products', json_encode($this->getData('products')));
        }
    }

    /**
     * Deserialize product data after load
     *
     * @return Mage_Core_Model_Abstract|void
     */
    protected function _afterLoad()
    {
        parent::_beforeSave();
        if (!empty($this->getData('products'))) {
            $this->setData('products', (array)json_decode($this->getData('products')));
        }
    }

    /**
     * Deserialize product data
     *
     * @return mixed
     */
    protected function _getProducts()
    {
        if (!is_array($this->getData('products'))) {
            $this->setData('products', (array)json_decode($this->getData('products')));
        }
        return $this->getData('products');
    }

    public function getProductCollection() {
        $products = $this->_getProducts();
        asort($products);
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addFieldToFilter('entity_id', ['in' => array_keys($products)])
            ->addAttributeToSelect('*');
        return $collection;
    }
}
