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
 * @subpackage Block_Adminhtml_Newsblock_Grid
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
        $dateFormatIso = Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT);
        $this->addColumn('title', [
            'header'    => Mage::helper('newsblock')->__('Title'),
            'align'     => 'left',
            'index'     => 'title',
        ]);

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
