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
 * @subpackage Model
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_MultipleFilter_Model_Catalog_Layer_Filter_Item extends Mage_Catalog_Model_Layer_Filter_Item
{

    /**
     * @var Mage_Core_Helper_Abstract
     */
    protected $_helper;

    /**
     * Set helper
     *
     * @return Mage_Core_Helper_Abstract
     */
    protected function helper()
    {
        if ($this->_helper === null) {
            $this->_helper = Mage::helper('multichoice');
        }
        return $this->_helper;
    }

    /**
     * Get filter item url
     *
     * @return string
     */
    public function getUrl()
    {
        $values = $this->getFilter()->getValues();
        if (!empty($values)) {
            $tmp = array_merge($values, [$this->getValue()]);
            $values = implode(Oggetto_Multiplefilter_Helper_Data::MULTIPLE_FILTERS_DELIMITER, $tmp);
        } else {
            $values = $this->getValue();
        }

        $query = [
            $this->getFilter()->getRequestVar() => $values,
            Mage::getBlockSingleton('page/html_pager')->getPageVarName() => null
        ];
        $params = [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => $query
        ];
        return Mage::getUrl('*/*/*', $params);
    }

    /**
     * Get url for remove item from filter
     *
     * @return string
     */
    public function getRemoveUrl()
    {
        $values = $this->getFilter()->getValues();
        if (!empty($values)) {
            $tmp = array_diff($values, [$this->getValue()]);
            if (!empty($tmp)) {
                $values = implode(Oggetto_Multiplefilter_Helper_Data::MULTIPLE_FILTERS_DELIMITER, $tmp);
            } else {
                $values = null;
            }
        } else {
            $values = null;
        }

        $query = [$this->getFilter()->getRequestVar() => $values];
        $params = [
            '_current' => true,
            '_use_rewrite' => true,
            '_query' => $query
        ];
        return Mage::getUrl('*/*/*', $params);
    }

    /**
     * Check if current filter is selected
     * 
     * @return boolean 
     */
    public function isSelected()
    {
        $values = $this->getFilter()->getValues();
        return is_array($values) && in_array($this->getValue(), $values);
    }
}
