#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for Juice trans-system transfers (I can't believe I just wrote that)
#/usa/morecraf/bin/php /var/www/html/TS_Testing/Juice_BNI_to_RF.php
/usa/morecraf/bin/php /var/www/html/inventory/crons/Juice_BNI_to_RF.php

