<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\CountryVat\Controller\Admin;

use OxidEsales\Eshop\Core\Registry;
use OxidEsales\CountryVat\Model\Country2Vat;

class CountryMain extends CountryMain_parent
{
    public function save()
    {
        $params = Registry::getRequest()->getRequestParameter("editval");
        $countryVatUpdated = true;
        if (key_exists('oxps_countryvatadministration_country_vat', $params)) {
            $countryToVat = oxNew(Country2Vat::class);
            $oxcountryId = $this->getEditObjectId();
            $shopId = (int) Registry::getConfig()->getShopId();
            $countryToVat->loadFromCountryAndShopId($oxcountryId, $shopId);

            if (trim($params['oxps_countryvatadministration_country_vat']) == '') {
                $countryVatUpdated = $countryToVat->delete();

                return parent::save() && $countryVatUpdated;
            }

            $vat = (float) $params['oxps_countryvatadministration_country_vat'];
            $countryToVat->assign(['OXCOUNTRYID' => $oxcountryId, 'OXSHOPID' => $shopId, 'VAT' => $vat]);
            $countryVatUpdated = $countryToVat->save();
        }
        return $countryVatUpdated && parent::save();
    }

    public function configuredVat()
    {
        $countryToVat = oxNew(Country2Vat::class);
        $oxcountryId = $this->getEditObjectId();
        $shopId = (int) Registry::getConfig()->getShopId();
        $countryToVat->loadFromCountryAndShopId($oxcountryId, $shopId);
        return $countryToVat->vat();
    }
}
