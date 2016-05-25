#!/bin/tcsh

source ~morecraf/.cshrc

# CLR-EDI procedures


#/usa/morecraf/bin/php /var/www/html/TS_Testing/CLR_EDI_check.php
#if ( $? == 5 ) then

#/usa/morecraf/bin/php /var/www/html/TS_Testing/can_AMSEDI_filesplit/AMS_EDI_filesplit.php
#/usa/morecraf/bin/php /var/www/html/TS_Testing/can_AMSEDI_filesplit/split_files/AMS_EDI_parse.php
#/usa/morecraf/bin/php /var/www/html/TS_Testing/clr_rel_edis/push_CLREDI_to_hist_table.php
#/usa/morecraf/bin/php /var/www/html/TS_Testing/clr_rel_edis/move_350_hist_to_CLR_main.php
#/usa/morecraf/bin/php /var/www/html/TS_Testing/CLR_EDI_assign.php

#endif



/usa/morecraf/bin/php /var/www/html/TS_Program/cron_checks/CLR_EDI_check.php
if ( $? == 5 ) then

/usa/morecraf/bin/php /var/www/html/TS_Program/can_AMSEDI_filesplit/AMS_EDI_filesplit.php
/usa/morecraf/bin/php /var/www/html/TS_Program/can_AMSEDI_filesplit/split_files/AMS_EDI_parse.php
/usa/morecraf/bin/php /var/www/html/TS_Program/clr_rel_edis/push_CLREDI_to_hist_table.php
/usa/morecraf/bin/php /var/www/html/TS_Program/clr_rel_edis/move_350_hist_to_CLR_main.php
/usa/morecraf/bin/php /var/www/html/TS_Program/cron_checks/CLR_EDI_assign.php

endif
