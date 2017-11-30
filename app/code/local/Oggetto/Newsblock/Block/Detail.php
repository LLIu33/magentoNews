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
 * the Oggetto Newsblock module to newer versions in the future.
 * If you wish to customize the Oggetto Newsblock module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @copyright  Copyright (C) 2017 Oggetto Web ltd (http://oggettoweb.com/)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Oggetto Newsblock
 *
 * @category   Oggetto
 * @package    Oggetto_Newsblock
 * @subpackage Block
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Block_Detail extends Mage_Core_Block_Template
{
    /**
     * Model for news detail
     *
     * @var Oggetto_Newsblock_Model_Item
     */
    protected $_newsDetail;

    /**
     * Retrieve current news
     *
     * @return Mage_Core_Model_Abstract
     * @throws Exception
     */
    public function getNewsDetail()
    {
        $this->_newsDetail = $this->_newsDetail ?? Mage::getModel('newsblock/item');
        return $this->_newsDetail->load($this->getRequest()->getParam('id'));
    }

    /**
     * Modifying head block
     *
     * @return Mage_Core_Block_Abstract
     * @throws Exception
     */
    public function _prepareLayout()
    {
        $head = $this->getLayout()->getBlock('head');
        $block = $this->getNewsDetail();
        $head->setTitle($block->getPageTitle())
            ->setKeywords($block->getMetaKeywords())
            ->setDescription($block->getMetaDescription());
        return parent::_prepareLayout();
    }
}
