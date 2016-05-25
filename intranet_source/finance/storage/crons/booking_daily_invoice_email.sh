#!/bin/tcsh

source ~morecraf/.cshrc

# A script to send out emails for booking
#/usa/morecraf/bin/php /var/www/html/TS_Testing/booking_daily_invoice_email.php
/usa/morecraf/bin/php /var/www/html/finance/storage/crons/booking_daily_invoice_email.php
