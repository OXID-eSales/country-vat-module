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
    'version'     => '1.0.4',
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
    'templates' => [
        '@oecountryvat/admin/ajax_article_popup.tpl' => 'views/smarty/admin/ajax_article_popup.tpl',
        '@oecountryvat/admin/ajax_category_popup.tpl' => 'views/smarty/admin/ajax_category_popup.tpl',
    ],
    'blocks'      => [
        [
            'template' => 'country_main.tpl',
            'block'    => 'admin_country_main_form',
            'file'     => 'views/smarty/blocks/admin/admin_country_main_form.tpl'
        ],
        [
            'template' => 'article_main.tpl',
            'block'    => 'admin_article_main_form',
            'file'     => 'views/smarty/blocks/admin/admin_article_main_form.tpl'
        ],
        [
            'template' => 'include/category_main_form.tpl',
            'block'    => 'admin_category_main_form',
            'file'     => 'views/smarty/blocks/admin/admin_category_main_form.tpl'
        ],
    ],
    'settings'    => [],
];
