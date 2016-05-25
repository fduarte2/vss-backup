#!/bin/tcsh


source ~morecraf/.cshrc

# A script to populate the database on a given day
/usa/morecraf/bin/php /var/www/html/data_warehouse/commodity_itg_import.php

