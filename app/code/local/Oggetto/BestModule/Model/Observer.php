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
 * @subpackage Model
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_BestModule_Model_Observer extends Varien_Event_Observer
{
    /**
     * Save the order after having saved the payment method
     *
     * @param Varien_Event_Observer $observer
     * @event controller_action_postdispatch_checkout_onepage_savePayment
     * @return void
     */
    public function saveOrder(Varien_Event_Observer $observer)
    {
        /** @var $controllerAction Mage_Checkout_OnepageController */
        $controllerAction = $observer->getEvent()->getControllerAction();
        /** @var $response Mage_Core_Controller_Response_Http */
        $response = $controllerAction->getResponse();

        $paymentResponse = Mage::helper('core')->jsonDecode($response->getBody());
        if (!isset($paymentResponse['error']) || !$paymentResponse['error']) {
            $controllerAction->getRequest()
                ->setParam('form_key', Mage::getSingleton('core/session')->getFormKey());
            $controllerAction->getRequest()
                ->setPost('agreement', array_flip(Mage::helper('checkout')->getRequiredAgreementIds()));
            $controllerAction->saveOrderAction();

            $orderResponse = Mage::helper('core')->jsonDecode($response->getBody());
            if ($orderResponse['error'] === false && $orderResponse['success'] === true) {
                if (!isset($orderResponse['redirect']) || !$orderResponse['redirect']) {
                    $orderResponse['redirect'] = Mage::getUrl('*/*/success');
                }
                $controllerAction->getResponse()->setBody(Mage::helper('core')->jsonEncode($orderResponse));
            }
        }
    }

    /**
     * Add is_best column to product grid
     *
     * @param Varien_Event_Observer $observer
     * @event core_block_abstract_to_html_before
     * @return void
     */
    public function beforeBlockToHtml(Varien_Event_Observer $observer)
    {
        $grid = $observer->getBlock();

        $values = Mage::getModel('Oggetto_BestModule_Model_Attribute_Source_Option')->toArray();

        if ($grid instanceof Mage_Adminhtml_Block_Catalog_Product_Grid) {
            /* @var Mage_Adminhtml_Block_Catalog_Product_Grid $grid */
            $grid->addColumnAfter(
                'is_best',
                [
                    'header' => Mage::helper('bestmodule')->__('Is Best'),
                    'index'  => 'is_best',
                    'type'   => 'options',
                    'options' => $values,
                ],
                'entity_id'
            );
        }
    }

    /**
     * Add is_best data to product collection
     *
     * @param Varien_Event_Observer $observer
     * @event eav_collection_abstract_load_before
     * @return void
     */
    public function beforeCollectionLoad(Varien_Event_Observer $observer)
    {
        $collection = $observer->getCollection();
        if (!isset($collection)) {
            return;
        }

        /**
         * Mage_Customer_Model_Resource_Customer_Collection
         */
        if ($collection instanceof Mage_Catalog_Model_Resource_Product_Collection) {
            /* @var $collection Mage_Catalog_Model_Resource_Product_Collection */
            $collection->addAttributeToSelect('is_best');
        }
    }
}
