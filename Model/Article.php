<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Model;

use OxidEsales\Eshop\Core\Registry as EshopRegistry;

class Article extends Article_parent
{
    /**
     * Returns custom article VAT value if possible
     * By default value is taken from oxarticle__oxvat field.
     *
     * @return float
     */
    public function getCustomVAT()
    {
        $vat = $this->getArticleUserCountryVat();
        if (is_numeric($vat)) {
            return $vat;
        }
        return parent::getCustomVAT();
    }

    /**
     * get special vat for article user country.
     *
     * @return null|float
     */
    public function getArticleUserCountryVat()
    {
        $countryId = $this->getArticleUserVatCountryId();

        if (!$countryId) {
            return null;
        }

        return $this->getArticleCountryVat($countryId) ?? $this->getArticleCategoryCountryVat($countryId) ?? $this->getCountryVat($countryId);
    }

    /**
     * get article category user country vat.
     *
     * @return null|float
     */
    public function getArticleCategoryCountryVat(string $countryId)
    {
        // fetch category ids, first in is the main category
        $categoryIds = $this->getCategoryIds();

        $categoryVatRelation = oxNew(Category2CountryVat::class);
        $categoryVatRelation->loadByFirstCategoryCountry($categoryIds, $countryId);

        return $categoryVatRelation->getVat();
    }

    /**
     * get article user country vat.
     *
     * @return null|float
     */
    public function getArticleCountryVat(string $countryId)
    {
        $articleVatRelation = oxNew(Product2CountryVat::class);
        $loaded             = $articleVatRelation->loadByProductCountry((string) $this->getId(), $countryId);

        // TODO: check if this can be fetched in one go
        // if we failed to find something, we might have a variant so check the parent
        if (!$loaded && ($parentId = $this->getParentId())) {
            $articleVatRelation->loadByProductCountry($parentId, $countryId);
        }

        return $articleVatRelation->getVat();
    }

    /**
     * @return null|string
     */
    public function getArticleUserVatCountryId()
    {
        $user = $this->getArticleUser();

        if (!$user) {
            // bail out, we don't know the country
            return null;
        }

        return $user->getVatCountry();
    }

    /**
     * get user country vat.
     *
     * @return null|float
     */
    protected function getCountryVat(string $countryId)
    {
        $countryVat = oxNew(Country2Vat::class);

        if ($countryVat->loadFromCountryAndShopId($countryId, EshopRegistry::getConfig()->getShopId())) {
            return $countryVat->vat();
        }

        return null;
    }
}
