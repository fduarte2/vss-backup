#!/bin/tcsh
source ~morecraf/.cshrc

# nightly storage bills for scanned cargo 
/usa/morecraf/bin/php /web/web_pages/finance/storage/crons/set_free_time_for_autostorage.php
/usr/bin/php /web/web_pages/finance/storage/crons/rf_storage_autobills_v2.php
/usr/bin/php /web/web_pages/finance/storage/crons/rf_storage_bills_email.php
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/set_free_time_for_autostorage.php
#/usr/bin/php /web/web_pages/TS_Testing/rf_storage_autobills_v2.php
