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

class Category2CountryVat extends BaseModel
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

    public function loadByFirstCategoryCountry(array $categoryIds, string $countryId): bool
    {
        if (empty($categoryIds)) {
            //nothing to be done
            return false;
        }

        $shopId = EshopRegistry::getConfig()->getShopId();
        $db = DatabaseProvider::getDb(DatabaseProvider::FETCH_MODE_ASSOC);

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

        return ($this->$longFieldName instanceof Field) ? $this->$longFieldName->value : null;
    }
}