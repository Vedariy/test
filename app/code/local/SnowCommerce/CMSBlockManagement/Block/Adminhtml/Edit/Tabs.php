<?php
/**
 * Class SnowCommerce_CMSVersions_Block_Adminhtml_Block_Edit_Tabs
 */

class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('block_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('sc_cmsblockmanagement')->__('Block Information'));
    }
}
