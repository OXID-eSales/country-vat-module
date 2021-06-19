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
            $ajax = oxNew(ArticleMainAjax::class);
            $this->_aViewData['oxajax'] = $ajax->getColumns();
            return 'ajax_article_popup.tpl';
        }
        return $fromParent;
    }
}