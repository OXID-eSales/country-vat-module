<?php


namespace OxidProfessionalServices\CountryVatAdministration\Controller\Admin;

use OxidProfessionalServices\CountryVatAdministration\Model\Country2Vat;

class CountryMain extends CountryMain_parent
{
    public function save()
    {
        $params = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("editval");
        $noProblemsWithCountryVatUpdate = true;
        if (key_exists('oxps_countryvatadministration_country_vat', $params)) {
            $countryToVat = oxNew(Country2Vat::class);
            $oxcountryId = $this->getEditObjectId();
            $shopId = (int) $this->getConfig()->getShopId();
            $countryToVat->loadFromCountryAndShopId($oxcountryId, $shopId);
            $deleteVatConfiguration = (bool) (trim($params['oxps_countryvatadministration_country_vat']) == '');

            if ($deleteVatConfiguration) {
                $countryToVat->delete();
            } else {
                $vat = (float) $params['oxps_countryvatadministration_country_vat'];
                $countryToVat->assign(['OXCOUNTRYID' => $oxcountryId, 'OXSHOPID' => $shopId, 'VAT' => $vat]);
                $noProblemsWithCountryVatUpdate = $countryToVat->save();
            }

        }
        return $noProblemsWithCountryVatUpdate && parent::save();
    }

    public function configuredVat()
    {
        $countryToVat = oxNew(Country2Vat::class);
        $oxcountryId = $this->getEditObjectId();
        $shopId = (int) $this->getConfig()->getShopId();
        $countryToVat->loadFromCountryAndShopId($oxcountryId, $shopId);
        return $countryToVat->vat();
    }
}