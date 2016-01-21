<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2/25/14
 * Time: 9:11 PM
 */

class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Labels_Renderer_Color extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value =  $row->getData($this->getColumn()->getIndex());
        return Mage::Helper('sc_cmsblockmanagement')->GetColorByHex($value);
    }
}