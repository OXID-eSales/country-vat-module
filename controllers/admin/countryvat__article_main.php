<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

class countryvat__article_main extends countryvat__article_main_parent
{
    /**
     * Render method.
     *
     * @return string
     * @throws oxSystemComponentException
     */
    public function render()
    {
        $fromParent = parent::render();

        if (oxRegistry::getConfig()->getRequestParameter('cvat')) {
            $ajax = oxNew('country_vat_article_main_ajax');
            $this->addTplParam('oxajax', $ajax->getColumns());

            return 'ajax_article_popup.tpl';
        }

        return $fromParent;
    }
}