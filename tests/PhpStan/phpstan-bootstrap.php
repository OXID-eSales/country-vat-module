<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

class_alias(
    \OxidEsales\Eshop\Application\Controller\Admin\ArticleMain::class,
    \OxidEsales\CountryVat\Controller\Admin\ArticleMain_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::class,
    \OxidEsales\CountryVat\Controller\Admin\CategoryMain_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\Admin\CountryMain::class,
    \OxidEsales\CountryVat\Controller\Admin\CountryMain_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Model\Article::class,
    \OxidEsales\CountryVat\Model\Article_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Model\User::class,
    \OxidEsales\CountryVat\Model\User_parent::class
);
