<?php
/**
 * Produced by SnowCommerce development team
 */
class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Importexport extends Mage_Core_Block_Template

{
    public function __construct()
    {
        $this->_controller = 'adminhtml_importexport';
        $this->_blockGroup = 'sc_cmsblockmanagement';
        $this->_headerText = Mage::helper('sc_cmsblockmanagement')->__('Import & Export');

        parent::__construct();
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    public function getHeaderText()
    {
        return Mage::helper('sc_cmsblockmanagement')->__('Import & Export');
    }
}