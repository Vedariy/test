<?php
/**
 * Produced by SnowCommerce development team
 */

class SnowCommerce_CMSBlockManagement_Adminhtml_LabelController extends Mage_Adminhtml_Controller_Action
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
            ->_title($this->__('Manage Labels'));

        $this->_initAction()
            ->renderLayout();
    }

    public function newAction()
    {
        $this->_forward('edit');
    }

    public function editAction()
    {
        // 1. Get ID and create model
        $id = $this->getRequest()->getParam('label_id');
        $model = Mage::getModel('sc_cmsblockmanagement/labels');

        // 2. Initial checking
        if ($id) {
            $model->load($id);
            if (! $model->getId()) {
                Mage::getSingleton('adminhtml/session')->addError(Mage::helper('cms')->__('This label no longer exists.'));
                $this->_redirect('*/*/');
                return;
            }
        }

        // 3. Set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getFormData(true);

        if (! empty($data)) {
            $model->setData($data);
        }

        // 4. Register model to use later in blocks
        Mage::register('block_label', $model);
        $this->_initAction()
            ->_addContent($this->getLayout()->createBlock('sc_cmsblockmanagement/adminhtml_labels_edit')->setData('action',$this->getUrl('*/*/save')))
            ->renderLayout();
    }

    public function saveAction()
    {
        try{
            $data = $this->getRequest()->getParams();
            $model = Mage::getModel('sc_cmsblockmanagement/labels')
                ->load($data['entity_id'])
                ->setData($data);


            if(!isset($data['entity_id']))
            {
                $collection = Mage::getModel('sc_cmsblockmanagement/labels')->getCollection()
                    ->addFieldToFilter('label_name',$model['label_name']);
                $id = $collection->getFirstItem()->getData('entity_id');
                if($id)
                {
                    Mage::getSingleton('core/session')->addError('This label already exists! Choose another label name please or delete previous label with this name.');
                    return $this->_redirect('*/*/new/');
                }
            }

            $model->save();
            if(!$data['entity_id'])
            {
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Label added!'));
            }
            else
            {
                Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Label edited!'));
            }
            $this->_redirect('*/*/');
        }
        catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
            $this->_redirect('*/*/');
        }
    }

    public function deleteAction()
    {
        try {
            $data = $this->getRequest()->getParams();
            $model = Mage::getModel('sc_cmsblockmanagement/labels');
            $label = $model->load($data['label_id']);
            $label->delete();
            Mage::getSingleton('adminhtml/session')->addSuccess($this->__('Label deleted!'));
            $this->_redirect('*/*/');
        }
        catch(Exception $e) {
            Mage::getSingleton('adminhtml/session')->addError($this->__($e->getMessage()));
            $this->_redirect('*/*/');
        }
    }
}