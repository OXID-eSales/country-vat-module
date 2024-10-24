<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\DoctrineMigrationWrapper;

use OxidEsales\Facts\Facts;
use OxidEsales\Facts\Config\ConfigFile;
use OxidEsales\Codeception\Module\Database\DatabaseDefaultsFileGenerator;
use Symfony\Component\Filesystem\Path;

if ($shopRootPath = getenv('SHOP_ROOT_PATH')){
    require_once(Path::join($shopRootPath, 'source', 'bootstrap.php'));
}

$facts = new Facts();

return [
    'SHOP_URL' => $facts->getShopUrl(),
    'SHOP_SOURCE_PATH' => $facts->getSourcePath(),
    'VENDOR_PATH' => $facts->getVendorPath(),
    'DB_NAME' => $facts->getDatabaseName(),
    'DB_USERNAME' => $facts->getDatabaseUserName(),
    'DB_PASSWORD' => $facts->getDatabasePassword(),
    'DB_HOST' => $facts->getDatabaseHost(),
    'DB_PORT' => $facts->getDatabasePort(),
    'DUMP_PATH' => getTestDataDumpFilePath(),
    'FIXTURES_PATH' => getTestFixtureSqlFilePath(),
    'MODULE_DUMP_PATH' => getModuleTestDataDumpFilePath(),
    'MYSQL_CONFIG_PATH' => getMysqlConfigPath(),
    'SELENIUM_SERVER_PORT' => getenv('SELENIUM_SERVER_PORT') ?: '4444',
    'SELENIUM_SERVER_HOST' => getenv('SELENIUM_SERVER_HOST') ?: 'selenium',
    'THEME_ID' => getenv('THEME_ID') ?: 'flow',
    'BROWSER_NAME' => getenv('BROWSER_NAME') ?: 'chrome',
    'PHP_BIN' => getenv('PHPBIN') ?: 'php',
];

function getTestDataDumpFilePath(): string
{
    return Path::join(__DIR__, '/../', 'Support', 'Data', 'generated', 'dump.sql');
}

function getTestFixtureSqlFilePath(): string
{
    $facts = new Facts();

    return Path::join(__DIR__, '/../../', 'Fixtures', 'testdemodata_' . strtolower($facts->getEdition()) . '.sql');
}

function getModuleTestDataDumpFilePath()
{
    return Path::join(__DIR__, '/../', 'Support', 'Data', 'testdata.sql');
}

function getMysqlConfigPath(): string
{
    $configFile = new ConfigFile();

    return (new DatabaseDefaultsFileGenerator($configFile))->generate();
}