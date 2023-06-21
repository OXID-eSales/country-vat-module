<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration\Controller;

use OxidEsales\Eshop\Application\Controller\Admin\CountryMain;
use OxidEsales\Eshop\Application\Model\Country;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidProfessionalServices\CountryVatAdministration\Model\Country2Vat;
use OxidProfessionalServices\CountryVatAdministration\Tests\Integration\BaseTestCase;
use PHPUnit\Framework\TestCase;

class CountryMainTest extends BaseTestCase
{
    public function testAddSpecialCountryVat()
    {
        $_POST['editval'] = ['oxps_countryvatadministration_country_vat' => 10];
        $_POST['oxid'] = 'testcountry_de';

        /** @var \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CountryMain $controller */
        $controller = oxNew(CountryMain::class);
        $controller->save();

        $country2Vat = new Country2Vat();
        $country2Vat->loadFromCountryAndShopId('testcountry_de', Registry::getConfig()->getBaseShopId());

        $this->assertSame('10', $country2Vat->oxps_country2vat__vat->value);
    }

    public function testUpdateExistingSpecialCountryVat()
    {
        $country2Vat = new Country2Vat();
        $country2Vat->setId('test_model_id');
        $country2Vat->setShopId(Registry::getConfig()->getShopId());
        $country2Vat->oxps_country2vat__oxcountryid = new Field('testcountry_de');
        $country2Vat->oxps_country2vat__vat = new Field(8);
        $country2Vat->save();

        $_POST['editval'] = ['oxps_countryvatadministration_country_vat' => 11];
        $_POST['oxid'] = 'testcountry_de';

        /** @var \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CountryMain $controller */
        $controller = oxNew(CountryMain::class);
        $controller->save();

        $country2Vat->loadFromCountryAndShopId('testcountry_de', Registry::getConfig()->getBaseShopId());
        $this->assertSame('11', $country2Vat->oxps_country2vat__vat->value);
    }
}
