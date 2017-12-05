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
 * @subpackage Collection
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Model_Resource_Item_Collection extends Mage_Core_Model_Resource_Db_Collection_Abstract
{
    /**
     * Init Collection for News
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('newsblock/item');
        $this->_map['fields']['store'] = 'store_table.store_id';
    }

    /**
     * Filter collection by status
     *
     * @return Oggetto_Newsblock_Model_Resource_Item_Collection
     */
    public function addEnabledFilter()
    {
        $this->addFieldToFilter('item_status', Oggetto_Newsblock_Model_Source_Status::ENABLED);
        return $this;
    }

    /**
     * Filter collection by store
     *
     * @return Oggetto_Newsblock_Model_Resource_Item_Collection
     */
    public function addStoreFilter()
    {
        $this->getSelect()->joinLeft(
            ['store_table' => $this->getTable('newsblock/store')],
            'main_table.item_id = store_table.item_id',
            ['store_id' => 'store_table.store_id']
        );

        $this->addFieldToFilter(['store_id', 'store_id'],
            [
                ['eq' => Mage::app()->getStore()->getStoreId()],
                ['null' => true ]
            ]
        );
        return $this;
    }

    /**
     * Add filter by store for grid
     *
     * @param int|Mage_Core_Model_Store $store
     * @param bool $withAdmin
     * @return Mage_Cms_Model_Resource_Block_Collection
     */
    public function addStoreGridFilter($store, $withAdmin = true)
    {
        if ($store instanceof Mage_Core_Model_Store) {
            $store = [$store->getId()];
        }
        if (!is_array($store)) {
            $store = [$store];
        }
        if ($withAdmin) {
            $store[] = Mage_Core_Model_App::ADMIN_STORE_ID;
        }
        $this->addFilter('store', array('in' => $store), 'public');
        return $this;
    }

    /**
     * Join store relation table if there is store filter
     */
    protected function _renderFiltersBefore()
    {
        if ($this->getFilter('store')) {
            $this->getSelect()->join(
                ['store_table' => $this->getTable('newsblock/store')],
                'main_table.item_id = store_table.item_id',
                []
            )->group('main_table.item_id');

            /*
             * Allow analytic functions usage because of one field grouping
             */
            $this->_useAnalyticFunction = true;
        }
        return parent::_renderFiltersBefore();
    }
}
