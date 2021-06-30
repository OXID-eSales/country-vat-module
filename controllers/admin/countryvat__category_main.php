<?php
/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

class countryvat__category_main extends countryvat__category_main_parent
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
            $ajax = oxNew('country_vat_category_main_ajax');
            $this->addTplParam('oxajax', $ajax->getColumns());

            return 'ajax_category_popup.tpl';
        }

        return $fromParent;
    }
}