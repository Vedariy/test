<?php
/**
 * Produced by SnowCommerce development team
 */ 
class SnowCommerce_CMSBlockManagement_Helper_Data extends Mage_Core_Helper_Abstract
{
    public function GetColorByHex($value)
    {
        $values = array(
            '808080'     => 'Grey',
            'FF0000'     => 'Red',
            'FFFF00'     => 'Yellow',
            '00FF00'     => 'Green',
            '0000FF'     => 'Blue',
            'FFA500'     => 'Orange',
            '800080'     => 'Purple'
        );

        return $values[$value];
    }

    public function GetParsByLabelId($value)
    {
        $collection = Mage::getModel('sc_cmsblockmanagement/labels')->getCollection();
        foreach($collection as $model)
        {
            if($model->getLabelName() == $value)
            {
                $data = $model->getData();
                return $data;
            }
        }
        return 0;
    }

    public function GetBlockIdentifierByBlockId($blockId)
    {
        $collection = Mage::getModel('cms/block')->getCollection()
            ->addFieldToFilter('block_id',array(
                "eq" => $blockId
            ));
        return $collection->getFirstItem()->getIdentifier();
    }
}