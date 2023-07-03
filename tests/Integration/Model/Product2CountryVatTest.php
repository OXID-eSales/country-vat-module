<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\CountryVat\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Field;
use OxidEsales\CountryVat\Model\Article;
use OxidEsales\CountryVat\Model\Category2CountryVat;
use OxidEsales\CountryVat\Model\Country2Vat;
use OxidEsales\CountryVat\Model\Product2CountryVat;
use OxidEsales\CountryVat\Model\User;
use OxidEsales\CountryVat\Tests\Integration\BaseTestCase;

class Product2CountryVatTest extends BaseTestCase
{
    public function testGetArticleVatForUsersFromDifferentCountries()
    {
        $this->setArticleToCountryVat(self::ARTICLE_ID, self::COUNTRY_ID_DE, 10);
        $this->setArticleToCountryVat(self::ARTICLE_ID, self::COUNTRY_ID_BE, 15);

        $userDe = oxNew(User::class);
        $userDe->load(self::USER_ID_DE);

        $articleModel = oxNew(Article::class);
        $articleModel->load(self::ARTICLE_ID);
        $articleModel->setArticleUser($userDe);

        $this->assertEquals('10', $articleModel->getCustomVAT());

        $userBe = oxNew(User::class);
        $userBe->load(self::USER_ID_BE);

        $articleModel->setArticleUser($userBe);
        $this->assertEquals('15', $articleModel->getCustomVAT());
    }

    public function testGetArticleVatInsteadOfCountrySpecialVat()
    {
        $countryToVat = oxNew(Country2Vat::class);
        $countryToVat->assign(
            [
                'OXCOUNTRYID' => self::COUNTRY_ID_DE,
                'OXSHOPID' => 1,
                'VAT' => 12
            ]
        );
        $countryToVat->save();

        $this->setArticleToCountryVat(self::ARTICLE_ID, self::COUNTRY_ID_DE, 11);

        $userDe = oxNew(User::class);
        $userDe->load(self::USER_ID_DE);

        $articleModel = oxNew(Article::class);
        $articleModel->load(self::ARTICLE_ID);
        $articleModel->setArticleUser($userDe);

        //takes the vat value that is set first according to time stamp
        $this->assertEquals('11', $articleModel->getCustomVAT());
    }

    public function testGetArticleVatInsteadOfCategoryAndCountryVat()
    {
        $countryToVat = oxNew(Country2Vat::class);
        $countryToVat->assign(
            [
                'OXCOUNTRYID' => self::COUNTRY_ID_DE,
                'OXSHOPID' => 1,
                'VAT' => 15
            ]
        );
        $countryToVat->save();

        $categoryToCountryVat = oxNew(Category2CountryVat::class);
        $categoryToCountryVat->assign(
            [
                'OXCATEGORYID' => self::CATEGORY_ID,
                'OXCOUNTRYID' => self::COUNTRY_ID_DE,
                'OXSHOPID' => 1,
                'VAT' => 12
            ]
        );
        $categoryToCountryVat->save();

        $this->setArticleToCountryVat(self::ARTICLE_ID, self::COUNTRY_ID_DE, 10);

        $userDe = oxNew(User::class);
        $userDe->load(self::USER_ID_DE);

        $articleModel = oxNew(Article::class);
        $articleModel->load(self::ARTICLE_ID);
        $articleModel->setArticleUser($userDe);

        //takes the vat value that is set first according to time stamp
        $this->assertEquals('10', $articleModel->getCustomVAT());
    }

    protected function setArticleToCountryVat(string $articleId, string $countryId, int $value)
    {
        $oNew = oxNew(Product2CountryVat::class);
        $oNew->oxpsarticle2countryvat__oxarticleid = new Field($articleId);
        $oNew->oxpsarticle2countryvat__oxcountryid = new Field($countryId);
        $oNew->oxpsarticle2countryvat__vat = new Field($value);
        $oNew->save();
    }
}
