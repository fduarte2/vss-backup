#!/bin/tcsh

source ~morecraf/.cshrc

# A list of "constant" emails for walmart
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/walmart_awaiting_gfs_email.php
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/walmart_qc_can_go_now.php
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/walmart_carle_JQ.php
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/walmart_need_qc.php
#/usa/morecraf/bin/php /web/web_pages/TS_Testing/walmart_VR_notify.php
#/usa/morecraf/bin/php /web/web_pages/TS_Program/WM_change_hatch_to_carle.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_awaiting_gfs_email.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_qc_can_go_now.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_carle_JQ.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_need_qc.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_VR_notify.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/WM_change_hatch_to_carle.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/WM_QC_preselect_email.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/walmartcrons/walmart_VR_notify_for_ops.php
