<?php
/**
 * Class SnowCommerce_CMSVersions_Block_Adminhtml_Block_Edit
 */

class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        $this->_blockGroup = 'sc_cmsblockmanagement';
        $this->_controller = 'adminhtml';

        parent::__construct();

        $this->_updateButton('save', 'label', Mage::helper('cms')->__('Save Block'));

        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save and Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        if($this->getRequest()->getParam('block_id'))
        {
            $this->_addButton('delete', array(
                'label'     => Mage::helper('adminhtml')->__('Delete Block'),
                'onclick'   => "setLocation('".$this->getDeleteBlockUrl()."')",
                'class'     => 'delete',
            ), -200);
        }

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('block_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'block_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'block_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    /**
     * Get edit form container header text
     *
     * @return string
     */
    public function getHeaderText()
    {
        if (Mage::registry('cms_block')->getId()) {
            return Mage::helper('cms')->__("Edit Block '%s'", $this->htmlEscape(Mage::registry('cms_block')->getTitle()));
        }
        else {
            return Mage::helper('cms')->__('New Block');
        }
    }

    public function getDeleteBlockUrl()
    {
        return $this->getUrl('*/cms_block/delete', array('block_id' => $this->getRequest()->getParam('block_id')));
    }
}