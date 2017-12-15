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
 * @subpackage bestmodule_setup
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();
try {
    /*
     * ATTRIBUTE: customer_active
     */
    $attributeName = 'customer_active';
    $installer->addAttribute('customer', $attributeName,
        [
            'type' => 'int',
            'input' => 'select',
            'label' => 'Active',
            'global' => true,
            'visible' => true,
            'required' => false,
            'user_defined' => false,
            'default' => 1,
            'visible_on_front' => false,
            'source' => 'eav/entity_attribute_source_boolean'
        ]
    );

    $customer  = Mage::getModel('customer/customer');
    $attrSetId = $customer->getResource()->getEntityType()->getDefaultAttributeSetId();
    $installer->addAttributeToSet('customer', $attrSetId, 'General', $attributeName);

    Mage::getSingleton('eav/config')
        ->getAttribute('customer', $attributeName)
        ->setData('used_in_forms', ['adminhtml_customer'])
        ->save();

    /*
     * ATTRIBUTE: customer_logins_failed
     */
    $attributeName = 'customer_logins_failed';
    $installer->addAttribute('customer', $attributeName,
        [
            'type' => 'int',
            'input' => 'text',
            'label' => 'Last failed login',
            'global' => true,
            'visible' => false,
            'required' => false,
            'user_defined' => false,
            'default' => '1',
            'visible_on_front' => false
        ]
    );

    /*
     * ATTRIBUTE: customer_last_login_failed
     */
    $attributeName = 'customer_last_login_failed';
    $installer->addAttribute('customer', $attributeName,
        [
            'type' => 'int',
            'input' => 'text',
            'label' => 'Failed logins',
            'global' => true,
            'visible' => false,
            'required' => false,
            'user_defined' => false,
            'default' => '1',
            'visible_on_front' => false
        ]
    );
} catch (Exception $e) {
    Mage::logException($e);
}
$installer->endSetup();