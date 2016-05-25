#!/bin/tcsh

source ~morecraf/.cshrc

# A script to populate the database on a given day
/usa/morecraf/bin/php /var/www/html/data_warehouse/bni_populate_s17.php
# /usa/morecraf/bin/php /var/www/html/data_warehouse/ccds_populate_s17.php
/usa/morecraf/bin/php /var/www/html/data_warehouse/rf_populate_s17.php
#/usa/morecraf/bin/php /var/www/html/data_warehouse/paper_populate_s17.php

echo "Check for 12:10 cron-cube job" | mutt -s "12:10A cron-cube test email" awalter@port.state.de.us hdadmin@port.state.de.us
