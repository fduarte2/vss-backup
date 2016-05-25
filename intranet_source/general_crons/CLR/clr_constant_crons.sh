#!/bin/tcsh

source ~morecraf/.cshrc

# A script to update CLR stuff
#/usa/morecraf/bin/php /var/www/html/TS_Testing/clr_clem_eport2_sync.php



# join eport2 to CLR for any updated eport2 orders
/usa/morecraf/bin/php /var/www/html/general_crons/CLR/clr_clem_eport2_sync.php
# autojoin CLR trucks to cargo if able
/usa/morecraf/bin/php /var/www/html/general_crons/CLR/truck_join_attempt.php
