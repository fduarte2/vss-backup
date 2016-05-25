#!/bin/tcsh
source ~morecraf/.cshrc


# run the temperature reminder
/usa/morecraf/bin/php /web/web_pages/TS_Testing/oppyEDIconfirm/truckOK/oppyEDI_truck_OK.php
/usa/morecraf/bin/php /web/web_pages/TS_Testing/oppyEDIconfirm/truckSTUFF/oppyEDI_truck_stuff.php
/usa/morecraf/bin/php /web/web_pages/TS_Testing/oppyEDIconfirm/truckCONFIRMS/oppy_parse_confirm.php





