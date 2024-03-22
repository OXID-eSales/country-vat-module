# Country VAT Administration

The provided module offers convenient functionality that allows administrators to configure country-specific VAT values
for countries, categories, and products.

When determining the applicable VAT rate, the module follows a specific priority order. The priority is as follows:
* Product-Specific VAT Value: If a product has a country-specific VAT value assigned to it, that VAT rate takes
the highest priority. It means that the assigned VAT rate for the product will override any other VAT rates set for categories or countries.
* Category-Specific VAT Value: In the absence of a product-specific VAT value, the module will check if the category
to which the product belongs has a country-specific VAT value assigned. If a category has a VAT rate assigned for
a particular country, that VAT rate will be used for all products within that category.
* Country-Specific VAT Value: If neither the product nor the category has a country-specific VAT value assigned,
the module will default to the VAT rate assigned specifically for the country.


## Branch Compatibility

* b-7.1.x - compatible with OXID eShop b-7.1.x branch, works with `Twig engine` only
* b-7.0.x branch is compatible with OXID eShop compilation b-7.0.x and allows `Legacy Smarty engine` version support
* b-6.x branch / v1.x is compatible with
  * OXID eShop compilation 6.1, 6.2 and 6.3 (6.0 with higher php versions may work as well, but module is not tested with it, so we cannot guarantee)
  * OXID eShop b-6.4.x branch with PHP 7.4 and 8.0
  * OXID eShop b-6.5.x branch with PHP 7.4, 8.0 and 8.1


## Installation 

```
composer require oxid-professional-services/countryvatadministration
```

After requiring the module, you need to activate it, e.g. via OXID eShop admin.

This module requires news tables in the database which will be created on module activation.

## How to use

To add a special VAT to product or category go to shop admin and navigate to the section where
you can manage your products or categories. Choose the specific product or category
to which you wish to assign a special VAT. Within the 'Main' tab, you will find a button labeled 'Country Specific VAT'.
Clicking the button opens a popup - this popup window allows you to assign VAT rates
for the chosen product or category based on specific countries.
You can specify the desired VAT rate for each of the assigned countries individually
in the field that will show up on the right. Click the 'Save' button within the popup window to save the configuration.

![Image alt](./assign-vat.png)

Within the shop admin panel, locate and navigate to the `Master Settings -> Countries`, where you will be presented
with a list of available countries.Locate and select the country for which you wish to configure the VAT settings.
Within the 'Main' tab,you will find an input field specifically designated for the country-specific VAT rate. 
Enter the desired VAT rate for this country in the provided input field, then click the "Save" button to save the configuration.

![Image alt](./assign-country-vat.png)
