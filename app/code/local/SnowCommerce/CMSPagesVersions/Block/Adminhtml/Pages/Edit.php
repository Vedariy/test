<?php
/**
 * Class SnowCommerce_CMSVersions_Block_Adminhtml_Pages_Edit
 */

class SnowCommerce_CMSPagesVersions_Block_Adminhtml_Pages_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'sc_cmspagesversions';
        $this->_controller = 'adminhtml_pages';
        $this->_headerText = Mage::helper('sc_cmspagesversions')->__('Pages versions');
        parent::__construct();

        $this->addButton('delete', array(
            'label'     => Mage::helper('sc_cmspagesversions')->__('Delete Version'),
            'onclick'   => "setLocation('".$this->getDeleteVersionUrl()."')",
            'class'     => 'delete',
        ),100);

        $this->_addButton('go_back', array(
            'label'     => Mage::helper('sc_cmspagesversions')->__('Back'),
            'onclick'   => "setLocation('".$this->getPageUrl()."')",
            'class'     => 'back',
        ),0);

        $this->_addButton('confirm', array(
            'label'     => Mage::helper('sc_cmspagesversions')->__('Set as Actual'),
            'onclick'   => "setLocation('".$this->getConfirmVersionUrl()."')",
            'class'     => 'save',
        ),1);

        $this->_removeButton('back');
        $this->_removeButton('reset');
        $this->_removeButton('save');
    }

    /**
     * Gets URL of deleting version
     * @return string
     */
    public function getDeleteVersionUrl()
    {
        return $this->getUrl('*/page/delete', array('version_id' => $this->getRequest()->getParam('version_id')));
    }


    /**
     * Gets URL of confirming version
     * @return string
     */
    public function getConfirmVersionUrl()
    {
        return $this->getUrl('*/page/confirm', array('version_id' => $this->getRequest()->getParam('version_id')));
    }

    /**
     * Gets page's URL
     * @return string
     */
    public function getPageUrl()
    {
        $pageModel = Mage::registry('cms_page_version');
        return $this->getUrl('*/cms_page/edit', array('page_id' => $pageModel->getData('page_id')));
    }
}