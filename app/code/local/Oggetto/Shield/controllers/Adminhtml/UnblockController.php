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
 * @subpackage ShieldController
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Shield_Adminhtml_UnblockController extends Mage_Adminhtml_Controller_Action
{
    /**
     * Unblock customer from admin panel
     *
     * @return void
     */
    public function indexAction()
    {
        $customerId = (int) $this->getRequest()->getParam('id');
        $customer = Mage::getModel('customer/customer')->load($customerId);
        if ($customer->getId()) {
            $customer->setData('customer_login_attempts', 0)
                ->setData('is_blocked', 1)
                ->setData('customer_blocked_at', null)
                ->save();
        }
        $this->_redirectReferer();
    }
}
