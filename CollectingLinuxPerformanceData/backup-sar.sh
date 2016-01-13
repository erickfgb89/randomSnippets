#!/bin/bash
# Created 07.02.2012 / Nigel Pond

# Script to create backup of and rename current hostname 
# and date of sar file and send to off-system storage (if required)

# Cycle through directory once looking for pertinent sar file

SARBACKUPS=/root/performanceStats/sarBackups

cd /var/log/sa

ls -1 sar* | while read SARNAME
  do
    NEWHOST=$( echo $HOSTNAME | awk -F'.' '{ print $1 }' )
    mv "$SARNAME" $( echo "$SARBACKUPS"/"$NEWHOST"-"$SARNAME"-`date +"%Y%m%d"`.bkup )
  done

# insert method here for scp/mv/ftp etc if required
