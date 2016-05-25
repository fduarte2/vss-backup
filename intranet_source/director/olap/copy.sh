#!/bin/tcsh

source ~morecraf/.cshrc

echo "copy start   "
# A script to send email
cd /web/web_pages

/usa/morecraf/bin/php /web/web_pages/director/olap/copy.php
echo "copy finished"
