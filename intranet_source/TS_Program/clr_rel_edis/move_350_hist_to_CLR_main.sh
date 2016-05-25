#!/bin/tcsh

source ~morecraf/.cshrc

# A script to move 350-EDIs to the history table for further processing
#/usa/morecraf/bin/php /var/www/html/TS_Testing/move_350_hist_to_CLR_main.php
/usa/morecraf/bin/php /var/www/html/TS_Program/clr_rel_edis/move_350_hist_to_CLR_main.php
