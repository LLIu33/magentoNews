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

/** @var Mage_Core_Model_Resource_Setup $installer */

$installer = new Mage_Sales_Model_Resource_Setup('core_setup');
$installer->startSetup();
try {
    $entities = [
        'quote',
        'quote_item',
        'order',
        'order_item'
    ];
    $options = [
        'type'     => Varien_Db_Ddl_Table::TYPE_INTEGER,
        'visible'  => true,
        'required' => false
    ];
    foreach ($entities as $entity) {
        $installer->addAttribute($entity, 'is_best', $options);
    }
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();
