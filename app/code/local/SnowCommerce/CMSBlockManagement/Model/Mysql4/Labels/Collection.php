<?php
/**
 * Produced by SnowCommerce development team
 */
class SnowCommerce_CMSBlockManagement_Model_Mysql4_Labels_Collection extends Mage_Core_Model_Mysql4_Collection_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('sc_cmsblockmanagement/labels');
    }
}