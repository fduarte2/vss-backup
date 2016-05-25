#!/bin/tcsh

source ~morecraf/.cshrc

# A script to move 350-EDIs to the history table for further processing
#/usa/morecraf/bin/php /var/www/html/TS_Testing/push_CLREDI_to_hist_table.php
/usa/morecraf/bin/php /var/www/html/TS_Program/clr_rel_edis/push_CLREDI_to_hist_table.php
