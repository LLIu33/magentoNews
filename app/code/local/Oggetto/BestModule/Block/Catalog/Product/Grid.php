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
class Oggetto_BestModule_Block_Catalog_Product_Grid
    extends Mage_Adminhtml_Block_Catalog_Product_Grid
{
    /**
     * Prepare columns
     *
     * @return Mage_Adminhtml_Block_Catalog_Product_Grid
     */
    protected function _prepareColumns()
    {
        $values = Mage::getModel('Oggetto_BestModule_Model_Attribute_Source_Option')->toArray();
        $this->addColumnAfter(
            'is_best',
            [
                'header' => Mage::helper('bestmodule')->__('Is Best'),
                'index'  => 'is_best',
                'type'   => 'options',
                'options' => $values,
            ],
            'entity_id'
        );
        return parent::_prepareColumns();
    }

    /**
     * Modifying setter collection
     *
     * @param Varien_Data_Collection $collection
     * @return void
     */
    public function setCollection($collection)
    {
        $collection->addAttributeToSelect('is_best');
        parent::setCollection($collection);
    }
}
