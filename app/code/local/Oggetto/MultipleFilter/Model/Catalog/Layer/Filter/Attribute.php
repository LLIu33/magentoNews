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
class Oggetto_MultipleFilter_Model_Catalog_Layer_Filter_Attribute extends Mage_Catalog_Model_Layer_Filter_Attribute
{

    /**
     * Attribute filter values
     *
     * @var array
     */
    protected $_values = [];

    /**
     * Return values
     *
     * @return array
     */
    public function getValues()
    {
        return $this->_values;
    }

    /**
     * Apply attribute filter to layer
     *
     * @param Zend_Controller_Request_Abstract  $request
     * @param object                            $filterBlock
     * @return Enterprise_Search_Model_Catalog_Layer_Filter_Attribute
     */
    public function apply(Zend_Controller_Request_Abstract $request, $filterBlock)
    {
        $filter = $request->getParam($this->_requestVar);

        if (is_array($filter)) {
            return $this;
        }

        if (empty($filter)) {
            return $this;
        }

        $this->_values = explode(Oggetto_MultipleFilter_Helper_Data::MULTIPLE_FILTERS_DELIMITER, $filter);

        if (!empty($this->_values)) {
            $this->_getResource()->applyFilterToCollection($this, $this->_values);
            foreach ($this->_values as $filter) {
                $text = $this->_getOptionText($filter);
                $this->getLayer()->getState()->addFilter($this->_createItem($text, $filter));
                $model = $filterBlock->getAttributeModel();
                if ($model && !$model->getIsMultiple()) {
                    $this->_items = [];
                }
            }
        }
        return $this;
    }
}
