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
    const WRONG_PASSWORD_FLOW = 'admin/security/wrong_password_flow_secure';
    const LOGIN_ATTEMPTS_LIMIT_BY_IP = 'admin/security/requests_from_ip';
    const BLOCKING_PERIOD_FOR_IP = 'admin/security/blocking_period_for_ip';
    const LOGIN_ATTEMPTS_LIMIT_BY_EMAIL = 'admin/security/requests_from_email';
    const BLOCKING_PERIOD_FOR_EMAIL = 'admin/security/blocking_period_for_email';

    const FLOW_DISABLED = 0;
    const FLOW_BY_EMAIL_AND_IP = 1;
    const FLOW_BY_IP = 2;
    const FLOW_BY_EMAIL = 3;

    /**
     * Retrieve customer session model object
     *
     * @return Mage_Customer_Model_Session Customer Session
     */
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    /**
     * Retrieve the current website ID
     *
     * @return int Current Website ID
     */
    protected function _getWebsiteId()
    {
        return Mage::app()->getWebsite()->getId();
    }

    /**
     * Increment counter of incorrect password
     *
     * @param Mage_Customer_Model_Customer $customer
     * @return void
     */
    protected function _incrementAttemptsCounterByEmail($customer)
    {
        $attemptsLimit = Mage::getStoreConfig(self::LOGIN_ATTEMPTS_LIMIT_BY_EMAIL);
        $attempts = $customer->getData('customer_login_attempts');
        $blockedAt = null;
        $customerIsActive = true;

        if (!is_numeric($attempts)) {
            $attempts = 1;
        } else {
            $attempts++;
        }

        if ($attempts == $attemptsLimit) {
            $customerIsActive = false;
            $blockedAt = now();
        }

        if ($attempts > $attemptsLimit) {
            $attempts = 0;
            $customerIsActive = false;
        }

        $customer->setData('customer_login_attempts', $attempts)
            ->setData('is_blocked', $customerIsActive)
            ->setData('customer_blocked_at', $blockedAt)
            ->save();
    }

    /**
     * Count attempts for ip
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    protected function _countFailedLoginsByEmail($observer)
    {
        if ($this->_getSession()->isLoggedIn()) {
            $customer = $this->_getSession()->getCustomer();
            $customer->setData('customer_login_attempts', 0)
                ->save();
            return;
        }
        $loginParams = $observer->getControllerAction()->getRequest()->getParam('login');
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId($this->_getWebsiteId())
            ->loadByEmail($loginParams['username']);
        if ($customer->getId()) {
            $this->_incrementAttemptsCounterByEmail($customer);;
        }
    }

    /**
     * Count attempts for ip
     *
     * @param Varien_Event_Observer $observer
     * @return void
     */
    protected function _countFailedLoginsByIp($observer)
    {
        $attemptsLimit = Mage::getStoreConfig(self::LOGIN_ATTEMPTS_LIMIT_BY_IP);
        $remoteIp = Mage::helper('core/http')->getRemoteAddr();
        $ipData = Mage::getModel('shield/ip');
        $ipData->load($remoteIp, 'ip');
        if (!$ipData->getId()) {
            $ipData->setIp($remoteIp);
        }
        $blockedAt = null;
        $attempts = 0;

        if (!$this->_getSession()->isLoggedIn()) {
            if (is_numeric($ipData->getData('attempts'))) {
                $attempts = $ipData->getData('attempts');
            }
            $attempts++;

            if ($attempts >= $attemptsLimit) {
                $blockedAt = time();
            }
        }
        $ipData->setData('attempts', $attempts)
            ->setData('blocked_at', $blockedAt)
            ->save();
    }

    /**
     * Check blocking condition by email
     *
     * @param string $email
     * @return bool
     */
    protected function _isBlockedByEmail($email)
    {
        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId($this->_getWebsiteId())
            ->loadByEmail($email);
        if ($customer->getData('is_blocked') !== '0') {
            return false;
        }
        $now = time();
        $blockingPeriodSec = Mage::getStoreConfig(self::BLOCKING_PERIOD_FOR_EMAIL) * 60;
        $endBlockingTime = strtotime(date($customer->getData('customer_blocked_at'))) + $blockingPeriodSec;
        if ($customer->getData('customer_blocked_at') && $now > $endBlockingTime) {
            $customer->setData('is_blocked', true)
                ->setData('customer_blocked_at', null)
                ->save();
            return false;
        }
        return true;
    }

    /**
     * Check blocking condition by ip
     *
     * @param string $ip
     * @return bool
     */
    protected function _isBlockedByIp($ip)
    {
        $ipData = Mage::getModel('shield/ip');
        $ipData->load($ip, 'ip');
        if (!$ipData->getId() || !$ipData->getData('blocked_at')) {
            return false;
        }

        $now = time();
        $blockingPeriodSec = Mage::getStoreConfig(self::BLOCKING_PERIOD_FOR_IP) * 60;
        $startBlockingTime = strtotime(date($ipData->getData('blocked_at')));
        if ($now > $startBlockingTime + $blockingPeriodSec) {
            $ipData->setData('blocked_at', null)
                ->setData('attempts', 0)
                ->save();
            return false;
        }
        return true;
    }

    /**
     * Counts the number of failed attempts to change password of a current users.
     *
     * @param Varien_Event_Observer $observer Observer Instance
     * @return void
     */
    public function countFailedChangePassByEmail(Varien_Event_Observer $observer)
    {
        $wrongPasswordFlow = Mage::getStoreConfig(self::WRONG_PASSWORD_FLOW);
        if ($wrongPasswordFlow != self::FLOW_BY_EMAIL_AND_IP || $wrongPasswordFlow != self::FLOW_BY_EMAIL) {
            return;
        }

        $username = $observer->getControllerAction()->getRequest()->getParam('email');
        $password = $observer->getControllerAction()->getRequest()->getParam('current_password');
        try {
            Mage::getModel('customer/customer')
                ->setWebsiteId($this->_getWebsiteId())
                ->authenticate($username, $password);
            $isCorrectPassword = true;
        } catch (Exception $ex) {
            $isCorrectPassword = false;
        }

        $customer = Mage::getModel('customer/customer')
            ->setWebsiteId($this->_getWebsiteId())
            ->loadByEmail($username);

        if (!$isCorrectPassword && $customer->getId()) {
            $this->_incrementAttemptsCounterByEmail($customer);
        }
    }

    /**
     * Validates the customer before logging him in. A customer can be set to active/inactive
     *
     * @param Varien_Event_Observer $observer Observer Instance
     * @return void
     */
    public function validateCustomerActivationBeforeChangePass(Varien_Event_Observer $observer)
    {
        $wrongPasswordFlow = Mage::getStoreConfig(self::WRONG_PASSWORD_FLOW);
        if ($wrongPasswordFlow != self::FLOW_BY_EMAIL_AND_IP || $wrongPasswordFlow != self::FLOW_BY_EMAIL) {
            return;
        }
        $username = $observer->getControllerAction()->getRequest()->getParam('email');
        $isBlocked = $this->_isBlockedByEmail($username);
        try {
            if ($isBlocked) {
                throw new Exception(
                    Mage::helper('shield')->__('Your account is locked due to too many failed login attempts.')
                );
            }
        } catch (Exception $e) {
            $this->_getSession()->addError(Mage::helper('shield')->__($e->getMessage()));

            Mage::getSingleton('customer/session')->logout();
        }
    }

    /**
     * Counts the number of failed logins of a current users.
     *
     * @param Varien_Event_Observer $observer Observer Instance
     * @return void
     */
    public function countFailedLogins(Varien_Event_Observer $observer)
    {
        $wrongPasswordFlow = Mage::getStoreConfig(self::WRONG_PASSWORD_FLOW);

        if (!$wrongPasswordFlow) {
            return;
        }

        if ($wrongPasswordFlow == self::FLOW_BY_EMAIL_AND_IP || $wrongPasswordFlow == self::FLOW_BY_EMAIL) {
            $this->_countFailedLoginsByEmail($observer);
        }

        if ($wrongPasswordFlow == self::FLOW_BY_EMAIL_AND_IP || $wrongPasswordFlow == self::FLOW_BY_IP) {
            $this->_countFailedLoginsByIp($observer);
        }

    }

    /**
     * Validates the customer before logging him in. A customer can be set to active/inactive
     *
     * @param Varien_Event_Observer $observer Observer Instance
     * @return void
     */
    public function validateCustomerActivationBeforeLogin(Varien_Event_Observer $observer)
    {
        $wrongPasswordFlow = Mage::getStoreConfig(self::WRONG_PASSWORD_FLOW);
        if (!$wrongPasswordFlow) {
            return;
        }
        $controller = $observer->getControllerAction();
        $isBlocked = false;

        if ($wrongPasswordFlow == self::FLOW_BY_EMAIL_AND_IP || $wrongPasswordFlow == self::FLOW_BY_EMAIL) {
            $loginParams = $controller->getRequest()->getParam('login');
            if (!isset($loginParams['username'])) {
                return;
            }
            $isBlocked = $this->_isBlockedByEmail($loginParams['username']);
        }
        if ($wrongPasswordFlow == self::FLOW_BY_EMAIL_AND_IP || $wrongPasswordFlow == self::FLOW_BY_IP) {
            $isBlocked = $isBlocked || $this->_isBlockedByIp(Mage::helper('core/http')->getRemoteAddr());
        }
        try {
            if ($isBlocked) {
                throw new Exception(
                    Mage::helper('shield')->__('Your account is locked due to too many failed login attempts.')
                );
            }
        } catch (Exception $e) {
            $this->_getSession()->addError(Mage::helper('shield')->__($e->getMessage()));

            // Set no-dispatch flag
            $controller->setFlag(
                $controller->getRequest()->getActionName(),
                Mage_Core_Controller_Varien_Action::FLAG_NO_DISPATCH,
                true
            );
            Mage::app()->getFrontController()->getResponse()
                ->setRedirect(Mage::getUrl('customer/account/login'));
        }
    }
}
