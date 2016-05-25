#!/bin/tcsh

source ~morecraf/.cshrc

# A script to sent customs email for Clementines
/usa/morecraf/bin/php /var/www/html/TS_Program/CBP_burnac.php
/usa/morecraf/bin/php /var/www/html/TS_Program/CBP_DC.php
