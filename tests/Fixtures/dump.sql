UPDATE `oxcountry` SET `OXACTIVE` = 1 , `OXID` = 'testcountry_be' WHERE `OXISOALPHA2` = 'BE';
UPDATE `oxcountry` SET `OXACTIVE` = 1 , `OXID` = 'testcountry_de' WHERE `OXISOALPHA2` = 'DE';

#Articles demodata
REPLACE INTO `oxarticles` (`OXID`,   `OXMAPID`,   `OXSHOPID`,   `OXPARENTID`, `OXACTIVE`, `OXARTNUM`, `OXTITLE`,                     `OXSHORTDESC`,                   `OXPRICE`, `OXPRICEA`, `OXPRICEB`, `OXPRICEC`, `OXTPRICE`, `OXUNITNAME`, `OXUNITQUANTITY`, `OXVAT`, `OXWEIGHT`, `OXSTOCK`, `OXSTOCKFLAG`, `OXSTOCKTEXT`, `OXNOSTOCKTEXT`,       `OXDELIVERY`, `OXINSERT`,   `OXTIMESTAMP`,        `OXLENGTH`, `OXWIDTH`, `OXHEIGHT`, `OXSEARCHKEYS`, `OXISSEARCH`, `OXVARNAME`,              `OXVARSTOCK`, `OXVARCOUNT`, `OXVARSELECT`, `OXVARMINPRICE`, `OXVARMAXPRICE`, `OXVARNAME_1`,             `OXVARSELECT_1`,   `OXTITLE_1`,                 `OXSHORTDESC_1`,                        `OXSEARCHKEYS_1`, `OXBUNDLEID`, `OXSTOCKTEXT_1`,       `OXNOSTOCKTEXT_1`,         `OXSORT`, `OXVENDORID`,      `OXMANUFACTURERID`, `OXMINDELTIME`, `OXMAXDELTIME`, `OXDELTIMEUNIT`) VALUES
                         ('1000',   101,   1, '',            1,         '1000',     '[DE 4] Test product 0 šÄßüл', 'Test product 0 short desc [DE]', 50,        35,         45,         55,         0,         'kg',          2,                NULL,    2,          15,        1,            'In stock [DE]', 'Out of stock [DE]', '0000-00-00', '2008-02-04', '2008-02-04 17:07:48', 1,          2,         2,         'search1000',    1,           '',                        0,            0,           '',             50,                0,                   '',                        '',                'Test product 0 [EN] šÄßüл', 'Test product 0 short desc [EN] šÄßüл', 'šÄßüл1000',      '',           'In stock [EN] šÄßüл', 'Out of stock [EN] šÄßüл',  0,       'testdistributor', 'testmanufacturer',  1,              1,             'DAY'),
                         ('1001',   102,   1, '',            1,         '1001',     '[DE 1] Test product 1 šÄßüл', 'Test product 1 short desc [DE]', 100,       0,          0,          0,          150,       '',            0,                10,      0,          0,         1,            '',              '',                  '2030-01-01', '2008-02-04', '2008-02-04 17:35:49', 0,          0,         0,         'search1001',    1,           '',                        0,            0,           '',             100,               0,                   '',                        '',                'Test product 1 [EN] šÄßüл', 'Test product 1 short desc [EN] šÄßüл', 'šÄßüл1001',      '',           '',                    '',                         0,       'testdistributor', 'testmanufacturer',  0,              1,             'WEEK');

#Articles long desc
REPLACE INTO `oxartextends` (`OXID`,   `OXLONGDESC`,                                  `OXLONGDESC_1`) VALUES
                           ('1001',   '<p>Test product 1 long description [DE]</p>', '<p>Test product 1 long description [EN] šÄßüл</p>'),
                           ('1000',   '<p>Test product 0 long description [DE]</p>', '<p>Test product 0 long description [EN] šÄßüл</p>');

REPLACE INTO `oxarticles2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
(1, 101),
(1, 102);

REPLACE INTO `oxcategories` (`OXID`, `OXMAPID`,    `OXPARENTID`,   `OXLEFT`, `OXRIGHT`, `OXROOTID`,     `OXSORT`, `OXACTIVE`, `OXSHOPID`,   `OXTITLE`,                    `OXDESC`,                    `OXLONGDESC`,                `OXDEFSORT`, `OXDEFSORTMODE`, `OXPRICEFROM`, `OXPRICETO`, `OXACTIVE_1`, `OXTITLE_1`,                  `OXDESC_1`,                        `OXLONGDESC_1`,                    `OXVAT`, `OXSHOWSUFFIX`) VALUES
                           ('testcategory0', 101, 'oxrootid',      1,        4,        'testcategory0', 1,        1,         1, 'Test category 0 [DE] šÄßüл', 'Test category 0 desc [DE]', 'Category 0 long desc [DE]', 'oxartnum',   0,               0,             0,           1,           'Test category 0 [EN] šÄßüл', 'Test category 0 desc [EN] šÄßüл', 'Category 0 long desc [EN] šÄßüл',  5,       1),
                           ('testcategory1', 102, 'oxrootid',      1,        4,        'testcategory1', 1,        1,         1, 'Test category 1 [DE] šÄßüл', 'Test category 1 desc [DE]', 'Category 1 long desc [DE]', 'oxartnum',   0,               0,             0,           1,           'Test category 1 [EN] šÄßüл', 'Test category 1 desc [EN] šÄßüл', 'Category 1 long desc [EN] šÄßüл',  5,       1);

REPLACE INTO `oxcategories2shop` (`OXSHOPID`, `OXMAPOBJECTID`) VALUES
(1, 101),
(1, 102);

#Article2Category
REPLACE INTO `oxobject2category` (`OXID`,                       `OXOBJECTID`, `OXCATNID`,     `OXPOS`, `OXTIME`) VALUES
                                ('6f047a71f53e3b6c2.93342239', '1000',       'testcategory0', 0,       1202134867),
                                ('testobject2category', '1001',       'testcategory0', 0,       1202134869),
                                ('6f047a71f53e3b6c2.93342238', '1001',       'testcategory1', 0,       1202134868);

#Users demodata
REPLACE INTO `oxuser` (`OXID`,     `OXACTIVE`, `OXRIGHTS`, `OXSHOPID`,   `OXUSERNAME`,         `OXPASSWORD`,                       `OXPASSSALT`,        `OXCUSTNR`, `OXUSTID`, `OXCOMPANY`,          `OXFNAME`,        `OXLNAME`,           `OXSTREET`,        `OXSTREETNR`, `OXADDINFO`,                   `OXCITY`,            `OXCOUNTRYID`,                `OXZIP`, `OXFON`,        `OXFAX`,       `OXSAL`, `OXBONI`, `OXCREATE`,            `OXREGISTER`,          `OXPRIVFON`,   `OXMOBFON`,    `OXBIRTHDATE`) VALUES
                     ('de_user',  1,         'user',     1, 'german_user@oxid-esales.dev', 'c9dadd994241c9e5fa6469547009328a', '7573657275736572',   8,         '',        'UserCompany šÄßüл',  'German',  'UserSurnamešÄßüл',  'Musterstr.šÄßüл', '1',          'User additional info šÄßüл',  'Musterstadt šÄßüл', 'testcountry_de', '79098',  '0800 111111', '0800 111112', 'Mr',     500,     '2008-02-05 14:42:42', '2008-02-05 14:42:42', '0800 111113', '0800 111114', '1980-01-01'),
                     ('be_user',  1,         'user',     1, 'be_user@oxid-esales.dev', 'c9dadd994241c9e5fa6469547009328a', '7573657275736572',   8,         '',        'UserCompany šÄßüл',  'UK',  'UserSurnamešÄßüл',  'Musterstr.šÄßüл', '1',          'User additional info šÄßüл',  'Musterstadt šÄßüл', 'testcountry_be', '23233',  '0800 111111', '0800 111112', 'Mr',     500,     '2008-02-05 14:42:42', '2008-02-05 14:42:42', '0800 111113', '0800 111114', '1980-01-01');

#object2Group
REPLACE INTO `oxobject2group` (`OXID`,                       `OXSHOPID`,   `OXOBJECTID`,   `OXGROUPSID`) VALUES
                             ('aad47a85a83749c71.33568407', 1, 'de_user',     'oxidnewcustomer'),
                             ('aad47a85a83749c72.33568408', 1, 'be_user',     'oxidnewcustomer');

