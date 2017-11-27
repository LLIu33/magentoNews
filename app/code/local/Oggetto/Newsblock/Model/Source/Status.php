<?php

class Oggetto_Newsblock_Model_Source_Status
{
    const ENABLED = '1';
    const DISABLED = '0';

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array(
                'value' => self::ENABLED,
                'label' => Mage::helper('newsblock')->__('Enabled')
            ),
            array(
                'value' => self::DISABLED,
                'label' => Mage::helper('newsblock')->__('Disabled')
            ),
        );
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return array(
            self::DISABLED => Mage::helper('newsblock')->__('Disabled'),
            self::ENABLED => Mage::helper('newsblock')->__('Enabled'),
        );
    }

}
