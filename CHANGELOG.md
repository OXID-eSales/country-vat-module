# Change Log for OXID Country VAT administration module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.5.0] - 2026-04-22

### Changed
- Updated to work with OXID eShop 7.5.x
- Minimum PHP version is now 8.3, tested up to PHP 8.5

### Added
- PHPUnit 12.5 support

## [2.4.0] - 2025-10-28

### Changed
- Updated to work with OXID eShop 7.4.x

## [2.3.0] - 2025-06-11
This is the stable release for v2.3.0. No changes have been made since v2.3.0-rc.1.

## [2.3.0-rc.1] - 2025-04-24

### Added
- PHP 8.4 support
- Upgraded to work with OXID eShop 7.3.x

## [2.2.0] - 2024-11-26
This is the stable release for v2.2.0. No changes have been made since v2.2.0-rc.2.

## [2.2.0-rc.2] - 2024-10-31

### Fixed
- [0007733](https://bugs.oxid-esales.com/view.php?id=7733) No error feedback or auto fill or not required in for countries in backend

## [2.2.0-rc.1] - 2024-10-17

### Added
- PHP 8.3
- Upgraded to work with OXID eShop 7.2.x

### Removed
- Support of PHP 8.1

## [v2.1.0] - 2024-06-26
This is the stable release for v2.1.0. No changes have been made since v2.1.0-rc.1.

## [v2.1.0-rc.1] - 2024-05-28

### Added
- PHP 8.2
- Upgraded to work with shop compilation 7.1.0

### Changed
- New module logo 
- Updated the structure to Codeception 5 
- Modify GitHub workflows to use new universal workflow
- Phpunit version to 10.4

### Removed
- PHP 8.0 support
- Legacy Smarty engine variant is not supported anymore

## [v2.0.0] - 2023-08-09

### Added
- Migrations directory configured, queries from activation events moved to migrations
- Compatibility with twig engine (Twig related extensions in Twig directory)
- Integration and Codeception tests
- Support for APEX theme
- Development workflows with test runners
- PHP 8.0 and 8.1 support
- Support for MySQL 8

### Changed
- Namespaces changed from **OxidProfessionalServices\CountryVatAdministration** to **OxidEsales\CountryVat**
- Adapted module to work with OXID eShop 7.0.x
- Module id changed from **oxps/countryvatadministration** to **oecountryvat** for being compatible with shop documentation
- Moved all php code to `src` directory

## [v1.0.4] - 2023-08-09

### Changed
- License updated - now using OXID Module and Component License

## [v1.0.3] - 2021-07-21

### Fixed
- [0007252](https://bugs.oxid-esales.com/view.php?id=7252) Country vat gets overwritten when changing an existing order

## [v1.0.2] - 2021-06-22

### Fixed
- Category sorting for product VAT calculation.

## [v1.0.1] - 2021-06-21

### Fixed
- Countries appear only once in the assignment window 
- Resolved conflict with other assignment overlays   
- Country VAT assignment only for existing categories and products possible
- It is possible to assign all countries at once now

[2.5.0]: https://github.com/OXID-eSales/country-vat-module/compare/v2.4.0...v2.5.0
[2.4.0]: https://github.com/OXID-eSales/country-vat-module/compare/v2.3.0...v2.4.0
[2.3.0]: https://github.com/OXID-eSales/country-vat-module/compare/v2.3.0-rc.1...v2.3.0
[2.3.0-rc.1]: https://github.com/OXID-eSales/country-vat-module/compare/v2.2.0...v2.3.0-rc.1
[2.2.0]: https://github.com/OXID-eSales/country-vat-module/compare/v2.2.0-rc.2...v2.2.0
[2.2.0-rc.2]: https://github.com/OXID-eSales/country-vat-module/compare/v2.2.0-rc.1...v2.2.0-rc.2
[2.2.0-rc.1]: https://github.com/OXID-eSales/country-vat-module/compare/v2.1.0...v2.2.0-rc.1
[v2.2.0]: https://github.com/OXID-eSales/country-vat-module/compare/v2.1.0...v2.2.0-rc.1
[v2.1.0]: https://github.com/OXID-eSales/country-vat-module/compare/v2.1.0-rc.1...v2.1.0
[v2.1.0-rc.1]: https://github.com/OXID-eSales/country-vat-module/compare/v2.0.0...v2.1.0-rc.1
[v2.0.0]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.4...v2.0.0
[v1.0.4]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.3...v1.0.4
[v1.0.3]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.2...v1.0.3
[v1.0.2]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.1...v1.0.2
[v1.0.1]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.0...v1.0.1