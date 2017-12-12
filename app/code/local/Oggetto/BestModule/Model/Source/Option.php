<?php

class Oggetto_BestModule_Model_Source_Option extends Mage_Eav_Model_Entity_Attribute_Source_Table
{
    const YES = 'yes';
    const NO = 'no';
    const MAYBE = 'maybe';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'value' => self::YES,
                'label' => Mage::helper('bestmodule')->__('Yes')
            ],
            [
                'value' => self::NO,
                'label' => Mage::helper('bestmodule')->__('No')
            ],
            [
                'value' => self::MAYBE,
                'label' => Mage::helper('bestmodule')->__('Maybe')
            ]
        ];
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