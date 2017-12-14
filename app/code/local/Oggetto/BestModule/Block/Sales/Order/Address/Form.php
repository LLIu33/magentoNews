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
class Oggetto_BestModule_Block_Sales_Order_Address_Form
    extends Mage_Adminhtml_Block_Sales_Order_Address_Form
{
    /**
     * Prepare Form and add elements to form
     *
     * @return Mage_Adminhtml_Block_Sales_Order_Create_Form_Address
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        $fieldset = $this->_form->getElement('main');

        $fieldset->addField('delivery_instruction', 'text', [
            'name'      => 'delivery_instruction',
            'label'     => Mage::helper('bestmodule')->__('Delivery Time'),
            'title'     => Mage::helper('bestmodule')->__('Delivery Time'),
        ]);
        $this->_form->setValues($this->getFormValues());
        return $this;
    }
}
