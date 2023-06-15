<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Controller\Admin;

use OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax;
use OxidEsales\Eshop\Application\Model\Article;
use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Registry;
use OxidProfessionalServices\CountryVatAdministration\Model\Product2CountryVat;

class ArticleMainAjax extends ListComponentAjax
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
                                ['oxid', 'oxpsarticle2countryvat', 0, 0, 1],
                                ['vat', 'oxpsarticle2countryvat', 0, 1, 1],
                                ['oxcountryid', 'oxpsarticle2countryvat', 0, 0, 1],
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
        $sArtId = Registry::getRequest()->getRequestParameter('oxid');
        $sSynchArtId = Registry::getRequest()->getRequestParameter('synchoxid');

        $sAttrViewName = $this->getViewName('oxcountry');
        $sO2AViewName = $this->getViewName('oxpsarticle2countryvat');
        if ($sArtId) {
            $sQAdd = " from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxarticleid = " . $oDb->quote($sArtId) . " " .
                     " and {$sAttrViewName}.oxactive = " . $oDb->quote(1) . " ";
        } else {
            $sQAdd = " from {$sAttrViewName} where {$sAttrViewName}.oxid not in ( select {$sO2AViewName}.oxcountryid " .
                     "from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxarticleid = " . $oDb->quote($sSynchArtId) . " ) " .
                     " and {$sAttrViewName}.oxactive = " . $oDb->quote(1) . " ";
        }

        return $sQAdd;
    }

    /**
     * Removes article attributes.
     */
    public function removeAttr()
    {
        $aChosenArt = $this->getActionIds('oxpsarticle2countryvat.oxid');
        if (Registry::getRequest()->getRequestParameter('all')) {
            $sO2AViewName = $this->getViewName('oxpsarticle2countryvat');
            $sQ = $this->addFilter("delete $sO2AViewName.* " . $this->getQuery());
            DatabaseProvider::getDb()->Execute($sQ);
        } elseif (is_array($aChosenArt)) {
            $sChosenArticles = implode(", ", DatabaseProvider::getDb()->quoteArray($aChosenArt));
            $sQ = "delete from oxpsarticle2countryvat where oxpsarticle2countryvat.oxid in ({$sChosenArticles}) ";
            DatabaseProvider::getDb()->Execute($sQ);
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
                $oNew = oxNew(Product2CountryVat::class);
                $oNew->oxpsarticle2countryvat__oxarticleid = new Field($soxId);
                $oNew->oxpsarticle2countryvat__oxcountryid = new Field($sAdd);
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
        $database = DatabaseProvider::getDb();
        $request = Registry::getRequest();
        $this->resetContentCache();

        $articleId = $request->getRequestParameter("oxid");
        $countryId = $request->getRequestParameter("attr_oxid");
        $vatValue = $request->getRequestParameter("attr_value");

        $article = oxNew(Article::class);
        if ($article->load($articleId)) {
            if (isset($vatValue) && ("" != $vatValue)) {
                $viewName = $this->getViewName("oxpsarticle2countryvat");
                $quotedArticleId = $database->quote($article->oxarticles__oxid->value);
                $select = "select * from {$viewName} where {$viewName}.oxarticleid= {$quotedArticleId} and
                            {$viewName}.oxcountryid= " . $database->quote($countryId);
                $record = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC)->select($select);

                $objectToAttribute = oxNew(Product2CountryVat::class);
                if ($record && $record->count() > 0) {
                    $objectToAttribute->assign($record->fields);
                    $objectToAttribute->oxpsarticle2countryvat__vat->setValue($vatValue);
                    $objectToAttribute->save();
                }
            }
        }
    }
}