<?php
class Oggetto_BestModule_Model_Payment extends Mage_Payment_Model_Method_Abstract {

    protected $_code = 'bestpayment';

    /**
     * Check whether payment method can be used
     *
     * @param Mage_Sales_Model_Quote|null $quote
     * @return bool
     */
    public function isAvailable($quote = null)
    {
        $checkResult->isAvailable = parent::isAvailable();

        $attribute = Mage::getModel('eav/config')->getAttribute(Mage_Catalog_Model_Product::ENTITY, 'is_best');
        $options = $attribute->getSource()->getAllOptions(false);
        $values = [];
        foreach ($options as $option){
            if (!($option['label'] == 'No')) {
                $values[] = $option['value'];
            }
        }

        foreach ($quote->getAllItems() as $item) {
            $product = Mage::getModel('catalog/product')->load($item->getProductId());

            if(!in_array($product->getIsBest(), $values)) {
                $checkResult->isAvailable = false;
            }
        }
        return $checkResult->isAvailable;
    }
}
