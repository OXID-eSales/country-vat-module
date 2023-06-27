<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidEsales\EshopCommunity\Tests\Integration\IntegrationTestCase;
use OxidEsales\Facts\Facts;
use Symfony\Component\Filesystem\Path;

abstract class BaseTestCase extends IntegrationTestCase
{
    protected const COUNTRY_ID_DE = 'testcountry_de';
    protected const COUNTRY_ID_BE = 'testcountry_be';
    protected const ARTICLE_ID = '1000';
    protected const OTHER_ARTICLE_ID = '1001';
    protected const CATEGORY_ID = 'testcategory0';
    protected const OTHER_CATEGORY_ID = 'testcategory1';
    protected const USER_ID_DE = 'de_user';
    protected const USER_ID_BE = 'be_user';

    public function setUp(): void
    {
        parent::setUp();

        $connection = ContainerFactory::getInstance()
            ->getContainer()
            ->get(QueryBuilderFactoryInterface::class)
            ->create()
            ->getConnection();

        $facts = new Facts();
        $connection->executeStatement(
            file_get_contents(
                Path::join(__DIR__, '/../', 'Fixtures', 'testdemodata_' . strtolower($facts->getEdition()) . '.sql')
            )
        );
    }
}
