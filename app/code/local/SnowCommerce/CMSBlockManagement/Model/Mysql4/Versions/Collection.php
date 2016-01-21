<?php
/**
 * Class SnowCommerce_CMSVersions_Model_Mysql4_Block_Collection
 */

class SnowCommerce_CMSBlockManagement_Model_Mysql4_Versions_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('sc_cmsblockmanagement/versions');
    }
}