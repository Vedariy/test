<?php
/**
 * Class SnowCommerce_CMSVersions_Adminhtml_BlockController
 */

class SnowCommerce_CMSBlockManagement_Adminhtml_BlockController extends Mage_Adminhtml_Controller_Action
{
    /**
     * @return $this
     */
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
            ->_title($this->__('Manage Versions'));

        $versionId = $this->getRequest()->getParam('version_id');
        $blockModel = Mage::getModel('cms/block');
        $versionModel = Mage::getModel('sc_cmsblockmanagement/versions');

        if ($versionId) {
            $versionModel->load($versionId);
            $data = unserialize($versionModel->getContent());
            if(@$data['sc_block_label'])
            {
                $data['sc_block_label'] = unserialize($data['sc_block_label']);
            }
            $blockModel->setData($data);
            if (!$blockModel->getBlockId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sc_cmsblockmanagement')->__('Some error occured, try to load another version'));
                $this->_redirect('*/cms_block/');
                return;
            }
        }

        Mage::register('cms_block_version', $blockModel);

        $this->_initAction()
            ->renderLayout();
    }

    /**
     * Confirm action
     */
    public function confirmAction()
    {
        if ($id = $this->getRequest()->getParam('version_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('sc_cmsblockmanagement/versions');
                $blockModel = Mage::getModel('cms/block');
                Mage::log($blockModel); exit;
                $model->load($id);
                $blockModel->setData(unserialize($model->getContent()));
                $blockModel->save();
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('cms')->__('The version has been confirmed as actual.'));
                $this->_redirect('*/cms_block/edit', array('block_id' => $model->getData('block_id')));
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/cms_block/edit', array('block_id' => $model->getData('block_id')));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('Unable to find a block to delete.'));
        // go to grid
        $this->_redirect('*/*/*');
    }

    /**
     * Delete action
     */
    public function deleteAction()
    {
        // check if we know what should be deleted
        if ($id = $this->getRequest()->getParam('version_id')) {
            try {
                // init model and delete
                $model = Mage::getModel('sc_cmsblockmanagement/versions');
                $model->load($id);
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('cms')->__('The version has been deleted.'));
                $this->_redirect('*/cms_block/edit', array('block_id' => $model->getData('block_id')));
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/cms_block/edit', array('block_id' => $model->getData('block_id')));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('sc_cmsblockmanagement')->__('Unable to find a block to delete.'));
        // go to grid
        $this->_redirect('*/*/*');
    }

    public function validateAction()
    {
        $value = $this->getRequest()->getParam('value');
        $block_id = $this->getRequest()->getParam('block_id');
        $model = Mage::getModel('cms/block')->load($block_id);
        $origIdentifier = $model->getData('identifier');

        if($origIdentifier == $value)
        {
            echo true;
        }
        else
        {
            $resource = Mage::getSingleton('core/resource');
            $readConnection = $resource->getConnection('core_read');
            $table = $resource->getTableName('cms/block');
            $result = $readConnection->fetchCol('SELECT * FROM ' . $table . " WHERE identifier='" . $value . "'");
            if($result)
            {
                echo false;
            }
            else
            {
                echo true;
            }
        }
    }
}