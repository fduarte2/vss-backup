#!/bin/tcsh
source ~morecraf/.cshrc

# nightly storage bills for scanned cargo 
#/usr/bin/php /web/web_pages/finance/storage/crons/test/rf_bad_palletstorage_remind.php
/usr/bin/php /web/web_pages/finance/storage/crons/rf_bad_palletstorage_remind.php
