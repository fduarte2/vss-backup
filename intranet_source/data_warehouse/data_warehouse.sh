#!/bin/tcsh

source ~morecraf/.cshrc

# A script to populate the database on a given day
/usa/morecraf/bin/php /var/www/html/data_warehouse/bni_populate.php
/usa/morecraf/bin/php /var/www/html/data_warehouse/ccds_populate.php
/usa/morecraf/bin/php /var/www/html/data_warehouse/rf_populate.php
