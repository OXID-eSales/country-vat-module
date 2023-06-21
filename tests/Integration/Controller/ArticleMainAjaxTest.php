<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration\Controller;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidProfessionalServices\CountryVatAdministration\Controller\Admin\ArticleMainAjax;
use OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CategoryMainAjax;
use OxidProfessionalServices\CountryVatAdministration\Model\Article;
use OxidProfessionalServices\CountryVatAdministration\Model\Category2CountryVat;
use OxidProfessionalServices\CountryVatAdministration\Model\Country2Vat;
use OxidProfessionalServices\CountryVatAdministration\Model\Product2CountryVat;
use OxidProfessionalServices\CountryVatAdministration\Model\User;
use OxidProfessionalServices\CountryVatAdministration\Tests\Integration\BaseTestCase;
use PHPUnit\Framework\TestCase;

class ArticleMainAjaxTest extends BaseTestCase
{
    public function testAssignCountryToArticleWithSpecialVat()
    {
        $_POST['oxid'] = '1000';
        $_POST['attr_oxid'] = 'testcountry_de';
        $_POST['attr_value'] = '19';
        $_POST['synchoxid'] = '1000';
        $_POST['cmpid'] = 'container1';
        $_POST['_1'] = ['testcountry_be', 'testcountry_de'];

        $ajax = oxNew(ArticleMainAjax::class);
        $ajax->addAttr();
        $ajax->saveAttributeValue();

        $userModel = oxNew(User::class);
        $userModel->load('germanuser');

        $articleModel = oxNew(Article::class);
        $articleModel->load('1000');
        $articleModel->setArticleUser($userModel);

        $this->assertSame('19', $articleModel->getCustomVAT());
    }

    public function testUnassignCountryFromCategory()
    {
        $_POST['synchoxid'] = '1000';
        $_POST['cmpid'] = 'container1';
        $_POST['_1'] = ['testcountry_be', 'testcountry_de'];

        $ajax = oxNew(ArticleMainAjax::class);
        $ajax->addAttr();

        $product2Country = oxNew(Product2CountryVat::class);
        $this->assertTrue($product2Country->loadByProductCountry('1000', 'testcountry_de'));
        $deProduct2CountryId = $product2Country->getId();
        $this->assertTrue($product2Country->loadByProductCountry('1000', 'testcountry_be'));
        $beProduct2CountryId = $product2Country->getId();

        $_POST['cmpid'] = 'container2';
        $_POST['_1'] = [$deProduct2CountryId, $beProduct2CountryId];
        $ajax->removeAttr();

        $this->assertFalse($product2Country->loadByProductCountry('1000', 'testcountry_de'));
        $this->assertFalse($product2Country->loadByProductCountry('1000', 'testcountry_be'));
    }
}
