<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

use OxidEsales\CountryVat\Core\Module;

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id'          => Module::MODULE_ID,
    'title'       => 'Country VAT administration',
    'description' => '',
    'thumbnail'   => 'logo.png',
    'version'     => '2.2.0-rc.1',
    'author'      => 'OXID eSales AG',
    'url'         => 'https://www.oxid-esales.com/',
    'email'       => '',
    'extend'      => [
        \OxidEsales\Eshop\Application\Controller\Admin\CountryMain::class => \OxidEsales\CountryVat\Controller\Admin\CountryMain::class,
        \OxidEsales\Eshop\Application\Controller\Admin\ArticleMain::class => \OxidEsales\CountryVat\Controller\Admin\ArticleMain::class,
        \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::class => \OxidEsales\CountryVat\Controller\Admin\CategoryMain::class,
        \OxidEsales\Eshop\Application\Model\Article::class => \OxidEsales\CountryVat\Model\Article::class,
        \OxidEsales\Eshop\Application\Model\User::class => \OxidEsales\CountryVat\Model\User::class,
    ],
    'controllers' => [
        'article_mainvat_ajax' => \OxidEsales\CountryVat\Controller\Admin\ArticleMainAjax::class,
        'category_mainvat_ajax' => \OxidEsales\CountryVat\Controller\Admin\CategoryMainAjax::class,
    ],
    'events'       => [
        'onActivate'   => '\OxidEsales\CountryVat\Core\Events::onActivate',
    ],
    'settings'    => [],
];
