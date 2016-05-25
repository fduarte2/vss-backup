#!/bin/tcsh
source ~morecraf/.cshrc


# run the dole-9722 ftp retrieval program
/usa/morecraf/bin/php /web/web_pages/TS_Program/DoleEDIreceive9722.php
/usa/morecraf/bin/php /web/web_pages/TS_Program/DoleEDI9722/DoleEDI9722Resolve.php

#/usa/morecraf/bin/php /web/web_pages/TS_Testing/DoleEDIreceive9722.php

