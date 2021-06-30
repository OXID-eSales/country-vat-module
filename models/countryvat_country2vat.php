<?php

class countryvat_country2vat extends oxBase
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

    public function loadFromCountryAndShopId($countryId, $shopId)
    {
        $db = oxDb::getDb();
        $oxid = (string)$db->getOne('SELECT OXID FROM ' . $this->getCoreTableName() . ' WHERE OXCOUNTRYID=' . $db->quote($countryId) . ' AND' . ' OXSHOPID=' . $db->quote($shopId));

        return $this->load($oxid);
    }

    public function vat()
    {
        return $this->oxps_country2vat__vat->value;
    }
}