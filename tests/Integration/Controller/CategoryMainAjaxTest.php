<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration\Controller;

use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CategoryMainAjax;
use OxidProfessionalServices\CountryVatAdministration\Model\Article;
use OxidProfessionalServices\CountryVatAdministration\Model\Category2CountryVat;
use OxidProfessionalServices\CountryVatAdministration\Model\Country2Vat;
use OxidProfessionalServices\CountryVatAdministration\Model\User;
use OxidProfessionalServices\CountryVatAdministration\Tests\Integration\BaseTestCase;
use PHPUnit\Framework\TestCase;

class CategoryMainAjaxTest extends BaseTestCase
{
    public function testAssignCountryToCategoryWithSpecialVat()
    {
        $_POST['oxid'] = 'testcategory0';
        $_POST['attr_oxid'] = 'testcountry_de';
        $_POST['attr_value'] = '9';
        $_POST['synchoxid'] = 'testcategory0';
        $_POST['cmpid'] = 'container1';
        $_POST['_1'] = ['testcountry_be', 'testcountry_de'];

        $ajax = oxNew(CategoryMainAjax::class);
        $ajax->addAttr();
        $ajax->saveAttributeValue();

        $userModel = oxNew(User::class);
        $userModel->load('germanuser');

        $articleModel = oxNew(Article::class);
        $articleModel->load('1000');
        $articleModel->setArticleUser($userModel);

        $this->assertSame('9', $articleModel->getCustomVAT());
    }

    public function testUnassignCountryFromCategory()
    {
        $_POST['synchoxid'] = 'testcategory0';
        $_POST['cmpid'] = 'container1';
        $_POST['_1'] = ['testcountry_be', 'testcountry_de'];

        $ajax = oxNew(CategoryMainAjax::class);
        $ajax->addAttr();

        $category2Country = oxNew(Category2CountryVat::class);
        $this->assertTrue($category2Country->loadByFirstCategoryCountry(['testcategory0'], 'testcountry_de'));
        $deCat2CountryId = $category2Country->getId();
        $this->assertTrue($category2Country->loadByFirstCategoryCountry(['testcategory0'], 'testcountry_be'));
        $beCat2CountryId = $category2Country->getId();

        $_POST['cmpid'] = 'container2';
        $_POST['_1'] = [$deCat2CountryId, $beCat2CountryId];
        $ajax->removeAttr();

        $this->assertFalse($category2Country->loadByFirstCategoryCountry(['testcategory0'], 'testcountry_de'));
        $this->assertFalse($category2Country->loadByFirstCategoryCountry(['testcategory0'], 'testcountry_be'));
    }
}
