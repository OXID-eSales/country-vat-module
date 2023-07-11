<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

namespace OxidProfessionalServices\CountryVatAdministration\Controller\Admin;

use OxidEsales\Eshop\Core\Registry as EshopRegistry;

class ArticleMain extends ArticleMain_parent
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
            $ajax                           = oxNew(ArticleMainAjax::class);
            $this->_aViewData['oxajax']     = $ajax->getColumns();
            $this->_aViewData['container1'] = $ajax->getAjaxContainer('container1', 'synchoxid');
            $this->_aViewData['container2'] = $ajax->getAjaxContainer('container2');

            return '@oxps_countryvatadministration/ajax_article_popup';
        }

        return $fromParent;
    }
}
