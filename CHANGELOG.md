# Change Log for OXID Country VAT administration module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

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
