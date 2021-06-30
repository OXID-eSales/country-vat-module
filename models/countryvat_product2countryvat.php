<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

class countryvat_product2countryvat extends oxBase
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

    public function loadByProductCountry($articleId, $countryId)
    {
        $shopId = oxRegistry::getConfig()->getShopId();
        $db = oxDb::getDb();

        $query =  'SELECT OXID FROM ' . $this->getCoreTableName() .
                  ' WHERE OXARTICLEID=' . $db->quote($articleId) .
                  ' AND   OXCOUNTRYID=' . $db->quote($countryId) .
                  ' AND   OXSHOPID=' . $db->quote($shopId);

        $oxid = (string) $db->getOne($query);

        return $this->load($oxid);
    }

    /**
     * @return double | null
     */
    public function getVat()
    {
        return $this->getFieldData('vat');
    }

    /**
     * Gets field data
     *
     * @param string $fieldName name (eg. 'oxtitle') of a data field to get
     *
     * @return mixed value of a data field
     */
    public function getFieldData($fieldName)
    {
        $longFieldName = $this->_getFieldLongName($fieldName);

        return ($this->$longFieldName instanceof oxField) ? $this->$longFieldName->value : null;
    }
}