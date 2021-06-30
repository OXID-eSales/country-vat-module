<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = [
    'id'          => 'oxps/countryvatadministration',
    'title'       => 'Country VAT administration for OXID < 6.x',
    'description' => '',
    'version'     => '0.1.0',
    'author'      => 'OXID eSales AG',
    'url'         => 'https://www.oxid-esales.com/',
    'email'       => '',
    'extend'      => [
        'country_main'  => 'oxps/countryvatadministration/controllers/admin/countryvat__country_main',
        'article_main'  => 'oxps/countryvatadministration/controllers/admin/countryvat__article_main',
        'category_main' => 'oxps/countryvatadministration/controllers/admin/countryvat__category_main',
        'oxarticle'    => 'oxps/countryvatadministration/models/countryvat__oxarticles',
        'oxuser'        => 'oxps/countryvatadministration/models/countryvat__oxuser',
    ],
    'files'       => [
        'country_vat_article_main_ajax'  => 'oxps/countryvatadministration/controllers/admin/country_vat_article_main_ajax.php',
        'country_vat_category_main_ajax' => 'oxps/countryvatadministration/controllers/admin/country_vat_category_main_ajax.php',
        'countryvat_country2vat' => 'oxps/countryvatadministration/models/countryvat_country2vat.php',
        'countryvat_category2countryvat' => 'oxps/countryvatadministration/models/countryvat_category2countryvat.php',
        'countryvat_product2countryvat' => 'oxps/countryvatadministration/models/countryvat_product2countryvat.php',
    ],
//    'events'      => [
//        'onActivate' => 'OxidProfessionalServices\CountryVatAdministration\Core\Events::onActivate',
//    ],
    'templates'   => [
        'ajax_article_popup.tpl'  => 'oxps/countryvatadministration/views/templates/ajax_article_popup.tpl',
        'ajax_category_popup.tpl' => 'oxps/countryvatadministration/views/templates/ajax_category_popup.tpl',
    ],
    'blocks'      => [
        [
            'template' => 'country_main.tpl',
            'block'    => 'admin_country_main_form',
            'file'     => 'views/blocks/admin/admin_country_main_form.tpl',
        ],
        [
            'template' => 'article_main.tpl',
            'block'    => 'admin_article_main_form',
            'file'     => 'views/blocks/admin/admin_article_main_form.tpl',
        ],
        [
            'template' => 'category_main.tpl',
            'block'    => 'admin_category_main_form',
            'file'     => 'views/blocks/admin/admin_category_main_form.tpl',
        ],
    ],
    'settings'    => [],
];