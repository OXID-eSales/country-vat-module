<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Field;
use OxidEsales\Eshop\Core\Model\BaseModel;
use OxidEsales\Eshop\Core\Registry as EshopRegistry;

class Product2CountryVat extends BaseModel
{
    /**
     * Core database table name. $sCoreTable could be only original data table name and not view name.
     *
     * @var string
     */
    protected $_sCoreTable = 'oxpsarticle2countryvat';

    public function __construct()
    {
        parent::__construct();
        $this->init('oxpsarticle2countryvat');
    }

    public function loadByProductCountry(string $articleId, string $countryId): bool
    {
        $shopId = EshopRegistry::getConfig()->getShopId();
        $oDb = DatabaseProvider::getDb();

        $query = 'SELECT OXID FROM ' . $this->getCoreTableName() .
                  ' WHERE OXARTICLEID=' . $oDb->quote($articleId) .
                  ' AND   OXCOUNTRYID=' . $oDb->quote($countryId) .
                  ' AND   OXSHOPID=' . $oDb->quote($shopId);

        $oxid = (string) $oDb->getOne($query);

        return $this->load($oxid);
    }

    /**
     * @return double | null
     */
    public function getVat()
    {
        return $this->getFieldData('vat');
    }
}
