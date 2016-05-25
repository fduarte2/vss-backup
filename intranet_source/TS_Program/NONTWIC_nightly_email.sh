#!/bin/tcsh

source ~morecraf/.cshrc

# A script to populate the database on a given day
/usa/morecraf/bin/php /var/www/html/TS_Program/NONTWIC_nightly_email.php
