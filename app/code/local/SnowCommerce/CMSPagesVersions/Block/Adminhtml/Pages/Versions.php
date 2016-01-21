<?php
/**
 * Created by JetBrains PhpStorm.
 * User: alex
 * Date: 25.07.13
 * Time: 16:08
 * To change this template use File | Settings | File Templates.
 */

class SnowCommerce_CMSPagesVersions_Block_Adminhtml_Pages_Versions extends Mage_Adminhtml_Block_Widget_Grid_Container
    implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function __construct()
    {
        $this->_controller = 'adminhtml_pages_versions';
        $this->_blockGroup = 'sc_cmspagesversions';
        $this->_headerText = Mage::helper('sc_cmspagesversions')->__('Versions available. Actual version is always at the bottom of the list.');

        parent::__construct();

        $this->_removeButton('add');
    }

    /**
     * Prepare label for tab
     *
     * @return string
     */
    public function getTabLabel()
    {
        return Mage::helper('cms')->__('Versions');
    }

    /**
     * Prepare title for tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return Mage::helper('cms')->__('Versions');
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
}