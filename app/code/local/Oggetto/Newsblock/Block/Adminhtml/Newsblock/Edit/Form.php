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
class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Init form
     *
     * @return void
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('block_form');
    }

    /**
     * Load Wysiwyg on demand and Prepare layout
     *
     * @return void
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    /**
     * Preparing form
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('newsblock_item');
        $form = new Varien_Data_Form(
            [
                'id' => 'edit_form',
                'action' => $this->getUrl(
                    '*/*/save',
                    ['item_id' => $this->getRequest()->getParam('item_id')]
                ),
                'method' => 'post',
                'enctype' => 'multipart/form-data'
            ]
        );

        $form->setHtmlIdPrefix('item_');
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset = $form->addFieldset(
            'base_fieldset',
            [
                'legend' => Mage::helper('newsblock')->__('General Information'),
                'class' => 'fieldset-wide'
            ]
        );

        $fieldset->addField('title', 'text', [
            'name'      => 'title',
            'label'     => Mage::helper('newsblock')->__('News Title'),
            'title'     => Mage::helper('newsblock')->__('News Title'),
            'required'  => true,
        ]);

        if ($model->getItemId()) {
            $fieldset->addField(
                'created_at',
                'date',
                [
                    'label'     => Mage::helper('newsblock')->__('Created at'),
                    'readonly'  => true,
                    'class'     => 'readonly',
                    'name'      => 'created_at',
                    'format'    => $dateFormatIso,
                    'time' => true,
                ]
            );

            $fieldset->addField(
                'updated_at',
                'date',
                [
                    'label'     => Mage::helper('newsblock')->__('Updated at'),
                    'readonly'  => true,
                    'class'     => 'readonly',
                    'name'      => 'updated_at',
                    'format'    => $dateFormatIso,
                    'time' => true,
                ]
            );
        }

        $fieldset->addField(
            'item_status',
            'select',
            [
                'label'     => Mage::helper('newsblock')->__('Status'),
                'title'     => Mage::helper('newsblock')->__('Status'),
                'name'      => 'item_status',
                'required'  => true,
                'options'   => Mage::getModel('newsblock/source_status')->toArray(),
            ]
        );

        $fieldset->addField(
            'image',
            'image',
            [
                'name'      => 'image',
                'label'     => Mage::helper('newsblock')->__('Image'),
                'title'     => Mage::helper('newsblock')->__('Image'),
                'required'  => false,
            ]
        );

        $fieldset->addField('description', 'textarea', [
            'name'      => 'description',
            'label'     => Mage::helper('newsblock')->__('Description'),
            'title'     => Mage::helper('newsblock')->__('Description'),
            'style'     => 'height:12em',
            'required'  => true,
        ]);

        $fieldset->addField('content', 'editor', [
            'name'      => 'content',
            'label'     => Mage::helper('newsblock')->__('Content'),
            'title'     => Mage::helper('newsblock')->__('Content'),
            'style'     => 'height:36em',
            'required'  => true,
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            'wysiwyg'   => true,
        ]);

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}
