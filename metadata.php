<?php

/**
 * Metadata version
 */
$sMetadataVersion = '2.1';

/**
 * Module information
 */
$aModule = array(
    'id'          => 'oxps/countryvatadministration',
    'title'       => 'OXPS :: Country VAT administration',
    'description' => '',
    'version'     => '0.0.4',
    'author'      => 'OXPS',
    'url'         => '',
    'email'       => '',
    'extend'      => array(
        \OxidEsales\Eshop\Application\Controller\Admin\CountryMain::class => \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CountryMain::class,
        \OxidEsales\Eshop\Application\Model\VatSelector::class => \OxidProfessionalServices\CountryVatAdministration\Model\VatSelector::class,
    ),
    'blocks'      => array(
        array(
            'template' => 'country_main.tpl',
            'block'    => 'admin_country_main_form',
            'file'     => 'views/blocks/admin/admin_country_main_form.tpl'
        ),
    ),
    'settings'    => array(),
);