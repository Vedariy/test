<?php
/**
 * Class SnowCommerce_CMSVersions_Model_Observer
 */

class SnowCommerce_CMSPagesVersions_Model_Observer
{
    /**
     * @param $observer
     */
    public function SavePageVersion($observer)
    {
        $page = $observer->getObject();

        if($page->getScKeepVersions())
        {
            $user = Mage::getSingleton('admin/session')->getUser();
            if($user)
            {
                if(!($this->WasPageDataChanged($page))) return;

                $collection = Mage::getModel('sc_cmspagesversions/page')->getCollection();
                $collection->addFieldToFilter('page_id',array(
                    "eq" => $observer->getObject()->getPageId()
                ));
                foreach($collection as $model)
                {
                    $model->setIsActual('0');
                    $model->save();
                }

                $model = Mage::getModel('sc_cmspagesversions/page');
                $data = array(
                    'page_id'       => $page['page_id'],
                    'version'       => $time = date("Y-m-d H:i:s", Mage::getModel('core/date')->timestamp(time())),
                    'content'       => serialize($page->getData()),
                    'admin_name'    => $user->getUsername(),
                    'identifier'    => $page['identifier'],
                    'is_actual'     => 1,
                );
                $model->setData($data);
                $model->save();
            }
        }
    }

    /**
     * Saves default pages' versions
     * @param $observer
     */
    public function DefaultPageVersion($observer)
    {
        $collection = Mage::getModel('sc_cmspagesversions/page')->getCollection();
        $collection->addFieldToFilter('page_id',array(
            "eq" => $observer->getObject()->getPageId()
        ));
        if(count($collection) == 0)
        {
            $this->SavePageVersion($observer);
        }
    }

    /**
     * Checks was page data changed or not
     * Да, говнокод, щито поделать
     * @param $model
     * @return int
     */
    public function WasPageDataChanged($model)
    {
        $flag = 0;
        $set = array("title", "identifier", "is_active", "content_heading", "content", "root_template", "layout_update_xml", "custom_theme_from", "custom_theme_to", "custom_theme", "custom_root_template", "custom_layout_update_xml", "meta_keywords", "meta_description");
        foreach($set as $field)
        {
            $origValue = $model->getOrigData($field);
            $newValue = $model->getData($field);
            if($origValue != $newValue) $flag = 1;
        }
        return $flag;
    }

    public function addPageVersionsField($observer)
    {
        $form = $observer->getEvent()->getForm();
        $fieldset = $form->getElement('base_fieldset');
        $yesnoSource = Mage::getModel('adminhtml/system_config_source_yesno')->toOptionArray();
        $fieldset->addField('sc_keep_versions', 'select', array(
            'name'      => 'sc_keep_versions',
            'label'     => Mage::helper('cms')->__('Keep versions of this page?'),
            'title'     => Mage::helper('cms')->__('Keep versions of this page?'),
            'values'    => $yesnoSource,
        ));
        return $this;
    }

}