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
class YK_AwesomeSizingChart_Block_Adminhtml_Chart_Edit 
    extends Mage_Adminhtml_Block_Widget_Form_Container
{
    /** 
     * Init class
     */
    public function __construct()
    {   
        $this->_blockGroup = 'yk_asc';
        $this->_controller = 'adminhtml_chart';
     
        parent::__construct();
     
        $this->_updateButton('save', 'label', $this->__('Save Sizing Chart'));
        $this->_updateButton('delete', 'label', $this->__('Delete Sizing Chart'));
    }

    protected function _prepareLayout() {
        parent::_prepareLayout();
        if (Mage::getSingleton('cms/wysiwyg_config')->isEnabled()) {
            $this->getLayout()->getBlock('head')->setCanLoadTinyMce(true);
        }
    }
     
    /** 
     * Get Header text
     *
     * @return string
     */
    public function getHeaderText()
    {   
        if (Mage::registry('sizing_chart')->getId()) {
            return $this->__('Edit Sizing Chart');
        }   
        else {
            return $this->__('New Sizing Chart');
        }   
    }   
}
