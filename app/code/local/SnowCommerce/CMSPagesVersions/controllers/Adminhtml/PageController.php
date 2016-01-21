<?php

/**
 * Class SnowCommerce_CMSPagesVersions_Adminhtml_PageController
 */
class SnowCommerce_CMSPagesVersions_Adminhtml_PageController extends Mage_Adminhtml_Controller_Action
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
            ->_title($this->__('Pages'))
            ->_title($this->__('Manage Versions'));

        $versionId = $this->getRequest()->getParam('version_id');
        $pageModel = Mage::getModel('cms/page');
        $versionModel = Mage::getModel('sc_cmspagesversions/page');

        if ($versionId) {
            $versionModel->load($versionId);
            $pageModel->setData(unserialize($versionModel->getContent()));
            if (!$pageModel->getPageId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('sc_cmspagesversions')->__('This page no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        Mage::register('cms_page_version', $pageModel);

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
                $model = Mage::getModel('sc_cmspagesversions/page');
                $pageModel = Mage::getModel('cms/page');
                $model->load($id);
                $pageModel->setData(unserialize($model->getContent()));
                $pageModel->save();
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('cms')->__('The version has been confirmed as actual.'));
                $this->_redirect('*/cms_page/edit', array('page_id' => $model->getData('page_id')));
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/cms_page/edit', array('page_id' => $model->getData('page_id')));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('Unable to find a page to delete.'));
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
                $model = Mage::getModel('sc_cmspagesversions/page');
                $model->load($id);
                if($model->getIsActual() == 1)
                {
                    Mage::getSingleton('adminhtml/session')->addError(
                        Mage::helper('cms')->__("Actual version can't be deleted."));
                    $this->_redirect('*/page/index', array('version_id' => $this->getRequest()->getParam('version_id')));
                    return;
                }
                $model->delete();
                // display success message
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('cms')->__('The version has been deleted.'));
                $this->_redirect('*/cms_page/edit', array('page_id' => $model->getData('page_id')));
                return;

            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
                // go back to edit form
                $this->_redirect('*/cms_page/edit', array('page_id' => $model->getData('page_id')));
                return;
            }
        }
        // display error message
        Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('Unable to find a version to delete.'));
        // go to grid

        $this->_redirect('*/*/*');
    }
}