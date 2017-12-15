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
 * the Oggetto Shield module to newer versions in the future.
 * If you wish to customize the Oggetto Shield module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Shield
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto Shield
 *
 * @category   Oggetto
 * @package    Oggetto_Shield
 * @subpackage Model
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Shield_Model_Options
{
    const WRONGPASS_FLOW_DISABLED  = 0;
    const WRONGPASS_FLOW_IP_EMAIL  = 1;
    const WRONGPASS_FLOW_IP        = 2;
    const WRONGPASS_FLOW_EMAIL     = 3;

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => self::WRONGPASS_FLOW_DISABLED, 'label' => Mage::helper('adminhtml')->__('Disabled')],
            ['value' => self::WRONGPASS_FLOW_IP_EMAIL, 'label' => Mage::helper('adminhtml')->__('By IP and Email')],
            ['value' => self::WRONGPASS_FLOW_IP,       'label' => Mage::helper('adminhtml')->__('By IP')],
            ['value' => self::WRONGPASS_FLOW_EMAIL,    'label' => Mage::helper('adminhtml')->__('By Email')],
        ];
    }

}