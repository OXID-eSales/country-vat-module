<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\CountryVat\Tests\Integration\Controller;

use OxidEsales\Eshop\Application\Controller\Admin\CountryMain;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\CountryVat\Model\Country2Vat;
use OxidEsales\CountryVat\Tests\Integration\BaseTestCase;

class CountryMainTest extends BaseTestCase
{
    public function testAddSpecialCountryVat()
    {
        $_POST['editval'] = ['oxps_countryvatadministration_country_vat' => 10];
        $_POST['oxid'] = self::COUNTRY_ID_DE;

        /** @var \OxidEsales\CountryVat\Controller\Admin\CountryMain $controller */
        $controller = oxNew(CountryMain::class);
        $controller->save();

        $country2Vat = new Country2Vat();
        $country2Vat->loadFromCountryAndShopId(self::COUNTRY_ID_DE, Registry::getConfig()->getBaseShopId());

        $this->assertEquals('10', $country2Vat->oxps_country2vat__vat->value);
    }

    public function testUpdateExistingSpecialCountryVat()
    {
        $country2Vat = new Country2Vat();
        $country2Vat->setId('test_model_id');
        $country2Vat->setShopId(Registry::getConfig()->getShopId());
        $country2Vat->oxps_country2vat__oxcountryid = new Field(self::COUNTRY_ID_DE);
        $country2Vat->oxps_country2vat__vat = new Field(8);
        $country2Vat->save();

        $_POST['editval'] = ['oxps_countryvatadministration_country_vat' => 11];
        $_POST['oxid'] = self::COUNTRY_ID_DE;

        /** @var \OxidEsales\CountryVat\Controller\Admin\CountryMain $controller */
        $controller = oxNew(CountryMain::class);
        $controller->save();

        $country2Vat->loadFromCountryAndShopId(self::COUNTRY_ID_DE, Registry::getConfig()->getBaseShopId());
        $this->assertEquals('11', $country2Vat->oxps_country2vat__vat->value);
    }
}
