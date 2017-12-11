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
 * the Oggetto MultipleFilter module to newer versions in the future.
 * If you wish to customize the Oggetto MultipleFilter module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_MultipleFilter
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto MultipleFilter
 *
 * @category   Oggetto
 * @package    Oggetto_MultipleFilter
 * @subpackage multiplefilter_setup
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */

$installer = $this;
$installer->startSetup();
try {
    // Add an extra column to the catalog_eav_attribute-table:
    $installer->getConnection()->addColumn(
        $installer->getTable('catalog/eav_attribute'),
        'is_multiple',
        [
            'type'      => Varien_Db_Ddl_Table::TYPE_SMALLINT,
            'unsigned'  => true,
            'nullable'  => true,
            'comment'   => 'Is Multiple'
        ]
    );
} catch (Exception $e) {
    Mage::logException($e);
}
$installer->endSetup();
