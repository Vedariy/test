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
class YK_AwesomeSizingChart_Model_Chart 
	extends Mage_Core_Model_Abstract
{
    protected function _construct()
    {   
        $this->_init('yk_asc/chart');
    }

    /**
     * Get Sizing charts by ptn
     */
    public function getSizingChart($ptn)
    {
    	if ($ptn) {
	    	$sizingChart = $this->getCollection()
	    		->addPtnFilter($ptn)
	    		->getFirstItem();
	    	if ($sizingChart->getId()) {
	    		return $sizingChart;
	    	} else {
                if ($content = $this->getNewSizingChartContent($ptn)) {
                    try {
                        $this->setPtn($ptn);
                        $this->setContent($content);
                        $new = $this->save();
                        if ($new->getId()) {
                            return $new;
                        }
                    } catch(Exception $e) {
                        Mage::log($e, null, 'sizing_charts.log');
                    }
                };
	    	}
    	}

    	return false;
    }

    /**
     * Get Sizing Chart content from remote resource
     */
    public function getNewSizingChartContent($ptn)
    {
        if ($ptn) {
            $uri = Mage::getStoreConfig('yk_config/asc/url') . $ptn;
            $client = new Zend_Http_Client();
            $client->setUri($uri);                 
            $response = $client->request(); 

            if ($html = $response->getBody()) {
            	$dom = new Zend_Dom_Query();
                $dom->setDocumentHtml($html);
        		$results = $dom->query(Mage::getStoreConfig('yk_config/asc/container'));
        		 
                if (count($results)) {
            		foreach ($results as $result) {
                        $innerHTML= ''; 
                        $children = $result->childNodes; 
                        foreach ($children as $child) { 
                            $innerHTML .= $child->ownerDocument->saveHTML($child); 
                        }

                        return $innerHTML;
            		}
                }
            }
        }

        return false;
	}
}
