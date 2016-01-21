<?php
/**
 * Class SnowCommerce_CMSVersions_Model_Mysql4_Page_Collection
 */

class SnowCommerce_CMSPagesVersions_Model_Mysql4_Page_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('sc_cmspagesversions/page');
    }
}