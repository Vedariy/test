<?php
/**
 * Class SnowCommerce_CMSVersions_Block_Adminhtml_Block_Edit_Tab_Main
 */

class SnowCommerce_CMSBlockManagement_Block_Adminhtml_Edit_Tab_Main
    extends Mage_Adminhtml_Block_Widget_Form
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('block_form');
        $this->setTitle(Mage::helper('sc_cmsblockmanagement')->__('Block Information'));
    }

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }

    protected function _prepareForm()
    {
        $model = Mage::registry('cms_block');
        $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();
        $form = new Varien_Data_Form(
            array('id' => 'edit_form', 'action' => $this->getData('action'), 'method' => 'post')
        );

        $form->setHtmlIdPrefix('block_');

        $fieldset = $form->addFieldset('base_fieldset', array('legend'=>Mage::helper('sc_cmsblockmanagement')->__('General Information'), 'class' => 'fieldset-wide'));

        if ($model->getBlockId()) {
            $fieldset->addField('block_id', 'hidden', array(
                'name' => 'block_id',
            ));
        }

        $fieldset->addField('sc_keep_versions', 'select', array(
            'name'      => 'sc_keep_versions',
            'label'     => Mage::helper('cms')->__('Keep versions of this block?'),
            'title'     => Mage::helper('cms')->__('Keep versions of this block?'),
            'values'    => $yesnoSource,
        ));

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
            'class'     => 'validate-xml-identifier validate-identifier',
        ));

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
//        $form->setUseContainer(true);
        $this->setForm($form);

        return parent::_prepareForm();
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('sc_cmsblockmanagement')->__('Block Information');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('sc_cmsblockmanagement')->__('Block Information');
    }

    /**
     * Returns status flag about this tab can be shown or not
     *
     * @return true
     */
    public function canShowTab()
    {
        return true;
    }

    /**
     * Returns status flag about this tab hidden or not
     *
     * @return true
     */
    public function isHidden()
    {
        return false;
    }

    /**
     * Check permission for passed action
     *
     * @param string $action
     * @return bool
     */
    protected function _isAllowedAction($action)
    {
        return Mage::getSingleton('admin/session')->isAllowed('cms/page/' . $action);
    }
}