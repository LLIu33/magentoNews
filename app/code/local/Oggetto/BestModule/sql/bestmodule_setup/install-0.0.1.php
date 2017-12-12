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

/* @var $installer Mage_Catalog_Model_Resource_Eav_Mysql4_Setup */


/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = Mage::getResourceModel('catalog/setup','catalog_setup');

$installer->startSetup();
try {
    $installer->addAttribute('catalog_product', 'is_best', array(
        'group'           => 'General',
        'label'           => 'Is Best',
        'input'           => 'select',
        'type'            => 'varchar',
        'user_defined'    => 0,
        'required'        => 0,
        'visible_on_front'=> 1,
        'filterable'      => 1,
        'searchable'      => 1,
        'comparable'      => 0,
        'user_defined'    => 0,
        'is_configurable' => 0,
        'global'          => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'option'          => [
            'value' => [
                'yes'   => ['Yes'],
                'no'    => ['No'],
                'maybe' => ['Maybe'],
            ]
        ],
        'note'            => '',
    ));
} catch (Exception $e) {
    Mage::logException($e);
}
$installer->endSetup();
