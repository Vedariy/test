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
$installer = $this;
$installer->startSetup();

/**
 * Create table 'yk_sizing_chart'
 */
$installer->run("
DROP TABLE IF EXISTS `{$installer->getTable('yk_asc/chart')}`;

CREATE TABLE `{$installer->getTable('yk_asc/chart')}` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `ptn` int(5),
  `content` text,
  PRIMARY KEY (`id`)
) ENGINE=`InnoDB` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

");

$installer->endSetup();
