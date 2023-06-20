<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Integration\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\CategoryMainAjax;
use OxidEsales\EshopCommunity\Internal\Container\ContainerFactory;
use OxidEsales\EshopCommunity\Internal\Framework\Database\QueryBuilderFactoryInterface;
use OxidProfessionalServices\CountryVatAdministration\Model\Article;
use OxidProfessionalServices\CountryVatAdministration\Model\Category2CountryVat;
use OxidProfessionalServices\CountryVatAdministration\Model\Country2Vat;
use OxidProfessionalServices\CountryVatAdministration\Tests\Integration\BaseTestCase;
use PHPUnit\Framework\TestCase;

class CategoryMainAjaxTest extends BaseTestCase
{
    public function testAssignCountryToCategoryWithSpecialVat()
    {
        $_POST['synchoxid'] = 'testcategory0';

        $ajax = $this->getMockBuilder(CategoryMainAjax::class)
            ->onlyMethods(['getActionIds', 'addAttr'])
            ->getMock();
        $ajax->expects($this->any())->method('getActionIds')->willReturn(['testcountry_be', 'testcountry_de']);
        $ajax->addAttr();

        $category2Country = oxNew(Category2CountryVat::class);
        $category2Country->loadByFirstCategoryCountry(['testcategory0'], 'testcountry_de');

        $_POST['oxid'] = 'testcategory0';
        $_POST['attr_oxid'] = $category2Country->getId();
        $_POST['attr_value'] = 9;

        $articleModel = $this->getMockBuilder(Article::class)->getMock();
        $articleModel->expects($this->any())->method('getArticleUserVatCountryId')->willReturn('testcountry_de');

        $this->assertSame('9', $articleModel->getArticleUserCountryVat());
    }
}
