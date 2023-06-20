<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration\Controller\Admin;


use OxidEsales\Eshop\Application\Controller\Admin\CountryMain;
use OxidEsales\Eshop\Application\Model\Address;
use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidProfessionalServices\CountryVatAdministration\Model\Country2Vat;
use PHPUnit\Framework\TestCase;

class CountryMainTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $country = new Country();
        $country->setId('country_id');
        $country->oxcountry__oxactive = new Field(1);
        $country->save();
    }

    public function testSaveWhenAddsSpecialCountryVat()
    {
        $_POST['editval'] = [
            'oxid' => 'country_id',
            'oxps_countryvatadministration_country_vat' => 10,
        ];

        /** @var \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CountryMain $controller */
        $controller = oxNew(CountryMain::class);
        $controller->save();

        $country = new Country();
        $country->load('country_id');

        $this->assertSame(10, $country->oxps_country2vat__vat->value);
    }

    public function testSaveWhenEditsSpecialCountryVat()
    {
        $countryToVat = new Country2Vat();
        $countryToVat->setId('test_model_id');
        $countryToVat->setShopId(Registry::getConfig()->getShopId());
        $countryToVat->oxps_country2vat__oxcountryid = new Field('country_id');
        $countryToVat->oxps_country2vat__vat = new Field(8);
        $countryToVat->save();

        $_POST['editval'] = [
            'oxid' => 'country_id',
            'oxps_countryvatadministration_country_vat' => 11,
        ];

        /** @var \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CountryMain $controller */
        $controller = oxNew(CountryMain::class);
        $controller->save();

        $country = new Country();
        $country->load('country_id');

        $this->assertSame(11, $country->oxps_country2vat__vat->value);
    }
}
