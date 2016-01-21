<?php
/**
 * Class SnowCommerce_CMSVersions_Block_Adminhtml_Pages_Edit_Form
 */

class SnowCommerce_CMSPagesVersions_Block_Adminhtml_Pages_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{

    protected function _prepareForm()
    {
        $form = new Varien_Data_Form(array(
            'id' => 'edit_form',
            'action' => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post',
        ));

        $this->setForm($form);
        $form->setUseContainer(true);
        return parent::_prepareForm();
    }
}