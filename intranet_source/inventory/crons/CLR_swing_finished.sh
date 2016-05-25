#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send email to finanace/invetory.  this one is sent whena  vessel "finishes" its swingloads.
/usa/morecraf/bin/php /var/www/html/inventory/crons/CLR_swing_finished.php
#/usa/morecraf/bin/php /var/www/html/TS_Testing/CLR_swing_finished.php
