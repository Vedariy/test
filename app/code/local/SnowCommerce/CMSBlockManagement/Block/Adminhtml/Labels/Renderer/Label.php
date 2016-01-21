<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2/25/14
 * Time: 9:11 PM
 */

class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Labels_Renderer_Label extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    public function render(Varien_Object $row)
    {
        $value = $row->getData($this->getColumn()->getIndex());
        if(is_array($value))
        {
            $res = "<div>";
            $keys = array_keys($value);
            $last_key = end($keys);
            foreach($value as $key => $val)
            {
                $data = Mage::Helper('sc_cmsblockmanagement')->GetParsByLabelId($val);
                $res .= '<div style="color:#FFF;font-weight:bold;background:#'.$data['label_color'].';border-radius:8px;width:100%;text-align:center">'.$data['label_name'].'</div>';
                if($key != $last_key)
                {
                    $res .= '<div style="height:1px;width:100%"></div>';
                }
            }

            $res .= "</div>";
            return $res;

        } elseif($value)
        {
            $data = Mage::Helper('sc_cmsblockmanagement')->GetParsByLabelId($value);
            return '<div style="color:#FFF;font-weight:bold;background:#'.$data['label_color'].';border-radius:8px;width:100%;text-align:center">'.$data['label_name'].'</div>';
        }
    }
}