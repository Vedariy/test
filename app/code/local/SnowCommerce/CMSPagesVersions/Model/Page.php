<?php
/**
 * Class SnowCommerce_CMSVersions_Model_Page
 */

class SnowCommerce_CMSPagesVersions_Model_Page extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('sc_cmspagesversions/page');
    }
}