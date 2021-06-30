<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

class countryvat_category2countryvat extends \oxBase
{
    /**
     * Core database table name. $sCoreTable could be only original data table name and not view name.
     *
     * @var string
     */
    protected $_sCoreTable = 'oxpscategory2countryvat';

    public function __construct()
    {
        parent::__construct();
        $this->init('oxpscategory2countryvat');
    }

    public function loadByFirstCategoryCountry($categoryIds, $countryId)
    {
        if (empty($categoryIds)) {
            //nothing to be done
            return false;
        }

        $shopId = oxRegistry::getConfig()->getShopId();
        $db = oxDb::getDb(oxDb::FETCH_MODE_ASSOC);

        $tmp = [];
        foreach ($categoryIds as $id) {
            $tmp[] = $db->quote($id);
        }
        $queryPart = implode(',', $tmp);

        $query =  'SELECT OXID, OXCATEGORYID FROM ' . $this->getCoreTableName() .
                  ' WHERE OXCATEGORYID IN (' . $queryPart . ')' .
                  ' AND   OXCOUNTRYID=' . $db->quote($countryId) .
                  ' AND   OXSHOPID=' . $db->quote($shopId) .
                  ' ORDER BY FIELD (OXCATEGORYID, ' . $queryPart . ')';

        $oxid = (string) $db->getOne($query);

        return $this->load($oxid);
    }

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