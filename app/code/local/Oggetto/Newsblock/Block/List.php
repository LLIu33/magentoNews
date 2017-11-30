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
    protected $sortField = 'created_at';

    /**
     * Default sort direction
     *
     * @var string
     */
    protected $sortDirection = 'desc';

    /**
     * Oggetto_Newsblock_Block_List constructor.
     *
     */
    public function _construct()
    {
        parent::_construct();
        $collection = Mage::getModel("newsblock/item")->getCollection()
            ->addFieldToFilter('item_status', array('eq' => Oggetto_Newsblock_Model_Source_Status::ENABLED))
            ->setOrder($this->sortField, $this->sortDirection );
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
        $dir = $this->getRequest()->getParam('dir');
        return ($dir) ? $dir : $this->sortDirection;
    }

    /**
     * Initial state for list of news
     *
     * @return $this
     * @throws Exception
     */
    public function _beforeToHtml()
    {
        /** @var Mage_Page_Block_Html_Pager $pager */
        parent::_beforeToHtml();
        $sortDirection = $this->getSortDirection();
        $pager = $this->getChild('pager');
        $pager->setAvailableLimit(array(15 => 15));
        $pager->setLimit(Mage::getStoreConfig('newsblock/settings/news_count'));
        $pager->setShowPerPage(true);
        $collection = $this->getCollection()->setOrder($this->sortField, $sortDirection);
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
        $urlParams = array();
        $urlParams['_current']  = true;
        $urlParams['_escape']   = true;
        $urlParams['_use_rewrite']   = true;
        $urlParams['_query']    = $params;
        return $this->getUrl('*/*/*', $urlParams);
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
