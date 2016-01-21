<?php
/**
 * Class SnowCommerce_CMSVersions_Model_Mysql4_Page
 */

class SnowCommerce_CMSPagesVersions_Model_Mysql4_Page extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('sc_cmspagesversions/sc_cmspagesversions','entity_id');
    }
}