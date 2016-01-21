<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 5/14/14
 * Time: 3:07 PM
 */ 
class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Cms_Block extends Mage_Adminhtml_Block_Cms_Block
{
    public function __construct()
    {
        $this->_controller = 'cms_block';
        $this->_headerText = Mage::helper('cms')->__('Static Blocks');
        $this->_addButtonLabel = Mage::helper('cms')->__('Add New Block');
        parent::__construct();
    }
}