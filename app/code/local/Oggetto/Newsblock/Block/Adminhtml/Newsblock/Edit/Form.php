<?php
class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Edit_Form
    extends Mage_Adminhtml_Block_Widget_Form
{

    /**
     * Init form
     *
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    public function _construct()
    {
        parent::_construct();
        $this->setId('block_form');
        $this->setTitle(Mage::helper('newsblock')->__('Item Information'));
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('newsblock_item');
        $form = new Varien_Data_Form(
            array(
                'id' => 'edit_form',
                'action' => $this->getUrl(
                    '*/*/save',
                    array('item_id' => $this->getRequest()->getParam('item_id'))
                ),
                'method' => 'post'
            )
        );

        $form->setHtmlIdPrefix('item_');
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);

        $fieldset = $form->addFieldset(
            'base_fieldset',
            array(
                'legend' => Mage::helper('newsblock')->__('General Information'),
                'class' => 'fieldset-wide'
            )
        );

        if ($model->getItemId()) {
            $fieldset->addField('item_id', 'hidden', array(
                'name' => 'item_id',
            ));
        }

        $fieldset->addField('title', 'textarea', array(
            'name'      => 'title',
            'label'     => Mage::helper('newsblock')->__('News Title'),
            'title'     => Mage::helper('newsblock')->__('News Title'),
            'required'  => true,
        ));

        if ($model->getItemId()) {
            $fieldset->addField(
                'created_at',
                'date',
                array(
                    'label'     => Mage::helper('newsblock')->__('Created at'),
                    'readonly'  => true,
                    'class'     => 'readonly',
                    'name'      => 'created_at',
                    'format'    => $dateFormatIso,
                    'time' => true,
                )
            );

            $fieldset->addField(
                'updated_at',
                'date',
                array(
                    'label'     => Mage::helper('newsblock')->__('Updated at'),
                    'readonly'  => true,
                    'class'     => 'readonly',
                    'name'      => 'updated_at',
                    'format'    => $dateFormatIso,
                    'time' => true,
                )
            );
        }

        $fieldset->addField(
            'item_status',
            'select',
            array(
                'label'     => Mage::helper('newsblock')->__('Status'),
                'title'     => Mage::helper('newsblock')->__('Status'),
                'name'      => 'item_status',
                'required'  => true,
                'options'   => Mage::getModel('newsblock/source_status')->toArray(),
            )
        );

        $fieldset->addField('image', 'image', array(
            'name'      => 'image',
            'label'     => Mage::helper('newsblock')->__('Image'),
            'title'     => Mage::helper('newsblock')->__('Image'),
            'required'  => true,
        ));

        $fieldset->addField('description', 'textarea', array(
            'name'      => 'description',
            'label'     => Mage::helper('newsblock')->__('Description'),
            'title'     => Mage::helper('newsblock')->__('Description'),
            'style'     => 'height:12em',
            'required'  => true,

        ));


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

