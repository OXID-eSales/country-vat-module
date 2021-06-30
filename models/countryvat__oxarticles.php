<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

class countryvat__oxarticles extends countryvat__oxarticles_parent
{
    /**
     * Returns custom article VAT value if possible
     * By default value is taken from oxarticle__oxvat field
     *
     * @return double
     */
    public function getCustomVAT()
    {
        if (!isAdmin()) {
            $vat = $this->getArticleUserCountryVat();
            if (is_numeric($vat)) {
                return $vat;
            }
        }

        return parent::getCustomVAT();
    }

    /**
     * get special vat for article user country
     *
     * @return double | null
     */
    public function getArticleUserCountryVat()
    {
        $countryId = $this->getArticleUserVatCountryId();

        if (!$countryId) {
            return null;
        }

        $vat = $this->getArticleCountryVat($countryId);

        if (is_null($vat)) {
            $vat = $this->getArticleCategoryCountryVat($countryId);
        }
        if (is_null($vat)) {
            $vat = $this->getCountryVat($countryId);
        }

        return $vat;
    }

    /**
     * get article category user country vat
     *
     * @param string $countryId
     *
     * @return float|null
     * @throws oxSystemComponentException
     */
    public function getArticleCategoryCountryVat($countryId)
    {
        //fetch category ids, first in is the main category
        $categoryIds = $this->getCategoryIds();

        $categoryVatRelation = oxNew('countryvat_category2countryvat');
        $categoryVatRelation->loadByFirstCategoryCountry($categoryIds, $countryId);

        return $categoryVatRelation->getVat();
    }

    /**
     * get article user country vat
     *
     * @param string $countryId
     *
     * @return float|null
     */
    public function getArticleCountryVat($countryId)
    {
        $articleVatRelation = oxNew('countryvat_product2countryvat');
        $loaded = $articleVatRelation->loadByProductCountry($this->getId(), $countryId);

        //TODO: check if this can be fetched in one go
        //if we failed to find something, we might have a variant so check the parent
        if (!$loaded && ($parentId = $this->getParentId())) {
            $articleVatRelation->loadByProductCountry($parentId, $countryId);
        }

        return $articleVatRelation->getVat();
    }

    /**
     * @return string | null
     */
    public function getArticleUserVatCountryId()
    {
        $user = $this->getArticleUser();

        if (!$user) {
            //bail out, we don't know the country
            return null;
        }

        return $user->getVatCountry();
    }

    /**
     * get user country vat
     *
     * @param string $countryId
     *
     * @return float|null
     */
    protected function getCountryVat($countryId)
    {
        $countryVat = oxNew('countryvat_country2vat');

        if ($countryVat->loadFromCountryAndShopId($countryId, oxRegistry::getConfig()->getShopId())) {
            return $countryVat->vat();
        }

        return null;
    }
}