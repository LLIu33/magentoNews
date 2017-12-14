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

$installer = $this;
$installer->startSetup();
try {
    /**
     * Adding Extra Column to sales_flat_quote_address
     * to store the delivery instruction field
     */
    $salesQuoteAddress = $installer->getTable('sales/quote_address');
    $installer->getConnection()
        ->addColumn($salesQuoteAddress, 'delivery_instruction', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'New Delivery Instruction Field Added'
        ));

    /**
     * Adding Extra Column to sales_flat_order_address
     * to store the delivery instruction field
     */
    $salesOrderAddress = $installer->getTable('sales/order_address');
    $installer->getConnection()
        ->addColumn($salesOrderAddress, 'delivery_instruction', array(
            'type' => Varien_Db_Ddl_Table::TYPE_TEXT,
            'comment' => 'New Delivery Instruction Field Added'
        ));
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();
