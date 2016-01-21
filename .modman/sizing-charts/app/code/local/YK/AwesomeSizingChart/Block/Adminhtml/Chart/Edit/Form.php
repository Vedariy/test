<?php
/**
 * YK extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the YK AwesomeSizingChart module to newer versions in the future.
 * If you wish to customize the YK AwesomeSizingChart module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   YK
 * @package    YK_AwesomeSizingChart
 * @copyright  Copyright (C) 2013 Yaroslav Kravchuk
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class YK_AwesomeSizingChart_Block_Adminhtml_Chart_Edit_Form 
    extends Mage_Adminhtml_Block_Widget_Form
{
    /** 
     * Init class
     */
    public function __construct()
    {
        parent::__construct();
     
        $this->setId('sizing_chart_form');
        $this->setTitle($this->__('Sizing Chart Information'));
    }   
     
    /** 
     * Setup form fields for inserts/updates
     * 
     * return Mage_Adminhtml_Block_Widget_Form
     */
    protected function _prepareForm()
    {   
        $model = Mage::registry('sizing_chart');
     
        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'))),
            'method'    => 'post'
        )); 
     
        $fieldset = $form->addFieldset('base_fieldset', array(
            'legend'    => Mage::helper('yk_asc')->__('Sizing Chart information')
        )); 
     
        if ($model->getId()) {
            $fieldset->addField('id', 'hidden', array(
                'name' => 'id',
            )); 
        }   
     
        $fieldset->addField('ptn', 'text', array(
            'name'      => 'ptn',
            'label'     => Mage::helper('yk_asc')->__('Product type number'),
            'title'     => Mage::helper('yk_asc')->__('Product type number'),
            'required'  => true,
            'style'     => "width:273px"
        ));

        $fieldset->addField('content', 'editor', array(
            'name'      => 'content',
            'label'     => Mage::helper('yk_asc')->__('Content'),
            'title'     => Mage::helper('yk_asc')->__('Content'),
            'style'     => 'height:30em;width:500px;',
            'wysiwyg'   => true,
            'required'  => true,
            'config'    => Mage::getSingleton('cms/wysiwyg_config')->getConfig(),
            ));

        
        $form->setValues($model->getData());
        $form->setUseContainer(true);
        $this->setForm($form);
     
        return parent::_prepareForm();
    }   
}
