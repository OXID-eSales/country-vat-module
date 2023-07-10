<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Core;

class Events
{
    /**
     * Execute action on activate event.
     */
    public static function onActivate()
    {
        $connection   = Service::getInstance()->getDatabaseConnection();
        $schema       = $connection->getSchemaManager();

        if (!$schema->tablesExist(['oxps_country2vat'])) {
            $query = "CREATE TABLE `oxps_country2vat` (
                      `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'id',
                      `OXCOUNTRYID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'country id',
                      `OXSHOPID` int(11) NOT NULL,
                      `VAT` float DEFAULT NULL COMMENT 'Value added tax. If specified, used in all calculations instead of global vat',
                      PRIMARY KEY (`OXID`),
                      UNIQUE KEY `OXCOUNTRYID` (`OXCOUNTRYID`,`OXSHOPID`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Countries list';";
            $connection->executeStatement($query);
        }

        if (!$schema->tablesExist(['oxpsarticle2countryvat'])) {
            $query = "CREATE TABLE `oxpsarticle2countryvat` (
                      `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'id',
                      `OXARTICLEID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'article id',
                      `OXCOUNTRYID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'country id',
                      `OXSHOPID` int(11) NOT NULL,
                      `VAT` float DEFAULT NULL COMMENT 'Value added tax. If specified, used in all calculations instead of global vat',
                      PRIMARY KEY (`OXID`),
                      UNIQUE KEY `OXARTCOUNTRYID` (`OXARTICLEID`,`OXCOUNTRYID`,`OXSHOPID`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Countries list';";
            $connection->executeStatement($query);
        }

        if (!$schema->tablesExist(['oxpscategory2countryvat'])) {
            $query = "CREATE TABLE `oxpscategory2countryvat` (
                      `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'id',
                      `OXCATEGORYID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'category id',
                      `OXCOUNTRYID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL COMMENT 'country id',
                      `OXSHOPID` int(11) NOT NULL,
                      `VAT` float DEFAULT NULL COMMENT 'Value added tax. If specified, used in all calculations instead of global vat',
                      PRIMARY KEY (`OXID`),
                      UNIQUE KEY `OXCATCOUNTRYID` (`OXCATEGORYID`,`OXCOUNTRYID`,`OXSHOPID`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Countries list';";
            $connection->executeStatement($query);
        }
    }
}
