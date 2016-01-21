<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 4/29/14
 * Time: 5:11 PM
 */

class SnowCommerce_CMSBlockManagement_Model_Import extends Mage_Core_Model_Abstract
{
    public function getModel($entityType)
    {
        switch($entityType)
        {
            case 'blocks':
                return $result = 'cms/block';
            case 'versions':
                return $result = 'sc_cmsblockmanagement/versions';
            case 'labels':
                return $result = 'sc_cmsblockmanagement/labels';
            default:
                return $result = '';
        }
    }

    public function overwriteValidation($entityType, $instance)
    {
        switch($entityType)
        {
            case 'blocks':
                $collection = Mage::getModel('cms/block')->getCollection()
                    ->addFieldToSelect('identifier');
                foreach($collection as $element)
                {
                    if($element->getData('identifier') == (string)$instance->identifier)
                    {
                        Mage::log("Block with the identifier '" . $instance->identifier . "' is already exists.",null,'cmsblockmanagement_import.log');
                        return false;
                    }
                }
                return true;
                break;
            case 'versions':
                $blockCollection = Mage::getModel('cms/block')->getCollection()
                    ->addFieldToSelect('identifier');
                $versionCollection = Mage::getModel('sc_cmsblockmanagement/versions')->getCollection();
                $blockExisting = false;
                foreach($blockCollection as $element)
                {
                    if($element->getData('identifier') == (string)$instance->block_identifier)
                    {
                        $blockExisting = true;
                    }
                }
                if($blockExisting)
                {
                    foreach($versionCollection as $version)
                    {
                        if($version->getData('content') == (string)$instance->content)
                        {
                            Mage::log("Version from " . $version->getData('version') . " for '" . $instance->block_identifier . "' block is already exists.",null,'cmsblockmanagement_import.log');
                            return false;
                        }
                    }
                    return true;
                }
                else
                {
                    Mage::log("You're trying to import versions for block which doesn't exist. Import or create the block first!",null,'cmsblockmanagement_import.log');
                    return false;
                }
                break;
            case 'labels':
                $collection = Mage::getModel('sc_cmsblockmanagement/labels')->getCollection()
                    ->addFieldToSelect('label_name');
                foreach($collection as $element)
                {
                    if($element->getData('label_name') == (string)$instance->label_name)
                    {
                        Mage::log("Label with the name '" . $instance->label_name . "' is already exists.",null,'cmsblockmanagement_import.log');
                        return false;
                    }
                }
                return true;
                break;
            default:
                break;
        }
        return false;
    }

    public function killConflicts($entityType, $instance)
    {
        switch($entityType)
        {
            case 'blocks':
                $collection = Mage::getModel('cms/block')->getCollection()
                    ->addFieldToSelect('block_id')
                    ->addFieldToSelect('identifier');
                foreach($collection as $element)
                {
                    if($element->getData('identifier') == (string)$instance->identifier)
                    {
                        $model = Mage::getModel('cms/block')
                            ->load($element->getData('block_id'))
                            ->delete();
                        Mage::log("Block with the identifier '" . $instance->identifier . "' has been deleted.",null,'cmsblockmanagement_import.log');
                    }
                }
                return true;
                break;
            case 'versions':
                $blockCollection = Mage::getModel('cms/block')->getCollection()
                    ->addFieldToSelect('identifier');
                $versionCollection = Mage::getModel('sc_cmsblockmanagement/versions')->getCollection();
                $blockExisting = false;
                foreach($blockCollection as $element)
                {
                    if($element->getData('identifier') == (string)$instance->block_identifier)
                    {
                        $blockExisting = true;
                    }
                }
                if($blockExisting)
                {
                    foreach($versionCollection as $version)
                    {
                        if($version->getData('content') == (string)$instance->content)
                        {
                            Mage::log("Version from " . $version->getData('version') . " for '" . $instance->block_identifier . "' block has been deleted.",null,'cmsblockmanagement_import.log');
                            $version->delete();
                        }
                    }
                    return true;
                }
                else
                {
                    return false;
                }
                break;
            case 'labels':
                $collection = Mage::getModel('sc_cmsblockmanagement/labels')->getCollection()
                    ->addFieldToSelect('entity_id')
                    ->addFieldToSelect('label_name');
                foreach($collection as $element)
                {
                    if($element->getData('label_name') == (string)$instance->label_name)
                    {
                        $model = Mage::getModel('sc_cmsblockmanagement/labels')
                            ->load($element->getData('entity_id'))
                            ->delete();
                        Mage::log("Label with the name '" . $instance->label_name . "' has been deleted.",null,'cmsblockmanagement_import.log');
                    }
                }
                return true;
                break;
            default:
                break;
        }
        return false;
    }
}