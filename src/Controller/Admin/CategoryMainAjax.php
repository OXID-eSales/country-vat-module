<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\CountryVat\Controller\Admin;

use OxidEsales\Eshop\Application\Model\Category;
use OxidEsales\Eshop\Core\Database\Adapter\Doctrine\ResultSet;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\CountryVat\Model\Category2CountryVat;
use OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax;

class CategoryMainAjax extends ListComponentAjax
{
    /**
     * Columns array
     *
     * @var array
     */
    protected $_aColumns = ['container1' => [ // field , table,         visible, multilanguage, ident
                                              ['oxtitle', 'oxcountry', 1, 1, 0],
                                              ['oxid', 'oxcountry', 0, 0, 1]
    ],
                            'container2' => [
                                ['oxtitle', 'oxcountry', 1, 1, 0],
                                ['oxid', 'oxpscategory2countryvat', 0, 0, 1],
                                ['vat', 'oxpscategory2countryvat', 0, 1, 1],
                                ['oxcountryid', 'oxpscategory2countryvat', 0, 0, 1],
                            ]
    ];

    /**
     * Returns SQL query for data to fetch
     *
     * @return string
     */
    protected function getQuery()
    {
        $oDb = DatabaseProvider::getDb();
        $sCategoryId = Registry::getRequest()->getRequestParameter('oxid');
        $sSynchArtId = Registry::getRequest()->getRequestParameter('synchoxid');

        $sAttrViewName = $this->getViewName('oxcountry');
        $sO2AViewName = $this->getViewName('oxpscategory2countryvat');
        if ($sCategoryId) {
            return " from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxcategoryid = " . $oDb->quote($sCategoryId) . " " .
                     " and {$sAttrViewName}.oxactive = " . $oDb->quote(1) . " ";
        }
        return " from {$sAttrViewName} where {$sAttrViewName}.oxid not in ( select {$sO2AViewName}.oxcountryid " .
                 "from {$sO2AViewName} left join {$sAttrViewName} " .
                 "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                 " where {$sO2AViewName}.oxcategoryid = " . $oDb->quote($sSynchArtId) . " ) " .
                 " and {$sAttrViewName}.oxactive = " . $oDb->quote(1) . " ";
    }

    /**
     * Removes article attributes.
     */
    public function removeAttr()
    {
        $aChosenArt = $this->getActionIds('oxpscategory2countryvat.oxid');
        if (Registry::getRequest()->getRequestParameter('all')) {
            $sO2AViewName = $this->getViewName('oxpscategory2countryvat');
            $sQuery = $this->addFilter("delete $sO2AViewName.* " . $this->getQuery());
            DatabaseProvider::getDb()->Execute($sQuery);
        } elseif (is_array($aChosenArt)) {
            $sChosenArticles = implode(", ", DatabaseProvider::getDb()->quoteArray($aChosenArt));
            $sQuery = "delete from oxpscategory2countryvat where oxpscategory2countryvat.oxid in ({$sChosenArticles}) ";
            DatabaseProvider::getDb()->Execute($sQuery);
        }
    }

    /**
     * Adds attributes to article.
     */
    public function addAttr()
    {
        $aAddCat = $this->getActionIds('oxcountry.oxid');
        $soxId = Registry::getRequest()->getRequestParameter('synchoxid');

        if (Registry::getRequest()->getRequestParameter('all')) {
            $sAttrViewName = $this->getViewName('oxcountry');
            $aAddCat = $this->getAll($this->addFilter("select $sAttrViewName.oxid " . $this->getQuery()));
        }

        if ($soxId && $soxId != "-1" && is_array($aAddCat)) {
            foreach ($aAddCat as $sAdd) {
                $oNew = oxNew(Category2CountryVat::class);
                $oNew->assign(
                    [
                        'oxcategoryid' => new Field($soxId),
                        'oxcountryid' => new Field($sAdd),
                    ]
                );
                $oNew->save();
            }
        }
    }

    /**
     * Saves attribute value
     */
    public function saveAttributeValue()
    {
        $database = DatabaseProvider::getDb();
        $request = Registry::getRequest();
        $this->resetContentCache();

        $categoryId = $request->getRequestParameter("oxid");
        $countryId = $request->getRequestParameter("attr_oxid");
        $vatValue = $request->getRequestParameter("attr_value");

        $category = oxNew(Category::class);
        if ($category->load($categoryId)) {
            if (isset($vatValue) && ("" != $vatValue)) {
                $viewName = $this->getViewName("oxpscategory2countryvat");
                $quotedCategoryId = $database->quote($category->getId());
                $select = "select * from {$viewName} where {$viewName}.oxcategoryid= {$quotedCategoryId} and
                            {$viewName}.oxcountryid= " . $database->quote($countryId);

                /** @var ResultSet $record */
                $record = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->select($select);

                $objectToAttribute = oxNew(Category2CountryVat::class);
                if ($record->count() > 0) {
                    $objectToAttribute->assign($record->fields);
                    $objectToAttribute->assign(['vat' => $vatValue]);
                    $objectToAttribute->save();
                }
            }
        }
    }
}
