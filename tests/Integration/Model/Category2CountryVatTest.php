<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration\Controller;

use OxidEsales\Eshop\Core\Field;
use OxidProfessionalServices\CountryVatAdministration\Model\Article;
use OxidProfessionalServices\CountryVatAdministration\Model\Category2CountryVat;
use OxidProfessionalServices\CountryVatAdministration\Model\Country2Vat;
use OxidProfessionalServices\CountryVatAdministration\Model\User;
use OxidProfessionalServices\CountryVatAdministration\Tests\Integration\BaseTestCase;

class Category2CountryVatTest extends BaseTestCase
{
    public function testGetCategoryVatOnArticleWithMoreCategories()
    {
        $this->setCategoryToCountryVat(self::CATEGORY_ID, self::COUNTRY_ID_DE, 10);
        $this->setCategoryToCountryVat(self::OTHER_CATEGORY_ID, self::COUNTRY_ID_DE, 15);

        $articleModel = oxNew(Article::class);
        $articleModel->load(self::OTHER_ARTICLE_ID);

        //takes the vat value that is set first according to time stamp
        $this->assertSame('15', $articleModel->getArticleCategoryCountryVat(self::COUNTRY_ID_DE));
    }

    public function testGetCategoryVatForUsersFromDifferentCountries()
    {
        $this->setCategoryToCountryVat(self::CATEGORY_ID, self::COUNTRY_ID_DE, 10);
        $this->setCategoryToCountryVat(self::CATEGORY_ID, self::COUNTRY_ID_BE, 15);

        $userDe = oxNew(User::class);
        $userDe->load(self::USER_ID_DE);

        $articleModel = oxNew(Article::class);
        $articleModel->load(self::ARTICLE_ID);
        $articleModel->setArticleUser($userDe);

        $this->assertSame('10', $articleModel->getCustomVAT());

        $userBe = oxNew(User::class);
        $userBe->load(self::USER_ID_BE);

        $articleModel->setArticleUser($userBe);
        $this->assertSame('15', $articleModel->getCustomVAT());
    }

    public function testGetCategoryVatInsteadOfCountrySpecialVat()
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

        $this->setCategoryToCountryVat(self::CATEGORY_ID, self::COUNTRY_ID_DE, 11);

        $userDe = oxNew(User::class);
        $userDe->load(self::USER_ID_DE);

        $articleModel = oxNew(Article::class);
        $articleModel->load(self::ARTICLE_ID);
        $articleModel->setArticleUser($userDe);

        //takes the vat value that is set first according to time stamp
        $this->assertSame('11', $articleModel->getCustomVAT());
    }

    protected function setCategoryToCountryVat(string $categoryId, string $countryId, int $value)
    {
        $oNew = oxNew(Category2CountryVat::class);
        $oNew->oxpscategory2countryvat__oxcategoryid = new Field($categoryId);
        $oNew->oxpscategory2countryvat__oxcountryid = new Field($countryId);
        $oNew->oxpscategory2countryvat__vat = new Field($value);
        $oNew->save();
    }
}
