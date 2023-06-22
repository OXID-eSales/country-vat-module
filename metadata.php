<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

use OxidProfessionalServices\CountryVatAdministration\Core\Module;

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
    'version'     => '1.0.3',
    'author'      => 'OXID eSales AG',
    'url'         => 'https://www.oxid-esales.com/',
    'email'       => '',
    'extend'      => [
        \OxidEsales\Eshop\Application\Controller\Admin\CountryMain::class => \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CountryMain::class,
        \OxidEsales\Eshop\Application\Controller\Admin\ArticleMain::class => \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\ArticleMain::class,
        \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::class => \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CategoryMain::class,
        \OxidEsales\Eshop\Application\Model\Article::class => \OxidProfessionalServices\CountryVatAdministration\Model\Article::class,
        \OxidEsales\Eshop\Application\Model\User::class => \OxidProfessionalServices\CountryVatAdministration\Model\User::class,
    ],
    'controllers' => [
        'article_mainvat_ajax' => \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\ArticleMainAjax::class,
        'category_mainvat_ajax' => \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CategoryMainAjax::class,
    ],
    'events'       => [
        'onActivate'   => '\OxidProfessionalServices\CountryVatAdministration\Core\Events::onActivate',
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
