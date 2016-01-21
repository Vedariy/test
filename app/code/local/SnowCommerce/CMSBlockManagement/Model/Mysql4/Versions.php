<?php
/**
 * Class SnowCommerce_CMSVersions_Model_Mysql4_Block
 */

class SnowCommerce_CMSBlockManagement_Model_Mysql4_Versions extends Mage_Core_Model_Mysql4_Abstract
{
    public function _construct()
    {
        $this->_init('sc_cmsblockmanagement/versions','entity_id');
    }
}