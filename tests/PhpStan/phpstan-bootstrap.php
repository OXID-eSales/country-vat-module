<?php

/**
 * Copyright © OXID eSales AG. All rights reserved.
 * See LICENSE file for license details.
 */

declare(strict_types=1);

class_alias(
    \OxidEsales\Eshop\Application\Controller\Admin\ArticleMain::class,
    \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\ArticleMain_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\Admin\CategoryMain::class,
    \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CategoryMain_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Controller\Admin\CountryMain::class,
    \OxidProfessionalServices\CountryVatAdministration\Controller\Admin\CountryMain_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Model\Article::class,
    \OxidProfessionalServices\CountryVatAdministration\Model\Article_parent::class
);

class_alias(
    \OxidEsales\Eshop\Application\Model\User::class,
    \OxidProfessionalServices\CountryVatAdministration\Model\User_parent::class
);
