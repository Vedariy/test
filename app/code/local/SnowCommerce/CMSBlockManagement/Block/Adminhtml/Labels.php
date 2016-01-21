<?php
/**
 * Produced by SnowCommerce development team
 */
class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Labels extends Mage_Adminhtml_Block_Widget_Grid_Container

{
    public function __construct()
    {
        $this->_controller = 'adminhtml_labels';
        $this->_blockGroup = 'sc_cmsblockmanagement';
        $this->_headerText = Mage::helper('sc_cmsblockmanagement')->__('Labels available.');

        parent::__construct();
    }
}