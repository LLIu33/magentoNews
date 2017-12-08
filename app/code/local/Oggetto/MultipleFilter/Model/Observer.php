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
 * the Oggetto MultipleFilter module to newer versions in the future.
 * If you wish to customize the Oggetto MultipleFilter module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_MultipleFilter
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto MultipleFilter
 *
 * @category   Oggetto
 * @package    Oggetto_MultipleFilter
 * @subpackage Model
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_MultipleFilter_Model_Observer extends Varien_Event_Observer
{
    /**
     * Hook that allows us to edit the form that is used to create and/or edit attributes.
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    public function addFieldToAttributeEditForm($observer)
    {
        // Add an extra field to the base fieldset:
        $fieldset = $observer->getForm()->getElement('front_fieldset');
        $fieldset->addField(
            'is_multiple',
            'select',
            [
                'name' => 'is_multiple',
                'label' => Mage::helper('multichoice')->__('Is Multiple'),
                'title' => Mage::helper('multichoice')->__('Is Multiple'),
                'values' => [
                    [
                        'value' => 0,
                        'label' => Mage::helper('multichoice')->__('No')
                    ],
                    [
                        'value' => 1,
                        'label' => Mage::helper('multichoice')->__('Yes')
                    ]
                ]
            ]
        );
    }

    public function test($observer) {
        /** @var $layout Mage_Core_Model_Layout */
        $layout = $observer->getEvent()->getLayout();
//        var_dump($layout);die;
    }
}
