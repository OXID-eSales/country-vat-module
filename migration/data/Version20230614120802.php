<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\CountryVat\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230614120802 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $platform = $this->connection->getDatabasePlatform();
        $platform->registerDoctrineTypeMapping('enum', 'string');

        if (!$schema->hasTable('oxps_country2vat')) {
            $this->addSql("CREATE TABLE `oxps_country2vat` (
                      `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                          COMMENT 'id',
                      `OXCOUNTRYID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                          COMMENT 'country id',
                      `OXSHOPID` int(11) NOT NULL,
                      `VAT` float DEFAULT NULL
                          COMMENT 'Value added tax. If specified, used in all calculations instead of global vat',
                      PRIMARY KEY (`OXID`),
                      UNIQUE KEY `OXCOUNTRYID` (`OXCOUNTRYID`, `OXSHOPID`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Countries list';");
        }

        if (!$schema->hasTable("oxpsarticle2countryvat")) {
            $this->addSql("CREATE TABLE `oxpsarticle2countryvat` (
                      `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                          COMMENT 'id',
                      `OXARTICLEID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                          COMMENT 'article id',
                      `OXCOUNTRYID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                          COMMENT 'country id',
                      `OXSHOPID` int(11) NOT NULL,
                      `VAT` float DEFAULT NULL
                          COMMENT 'Value added tax. If specified, used in all calculations instead of global vat',
                      PRIMARY KEY (`OXID`),
                      UNIQUE KEY `OXARTCOUNTRYID` (`OXARTICLEID`, `OXCOUNTRYID`, `OXSHOPID`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Countries list';");
        }

        if (!$schema->hasTable("oxpscategory2countryvat")) {
            $this->addSql("CREATE TABLE `oxpscategory2countryvat` (
                      `OXID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                          COMMENT 'id',
                      `OXCATEGORYID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                          COMMENT 'category id',
                      `OXCOUNTRYID` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL
                          COMMENT 'country id',
                      `OXSHOPID` int(11) NOT NULL,
                      `VAT` float DEFAULT NULL
                          COMMENT 'Value added tax. If specified, used in all calculations instead of global vat',
                      PRIMARY KEY (`OXID`),
                      UNIQUE KEY `OXCATCOUNTRYID` (`OXCATEGORYID`, `OXCOUNTRYID`, `OXSHOPID`)
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Countries list';");
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
