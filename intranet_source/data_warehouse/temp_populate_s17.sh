#!/bin/tcsh

source ~morecraf/.cshrc

cd /var/www/html/data_warehouse/

# A script to populate the database on a given day
/usa/morecraf/bin/php /var/www/html/data_warehouse/whs_temp_populate_s17.php

