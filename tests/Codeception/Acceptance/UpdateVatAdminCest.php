<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidProfessionalServices\CountryVatAdministration\Tests\Codeception;

use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\Codeception\Step\Basket;

final class UpdateVatAdminCest
{
    private string $countryVatSelector = "//div[@id='container2_c']/table//td//*[contains(text(), '%s')]";
    private string $countryVatInput = '#oxps_countryvatadministration_country_vat';
    private string $vatCell = '//tr[%d]//td[@class="vatPercent"]';
    private string $languageSelect = "//select[@name='changelang']";
    private string $specificVatButton = "//input[@value='Country Specific VAT']";
    private string $categoryVatInput = "#attr_value";

    /** @param AcceptanceTester $I */
    public function updateCountrySpecialVat(AcceptanceTester $I): void
    {
        $I->openAdmin();
        $adminPage = $I->loginAdmin();

        $this->addCountryVat($I, $adminPage, '11');

        // check vat value for German user is updated
        $this->checkVatInFrontend($I, '11%', 'germanUser');

        //check vat value for UK user is different
        $this->checkVatInFrontend($I, '5%', 'ukUser');
    }

    /** @param AcceptanceTester $I */
    public function updateCategorySpecialVat(AcceptanceTester $I): void
    {
        $I->openAdmin();
        $adminPage = $I->loginAdmin();

        $this->addCategoryVat($I, $adminPage, '15');

        // check vat value for German user is updated
        $this->checkVatInFrontend($I, '15%', 'germanUser');

        //check vat value for UK user is different
        $this->checkVatInFrontend($I, '5%', 'ukUser');
    }

    /** @param AcceptanceTester $I */
    public function updateProductSpecialVat(AcceptanceTester $I): void
    {
        $I->openAdmin();
        $adminPage = $I->loginAdmin();

        $this->addProductVat($I, $adminPage, '18');

        // check vat value for German user is updated
        $this->checkVatInFrontend($I, '18%', 'germanUser');

        //check vat value for UK user is different
        $this->checkVatInFrontend($I, '5%', 'ukUser');
    }

    /** @param AcceptanceTester $I */
    public function checkProductsWithDifferentVats(AcceptanceTester $I): void
    {
        $I->openAdmin();
        $adminPage = $I->loginAdmin();

        $this->addProductVat($I, $adminPage, '18');
        $this->addCountryVat($I, $adminPage, '11');
        $this->addCategoryVat($I, $adminPage, '15');

        $home = $I->openShop();

        $germanUser = Fixtures::get('germanUser');
        $home->loginUser($germanUser['userLoginName'], $germanUser['userPassword']);

        $products = [
            [
                'id'  => '1000',
                'vat' => '18'
            ], [
                'id'  => '1001',
                'vat' => '15'
            ], [
                'id'  => '1002-1',
                'vat' => '11'
            ],
        ];

        $basket = new Basket($I);
        foreach ($products as $product) {
            $basket->addProductToBasket($product['id'], 1);
        }

        $home->openMiniBasket()
            ->openBasketDisplay();

        foreach ($products as $index => $product) {
            $I->see($product['vat'], sprintf($this->vatCell, $index + 1));
        }
    }

    private function checkVatInFrontend(AcceptanceTester $I, string $vatValue, string $user)
    {
        $I->clearShopCache();
        $home = $I->openShop();

        $germanUser = Fixtures::get($user);
        $home->loginUser($germanUser['userLoginName'], $germanUser['userPassword']);

        $basket = new Basket($I);
        $basket->addProductToBasketAndOpenBasket('1000', 1);
        $I->see($vatValue, sprintf($this->vatCell, 1));
    }

    /**
     * @param \OxidProfessionalServices\CountryVatAdministration\Tests\Codeception\AcceptanceTester $I
     * @param string $language
     * @return void
     */
    private function switchLanguage(AcceptanceTester $I, string $language): void
    {
        $I->selectListFrame();
        $I->selectOption($this->languageSelect, $language);
        $I->seeOptionIsSelected($this->languageSelect, $language);
        $I->selectListFrame();
    }

    private function addProductVat(AcceptanceTester $I, $adminPage, string $vat): void
    {
        $productList = $adminPage->openProducts();
        $this->switchLanguage($I, Fixtures::get('language'));
        $productList->find('where[oxarticles][oxartnum]', Fixtures::get('product'));
        $I->selectEditFrame();

        $I->click($this->specificVatButton);
        $I->switchToNextTab();//codeception way of opening next window
        $I->waitForDocumentReadyState();
        $I->click(Translator::translate('GENERAL_AJAX_ASSIGNALL'));
        $I->waitForAjax(10);

        $I->click(sprintf($this->countryVatSelector, 'Germany'));
        $I->fillField($this->categoryVatInput, $vat);
        $I->click(Translator::translate('GENERAL_SAVE'));
        $I->closeTab();
    }

    private function addCategoryVat(AcceptanceTester $I, $adminPage, string $vat): void
    {
        $categoryList = $adminPage->openCategories();
        $this->switchLanguage($I, Fixtures::get('language'));
        $categoryList->selectCategory(Fixtures::get('category'));

        $I->selectEditFrame();
        $I->click($this->specificVatButton);
        $I->switchToNextTab();//codeception way of opening next window
        $I->waitForDocumentReadyState();
        $I->click(Translator::translate('GENERAL_AJAX_ASSIGNALL'));
        $I->waitForAjax(10);

        $I->click(sprintf($this->countryVatSelector, 'Germany'));
        $I->fillField($this->categoryVatInput, $vat);
        $I->click(Translator::translate('GENERAL_SAVE'));
        $I->closeTab();
    }

    private function addCountryVat(AcceptanceTester $I, $adminPage, string $vat): void
    {
        $countryList = $adminPage->openCountries();
        $this->switchLanguage($I, Fixtures::get('language'));
        $countryList->selectCountry('Germany');

        $I->selectEditFrame();
        $I->fillField($this->countryVatInput, $vat);
        $I->click(Translator::translate('GENERAL_SAVE'));
    }
}
