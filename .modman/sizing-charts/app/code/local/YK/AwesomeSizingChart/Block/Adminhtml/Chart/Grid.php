<?php
/**
 * YK extension for Magento
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
 * the YK AwesomeSizingChart module to newer versions in the future.
 * If you wish to customize the YK AwesomeSizingChart module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   YK
 * @package    YK_AwesomeSizingChart
 * @copyright  Copyright (C) 2013 Yaroslav Kravchuk
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class YK_AwesomeSizingChart_Block_Adminhtml_Chart_Grid 
    extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
         
        $this->setDefaultSort('id');
        $this->setId('sizing_chart_grid');
        $this->setDefaultDir('asc');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('yk_asc/chart_collection');
        $this->setCollection($collection);
         
        return parent::_prepareCollection();
    }
     
    protected function _prepareColumns()
    {
        $this->addColumn('id',
            array(
                'header'=> $this->__('ID'),
                'align' =>'right',
                'width' => '100px',
                'index' => 'id'
            )
        );

        $this->addColumn('ptn',
            array(
                'header'=> $this->__('Product Type Number'),
                'width' => '600px',
                'index' => 'ptn'
            )
        );

        return parent::_prepareColumns();
    }

    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('sizing_chart_id');
        $this->getMassactionBlock()->setFormFieldName('id');
        $this->getMassactionBlock()->addItem('delete', array(
            'label'=> Mage::helper('yk_asc')->__('Delete'),
            'url'  => $this->getUrl('*/*/massDelete', array('' => '')),
            'confirm' => Mage::helper('yk_asc')->__('Are you sure?')
        ));

        return $this;
    }
     
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
    }
}
