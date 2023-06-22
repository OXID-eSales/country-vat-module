<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\CountryVat\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use OxidEsales\Eshop\Core\Model\BaseModel;

class Country2Vat extends BaseModel
{
    /**
     * Core database table name. $sCoreTable could be only original data table name and not view name.
     *
     * @var string
     */
    protected $_sCoreTable = 'oxps_country2vat';

    public function __construct()
    {
        parent::__construct();
        $this->init('oxps_country2vat');
    }

    public function loadFromCountryAndShopId(string $countryId, int $shopId): bool
    {
        $oDb = DatabaseProvider::getDb();
        $oxid = (string) $oDb->getOne('SELECT OXID FROM ' . $this->getCoreTableName() . '
                                        WHERE OXCOUNTRYID=' . $oDb->quote($countryId) . '
                                        AND' . ' OXSHOPID=' . $oDb->quote($shopId));
        return $this->load($oxid);
    }

    public function vat()
    {
        return $this->getFieldData('vat');
    }
}
