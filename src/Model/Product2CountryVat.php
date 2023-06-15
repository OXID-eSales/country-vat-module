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
        $db = DatabaseProvider::getDb();

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

        return ($this->$longFieldName instanceof Field) ? $this->$longFieldName->value : null;
    }
}