<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Controller\Admin;

use Doctrine\DBAL\Connection;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\EshopCommunity\Core\ViewConfig;
use OxidProfessionalServices\CountryVatAdministration\Core\Service;
use OxidProfessionalServices\CountryVatAdministration\Model\AjaxContainer;
use OxidProfessionalServices\CountryVatAdministration\Model\Category2CountryVat;

class CategoryMainAjax extends \OxidEsales\Eshop\Application\Controller\Admin\ListComponentAjax
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
            ['oxid', 'oxpscategory2countryvat', 0, 0, 1],
            ['vat', 'oxpscategory2countryvat', 0, 1, 1],
            ['oxcountryid', 'oxpscategory2countryvat', 0, 0, 1],
        ],
    ];

    public function getAjaxContainer(string $index, string $oxidKey = 'oxid'): AjaxContainer {
        $data = AjaxContainer::buildFromColumns($this->_aColumns[$index] ?? []);
        return AjaxContainer::getInstance($index, $data, Registry::get(ViewConfig::class)->getAjaxLink() . "cmpid={$index}&container=category_mainvat&{$oxidKey}=" . Registry::getRequest()->getRequestParameter('oxid'));
    }

    /**
     * Removes article attributes.
     */
    public function removeAttr()
    {
        $aChosenArt = $this->getActionIds('oxpscategory2countryvat.oxid');
        if (Registry::getRequest()->getRequestParameter('all')) {
            $sO2AViewName = $this->getViewName('oxpscategory2countryvat');
            $sQ           = $this->addFilter("delete {$sO2AViewName}.* " . $this->getQuery());
            Service::getInstance()->getDatabaseConnection()->executeStatement($sQ);
        } elseif ($aChosenArt && is_array($aChosenArt)) {
            Service::getInstance()->getQueryBuilder()
                ->delete('oxpscategory2countryvat')
                ->where('oxpscategory2countryvat.oxid IN (:chosenArticles)')
                ->setParameter('chosenArticles', $aChosenArt, Connection::PARAM_STR_ARRAY)
                ->execute()
            ;
        }
    }

    /**
     * Adds attributes to article.
     */
    public function addAttr()
    {
        $aAddCat = $this->getActionIds('oxcountry.oxid');
        $soxId   = Registry::getRequest()->getRequestParameter('synchoxid');

        if (Registry::getRequest()->getRequestParameter('all')) {
            $sAttrViewName = $this->getViewName('oxcountry');
            $aAddCat       = $this->getAll($this->addFilter("select {$sAttrViewName}.oxid " . $this->getQuery()));
        }

        if ($soxId && '-1' != $soxId && is_array($aAddCat)) {
            foreach ($aAddCat as $sAdd) {
                $oNew                                        = oxNew(Category2CountryVat::class);
                $oNew->oxpscategory2countryvat__oxcategoryid = new \OxidEsales\Eshop\Core\Field($soxId);
                $oNew->oxpscategory2countryvat__oxcountryid  = new \OxidEsales\Eshop\Core\Field($sAdd);
                $oNew->save();
            }
        }
    }

    /**
     * Saves attribute value.
     */
    public function saveAttributeValue()
    {
        $request = Registry::getRequest();
        $this->resetContentCache();

        $categoryId = $request->getRequestParameter('oxid');
        $countryId  = $request->getRequestParameter('attr_oxid');
        $vatValue   = $request->getRequestParameter('attr_value');
        $vatValue   = filter_var($vatValue, FILTER_VALIDATE_FLOAT, FILTER_NULL_ON_FAILURE);

        $category = oxNew(\OxidEsales\Eshop\Application\Model\Category::class);
        if ($category->load($categoryId)) {
            $viewName         = $this->getViewName('oxpscategory2countryvat');
            $queryBuilder     = Service::getInstance()->getQueryBuilder();
            $record           = $queryBuilder
                ->select('*')
                ->from($viewName)
                ->where('oxcategoryid = :categoryId')
                ->andWhere('oxcountryid = :countryId')
                ->setParameter('categoryId', $category->oxcategories__oxid->value)
                ->setParameter('countryId', $countryId)
                ->andWhere('oxshopid = :shopId')
                ->setParameter('shopId', $category->oxcategories__oxshopid->value)
                ->execute()
                ->fetchAssociative()
            ;

            $objectToAttribute = oxNew(Category2CountryVat::class);
            if ($record) {
                $objectToAttribute->assign($record);
                $objectToAttribute->oxpscategory2countryvat__vat->setValue($vatValue);
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
        $connection  = Service::getInstance()->getDatabaseConnection();
        $request     = Registry::getRequest();
        $sArtId      = $request->getRequestParameter('oxid');
        $sSynchArtId = $request->getRequestParameter('synchoxid');

        $sAttrViewName = $this->getViewName('oxcountry');
        $sO2AViewName  = $this->getViewName('oxpscategory2countryvat');
        if ($sArtId) {
            $sQAdd = " from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxcategoryid = " . $connection->quote($sArtId) . ' ' .
                     " and {$sAttrViewName}.oxactive = " . $connection->quote(1) . ' ';
        } else {
            $sQAdd = " from {$sAttrViewName} where {$sAttrViewName}.oxid not in ( select {$sO2AViewName}.oxcountryid " .
                     "from {$sO2AViewName} left join {$sAttrViewName} " .
                     "on {$sAttrViewName}.oxid={$sO2AViewName}.oxcountryid " .
                     " where {$sO2AViewName}.oxcategoryid = " . $connection->quote($sSynchArtId) . ' ) ' .
                     " and {$sAttrViewName}.oxactive = " . $connection->quote(1) . ' ';
        }

        return $sQAdd;
    }
}
