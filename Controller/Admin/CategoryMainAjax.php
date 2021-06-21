<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Controller\Admin;

use OxidProfessionalServices\CountryVatAdministration\Model\Category2CountryVat;

class CategoryMainAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
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
    protected function _getQuery()
    {
        $oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $sArtId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');
        $sSynchArtId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        $sAttrViewName = $this->_getViewName('oxcountry');
        $sO2AViewName = $this->_getViewName('oxpscategory2countryvat');
        if ($sArtId) {
            $sQAdd = " from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxcategoryid = " . $oDb->quote($sArtId) . " " .
                     " and {$sAttrViewName}.oxactive = " . $oDb->quote(1) . " ";
        } else {
            $sQAdd = " from {$sAttrViewName} where {$sAttrViewName}.oxid not in ( select {$sO2AViewName}.oxcountryid " .
                     "from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxcategoryid = " . $oDb->quote($sSynchArtId) . " ) " .
                     " and {$sAttrViewName}.oxactive = " . $oDb->quote(1) . " ";
        }

        return $sQAdd;
    }

    /**
     * Removes article attributes.
     */
    public function removeAttr()
    {
        $aChosenArt = $this->_getActionIds('oxpscategory2countryvat.oxid');
        $sOxid = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('oxid');
        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $sO2AViewName = $this->_getViewName('oxpscategory2countryvat');
            $sQ = $this->_addFilter("delete $sO2AViewName.* " . $this->_getQuery());
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute($sQ);
        } elseif (is_array($aChosenArt)) {
            $sChosenArticles = implode(", ", \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($aChosenArt));
            $sQ = "delete from oxpscategory2countryvat where oxpscategory2countryvat.oxid in ({$sChosenArticles}) ";
            \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->Execute($sQ);
        }
    }

    /**
     * Adds attributes to article.
     */
    public function addAttr()
    {
        $aAddCat = $this->_getActionIds('oxcountry.oxid');
        $soxId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('synchoxid');

        if (\OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter('all')) {
            $sAttrViewName = $this->_getViewName('oxcountry');
            $aAddCat = $this->_getAll($this->_addFilter("select $sAttrViewName.oxid " . $this->_getQuery()));
        }

        if ($soxId && $soxId != "-1" && is_array($aAddCat)) {
            foreach ($aAddCat as $sAdd) {
                $oNew = oxNew(Category2CountryVat::class);
                $oNew->oxpscategory2countryvat__oxcategoryid = new \OxidEsales\Eshop\Core\Field($soxId);
                $oNew->oxpscategory2countryvat__oxcountryid = new \OxidEsales\Eshop\Core\Field($sAdd);
                $oNew->save();
            }
        }
    }

    /**
     * Saves attribute value
     *
     * @return null
     */
    public function saveAttributeValue()
    {
        $database = \OxidEsales\Eshop\Core\DatabaseProvider::getDb();
        $this->resetContentCache();

        $categoryId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("oxid");
        $countryId = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("attr_oxid");
        $vatValue = \OxidEsales\Eshop\Core\Registry::getConfig()->getRequestParameter("attr_value");

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        if ($category->load($categoryId)) {
            if (isset($vatValue) && ("" != $vatValue)) {
                $viewName = $this->_getViewName("oxpscategory2countryvat");
                $quotedCategoryId = $database->quote($category->oxcategories__oxid->value);
                $select = "select * from {$viewName} where {$viewName}.oxcategoryid= {$quotedCategoryId} and
                            {$viewName}.oxcountryid= " . $database->quote($countryId);

                $objectToAttribute = oxNew(Category2CountryVat::class);
                if ($objectToAttribute->assignRecord($select)) {
                    $objectToAttribute->oxpscategory2countryvat__vat->setValue($vatValue);
                    $objectToAttribute->save();
                }
            }
        }
    }
}