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
 * @subpackage Item_List
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Block_List extends Mage_Core_Block_Template
{
    /**
     * Oggetto_Newsblock_Block_List constructor.
     *
     * @param array $args
     */
    public function __construct(array $args = array())
    {
        parent::__construct($args);
        $collection = Mage::getModel('newsblock/item')->getCollection();
        $this->setCollection($collection);
    }

    /**
     * Modifying collection depends on url attributes
     *
     * @return Oggetto_Newsblock_Model_Resource_Item_Collection
     */
    public function _getCollection()
    {
        $limit = Mage::getStoreConfig('newsblock/settings/news_count');
        $currPage = 1;
        $dir = 'desc';

        if (Mage::app()->getRequest()->getParam('p')) {
            $currPage = Mage::app()->getRequest()->getParam('p');
        }
        if (Mage::app()->getRequest()->getParam('limit')) {
            $limit = Mage::app()->getRequest()->getParam('limit');
        }
        if (Mage::app()->getRequest()->getParam('dir')) {
            $dir = Mage::app()->getRequest()->getParam('dir');
        }
        //Calculate Offset
        $offset = ($currPage - 1) * $limit;
        $collection = Mage::getModel("newsblock/item")->getCollection()
            ->addFieldToFilter('item_status', array('eq' => Oggetto_Newsblock_Model_Source_Status::ENABLED))
            ->setOrder('created_at', $dir );
        $collection->getSelect()->limit($limit, $offset);
        return $collection;
    }

    /**
     * Initial state for list of news
     *
     * @return $this
     */
    public function _prepareLayout()
    {
        /** @var Mage_Page_Block_Html_Pager $pager */
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock('page/html_pager', 'custom.pager');
        $pager->setAvailableLimit(array(15 => 15));
        $pager->setLimit(Mage::getStoreConfig('newsblock/settings/news_count'));
        $pager->setShowPerPage(true);
        $pager->setCollection($this->getCollection());
        $this->setChild('pager', $pager);
        $this->getCollection()->load();
        return $this;
    }

    /**
     * Retrun current sort direction
     *
     * @return string
     */
    public function getCurrentDirection()
    {
        $dir = Mage::app()->getRequest()->getParam('dir');
        return ($dir) ? $dir : 'desc';
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
