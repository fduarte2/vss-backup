#!/bin/tcsh

source ~morecraf/.cshrc

# A script to populate the database on a given day
/usa/morecraf/bin/php /var/www/html/data_warehouse/populate_rev_cost_detail.php
/usa/morecraf/bin/php /var/www/html/data_warehouse/populate_tonnage.php
