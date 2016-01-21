<?php
/**
 * Class SnowCommerce_CMSVersions_Model_Observer
 */

class SnowCommerce_CMSBlockManagement_Model_Observer extends Varien_Event_Observer
{
    /**
     * @param Varien_Event_Observer $observer
     */
    public function SaveBlock(Varien_Event_Observer $observer)
    {
        $block = $observer->getObject();
        if(get_class($block) == 'Mage_Cms_Model_Block')
        {
            //getting labels ids
            $data = $block->getData();

            if(@$data['sc_block_label'])
            {
                //saving to data
                $data['sc_block_label'] = serialize($data['sc_block_label']);
                $block->setData($data);
            }
        }
    }

    /**
     * @param Varien_Event_Observer $observer
     */
    public function LoadBlock(Varien_Event_Observer $observer)
    {
        $block = $observer->getObject();
        if(get_class($block) == 'Mage_Cms_Model_Block')
        {
            $data = $block->getData();
            if(@$data['sc_block_label'] && !(is_array(@$data['sc_block_label'])))
            {
                $data['sc_block_label'] = unserialize($data['sc_block_label']);
            }
            $block->setData($data);
        }
    }

     /**
     * @param $observer
     */
    public function SaveBlockVersion(Varien_Event_Observer $observer)
    {
        $block = $observer->getObject();
        if($block->getScKeepVersions())
        {
            $user = Mage::getSingleton('admin/session')->getUser();
            if($user)
            {
                $block = $observer->getObject();
                if(!($this->WasBlockDataChanged($block))) return;

                if(get_class($block) == "Mage_Cms_Model_Block")
                {
                    $collection = Mage::getModel('sc_cmsblockmanagement/versions')->getCollection();
                    $collection->addFieldToFilter('block_identifier',array(
                        "eq" => $block->getIdentifier()
                    ));

                    foreach($collection as $model)
                    {
                        $model->setIsActual('0');
                        $model->save();
                    }
                    $model = Mage::getModel('sc_cmsblockmanagement/versions');
                    $data = array(
                        'block_identifier'      => $block['identifier'],
                        'version'       => $time = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
                        'content'       => serialize($block->getData()),
                        'admin_name'    => Mage::getSingleton('admin/session')->getUser()->getUsername(),
                        'is_actual'     => 1
                    );
                    $model->setData($data);
                    $model->save();
                }
            }
        }
    }

    /**
     * Saves default blocks' versions
     * @param $observer
     */
    public function DefaultBlockVersion(Varien_Event_Observer $observer)
    {
        $block = $observer->getObject();
        if(get_class($block) == "Mage_Cms_Model_Block")
        {
            $collection = Mage::getModel('sc_cmsblockmanagement/versions')->getCollection();
            $collection->addFieldToFilter('block_identifier',array(
                "eq" => $block->getIdentifier()
            ));
            if(count($collection) == 0)
            {
                $this->SaveBlockVersion($observer);
            }
        }
    }

    public function WasBlockDataChanged($model)
    {
        $flag = 0;
        $set = array("title", "identifier", "is_active", "content","sc_block_label");
        foreach($set as $field)
        {
            $origValue = $model->getOrigData($field);
            $newValue = $model->getData($field);
            if($origValue != $newValue) $flag = 1;
        }
        return $flag;
    }
}