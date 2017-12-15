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
 * @subpackage bestmodule_setup
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */

/** @var Mage_Customer_Model_Entity_Setup $installer */

$installer = new Mage_Customer_Model_Entity_Setup('core_setup');
$installer->startSetup();
try {
    $entityTypeId = $installer->getEntityTypeId('customer_address');
    $attributeSetId = $installer->getDefaultAttributeSetId($entityTypeId);
    $attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

    //this is for creating a new attribute for customer address entity
    $installer->addAttribute('customer_address', 'delivery_instruction', [
        'type' => 'varchar',
        'backend' => '',
        'label' => 'Delivery Instruction',
        'input' => 'text',
        'source' => '',
        'visible' => true,
        'required' => false,
        'default' => '',
        'frontend' => '',
        'unique' => false,
        'note' => 'Custom Attribute Will Be Used Show Delivery Instruction Field In Checkout Page'
    ]);

    $attribute = Mage::getSingleton('eav/config')->getAttribute('customer_address', 'delivery_instruction');

    $installer->addAttributeToGroup(
        $entityTypeId, $attributeSetId, $attributeGroupId, 'delivery_instruction', '999'  //sort_order
    );

    $attribute
        ->setData('used_in_forms', ['customer_register_address', 'customer_address_edit', 'adminhtml_customer_address'])
        ->setData('is_used_for_customer_segment', true)
        ->setData('is_system', 0)
        ->setData('is_user_defined', 1)
        ->setData('is_visible', 1)
        ->setData('sort_order', 100);
    $attribute->save();
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();
