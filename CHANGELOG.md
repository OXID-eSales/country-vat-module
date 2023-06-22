# Change Log for OXID Country VAT administration module

All notable changes to this project will be documented in this file.
The format is based on [Keep a Changelog](http://keepachangelog.com/)
and this project adheres to [Semantic Versioning](http://semver.org/).

## [2.0.0] - Unreleased

### Added
- Migrations directory configured, queries from activation events moved to migrations
- Compatibility with twig engine (Twig related extensions in Twig directory)

### Changed
- Module id changed from **oxps/countryvatadministration** to **oecountryvat** for being compatible with shop documentation
- Moved all php code to `src` directory

## [1.0.4] - Unreleased

### Changed
- License updated - now using OXID Module and Component License

## [1.0.3] - 2021-07-21

### Fixed
- [0007252](https://bugs.oxid-esales.com/view.php?id=7252) Country vat gets overwritten when changing an existing order

## [1.0.2] - 2021-06-22

### Fixed
- Category sorting for product VAT calculation.

## [1.0.1] - 2021-06-21

### Fixed
- Countries appear only once in the assignment window 
- Resolved conflict with other assignment overlays   
- Country VAT assignment only for existing categories and products possible
- It is possible to assign all countries at once now

[1.0.4]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.3...b-6.x
[1.0.3]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.2...v1.0.3
[1.0.2]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.1...v1.0.2
[1.0.1]: https://github.com/OXID-eSales/country-vat-module/compare/v1.0.0...v1.0.1
