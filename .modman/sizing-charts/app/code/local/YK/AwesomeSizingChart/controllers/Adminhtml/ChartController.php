<?php
/**
 * YK extension for Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade
 * the YK AwesomeSizingChart module to newer versions in the future.
 * If you wish to customize the YK AwesomeSizingChart module for your needs
 * please refer to http://www.magentocommerce.com for more information.
 *
 * @category   YK
 * @package    YK_AwesomeSizingChart
 * @copyright  Copyright (C) 2013 Yaroslav Kravchuk
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
class YK_AwesomeSizingChart_Adminhtml_ChartController 
    extends Mage_Adminhtml_Controller_Action
{
    /**
     * Initialize action
     *
     * Here, we set the breadcrumbs and the active menu
     *
     * @return Mage_Adminhtml_Controller_Action
     */
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('cms/manage_sizing_chart')
            ->_title($this->__('Manage Sizing Charts'))
            ->_addBreadcrumb(
                $this->__('Manage Sizing Charts'), 
                $this->__('Manage Sizing Charts')
                );
         
        return $this;
    }

    public function indexAction()
    {
        $this->_initAction()
            ->renderLayout();
    }
     
    public function editAction()
    {   
        $this->_initAction();

        $chartId  = $this->getRequest()->getParam('id');
        $chartObj = Mage::getModel('yk_asc/chart');
     
        if ($chartId) {
            $chartObj->load($chartId);
     
            if (!$chartObj->getId()) {
                Mage::getSingleton('adminhtml/session')
                    ->addError($this->__('This Sizing Chart no longer exists.'));
                $this->_redirect('*/*/');
     
                return;
            }   
        }

        $data = Mage::getSingleton('adminhtml/session')->getChartData(true);
        if (!empty($data)) {
            $chartObj->setData($data);
        }   
     
        Mage::register('sizing_chart', $chartObj);
     
        $this->_initAction()
            ->_addContent(
                $this->getLayout()->createBlock('yk_asc/adminhtml_chart_edit')
                    ->setData('action', $this->getUrl('*/*/save')))
            ->renderLayout();
    }
     
    public function saveAction()
    {
        if ($postData = $this->getRequest()->getPost()) {
            if ($this->checkDuplicates($postData) && !isset($postData['id'])) {
                Mage::getSingleton('adminhtml/session')
                    ->addError($this->__('Sizing Chart for this product type already exists.'));

                $this->_redirect('*/*/');
     
                return;
            } else {
                $model = Mage::getSingleton('yk_asc/chart');
                $model->setData($postData);

                try {
                    $model->save();
     
                    Mage::getSingleton('adminhtml/session')
                        ->addSuccess($this->__('Sizing Chart has been saved.'));
                    $this->_redirect('*/*/');
     
                    return;
                }   
                catch (Mage_Core_Exception $e) {
                    Mage::getSingleton('adminhtml/session')
                        ->addError($e->getMessage());
                }
                catch (Exception $e) {
                    Mage::getSingleton('adminhtml/session')
                        ->addError($this->__('An error occurred while saving this sizing chart.'));
                }
            }
 
            Mage::getSingleton('adminhtml/session')->setChartData($postData);
            $this->_redirectReferer();
        }
    }

    /**
     * Check existed duplicates
     */
    public function checkDuplicates($postData)
    {
        $charts = Mage::getModel('yk_asc/chart')
            ->getCollection()
            ->addPtnFilter($postData['ptn']);
        if ($charts->count()) {
            return true;
        }

        return false;
    }

    /**
     * Delete sizing chart action
     */
    public function deleteAction()
    {
        if ($id = $this->getRequest()->getParam('id')) {
            $product = Mage::getModel('yk_asc/chart')
                ->load($id);
            try {
                $product->delete();
                $this->_getSession()->addSuccess($this->__('Sizing Chart has been deleted.'));
            } catch (Exception $e) {
                $this->_getSession()->addError($e->getMessage());
            }
        }
        $this->getResponse()
            ->setRedirect($this->getUrl('*/*/', array('store'=>$this->getRequest()->getParam('store'))));
    }

    public function massDeleteAction()
    {
        $chartIds = $this->getRequest()->getParam('id');
        if(!is_array($chartIds)) {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('yk_asc')->__('Please select chart(s).'));
        } else {
            try {
                $cartModel = Mage::getModel('yk_asc/chart');
                foreach ($chartIds as $chartId) {
                    $cartModel->load($chartId)->delete();
                }
                Mage::getSingleton('adminhtml/session')->addSuccess(
                    Mage::helper('yk_asc')->__(
                        'Total of %d record(s) were deleted.', count($chartIds)
                    )
                );
            } catch (Exception $e) {
                Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
            }
        }
        $this->_redirect('*/*/index');
    }
}
