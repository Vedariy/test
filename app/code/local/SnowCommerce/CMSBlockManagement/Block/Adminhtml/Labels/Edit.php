<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2/25/14
 * Time: 6:04 PM
 */

class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Labels_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'sc_cmsblockmanagement';
        $this->_controller = 'adminhtml_labels';
        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('sc_cmsblockmanagement')->__('Save Label'));
        $this->_addButton('delete', array(
            'label'     => $this->__('Delete Label'),
            'onclick'   => "setLocation('".$this->getDeleteLabelUrl()."')",
            'class'     => 'delete'
        ));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
    }

    public function getHeaderText()
    {
        if ($this->getRequest()->getParam('label_id')) {
            return Mage::helper('sc_cmsblockmanagement')->__('Edit Label');
        }
        else {
            return Mage::helper('sc_cmsblockmanagement')->__('New Label');
        }
    }

    public function getDeleteLabelUrl()
    {
        return $this->getUrl('*/label/delete', array('label_id' => $this->getRequest()->getParam('label_id')));
    }
}