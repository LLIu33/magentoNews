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
     * Set is_best for quoteItem
     *
     * @param Varien_Event_Observer $observer
     * @event sales_quote_item_set_product
     * @return void
     */
    public function salesQuoteItemSetIsBest(Varien_Event_Observer $observer)
    {
        $quoteItem = $observer->getQuoteItem();
        $product = $observer->getProduct();
        $quoteItem->setIsBest($product->getIsBest());
    }
}
