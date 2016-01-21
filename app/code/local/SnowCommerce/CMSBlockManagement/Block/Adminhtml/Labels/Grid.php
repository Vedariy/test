<?php
/**
 * Produced by SnowCommerce development team
 */

class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Labels_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('BlockLabelsGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('sc_cmsblockmanagement/labels')->getCollection();
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('entity_id', array(
            'header'    => Mage::helper('sc_cmsblockmanagement')->__('Label ID'),
            'align'     => 'left',
            'width'     => '50px',
            'index'     => 'entity_id',
        ));


        $this->addColumn('label_name', array(
            'header'    => Mage::helper('sc_cmsblockmanagement')->__('Label Name'),
            'align'     => 'left',
            'index'     => 'label_name',
        ));

        $this->addColumn('label_color', array(
            'header'    => Mage::helper('sc_cmsblockmanagement')->__('Label Color'),
            'align'     => 'left',
            'index'     => 'label_color',
            'renderer'  => 'SnowCommerce_CMSBlockManagement_Block_Adminhtml_Labels_Renderer_Color',
        ));

        return parent::_prepareColumns();
    }

    /**
     * Gets row's URL
     * @param $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/label/edit', array('label_id' => $row->getId()));
    }
}