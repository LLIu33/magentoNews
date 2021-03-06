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
class Oggetto_Newsblock_Block_List extends Mage_Core_Block_Template
{
    /**
     * Default sort field
     *
     * @var string
     */
    protected $_sortField = 'created_at';

    /**
     * Default sort direction
     *
     * @var string
     */
    protected $_sortDirection = 'desc';

    /**
     * Oggetto_Newsblock_Block_List constructor.
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $collection = Mage::getModel('newsblock/item')->getCollection();
        $this->setCollection($collection);
    }

    /**
     * Get current sort direction
     *
     * @return string
     * @throws Exception
     */
    public function getSortDirection()
    {
        return $this->getRequest()->getParam('dir', $this->_sortDirection);
    }

    /**
     * Initial state for list of news
     *
     * @return $this
     * @throws Exception
     */
    public function _beforeToHtml()
    {
        parent::_beforeToHtml();
        $sortDirection = $this->getSortDirection();
        /** @var Mage_Page_Block_Html_Pager $pager */
        $pager = $this->getChild('pager');
        $pager->setAvailableLimit([15 => 15]);
        $pager->setLimit(Mage::getStoreConfig('newsblock/settings/news_count'));
        $pager->setShowPerPage(true);
        /** @var Oggetto_Newsblock_Model_Resource_Item_Collection $collection */
        $collection = $this->getCollection()
            ->addEnabledFilter()
            ->addStoreFilter()
            ->setOrder($this->_sortField, $sortDirection);
        $pager->setCollection($collection);
        return $this;
    }

    /**
     * Generate url with order rules
     *
     * @param string $direction
     * @return string
     */
    public function getOrderUrl($direction)
    {
        $params = [
            'dir' => $direction
        ];
        $urlParams = [];
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return $this->getUrl('*/*/*', $urlParams);
    }

    /**
     * Generating url for detail page
     *
     * @param Oggetto_Newsblock_Model_Item $newsItem
     * @return string
     */
    public function getDetailUrl(Oggetto_Newsblock_Model_Item $newsItem)
    {
        return Mage::getUrl('newsblock/index/detail', ['id' => $newsItem->getId()]);
    }

    /**
     * Render pager
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
