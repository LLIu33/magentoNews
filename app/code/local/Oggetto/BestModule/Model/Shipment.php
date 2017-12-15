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
class Oggetto_BestModule_Model_Shipment
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface
{

    /**
     * @var Shipping code
     */
    protected $_code = 'bestshipping';

    /**
     * Collect rates
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return Mage_Core_Model_Abstract|Mage_Shipping_Model_Rate_Result|null
     */
    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        /** @var Mage_Shipping_Model_Rate_Result $result */
        $result = Mage::getModel('shipping/rate_result');

        $weight = $request->getPackageWeight();
        $qty = $request->getPackageQty();

        if ($this->isShippingRestriction($request) && $qty) {
            $result->append($this->_getStandardRate($weight, $qty));
        }
        return $result;
    }

    /**
     * Check if shipping restrictions apply
     *
     * @param Mage_Shipping_Model_Rate_Request $request
     * @return bool
     */
    public function isShippingRestriction(Mage_Shipping_Model_Rate_Request $request)
    {
        $isAvailable = true;
        /** @var Mage_Sales_Model_Quote_Item $quoteItem */
        foreach ($request->getAllItems() as $quoteItem) {
            if ($quoteItem->getIsBest() == Oggetto_BestModule_Model_Attribute_Source_Option::YES) {
                $isAvailable = true;
                break;
            }

            if ($quoteItem->getIsBest() == Oggetto_BestModule_Model_Attribute_Source_Option::NO) {
                $isAvailable = false;
            }
        }
        return $isAvailable;
    }

    /**
     * Process standart shipping method
     *
     * @param float $weight
     * @param int   $qty
     * @return Mage_Shipping_Model_Rate_Result_Method
     */
    protected function _getStandardRate($weight, $qty)
    {
        /** @var Mage_Shipping_Model_Rate_Result_Method $rate */
        $rate = Mage::getModel('shipping/rate_result_method');

        $rate->setCarrier($this->_code);
        $rate->setCarrierTitle($this->getConfigData('title'));
        $rate->setMethod('large');
        $rate->setMethodTitle('Standard delivery');
        $ratio = $this->getConfigData('ratio');
        $rate->setPrice($ratio * $weight / $qty);
        return $rate;
    }

    /**
     * Check tracking
     *
     * @return bool
     */
    public function isTrackingAvailable()
    {
        return false;
    }

    /**
     * List of allowed shipping methods
     *
     * @return array
     */
    public function getAllowedMethods()
    {
        return ['standard' => 'Standard delivery'];
    }
}
