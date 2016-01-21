<?php
/**
 * Class SnowCommerce_CMSVersions_Block_Adminhtml_Pages_Versions_Grid_Renderer_Action
 */

class SnowCommerce_CMSPagesVersions_Block_Adminhtml_Pages_Versions_Grid_Renderer_Action
    extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{
    /**
     * Renders page's preview
     * @param Varien_Object $row
     * @return string
     */
    public function render(Varien_Object $row)
    {
        $urlModel = Mage::getModel('core/url')->setStore($row->getData('_first_store_id'));
        $href = $urlModel->getUrl(
            $row->getIdentifier(), array(
                '_current' => false,
                '_query' => '___store='.$row->getStoreCode()
           )
        );
        return '<a href="'.$href.'" target="_blank">'.$this->__('Preview').'</a>';
    }
}
