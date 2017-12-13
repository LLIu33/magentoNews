<?php
class Oggetto_BestModule_Model_Shipment
    extends Mage_Shipping_Model_Carrier_Abstract
    implements Mage_Shipping_Model_Carrier_Interface {

    protected $_code = 'bestshipping';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request)
    {
        /** @var Mage_Shipping_Model_Rate_Result $result */
        $result = Mage::getModel('shipping/rate_result');

        $weight = $request->getPackageWeight();
        $qty = $request->getPackageQty();

        if(!$this->isShippingRestriction($request) || $qty <= 0) {
            return $result;
        }

        /** @var Mage_Shipping_Model_Rate_Result_Method $method */
        $method = Mage::getModel('shipping/rate_result_method');

        $method->setCarrier($this->_code);
        $method->setCarrierTitle($this->getConfigData('title'));

        $result->append($this->_getStandardRate($weight, $qty));

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

        $attribute = Mage::getModel('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'is_best');
        $options = $attribute->getSource()->getAllOptions(false);
        $values = [];
        foreach ($options as $option){
            $values[$option['value']] = $option['label'];
        }

        /** @var Mage_Sales_Model_Quote_Item $quoteItem */
        foreach ($request->getAllItems() as $quoteItem) {
            $product = Mage::getModel('catalog/product')->load($quoteItem->getProductId());
            $isBest = ($product->getIsBest()) ? $values[$product->getIsBest()] : null;
            if ($isBest == 'Yes') {
                $isAvailable = true;
                break;
            }
            if (is_null($isBest) || $isBest == 'No') {
                $isAvailable = false;
            }
        }
        return $isAvailable;
    }

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

    public function isTrackingAvailable()
    {
        return false;
    }

    public function getAllowedMethods()
    {
        return array(
//            'packet' => 'Packet belpost',
//            'box'  => 'Box belpost'
            'standard'    =>  'Standard delivery'
        );
    }
}