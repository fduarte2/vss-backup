#!/bin/bash

ssh doleedi@172.22.15.61 'sh /home/doleedi/mail/doleedi/scripts/emailfetch.sh'

scp -r doleedi@172.22.15.61:/home/doleedi/*.csv /var/www/html/TS_Program/dole9722/

scp -r doleedi@172.22.15.61:/home/doleedi/*.xls* /var/www/html/TS_Program/dole9722/copied_files/

ssh doleedi@172.22.15.61 'sh /home/doleedi/mail/doleedi/scripts/move_files.sh'

