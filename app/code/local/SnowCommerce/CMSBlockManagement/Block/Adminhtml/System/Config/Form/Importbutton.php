<?php
/**
 * Produced by SnowCommerce development team
 */
class SnowCommerce_CMSBlockManagement_Block_Adminhtml_System_Config_Form_Importbutton extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    /**
     * Set template
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setTemplate('sc_cmsblockmanagement/system/config/importbutton.phtml');
    }

    /**
     * Return element html
     *
     * @param  Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->_toHtml();
    }

    /**
     * Generate button html
     *
     * @return string
     */
    public function getButtonHtml()
    {
        $button = $this->getLayout()->createBlock('adminhtml/widget_button')
            ->setData(array(
                'id'        => 'blockimport_importbutton',
                'label'     => $this->Helper('sc_cmsblockmanagement')->__('Import'),
                'onclick'   => 'importBlocks()'
            ));

        return $button->toHtml();
    }
}