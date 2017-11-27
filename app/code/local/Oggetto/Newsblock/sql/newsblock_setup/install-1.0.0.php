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
 * the Oggetto Api module to newer versions in the future.
 * If you wish to customize the Oggetto Api module for your needs
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
        ->newTable($installer->getTable('newsblock/item'))
        ->addColumn('item_id', Varien_Db_Ddl_Table::TYPE_INTEGER, null, [
            'identity' => true,
            'unsigned' => true,
            'nullable' => false,
            'primary' => true,
        ], 'Id')
        ->addColumn('title', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, [
            'nullable' => false,
        ], 'Title')
        ->addColumn('description', Varien_Db_Ddl_Table::TYPE_TEXT, null, [
            'nullable' => false,
        ], 'Description')
        ->addColumn('content', Varien_Db_Ddl_Table::TYPE_TEXT, null, [
            'nullable' => false,
        ], 'Content')
        ->addColumn('image', Varien_Db_Ddl_Table::TYPE_VARCHAR, null, [
            'nullable' => false,
        ], 'Image')
        ->addColumn('item_status', Varien_Db_Ddl_Table::TYPE_BOOLEAN, null, [
            'nullable' => false,
        ], 'Status')
        ->addColumn('created_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
            'nullable' => false,
        ], 'Created at')
        ->addColumn('updated_at', Varien_Db_Ddl_Table::TYPE_DATETIME, null, [
            'nullable' => false,
        ], 'Updated at');

    $installer->getConnection()->createTable($table);
} catch (Exception $e) {
    Mage::log('Something went wrong.', null, 'newsblock_setup.log', true);
}

$installer->endSetup();
