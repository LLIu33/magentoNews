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
 * @subpackage Model
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_BestModule_Model_Attribute_Source_Option extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    const YES = 1;
    const NO = 0;
    const MAYBE = 2;

    /**
     * Options getter
     *
     * @return array
     */
    public function getAllOptions()
    {
        if (is_null($this->_options)) {
            $this->_options = [
                ['value' => self::YES, 'label' => Mage::helper('bestmodule')->__('Yes')],
                ['value' => self::NO, 'label' => Mage::helper('bestmodule')->__('No')],
                ['value' => self::MAYBE, 'label' => Mage::helper('bestmodule')->__('Maybe')]
            ];
        }
        return $this->_options;
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->getAllOptions();
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::YES   => Mage::helper('bestmodule')->__('Yes'),
            self::NO    => Mage::helper('bestmodule')->__('No'),
            self::MAYBE => Mage::helper('bestmodule')->__('Maybe'),
        ];
    }
}
