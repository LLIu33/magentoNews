<?php

class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsBlockGrid');
        $this->setDefaultSort('item_identifier');
        $this->setDefaultDir('ASC');
    }

    /**
     * Prepearing collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('newsblock/item')->getCollection();
        /** @var $collection Mage_Cms_Model_Mysql4_Block_Collection */
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepearing columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {

        $this->addColumn('title', array(
            'header'    => Mage::helper('newsblock')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ));

        $this->addColumn('created_at', array(
            'header'    => Mage::helper('newsblock')->__('Created At'),
            'index'     => 'created_at',
            'type'      => 'date',
        ));

        $this->addColumn('updated_at', array(
            'header'    => Mage::helper('newsblock')->__('Updated At'),
            'index'     => 'updated_at',
            'type'      => 'date',
        ));

        $this->addColumn('description', array(
            'header'    => Mage::helper('newsblock')->__('Description'),
            'align'     => 'left',
            'index'     => 'description',
        ));

        $this->addColumn('item_status', array(
            'header'    => Mage::helper('cms')->__('Status'),
            'align'     => 'left',
            'type'      => 'options',
            'options'   => Mage::getModel('newsblock/source_status')->toArray(),
            'index'     => 'item_status'
        ));


        return parent::_prepareColumns();
    }

    /**
     * Prepearing mass actions
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('item_id');
        $this->getMassactionBlock()->setIdFieldName('item_id');
        $this->getMassactionBlock()
            ->addItem('delete',
                array(
                    'label' => Mage::helper('newsblock')->__('Delete'),
                    'url' => $this->getUrl('*/*/massDelete'),
                    'confirm' => Mage::helper('newsblock')->__('Are you sure?')
                )
            )
            ->addItem('status',
                array(
                    'label' => Mage::helper('newsblock')->__('Update status'),
                    'url' => $this->getUrl('*/*/massStatus'),
                    'additional' =>
                        array('item_status' =>
                            array(
                                'name'   => 'item_status',
                                'type'   => 'select',
                                'class'  => 'required-entry',
                                'label'  => Mage::helper('newsblock')->__('Status'),
                                'values' => Mage::getModel('newsblock/source_status')
                                    ->toOptionArray()
                            )
                        )
                )
            );

        return $this;
    }

    /**
     * Row click url
     *
     * @param string $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('item_id' => $row->getId()));
    }

}
