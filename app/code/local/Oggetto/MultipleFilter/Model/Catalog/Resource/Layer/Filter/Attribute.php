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
 * @subpackage Resource_Model
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Multiplefilter_Model_Catalog_Resource_Layer_Filter_Attribute
    extends Mage_Catalog_Model_Resource_Layer_Filter_Attribute
{
    /**
     * Apply attribute filter to product collection
     *
     * @param Mage_Catalog_Model_Layer_Filter_Attribute $filter
     * @param int                                       $value
     * @return Mage_Catalog_Model_Resource_Layer_Filter_Attribute
     */
    public function applyFilterToCollection($filter, $value)
    {
        $collection = $filter->getLayer()->getProductCollection();
        $attribute = $filter->getAttributeModel();
        $connection = $this->_getReadAdapter();
        $tableAlias = $attribute->getAttributeCode() . '_idx' . uniqid();
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $collection->getStoreId()),
        ];

        if (!is_array($value)) {
            $options = $attribute->getSource()->getAllOptions(false);
            foreach ($options as $option) {
                if ($option['label'] == $value) {
                    $value = $option['value'];
                }
            }
            $conditions[] = $connection->quoteInto("{$tableAlias}.value = ?", $value);
        } else {
            $conditions[] = "{$tableAlias}.value in ( ";
            foreach ($value as $v) {
                $conditions[count($conditions) - 1] .= $connection->quoteInto("?", $v) . ' ,';
            }
            $conditions[count($conditions) - 1] = rtrim($conditions[count($conditions) - 1], ',');
            $conditions[count($conditions) - 1] .= ')';
        }

        $collection->getSelect()->join(
            [$tableAlias => $this->getMainTable()], implode(' AND ', $conditions), []
        );
        $collection->getSelect()->distinct();

        return $this;
    }

    /**
     * Retrieve array with products counts per attribute option
     *
     * @param Mage_Catalog_Model_Layer_Filter_Attribute $filter
     * @return array
     */
    public function getCount($filter)
    {
        // clone select from collection with filters
        $select = clone $filter->getLayer()->getProductCollection()->getSelect();
        // reset columns, order and limitation conditions
        $select->reset(Zend_Db_Select::COLUMNS);
        $select->reset(Zend_Db_Select::ORDER);
        $select->reset(Zend_Db_Select::LIMIT_COUNT);
        $select->reset(Zend_Db_Select::LIMIT_OFFSET);


        $connection = $this->_getReadAdapter();
        $attribute = $filter->getAttributeModel();
        $tableAlias = sprintf('%s_idx', $attribute->getAttributeCode());
        $conditions = [
            "{$tableAlias}.entity_id = e.entity_id",
            $connection->quoteInto("{$tableAlias}.attribute_id = ?", $attribute->getAttributeId()),
            $connection->quoteInto("{$tableAlias}.store_id = ?", $filter->getStoreId()),
        ];

        // start removing all filters for current attribute - we need correct count
        $parts = $select->getPart(Zend_Db_Select::FROM);
        $from = [];
        foreach ($parts as $key => $part) {
            if (stripos($key, $tableAlias) === false) {
                $from[$key] = $part;
            }
        }
        $select->setPart(Zend_Db_Select::FROM, $from);
        // end of removing

        $select
            ->join(
                [$tableAlias => $this->getMainTable()],
                join(' AND ', $conditions),
                ['value', 'count' => new Zend_Db_Expr("COUNT({$tableAlias}.entity_id)")]
            )
            ->group("{$tableAlias}.value");

        return $connection->fetchPairs($select);
    }

}
