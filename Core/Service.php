<?php

namespace OxidProfessionalServices\CountryVatAdministration\Core;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\ConnectionProviderInterface;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;

class Service
{
    public static function getInstance(): static
    {
        return Registry::get(static::class);
    }

    public function getDatabaseConnection(): Connection
    {
        return ContainerFactory::getInstance()->getContainer()->get(ConnectionProviderInterface::class)->get();
    }

    public function getQueryBuilder(): QueryBuilder
    {
        return ContainerFactory::getInstance()->getContainer()->get(QueryBuilderFactoryInterface::class)->create();
    }
}
