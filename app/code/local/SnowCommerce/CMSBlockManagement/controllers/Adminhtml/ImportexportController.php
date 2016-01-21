<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 3/25/14
 * Time: 2:39 PM
 */
class SnowCommerce_CMSBlockManagement_Adminhtml_ImportexportController extends Mage_Adminhtml_Controller_Action
{
    public function _initAction()
    {
        $this->loadLayout();
        return $this;
    }

    /**
     * Index action
     */
    public function indexAction()
    {
        $this->_title($this->__('CMS'))
            ->_title($this->__('Block'))
            ->_title($this->__('Import & Export'));
        $this->loadLayout();
        $this->_initAction()
            ->renderLayout();
    }

    public function exportAction()
    {
        //preparing paths
        $exportOptions = $this->getRequest()->getParam('export_options');
        $_magentoPath = Mage::getBaseDir();
        $_xmlPath = $_magentoPath . "/var/export";
        $xmlFile = $_xmlPath . "/cms_block_export.xml";
        if (!file_exists($_xmlPath)) {
            mkdir($_xmlPath, 0777);
            Mage::log("The directory $_xmlPath was successfully created.",null,'cmsblockmanagement_export.log');
        } else {
            Mage::log("The directory $_xmlPath exists.",null,'cmsblockmanagement_export.log');
        }

        //creating new doc
        $doc = new DomDocument('1.0', 'UTF-8');
        $doc->preserveWhiteSpace = false;
        $doc->formatOutput = true;
        Mage::log('-----=====-----=====-----=====-----=====-----',null,'cmsblockmanagement_export.log');
        Mage::log('CMS BLOCK MANAGEMENT - BLOCK EXPORT',null,'cmsblockmanagement_export.log');
        Mage::log( "Export starts",null,'cmsblockmanagement_export.log');
        $main_root = $doc->createElement('cms_export');
        $main_root = $doc->appendChild($main_root);
        $model = Mage::getModel('sc_cmsblockmanagement/export');

        foreach($exportOptions as $index => $option)
        {
            switch($option)
            {
                case 'blocks':
                    $model->exportBlocks($doc,$main_root);
                    break;
                case 'versions':
                    $model->exportVersions($doc,$main_root);
                    break;
                case 'labels':
                    $model->exportLabels($doc,$main_root);
                    break;
            }
        }
        $doc->save( $xmlFile );
        Mage::log("Export has been finished successfully.",null,'cmsblockmanagement_export.log');
        Mage::log('-----=====-----=====-----=====-----=====-----',null,'cmsblockmanagement_export.log');
    }

    public function importAction()
    {
        Mage::log('-----=====-----=====-----=====-----=====-----',null,'cmsblockmanagement_import.log');
        Mage::log('CMS BLOCK MANAGEMENT - BLOCK IMPORT',null,'cmsblockmanagement_import.log');
        Mage::log( "Import starts",null,'cmsblockmanagement_import.log');
        $importOptions = $this->getRequest()->getParam('import_options');
        $filename = explode(".", $_FILES["block_management_import"]["name"]);
        $extension = strtolower(end($filename));
        if(($_FILES['block_management_import']['type'] == 'text/xml') && ($extension == 'xml'))
        {
            if ($_FILES["block_management_import"]["error"] > 0)
            {
                Mage::log("File uploading has returned error code: " . $_FILES["block_management_import"]["error"],null,'cmsblockmanagement_import.log');
            }
            elseif(is_uploaded_file($_FILES['block_management_import']['tmp_name']))
            {
                $string = file_get_contents($_FILES['block_management_import']['tmp_name']);
                $import = new SimpleXMLElement($string);
                $importModel = Mage::getModel('sc_cmsblockmanagement/import');
                foreach($import->children() as $child)
                {
                    $entityType = $child->getName();
                    foreach($child as $instance)
                    {
                        $validation = $importModel->overwriteValidation($entityType, $instance);
                        if($importOptions[0] == 'skip' && !($validation))
                        {
                            continue;
                        }
                        elseif($importOptions[0] == 'overwrite' && !($validation))
                        {
                            $importModel->killConflicts($entityType,$instance);
                        }
                        $modelName = $importModel->getModel($entityType);
                        $model = Mage::getModel($modelName);
                        $data = array();
                        foreach($instance as $name => $field)
                        {
                            if($name == 'sc_block_label')
                            {
                                $data[(string)$name] = (string)$field;
                                $data[(string)$name] = unserialize($data[(string)$name]);
                            }
                            else
                            {
                                $data[(string)$name] = (string)$field;
                            }
                        }
                        $model->setData($data);
                        $model->save();
                    }
                }

                Mage::log("Import has been finished successfully.",null,'cmsblockmanagement_import.log');
                Mage::log('-----=====-----=====-----=====-----=====-----',null,'cmsblockmanagement_import.log');
                $this->_getSession()->addSuccess(Mage::helper('sc_cmsblockmanagement')->__("Blocks have been imported successfully."));
                $this->_redirectReferer();
            }
            else
            {
                $this->_getSession()->addError(Mage::helper('sc_cmsblockmanagement')->__("Upload error."));
                $this->_redirectReferer();
            }
        }
        else
        {
            $this->_getSession()->addError(Mage::helper('sc_cmsblockmanagement')->__("Invalid file!"));
            $this->_redirectReferer();
        }
    }
}