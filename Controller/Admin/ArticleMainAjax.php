<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Controller\Admin;

use Doctrine\DBAL\Connection;
use OxidEsales\Eshop\Core\Registry;
use OxidProfessionalServices\CountryVatAdministration\Core\Service;
use OxidProfessionalServices\CountryVatAdministration\Model\Product2CountryVat;

class ArticleMainAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
{
    /**
     * Columns array.
     *
     * @var array
     */
    protected $_aColumns = ['container1' => [ // field , table,         visible, multilanguage, ident
        ['oxtitle', 'oxcountry', 1, 1, 0],
        ['oxid', 'oxcountry', 0, 0, 1],
    ],
        'container2' => [
            ['oxtitle', 'oxcountry', 1, 1, 0],
            ['oxid', 'oxpsarticle2countryvat', 0, 0, 1],
            ['vat', 'oxpsarticle2countryvat', 0, 1, 1],
            ['oxcountryid', 'oxpsarticle2countryvat', 0, 0, 1],
        ],
    ];

    /**
     * Removes article attributes.
     */
    public function removeAttr()
    {
        $aChosenArt = $this->getActionIds('oxpsarticle2countryvat.oxid');
        $request    = Registry::getRequest();
        if ($request->getRequestParameter('all')) {
            /** @var Connection $connection */
            $connection   = Service::getInstance()->getDatabaseConnection();
            $sO2AViewName = $this->getViewName('oxpsarticle2countryvat');
            $sQ           = $this->addFilter("delete {$sO2AViewName}.* " . $this->getQuery());
            $connection->executeStatement($sQ);
        } elseif ($aChosenArt && is_array($aChosenArt)) {
            $queryBuilder = Service::getInstance()->getQueryBuilder();
            // old query:
            // $sChosenArticles = implode(', ', \OxidEsales\Eshop\Core\DatabaseProvider::getDb()->quoteArray($aChosenArt));
            // $sQ              = "delete from oxpsarticle2countryvat where oxpsarticle2countryvat.oxid in ({$sChosenArticles}) ";
            $queryBuilder
                ->delete('oxpsarticle2countryvat')
                ->where('oxpsarticle2countryvat.oxid IN (:chosenArticles)')
                ->setParameter('chosenArticles', $aChosenArt, Connection::PARAM_STR_ARRAY)
                ->execute();
        }
    }

    /**
     * Adds attributes to article.
     */
    public function addAttr()
    {
        $aAddCat = $this->getActionIds('oxcountry.oxid');
        $request = Registry::getRequest();
        $soxId   = $request->getRequestParameter('synchoxid');

        if ($request->getRequestParameter('all')) {
            $sAttrViewName = $this->getViewName('oxcountry');
            $aAddCat       = $this->getAll($this->addFilter("select {$sAttrViewName}.oxid " . $this->getQuery()));
        }

        if ($soxId && '-1' != $soxId && is_array($aAddCat)) {
            foreach ($aAddCat as $sAdd) {
                $oNew                                      = oxNew(Product2CountryVat::class);
                $oNew->oxpsarticle2countryvat__oxarticleid = new \OxidEsales\Eshop\Core\Field($soxId);
                $oNew->oxpsarticle2countryvat__oxcountryid = new \OxidEsales\Eshop\Core\Field($sAdd);
                $oNew->save();
            }
        }
    }

    /**
     * Saves attribute value.
     */
    public function saveAttributeValue()
    {
        $request    = Registry::getRequest();
        $this->resetContentCache();

        $articleId = $request->getRequestParameter('oxid');
        $countryId = $request->getRequestParameter('attr_oxid');
        $vatValue  = $request->getRequestParameter('attr_value');
        $vatValue  = filter_var($vatValue, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);

        $article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
        if ($article->load($articleId)) {
            $viewName        = $this->getViewName('oxpsarticle2countryvat');
            $queryBuilder    = Service::getInstance()->getQueryBuilder();
            $record          = $queryBuilder
                ->select('*')
                ->from($viewName)
                ->where('oxarticleid = :articleId')
                ->setParameter('articleId', $articleId)
                ->andWhere('oxcountryid = :countryId')
                ->setParameter('countryId', $countryId)
                ->andWhere('oxshopid = :shopId')
                ->setParameter('shopId', $article->oxarticles__oxshopid->value)
                ->execute()
                ->fetchAssociative()
            ;

            $objectToAttribute = oxNew(Product2CountryVat::class);
            if ($record) {
                $objectToAttribute->assign($record);
                $objectToAttribute->oxpsarticle2countryvat__vat->setValue($vatValue);
                $objectToAttribute->save();
            }
        }
    }

    /**
     * Returns SQL query for data to fetch.
     *
     * @return string
     */
    protected function getQuery()
    {
        $connection   = Service::getInstance()->getDatabaseConnection();
        $request      = Registry::getRequest();
        $sArtId       = $request->getRequestParameter('oxid');
        $sSynchArtId  = $request->getRequestParameter('synchoxid');

        $sAttrViewName = $this->getViewName('oxcountry');
        $sO2AViewName  = $this->getViewName('oxpsarticle2countryvat');
        if ($sArtId) {
            $sQAdd = " from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxarticleid = " . $connection->quote($sArtId) . ' ' .
                     " and {$sAttrViewName}.oxactive = " . $connection->quote(1) . ' ';
        } else {
            $sQAdd = " from {$sAttrViewName} where {$sAttrViewName}.oxid not in ( select {$sO2AViewName}.oxcountryid " .
                     "from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxarticleid = " . $connection->quote($sSynchArtId) . ' ) ' .
                     " and {$sAttrViewName}.oxactive = " . $connection->quote(1) . ' ';
        }

        return $sQAdd;
    }
}
