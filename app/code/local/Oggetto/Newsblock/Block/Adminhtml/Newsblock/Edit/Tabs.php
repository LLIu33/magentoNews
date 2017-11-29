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
 * the Oggetto Api module to newer versions in the future.
 * If you wish to customize the Oggetto Api module for your needs
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
 * @subpackage Widget_Tabs
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{
    /**
     * Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit_Tabs constructor.
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('news_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('newsblock')->__('News Information'));
    }
//
//    protected function _prepareLayout()
//    {
//        $this->addTab('general_tab', [
//            'label'     => $this->__('General info'),
//            'title'     => $this->__('General info'),
//            'content'   => $this->getLayout()->createBlock('newsblock/adminhtml_edit_tab_general')
//        ]);
//        $this->addTab('meta_tab', [
//            'label'     => $this->__('Meta info'),
//            'title'     => $this->__('Meta info'),
//            'content'   => $this->getLayout()->createBlock('newsblock/adminhtml_edit_tab_meta')
//        ]);
//        return parent::_prepareLayout();
//    }
}
