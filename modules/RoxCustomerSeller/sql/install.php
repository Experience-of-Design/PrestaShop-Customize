<?php
/**
* 2007-2019 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2019 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */
$sql_query_1 = "CREATE TABLE `"._DB_PREFIX_."rox_customer_seller` (
                        `id_customer_seller` int(11) NOT NULL,
                        `id_seller` int(11) NOT NULL,
                        `date_add` datetime NOT NULL,
                        `points` decimal(15,1) NOT NULL DEFAULT '0.0',
                        `points_used` decimal(15,1) NOT NULL DEFAULT '0.0',
                        `status` int(5) NOT NULL DEFAULT '0',
                        PRIMARY KEY (`id_customer_seller`)
                        ) ENGINE=InnoDB DEFAULT CHARSET=latin1;";
$sql_query_2 = "CREATE TABLE `"._DB_PREFIX_."rox_customer_seller_customers` (
                        `id_customer_seller_customer` int(11) NOT NULL AUTO_INCREMENT,
                        `id_seller` int(11) NOT NULL,
                        `id_customer` int(11) NOT NULL,
                        `points_count` decimal(15,1) NOT NULL DEFAULT '0.0',
                        PRIMARY KEY (`id_customer_seller_customer`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;";
$sql_query_3 = "CREATE TABLE `"._DB_PREFIX_."rox_customer_seller_orders` (
                        `id_customer_seller_order` int(11) NOT NULL AUTO_INCREMENT,
                        `id_customer` int(11) NOT NULL,
                        `id_order` int(11) NOT NULL,
                        `price` decimal(15,2) NOT NULL DEFAULT '0.00',
                        `points` decimal(15,1) NOT NULL DEFAULT '0.0',
                        PRIMARY KEY (`id_customer_seller_order`)
                        ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf16 COLLATE=utf16_czech_ci;";

$sql = array($sql_query_1, $sql_query_2, $sql_query_3);
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'RoxCustomerSeller` (
    `id_RoxCustomerSeller` int(11) NOT NULL AUTO_INCREMENT,
    PRIMARY KEY  (`id_RoxCustomerSeller`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
