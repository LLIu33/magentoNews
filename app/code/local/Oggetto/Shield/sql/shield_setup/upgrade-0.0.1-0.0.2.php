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
 * the Oggetto Newsblock module to newer versions in the future.
 * If you wish to customize the Oggetto Newsblock module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto Newsblock
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @subpackage newsblock_setup
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */

/** @var Mage_Core_Model_Resource_Setup $installer */

$installer = $this;
$installer->startSetup();
try {
    $table = $installer->getConnection()
        ->newTable($installer->getTable('shield/ip'))
        ->addColumn('ip_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ], 'Id')
        ->addColumn('ip', Varien_Db_Ddl_Table::TYPE_TEXT, null, [
            'unique' => true,
            'nullable' => false,
        ], 'Ip address')
        ->addColumn('attempts', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
            'nullable' => true,
        ], 'Count of failure attempts')
        ->addColumn('blocked_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
            'nullable' => true,
        ], 'Blocked at');

    $installer->getConnection()->createTable($table);
} catch (Exception $e) {
    Mage::logException($e);
}

$installer->endSetup();
