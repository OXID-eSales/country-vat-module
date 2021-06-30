<?php

class countryvat__country_main extends countryvat__country_main_parent
{
    /**
     * @return bool
     * @throws oxSystemComponentException
     */
    public function save()
    {
        $params = oxRegistry::getConfig()->getRequestParameter("editval");
        $noProblemsWithCountryVatUpdate = true;
        if (key_exists('oxps_countryvatadministration_country_vat', $params)) {
            $countryToVat = oxNew('countryvat_country2vat');
            $oxcountryId = $this->getEditObjectId();
            $shopId = $this->getConfig()->getShopId();
            $countryToVat->loadFromCountryAndShopId($oxcountryId, $shopId);
            $deleteVatConfiguration = (bool)(trim($params['oxps_countryvatadministration_country_vat']) == '');

            if ($deleteVatConfiguration) {
                $countryToVat->delete();
            } else {
                $vat = (float)$params['oxps_countryvatadministration_country_vat'];
                $countryToVat->assign(['OXCOUNTRYID' => $oxcountryId, 'OXSHOPID' => $shopId, 'VAT' => $vat]);
                $noProblemsWithCountryVatUpdate = $countryToVat->save();
            }

        }

        return $noProblemsWithCountryVatUpdate && parent::save();
    }

    /**
     * @return mixed
     * @throws oxSystemComponentException
     */
    public function configuredVat()
    {
        $countryToVat = oxNew('countryvat_country2vat');
        $oxcountryId = $this->getEditObjectId();
        $shopId = $this->getConfig()->getShopId();
        $countryToVat->loadFromCountryAndShopId($oxcountryId, $shopId);

        return $countryToVat->vat();
    }
}