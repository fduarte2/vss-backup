#!/bin/tcsh

source ~morecraf/.cshrc

# A list of 1-per-day (4AM) emails for walmart
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/walmart_repack_waiting.php
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/walmart_reject_notifications.php
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/walmart_lucca_storage_notify.php

/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_repack_waiting.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_reject_notifications.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_lucca_storage_notify.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/WM_nightly_IH_save.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/WM_order_rollover.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/WM_truck_seq_reset.php
