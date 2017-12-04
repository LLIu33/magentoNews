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
 * Oggetto Newsblock
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @subpackage Widget_Grid
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit_Tab_Products_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{

    /**
     * @var Oggetto_Newsblock_Model_Item
     */
    protected $_newsItem;

    /**
     * Set grid params
     *
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('newsblock_product_grid');
        $this->setDefaultSort('entity_id');
        $this->setUseAjax(true);
        if ($this->_getNewsItem()->getId()) {
            $this->setDefaultFilter(['in_products' => 1]);
        }
    }

    /**
     * Return current News
     *
     * @return Mage_Core_Model_Abstract
     * @throws Exception
     */
    protected function _getNewsItem()
    {
        if (!$this->_newsItem) {
            $this->_newsItem = Mage::getModel('newsblock/item')->load($this->getRequest()->getParam('item_id'));
        }
        return $this->_newsItem;
    }

    /**
     * Add filter
     *
     * @param object $column
     * @return $this
     */
    protected function _addColumnFilterToCollection($column)
    {
        if ($column->getId() !== 'in_products') {
            parent::_addColumnFilterToCollection($column);
            return $this;
        }

        $productIds = $this->_getSelectedProducts();
        $productIds = $productIds ?: 0;
        $operator = $column->getFilter()->getValue() ? 'in' : 'nin';
        $this->getCollection()->addFieldToFilter('entity_id', [$operator => $productIds]);
        return $this;
    }

    /**
     * Preparing collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('catalog/product_collection')
            ->addAttributeToSelect('*');

        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Add columns to grid
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addColumn('in_products', [
            'header_css_class' => 'a-center',
            'type'      => 'checkbox',
            'name'      => 'in_products',
            'values'    => $this->_getSelectedProducts(),
            'align'     => 'center',
            'index'     => 'entity_id'
        ]);

        $this->addColumn('entity_id', [
            'header'    => Mage::helper('catalog')->__('ID'),
            'sortable'  => true,
            'width'     => 60,
            'index'     => 'entity_id'
        ]);
        $this->addColumn('name', [
            'header'    => Mage::helper('catalog')->__('Name'),
            'index'     => 'name'
        ]);

        $this->addColumn('type', [
            'header'    => Mage::helper('catalog')->__('Type'),
            'width'     => 100,
            'index'     => 'type_id',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_type')->getOptionArray(),
        ]);

        $sets = Mage::getResourceModel('eav/entity_attribute_set_collection')
            ->setEntityTypeFilter(Mage::getModel('catalog/product')->getResource()->getTypeId())
            ->load()
            ->toOptionHash();

        $this->addColumn('set_name', [
            'header'    => Mage::helper('catalog')->__('Attrib. Set Name'),
            'width'     => 130,
            'index'     => 'attribute_set_id',
            'type'      => 'options',
            'options'   => $sets,
        ]);

        $this->addColumn('status', [
            'header'    => Mage::helper('catalog')->__('Status'),
            'width'     => 90,
            'index'     => 'status',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_status')->getOptionArray(),
        ]);

        $this->addColumn('visibility', [
            'header'    => Mage::helper('catalog')->__('Visibility'),
            'width'     => 90,
            'index'     => 'visibility',
            'type'      => 'options',
            'options'   => Mage::getSingleton('catalog/product_visibility')->getOptionArray(),
        ]);

        $this->addColumn('sku', [
            'header'    => Mage::helper('catalog')->__('SKU'),
            'width'     => 80,
            'index'     => 'sku'
        ]);

        $this->addColumn('price', [
            'header'        => Mage::helper('catalog')->__('Price'),
            'type'          => 'currency',
            'currency_code' => (string) Mage::getStoreConfig(Mage_Directory_Model_Currency::XML_PATH_CURRENCY_BASE),
            'index'         => 'price'
        ]);

        $this->addColumn('position', [
            'header'            => Mage::helper('catalog')->__('Position'),
            'name'              => 'position',
            'type'              => 'number',
            'width'             => 60,
            'validate_class'    => 'validate-number',
            'index'             => 'position',
            'editable'          => true
        ]);
        return parent::_prepareColumns();
    }

    /**
     * Return url for grid
     *
     * @return mixed|string
     */
    public function getGridUrl()
    {
        $grid = $this->_getData('grid_url');
        return $grid ?: $this->getUrl('*/*/productsgrid', ['_current' => true]);
    }

    /**
     * Return selected products
     *
     * @return array
     */
    protected function _getSelectedProducts()
    {
        return array_keys($this->getSelectedBlockProducts());
    }

    /**
     * Return selected products
     *
     * @return array
     * @throws Exception
     */
    public function getSelectedBlockProducts()
    {
        $selected = $this->getRequest()->getParam('newsblock_products');
        $productIds = [];
        $productsDataCollection = Mage::getResourceModel('newsblock/product')->getProductRelatives($this->_newsItem);
        foreach ($productsDataCollection as $product) {
            $productIds[$product->getProductId()] = ['position' => $product->getPosition()];
        }
        foreach ($selected as $product) {
            if (!isset($productIds[$product])) {
                $productIds[$product] = ['position' => $product];
            }
        }
        return $productIds;
    }
}
