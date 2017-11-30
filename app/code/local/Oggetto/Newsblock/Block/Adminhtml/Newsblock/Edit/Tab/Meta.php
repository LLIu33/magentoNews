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
 * @subpackage Widget_Form
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit_Tab_Meta
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{

    /**
     * Init form
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('meta_form');
    }

    /**
     * Preparing form
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('newsblock_item');
        $form = new Varien_Data_Form();

        $form->setHtmlIdPrefix('meta_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => Mage::helper('newsblock')->__('Meta Information'),
                'class' => 'fieldset-wide'
            ]
        );

        $fieldset->addField('page_title', 'text', [
            'name'      => 'page_title',
            'label'     => Mage::helper('newsblock')->__('Page Title'),
            'title'     => Mage::helper('newsblock')->__('Page Title'),
            'required'  => true,
        ]);

        $fieldset->addField('meta_description', 'textarea', [
            'name'      => 'meta_description',
            'label'     => Mage::helper('newsblock')->__('Meta Description'),
            'title'     => Mage::helper('newsblock')->__('Meta Description'),
        ]);

        $fieldset->addField('meta_keywords', 'textarea', [
            'name'      => 'meta_keywords',
            'label'     => Mage::helper('newsblock')->__('Keywords'),
            'title'     => Mage::helper('newsblock')->__('Keywords'),
        ]);

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper( 'newsblock' )->__('Meta Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper( 'newsblock' )->__('Meta');
    }

    /**
     * Returns status flag about this tab can be showen or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     *
     * @return bool
     */
    protected function _isAllowedAction( $action )
    {
        return true;
    }
}
