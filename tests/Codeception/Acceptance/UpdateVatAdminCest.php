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
    private string $countryVatInput = '#oxps_countryvatadministration_country_vat';
    private string $vatCell = 'td[@class="vatPercent"]';
    private string $languageSelect = "//select[@name='changelang']";
    private string $specificVatButton = "//input[@value='Country Specific VAT']";
    private string $categoryVatInput = "#attr_value";

    /** @param AcceptanceTester $I */
    public function updateCountrySpecialVat(AcceptanceTester $I): void
    {
        $I->openAdmin();
        $adminPage = $I->loginAdmin();
        $countryList = $adminPage->openCountries();
        $this->switchLanguage($I, Fixtures::get('language'));
        $countryList->selectCountry('Germany');

        $I->selectEditFrame();
        $I->fillField($this->countryVatInput, '11');
        $I->click(Translator::translate('GENERAL_SAVE'));
        $I->selectListFrame();

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
        $categoryList = $adminPage->openCategories();
        $this->switchLanguage($I, Fixtures::get('language'));
        $categoryList->selectCategory(Fixtures::get('category'));
        $I->wait(60);
        $I->selectEditFrame();
        $I->click($this->specificVatButton);
        $I->switchToNextTab();//codeception way of opening next window
        $I->waitForDocumentReadyState();
        $I->click(Translator::translate('GENERAL_AJAX_ASSIGNALL'));
        $I->waitForAjax(10);

        $I->click('Germany');
        $I->see($this->categoryVatInput);
        $I->fillField($this->categoryVatInput, '15');
        $I->click(Translator::translate('GENERAL_SAVE'));
        $I->closeTab();

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
        $productList = $adminPage->openProducts();
        $this->switchLanguage($I, Fixtures::get('language'));
        $productList->find('where[oxarticles][oxartnum]', Fixtures::get('product'));
        $I->selectEditFrame();

        $I->click($this->specificVatButton);
        $I->switchToNextTab();//codeception way of opening next window
        $I->waitForDocumentReadyState();
        $I->click(Translator::translate('GENERAL_AJAX_ASSIGNALL'));
        $I->waitForAjax(10);

        $I->click('Germany');
        $I->see($this->categoryVatInput);
        $I->fillField($this->categoryVatInput, '18');
        $I->click(Translator::translate('GENERAL_SAVE'));
        $I->closeTab();

        // check vat value for German user is updated
        $this->checkVatInFrontend($I, '18%', 'germanUser');

        //check vat value for UK user is different
        $this->checkVatInFrontend($I, '5%', 'ukUser');
    }

    private function checkVatInFrontend(AcceptanceTester $I, string $vatValue, string $user)
    {
        $I->clearShopCache();
        $home = $I->openShop();

        $germanUser = Fixtures::get($user);
        $home->loginUser($germanUser['userLoginName'], $germanUser['userPassword']);

        $basket = new Basket($I);
        $basket->addProductToBasketAndOpenBasket('1000', 1);
        $I->see($vatValue, $this->vatCell);
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
}
