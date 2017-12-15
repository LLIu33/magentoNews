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
 * @subpackage Model
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Shield_Model_Observer extends Varien_Event_Observer
{
    /**
     * Counts the number of failed logins of a current users.
     * Validate the controller postDispatch action of customer_account_loginPost.
     *
     * If user is not logged in at this point in time but login parameters given,
     * the user had wrong credentials -> so increment the number of failed logins.
     * If otherwise the user is logged in successfully, we can forget about
     * previous login failures.
     *
     * Observer for controller_action_postdispatch_customer_account_loginPost event
     *
     * @param Varien_Event_Observer $observer Observer Instance
     */
    public function countFailedLogins(Varien_Event_Observer $observer)
    {
//        if (!$this->_getSession()->isLoggedIn()) {
//            $loginParams = $observer->getControllerAction()->getRequest()->getParam('login');
//            if (isset($loginParams) && isset($loginParams['username'])) {
//
//                $validator = new Zend_Validate_EmailAddress();
//
//                if ($validator->isValid($loginParams['username'])) {
//
//                    // Load Customer
//                    $customer = Mage::getModel('customer/customer')
//                        ->setWebsiteId($this->_getWebsiteId())
//                        ->loadByEmail($loginParams['username']);
//
//                    // If customer exists, set new values..
//                    if ($customer->getId()) {
//                        $attempts    = $customer->getData('customer_logins_failed');
//                        $lastAttempt = $customer->getData('customer_last_login_failed');
//                        $now         = time();
//
//                        if (!is_numeric($attempts)) {
//                            $attempts = 1;
//                        } else {
//                            if ($now - $lastAttempt > Mage::getStoreConfig(self::XML_PATH_LOGIN_ATTEMPT_SPAN)) {
//                                $attempts = 0;
//                            }
//                            $attempts++;
//                        }
//
//                        $customer->setData('customer_logins_failed', $attempts)
//                            ->setData('customer_last_login_failed', $now)
//                            ->save();
//                    }
//                }
//            }
//        } else {
//            $customer = $this->_getSession()->getCustomer();
//            $customer->setData('customer_logins_failed', 0)
//                ->setData('customer_last_login_failed', 0)
//                ->save();
//        }
    }

    /**
     * Validates the customer before logging him in. A customer can be set to active/inactive
     * and he can be inactivated if user tries to login with wrong user credentials.
     *
     * Observer for controller_action_predispatch_customer_account_loginPost event.
     *
     * @param Varien_Event_Observer $observer Observer Instance
     */
    public function validateCustomerActivationBeforeLogin(Varien_Event_Observer $observer)
    {
//        $controller = $observer->getControllerAction();
//
//        try {
//            $loginParams = $controller->getRequest()->getParam('login');
//            if (isset($loginParams['username'])) {
//                $validator = new Zend_Validate_EmailAddress();
//
//                if ($validator->isValid($loginParams['username'])) {
//
//                    // Load Customer
//                    $customer = Mage::getModel('customer/customer')
//                        ->setWebsiteId($this->_getWebsiteId())
//                        ->loadByEmail($loginParams['username']);
//
//                    // If user doesn't exist, throw exception
//                    if (!$customer->getId()) {
//                        throw new Exception(
//                            $this->_getHelper()->__('Login failed.')
//                        );
//                    }
//
//                    // check if customer given is valid and active
//                    $this->_validateCustomerActivationStatus($customer);
//                } else { // if ($validator->isValid($loginParams['username']))
//                    throw new Exception(
//                        $this->_getHelper()->__('The email address you entered is invalid.')
//                    );
//                }
//            }
//        } catch (Exception $e) {
//            $this->_getSession()->addError($this->_getHelper()->__($e->getMessage()));
//
//            // Set no-dispatch flag
//            $controller->setFlag(
//                $controller->getRequest()->getActionName(),
//                Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH,
//                true
//            );

            // Redirect to login page
//            Mage::helper('shield/redirect')->_loginPostRedirect();
//        }
    }
}