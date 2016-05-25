#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send email regarding transferrred clems.
/usa/morecraf/bin/php /var/www/html/inventory/crons/clem_trans_email.php
#/usa/morecraf/bin/php /var/www/html/TS_Testing/clem_trans_email.php
