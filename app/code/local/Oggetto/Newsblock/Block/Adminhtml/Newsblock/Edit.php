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
 * @subpackage Form_Container
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /**
     * Init Edit Page
     *
     * @return void
     */
    public function _construct()
    {
        $this->_objectId = 'item_id';
        $this->_controller = 'adminhtml_newsblock';
        $this->_blockGroup = 'newsblock';

        parent::_construct();

        $this->_updateButton(
            'save',
            'label', Mage::helper('newsblock')->__('Save News')
        );
        $this->_updateButton(
            'delete',
            'label', Mage::helper('newsblock')->__('Delete News')
        );

        $this->_addButton(
            'saveandcontinue',
            [
                'label'     =>
                    Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save',
            ],
            -100
        );

        $this->_formScripts[] = "
            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }


    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (!Mage::registry('newsblock_item')->getId()) {
            return Mage::helper('newsblock')->__('Fresh News');
        }
        return Mage::helper('newsblock')->__(
            "Edit News",
            $this->escapeHtml(Mage::registry('newsblock_item')->getTitle())
        );
    }

}
