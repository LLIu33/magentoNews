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
 * @subpackage Widget_Grid
 * @author     Artem Grechko <agrechko@oggettoweb.com>
 */
class Oggetto_Newsblock_Block_Adminhtml_Newsblock_Grid
    extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('cmsNewsGrid');
        $this->setDefaultSort('item_id');
        $this->setDefaultDir('ASC');
    }

    /**
     * Preparing collection
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('newsblock/item')->getCollection();
        /** @var $collection Oggetto_Newsblock_Model_Resource_Item_Collection */
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Preparing columns
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $this->addColumn('title', [
            'header'    => Mage::helper('newsblock')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ]);

        if (!Mage::app()->isSingleStoreMode()) {
            $this->addColumn('store_id', [
                'header'        => Mage::helper('newsblock')->__('Store View'),
                'index'         => 'store_id',
                'type'          => 'store',
                'store_all'     => true,
                'store_view'    => true,
                'sortable'      => false,
                'filter_condition_callback'
                    => array($this, '_filterStoreCondition'),
            ]);
        }

        $this->addColumn('created_at', [
            'header'    => Mage::helper('newsblock')->__('Created At'),
            'index'     => 'created_at',
            'type'      => 'date',
            'format'    => $dateFormatIso
        ]);

        $this->addColumn('updated_at', [
            'header'    => Mage::helper('newsblock')->__('Updated At'),
            'index'     => 'updated_at',
            'type'      => 'date',
            'format'    => $dateFormatIso
        ]);

        $this->addColumn('description', [
            'header'    => Mage::helper('newsblock')->__('Description'),
            'align'     => 'left',
            'index'     => 'description',
        ]);

        $this->addColumn('item_status', [
            'header'    => Mage::helper('cms')->__('Status'),
            'align'     => 'left',
            'type'      => 'options',
            'options'   => Mage::getModel('newsblock/source_status')->toArray(),
            'index'     => 'item_status'
        ]);


        return parent::_prepareColumns();
    }

    protected function _filterStoreCondition($collection, $column)
    {
        if (!$value = $column->getFilter()->getValue()) {
            return;
        }
        $this->getCollection()->addStoreGridFilter($value);
    }

    /**
     * Run afterLoad
     *
     * @return void
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
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
                [
                    'label' => Mage::helper('newsblock')->__('Delete'),
                    'url' => $this->getUrl('*/*/massDelete'),
                    'confirm' => Mage::helper('newsblock')->__('Are you sure?')
                ]
            )
            ->addItem('status',
                [
                    'label' => Mage::helper('newsblock')->__('Update status'),
                    'url' => $this->getUrl('*/*/massStatus'),
                    'additional' =>
                        ['item_status' =>
                            [
                                'name'   => 'item_status',
                                'type'   => 'select',
                                'class'  => 'required-entry',
                                'label'  => Mage::helper('newsblock')->__('Status'),
                                'values' => Mage::getModel('newsblock/source_status')
                                    ->toOptionArray()
                            ]
                        ]
                ]
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
        return $this->getUrl('*/*/edit', ['item_id' => $row->getId()]);
    }

}
