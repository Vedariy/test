<?php
/**
 * Produced by SnowCommerce development team
 */
class SnowCommerce_CMSBlockManagement_Model_Labels extends Mage_Core_Model_Abstract
{
    public function _construct()
    {
        parent::_construct();
        $this->_init('sc_cmsblockmanagement/labels');
    }
}