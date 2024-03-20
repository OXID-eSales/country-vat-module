<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

namespace OxidEsales\CountryVat\Tests\Codeception\Acceptance;

use Codeception\Util\Fixtures;
use OxidEsales\Codeception\Module\Translation\Translator;
use OxidEsales\Codeception\Step\Basket;
use OxidEsales\CountryVat\Tests\Codeception\Support\AcceptanceTester;

/**
 * @group oecountryvat
 */
final class UpdateVatAdminCest
{
    private string $countryVatSelector = "//div[@id='container2_c']/table//td//*[contains(text(), '%s')]";
    private string $countryVatInput = '#oxps_countryvatadministration_country_vat';
    private string $vatCell = '//div[contains(@class, "basketitemrow")][%d]//span[@class="article-vat"]';
    private string $vatCellSmarty = '//tr[%d]//td[@class="vatPercent"]';
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

        $home->openBasket();

        foreach ($products as $index => $product) {
            $this->seeProductVat($I, $product['vat'], $index + 1);
        }
    }

    private function checkVatInFrontend(AcceptanceTester $I, string $vatValue, string $user)
    {
        $I->clearShopCache();
        $home = $I->openShop();

        $germanUser = Fixtures::get($user);
        $home->loginUser($germanUser['userLoginName'], $germanUser['userPassword']);

        $basket = new Basket($I);
        $basket->addProductToBasket('1000', 1);
        $home->openBasket();

        $this->seeProductVat($I, $vatValue);
    }

    /**
     * @param \OxidEsales\CountryVat\Tests\Codeception\Support\AcceptanceTester $I
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
        $country = 'Germany';

        $productList = $adminPage->openProducts();
        $this->switchLanguage($I, Fixtures::get('language'));
        $productList->find('where[oxarticles][oxartnum]', Fixtures::get('product'));
        $I->selectEditFrame();

        $I->click($this->specificVatButton);
        $I->switchToNextTab();//codeception way of opening next window
        $I->waitForDocumentReadyState();
        $I->waitForText($country);
        $I->click(Translator::translate('GENERAL_AJAX_ASSIGNALL'));

        $I->waitForElement(sprintf($this->countryVatSelector, $country));
        $I->click(sprintf($this->countryVatSelector, $country));
        $I->fillField($this->categoryVatInput, $vat);
        $I->click(Translator::translate('GENERAL_SAVE'));
        $I->closeTab();
    }

    private function addCategoryVat(AcceptanceTester $I, $adminPage, string $vat): void
    {
        $categoryList = $adminPage->openCategories();
        $this->switchLanguage($I, Fixtures::get('language'));
        $categoryList->selectCategory(Fixtures::get('category'));

        $country = 'Germany';

        $I->selectEditFrame();
        $I->click($this->specificVatButton);
        $I->switchToNextTab();//codeception way of opening next window
        $I->waitForDocumentReadyState();
        $I->waitForText($country);
        $I->click(Translator::translate('GENERAL_AJAX_ASSIGNALL'));

        $I->waitForElement(sprintf($this->countryVatSelector, $country));
        $I->click(sprintf($this->countryVatSelector, $country));
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

    private function seeProductVat(AcceptanceTester $I, string $vatValue, int $index = 1): void
    {
        if (getenv('THEME_ID') !== 'apex') {
            $vatTextValue = $vatValue;
            $vatCellElement = $this->vatCellSmarty;
        } else {
            $vatTextValue = str_replace('%', ' % ' . Translator::translate('VAT'), $vatValue);
            $vatCellElement = $this->vatCell;
        }

        $I->see($vatTextValue, sprintf($vatCellElement, $index));
    }
}
