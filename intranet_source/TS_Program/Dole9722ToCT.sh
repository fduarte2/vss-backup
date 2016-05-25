#!/bin/tcsh

# Launching intranet-fetch to obtain email attachments from dole
ssh doleedi@172.22.15.61 'sh /home/doleedi/mail/doleedi/scripts/emailfetch.sh'

scp -r doleedi@172.22.15.61:/home/doleedi/*.csv /var/www/html/TS_Program/dole9722/

scp -r doleedi@172.22.15.61:/home/doleedi/*.xls* /var/www/html/TS_Program/dole9722/copied_files/

ssh doleedi@172.22.15.61 'sh /home/doleedi/mail/doleedi/scripts/move_files.sh'

source ~morecraf/.cshrc

# A script to test the new oracle DB routine
#/usa/morecraf/bin/php /var/www/html/TS_Testing/Dole9722ToCT.php
/usa/morecraf/bin/php /var/www/html/TS_Program/dole9722/Dole9722EDI.php
/usa/morecraf/bin/php /var/www/html/TS_Program/Dole9722ToCT.php
