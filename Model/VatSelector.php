<?php

namespace OxidProfessionalServices\CountryVatAdministration\Model;

use OxidProfessionalServices\CountryVatAdministration\Model\Country2Vat;

use OxidEsales\Eshop\Application\Model\Article as EshopModelArticle;
use OxidEsales\Eshop\Application\Model\User as EshopModelUser;

class VatSelector extends VatSelector_parent
{
    public function getUserVat(\OxidEsales\Eshop\Application\Model\User $oUser, $blCacheReset = false)
    {
        $cacheId = $oUser->getId() . '_' . $oUser->oxuser__oxcountryid->value;

        if (!$blCacheReset) {
            if (array_key_exists($cacheId, self::$_aUserVatCache) &&
                self::$_aUserVatCache[$cacheId] !== null
            ) {
                return self::$_aUserVatCache[$cacheId];
            }
        }
        $countryId = $this->_getVatCountry($oUser);
        $shopId = (int) $this->getConfig()->getShopId();

        $countryToVat = oxNew(Country2Vat::class);
        if ($countryToVat->loadFromCountryAndShopId($countryId, $shopId)) {
            $ret = (float) $countryToVat->vat();
            self::$_aUserVatCache[$cacheId] = $ret;
            return $ret;
        }

        return parent::getUserVat($oUser, $blCacheReset);
    }

}