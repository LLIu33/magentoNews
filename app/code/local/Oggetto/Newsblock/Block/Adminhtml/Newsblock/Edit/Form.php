<?php

class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Init form
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('block_form');
        $this->setTitle(Mage::helper('newsblock')->__('Block Information'));
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('newsblock_block');
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl(
                    '*/*/save',
                    array('block_id' => $this->getRequest()->getParam('block_id'))
                ),
                'method' => 'post'
            )
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array(
                'legend' => Mage::helper('newsblock')->__('General Information'),
                'class' => 'fieldset-wide'
            )
        );

        if ($model->getBlockId()) {
            $fieldset->addField('block_id', 'hidden', array(
                'name' => 'block_id',
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('newsblock')->__('Block Title'),
            'title'     => Mage::helper('newsblock')->__('Block Title'),
            'required'  => true,
        ));


        $fieldset->addField(
            'block_status',
            'select', array(
                'label'     => Mage::helper('newsblock')->__('Status'),
                'title'     => Mage::helper('newsblock')->__('Status'),
                'name'      => 'block_status',
                'required'  => true,
                'options'   => Mage::getModel('newsblock/source_status')->toArray(),
            )
        );


        $fieldset->addField('content', 'textarea', array(
            'name'      => 'content',
            'label'     => Mage::helper('newsblock')->__('Content'),
            'title'     => Mage::helper('newsblock')->__('Content'),
            'style'     => 'height:36em',
            'required'  => true,

        ));

        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

}

