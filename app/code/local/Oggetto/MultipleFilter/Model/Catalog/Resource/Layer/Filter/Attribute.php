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
            $conditions[] = $connection->quoteInto("{$tableAlias}.value = ?", $value);
        } else {
            $values = implode(Oggetto_Multiplefilter_Helper_Data::MULTIPLE_FILTERS_DELIMITER, $value);
            $conditions[] = "{$tableAlias}.value in ({$values})";
        }

        $collection->getSelect()->join(
            [$tableAlias => $this->getMainTable()], implode(' AND ', $conditions), []
        );
        $collection->getSelect()->distinct();

        return $this;
    }
}
