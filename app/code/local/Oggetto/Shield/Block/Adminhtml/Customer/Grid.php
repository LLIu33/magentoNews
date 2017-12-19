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
 * the Oggetto Shield module to newer versions in the future.
 * If you wish to customize the Oggetto Shield module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Shield
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto Shield
 *
 * @category   Oggetto
 * @package    Oggetto_Shield
 * @subpackage Block
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Shield_Block_Adminhtml_Customer_Grid extends
    Mage_Adminhtml_Block_Customer_Grid {

    /**
     * Modifying setter collection
     *
     * @param Varien_Data_Collection $collection
     * @return void
     */
    public function setCollection($collection) {
        $collection->addAttributeToSelect('customer_active');
        parent::setCollection($collection);
    }

    /**
     * Preparing collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns() {
        $this->addColumnAfter('customer_active', [
            'header' => Mage::helper('customer')->__('Is active'),
            'type' => 'options',
            'index' => 'customer_active',
            'options' => [
                '0' => 'Blocked',
                '1' => 'Enabled'
            ]
        ], 'name');

        $this->addColumnAfter('unblock',
            [
                'header'    =>  Mage::helper('customer')->__('Action'),
                'width'     => '100',
                'type'      => 'action',
                'getter'    => 'getId',
                'actions'   => [
                    [
                        'caption'   => Mage::helper('shield')->__('Unblock'),
                        'url'       => ['base'=> 'shield/adminhtml_unblock'],
                        'field'     => 'id'
                    ]
                ],
                'filter'    => false,
                'sortable'  => false,
                'index'     => 'stores',
                'is_system' => true,
            ], 'action');
        return parent::_prepareColumns();
    }
//
//    protected function _prepareLayout()
//    {
//        $this->_addButton('add_new', array(
//            'label'   => Mage::helper('customer')->__('Unblock'),
//            'onclick' => "setLocation('{$this->getUrl('*/*/unblock')}')",
//            'class'   => 'add'
//        ));
//
////        $this->setChild('grid', $this->getLayout()->createBlock('adminhtml/catalog_product_grid', 'product.grid'));
//        return parent::_prepareLayout();
//    }
}
