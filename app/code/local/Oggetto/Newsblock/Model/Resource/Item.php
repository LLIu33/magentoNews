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
 * @subpackage Model_Resource
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

    /**
     * Perform operations after object save
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Oggetto_Newsblock_Model_Resource_Item
     */
    protected function _afterSave(Mage_Core_Model_Abstract $object)
    {
        $oldStores = $this->lookupStoreIds($object->getId());
        $newStores = (array)$object->getStores();

        $table  = $this->getTable('newsblock/store');
        $insert = array_diff($newStores, $oldStores);
        $delete = array_diff($oldStores, $newStores);

        if ($delete) {
            $where = array(
                'item_id = ?'     => (int) $object->getId(),
                'store_id IN (?)' => $delete
            );
            $this->_getWriteAdapter()->delete($table, $where);
        }

        if ($insert) {
            $data = array();
            foreach ($insert as $storeId) {
                $data[] = array(
                    'item_id'  => (int) $object->getId(),
                    'store_id' => (int) $storeId
                );
            }
            $this->_getWriteAdapter()->insertMultiple($table, $data);
        }
        return parent::_afterSave($object);
    }

    /**
     * Get store ids to which specified item is assigned
     *
     * @param int $id
     * @return array
     */
    public function lookupStoreIds($id)
    {
        $adapter = $this->_getReadAdapter();
        $select  = $adapter->select()
            ->from($this->getTable('newsblock/store'), 'store_id')
            ->where('item_id = :item_id');
        $binds = array(
            ':item_id' => (int) $id
        );
        return $adapter->fetchCol($select, $binds);
    }


    /**
     * Perform operations after object load
     *
     * @param Mage_Core_Model_Abstract $object
     * @return Oggetto_Newsblock_Model_Resource_Item
     */
    protected function _afterLoad(Mage_Core_Model_Abstract $object)
    {
        if ($object->getId()) {
            $stores = $this->lookupStoreIds($object->getId());
            $object->setData('store_id', $stores);
            $object->setData('stores', $stores);
        }
        return parent::_afterLoad($object);
    }
}
