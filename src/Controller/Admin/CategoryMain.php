<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidEsales\CountryVat\Controller\Admin;

use OxidEsales\Eshop\Core\Registry as EshopRegistry;

class CategoryMain extends CategoryMain_parent
{
    /**
     * Render method.
     *
     * @return string
     */
    public function render()
    {
        $fromParent = parent::render();

        if (EshopRegistry::getRequest()->getRequestParameter('cvat')) {
            $ajax = oxNew(CategoryMainAjax::class);
            $this->_aViewData['oxajax'] = $ajax->getColumns();

            return '@oecountryvat/admin/ajax_category_popup';
        }
        return $fromParent;
    }
}
