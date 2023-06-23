<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration\Controller;

use OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CategoryMainAjax;
use OxidProfessionalServices\CountryVatAdministration\Model\Article;
use OxidProfessionalServices\CountryVatAdministration\Model\Category2CountryVat;
use OxidProfessionalServices\CountryVatAdministration\Model\User;
use OxidProfessionalServices\CountryVatAdministration\Tests\Integration\BaseTestCase;

class CategoryMainAjaxTest extends BaseTestCase
{
    public function testAssignCountryToCategoryWithSpecialVat()
    {
        $_POST['oxid'] = self::CATEGORY_ID;
        $_POST['attr_oxid'] = self::COUNTRY_ID_DE;
        $_POST['attr_value'] = '9';
        $_POST['synchoxid'] = self::CATEGORY_ID;
        $_POST['cmpid'] = 'container1';
        $_POST['_1'] = [self::COUNTRY_ID_BE, self::COUNTRY_ID_DE];

        $ajax = oxNew(CategoryMainAjax::class);
        $ajax->addAttr();
        $ajax->saveAttributeValue();

        $userModel = oxNew(User::class);
        $userModel->load(self::USER_ID_DE);

        $articleModel = oxNew(Article::class);
        $articleModel->load(self::ARTICLE_ID);
        $articleModel->setArticleUser($userModel);

        $this->assertEquals('9', $articleModel->getCustomVAT());
    }

    public function testUnassignCountryFromCategory()
    {
        $_POST['synchoxid'] = self::CATEGORY_ID;
        $_POST['cmpid'] = 'container1';
        $_POST['_1'] = [self::COUNTRY_ID_BE, self::COUNTRY_ID_DE];

        $ajax = oxNew(CategoryMainAjax::class);
        $ajax->addAttr();

        $category2Country = oxNew(Category2CountryVat::class);
        $this->assertTrue($category2Country->loadByFirstCategoryCountry([self::CATEGORY_ID], self::COUNTRY_ID_DE));
        $deCat2CountryId = $category2Country->getId();
        $this->assertTrue($category2Country->loadByFirstCategoryCountry([self::CATEGORY_ID], self::COUNTRY_ID_BE));
        $beCat2CountryId = $category2Country->getId();

        $_POST['cmpid'] = 'container2';
        $_POST['_1'] = [$deCat2CountryId, $beCat2CountryId];
        $ajax->removeAttr();

        $this->assertFalse($category2Country->loadByFirstCategoryCountry([self::CATEGORY_ID], self::COUNTRY_ID_DE));
        $this->assertFalse($category2Country->loadByFirstCategoryCountry([self::CATEGORY_ID], self::COUNTRY_ID_BE));
    }

    public function testAssignAndUnassignAllCountriesForCategory()
    {
        $_POST['synchoxid'] = self::CATEGORY_ID;
        $_POST['cmpid'] = 'container1';
        $_POST['all'] = 1;

        $ajax = oxNew(CategoryMainAjax::class);
        $ajax->addAttr();

        $category2Country = oxNew(Category2CountryVat::class);
        $this->assertTrue($category2Country->loadByFirstCategoryCountry([self::CATEGORY_ID], self::COUNTRY_ID_DE));
        $this->assertTrue($category2Country->loadByFirstCategoryCountry([self::CATEGORY_ID], self::COUNTRY_ID_BE));

        $_POST['oxid'] = self::CATEGORY_ID;
        $_POST['all'] = 1;
        $_POST['cmpid'] = 'container2';
        $ajax->removeAttr();

        $this->assertFalse($category2Country->loadByFirstCategoryCountry([self::CATEGORY_ID], self::COUNTRY_ID_DE));
        $this->assertFalse($category2Country->loadByFirstCategoryCountry([self::CATEGORY_ID], self::COUNTRY_ID_BE));
    }
}
