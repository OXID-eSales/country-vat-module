<?php

namespace OxidProfessionalServices\CountryVatAdministration\Model;

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

    public function loadFromCountryAndShopId(string $countryId, int $shopId): bool {
        $db = DatabaseProvider::getDb();
        $oxid = (string) $db->getOne('SELECT OXID FROM ' . $this->getCoreTableName() . ' WHERE OXCOUNTRYID=' . $db->quote($countryId) . ' AND' . ' OXSHOPID=' . $db->quote($shopId));
        return $this->load($oxid);
    }

    public function vat()
    {
        return $this->oxps_country2vat__vat->value;
    }
}