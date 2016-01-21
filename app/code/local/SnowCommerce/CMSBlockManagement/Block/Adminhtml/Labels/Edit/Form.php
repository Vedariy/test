<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 2/25/14
 * Time: 6:12 PM
 */
class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Labels_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    public function __construct()
    {
        parent::__construct();

        $this->setId('edit_form');
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('block_label');

        $form = new Varien_Data_Form(array(
            'id'     => 'edit_form',
            'action' => $this->getUrl('*/*/save',array('id' => $this->getRequest()->getParam('id'))),
            'method' => 'post'
        ));

        $fieldset = $form->addFieldset('base_fieldset',array(
            'legend'    => $this->getRequest()->getParam('label_id') ? Mage::helper('sc_cmsblockmanagement')->__('Edit existing label') : Mage::helper('sc_cmsblockmanagement')->__('Add new label'),
            'class'     => 'fieldset-wide'
        ));

        if($model->getId()){
            $fieldset->addField('entity_id','hidden',array(
                'name' => 'entity_id',
            ));
        }

        $fieldset->addField('label_name','text',array(
            'name'      => 'label_name',
            'label'     => Mage::helper('sc_cmsblockmanagement')->__('Label Name'),
            'title'     => Mage::helper('sc_cmsblockmanagement')->__('Label Name'),
            'required'  => true
        ));
        $fieldset->addField('label_color', 'select', array(
            'name'      => 'label_color',
            'label'     => Mage::helper('sc_cmsblockmanagement')->__('Label Color'),
            'title'     => Mage::helper('sc_cmsblockmanagement')->__('Label Color'),
            'class'     => 'required-entry',
            'required'  => false,
            'options' => array(
                '808080'     => 'Grey',
                'FF0000'     => 'Red',
                'FFFF00'     => 'Yellow',
                '00FF00'     => 'Green',
                '0000FF'     => 'Blue',
                'FFA500'     => 'Orange',
                '800080'     => 'Purple'
            ),
            'disabled' => false,
            'readonly' => false,
            'tabindex' => 1
        ));


        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }
}