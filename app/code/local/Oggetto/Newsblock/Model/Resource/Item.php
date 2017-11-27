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
 * Oggetto api model
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @subpackage Model_Resource_Item
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Model_Resource_Item extends Mage_Core_Model_Resource_Db_Abstract
{
    /**
     * Init Resource Model for News
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('newsblock/item', 'item_id');
    }
}
