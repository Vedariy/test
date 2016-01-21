<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alex
 * Date: 30.07.13
 * Time: 14:21
 * To change this template use File | Settings | File Templates.
 */

class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Versions_Version_Edit_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * Init form
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('block_form');
        $this->setTitle(Mage::helper('sc_cmsblockmanagement')->__('Block Information'));
    }

    /**
     * Load Wysiwyg on demand and Prepare layout
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    /**
     * @return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {
        $model = Mage::registry('cms_block_version');

        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('sc_cmsblockmanagement')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getBlockIdentifier()) {
            $fieldset->addField('block_identifier', 'hidden', array(
                'name' => 'block_identifier',
            ));
        }

        $collection = Mage::getModel('sc_cmsblockmanagement/labels')->getCollection();

        foreach($collection as $el)
        {
            $data = $el->getData();
            $labels[] = array (
                'value' => $data['label_name'],
                'label' => $data['label_name']
            );
        }

        if(isset($labels))
        {
            $fieldset->addField('sc_block_label', 'multiselect', array(
                'label'     => Mage::helper('sc_cmsblockmanagement')->__('Block label'),
                'required'  => false,
                'name'      => 'sc_block_label',
                'values'    => $labels,
                'disabled' => false,
                'readonly' => false,
                'tabindex' => 1
            ));
        }

        $fieldset->addField('title', 'text', array(
            'name'      => 'title',
            'label'     => Mage::helper('sc_cmsblockmanagement')->__('Block Title'),
            'title'     => Mage::helper('sc_cmsblockmanagement')->__('Block Title'),
            'required'  => true,
        ));

        $fieldset->addField('identifier', 'text', array(
            'name'      => 'identifier',
            'label'     => Mage::helper('sc_cmsblockmanagement')->__('Identifier'),
            'title'     => Mage::helper('sc_cmsblockmanagement')->__('Identifier'),
            'required'  => true,
            'class'     => 'validate-xml-identifier',
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field =$fieldset->addField('store_id', 'multiselect', array(
                'name'      => 'stores[]',
                'label'     => Mage::helper('sc_cmsblockmanagement')->__('Store View'),
                'title'     => Mage::helper('sc_cmsblockmanagement')->__('Store View'),
                'required'  => true,
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(false, true),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
        }
        else {
            $fieldset->addField('store_id', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $model->setStoreId(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('is_active', 'select', array(
            'label'     => Mage::helper('sc_cmsblockmanagement')->__('Status'),
            'title'     => Mage::helper('sc_cmsblockmanagement')->__('Status'),
            'name'      => 'is_active',
            'required'  => true,
            'options'   => array(
                '1' => Mage::helper('sc_cmsblockmanagement')->__('Enabled'),
                '0' => Mage::helper('sc_cmsblockmanagement')->__('Disabled'),
            ),
        ));
        if (!$model->getId()) {
            $model->setData('is_active', '1');
        }

        $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'label'     => Mage::helper('sc_cmsblockmanagement')->__('Content'),
            'title'     => Mage::helper('sc_cmsblockmanagement')->__('Content'),
            'style'     => 'height:36em',
            'required'  => true,
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig()
        ));

        $form->setValues($model->getData());
        $this->setForm($form);

        return parent::_prepareForm();
    }
}