<?php

class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'block_id';
        $this->_controller = 'adminhtml_newsblock';
        $this->_blockGroup = 'newsblock';

        parent::__construct();

        $this->_updateButton(
            'save',
            'label', Mage::helper('newsblock')->__('Save Block')
        );
        $this->_updateButton(
            'delete',
            'label', Mage::helper('newsblock')->__('Delete Block')
        );

        $this->_addButton(
            'saveandcontinue',
            array(
                'label'     =>
                    Mage::helper('adminhtml')->__('Save and Continue Edit'),
                'onclick'   => 'saveAndContinueEdit()',
                'class'     => 'save',
            ),
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
        if (Mage::registry('newsblock_block')->getId()) {
            return Mage::helper('newsblock')->__(
                "Edit Block '%s'",
                $this->escapeHtml(Mage::registry('newsblock_block')->getTitle())
            );
        } else {
            return Mage::helper('newsblock')->__('New Block');
        }
    }

}
