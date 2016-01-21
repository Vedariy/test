<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 4/29/14
 * Time: 5:13 PM
 */

class SnowCommerce_CMSBlockManagement_Model_Export extends Mage_Core_Model_Abstract
{
    public function exportBlocks(&$doc,&$main_root)
    {
        $collection = Mage::getModel('cms/block')->getCollection();
        if($collection->count() != 0)
        {
            $root = $doc->createElement('blocks');
            $root = $main_root->appendChild($root);
            Mage::log("Blocks to be exported: " . $collection->count(),null,'cmsblockmanagement_export.log');
            foreach($collection as $_block)
            {
                $_blockData = $_block->getData();
                $occ = $doc->createElement('block');
                $occ = $root->appendChild($occ);
                foreach ( $_blockData as $fieldName => $fieldValue )
                {
                    if ($fieldName == 'block_id') continue;
                    $child = $doc->createElement($fieldName);
                    $child = $occ->appendChild($child);
                    if ( is_array($fieldValue) ) {
                        $value = $doc->createTextNode(implode( "|", $fieldValue ));
                        $value = $child->appendChild($value);
                    } else {
                        $value = $doc->createTextNode($fieldValue);
                        $value = $child->appendChild($value);
                    }

                }
            }
            Mage::log("Blocks have been exported.",null,'cmsblockmanagement_export.log');
        }
    }

    public function exportVersions(&$doc,&$main_root)
    {
        $collection = Mage::getModel('sc_cmsblockmanagement/versions')->getCollection();
        if($collection->count() != 0)
        {
            $root = $doc->createElement('versions');
            $root = $main_root->appendChild($root);
            Mage::log("Versions to be exported: " . $collection->count(),null,'cmsblockmanagement_export.log');
            foreach($collection as $version)
            {
                $versionData = $version->getData();
                $occ = $doc->createElement('version');
                $occ = $root->appendChild($occ);
                foreach ( $versionData as $fieldName => $fieldValue )
                {
                    if ($fieldName == 'entity_id' || $fieldName == 'is_actual') continue;
                    $child = $doc->createElement($fieldName);
                    $child = $occ->appendChild($child);
                    if ( is_array($fieldValue) ) {
                        $value = $doc->createTextNode(implode( "|", $fieldValue ));
                        $value = $child->appendChild($value);
                    } else {
                        $value = $doc->createTextNode($fieldValue);
                        $value = $child->appendChild($value);
                    }

                }
            }
            Mage::log("Versions have been exported.",null,'cmsblockmanagement_export.log');
        }
    }

    public function exportLabels(&$doc,&$main_root)
    {
        $collection = Mage::getModel('sc_cmsblockmanagement/labels')->getCollection();
        if($collection->count() != 0)
        {
            $root = $doc->createElement('labels');
            $root = $main_root->appendChild($root);
            Mage::log("Labels to be exported: " . $collection->count(),null,'cmsblockmanagement_export.log');
            foreach($collection as $version)
            {
                $labelData = $version->getData();
                $occ = $doc->createElement('label');
                $occ = $root->appendChild($occ);
                foreach ( $labelData as $fieldName => $fieldValue )
                {
                    if ($fieldName == 'entity_id') continue;
                    $child = $doc->createElement($fieldName);
                    $child = $occ->appendChild($child);
                    if ( is_array($fieldValue) ) {
                        $value = $doc->createTextNode(implode( "|", $fieldValue ));
                        $value = $child->appendChild($value);
                    } else {
                        $value = $doc->createTextNode($fieldValue);
                        $value = $child->appendChild($value);
                    }

                }
            }
            Mage::log("Labels have been exported.",null,'cmsblockmanagement_export.log');
        }
    }
}