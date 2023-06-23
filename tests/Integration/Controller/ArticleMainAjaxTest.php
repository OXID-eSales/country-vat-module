<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration\Controller;

use OxidProfessionalServices\CountryVatAdministration\Controller\Admin\ArticleMainAjax;
use OxidProfessionalServices\CountryVatAdministration\Model\Article;
use OxidProfessionalServices\CountryVatAdministration\Model\Product2CountryVat;
use OxidProfessionalServices\CountryVatAdministration\Model\User;
use OxidProfessionalServices\CountryVatAdministration\Tests\Integration\BaseTestCase;

class ArticleMainAjaxTest extends BaseTestCase
{
    public function testAssignCountryToArticleWithSpecialVat()
    {
        $_POST['oxid'] = self::ARTICLE_ID;
        $_POST['attr_oxid'] = self::COUNTRY_ID_DE;
        $_POST['attr_value'] = '19';
        $_POST['synchoxid'] = self::ARTICLE_ID;
        $_POST['cmpid'] = 'container1';
        $_POST['_1'] = [self::COUNTRY_ID_BE, self::COUNTRY_ID_DE];

        $ajax = oxNew(ArticleMainAjax::class);
        $ajax->addAttr();
        $ajax->saveAttributeValue();

        $userModel = oxNew(User::class);
        $userModel->load(self::USER_ID_DE);

        $articleModel = oxNew(Article::class);
        $articleModel->load(self::ARTICLE_ID);
        $articleModel->setArticleUser($userModel);

        $this->assertEquals('19', $articleModel->getCustomVAT());
    }

    public function testUnassignCountryFromCategory()
    {
        $_POST['synchoxid'] = self::ARTICLE_ID;
        $_POST['cmpid'] = 'container1';
        $_POST['_1'] = [self::COUNTRY_ID_BE, self::COUNTRY_ID_DE];

        $ajax = oxNew(ArticleMainAjax::class);
        $ajax->addAttr();

        $product2Country = oxNew(Product2CountryVat::class);
        $this->assertTrue($product2Country->loadByProductCountry(self::ARTICLE_ID, self::COUNTRY_ID_DE));
        $deProduct2CountryId = $product2Country->getId();
        $this->assertTrue($product2Country->loadByProductCountry(self::ARTICLE_ID, self::COUNTRY_ID_BE));
        $beProduct2CountryId = $product2Country->getId();

        $_POST['cmpid'] = 'container2';
        $_POST['_1'] = [$deProduct2CountryId, $beProduct2CountryId];
        $ajax->removeAttr();

        $this->assertFalse($product2Country->loadByProductCountry(self::ARTICLE_ID, self::COUNTRY_ID_DE));
        $this->assertFalse($product2Country->loadByProductCountry(self::ARTICLE_ID, self::COUNTRY_ID_BE));
    }
}
