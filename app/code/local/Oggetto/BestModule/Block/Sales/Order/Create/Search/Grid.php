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
 * the Oggetto BestModule module to newer versions in the future.
 * If you wish to customize the Oggetto BestModule module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_BestModule
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto BestModule
 *
 * @category   Oggetto
 * @package    Oggetto_BestModule
 * @subpackage Block
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_BestModule_Block_Sales_Order_Create_Search_Grid
    extends Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid
{
    public function _construct()
    {
        parent::_construct();
        $this->setId('ogetto_bestmodule_adminhtml_block_sales_order_create_search_grid');
    }

    /**
     * Prepare columns
     *
     * @return Mage_Adminhtml_Block_Sales_Order_Create_Search_Grid
     */
    protected function _prepareColumns()
    {
        parent::_prepareColumns();
        $attribute = Mage::getModel('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'is_best');
        $options = $attribute->getSource()->getAllOptions(false);
        $values = [];
        foreach ($options as $option){
            $values[$option['value']] = $option['label'];
        }

        $this->addColumnAfter(
            'is_best',
            [
                'header'    => Mage::helper('sales')->__('Is Best'),
                'width'     => '40',
                'index'     => 'is_best',
                'type'      => 'options',
                'options'   => $values
            ],
            'name');

        return Mage_Adminhtml_Block_Widget_Grid::_prepareColumns();
    }
}
