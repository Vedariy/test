<?php
/**
 * Class SnowCommerce_CMSVersions_Block_Adminhtml_Pages_Edit_Tabs
 */

class SnowCommerce_CMSPagesVersions_Block_Adminhtml_Pages_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

    public function __construct()
    {
        parent::__construct();
        $this->setId('page_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(Mage::helper('sc_cmspagesversions')->__('Page Information'));
    }
}
