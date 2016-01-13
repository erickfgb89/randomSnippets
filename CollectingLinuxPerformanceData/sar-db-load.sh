#!/bin/bash
# Created 07.02.2012 / Nigel Pond 

# This script will parse the sar file and prepare/format the data to make it
# available to upload into a MySQL database. It will then call and upload the
# data into the selected MySQL database and its respective tables.

# Set miscellaneous variables needed.
SARBACKUPS=/root/performanceStats/sarBackups
WORKDIR=/root/performanceStats/workingDir
FORMATDIR=/root/performanceStats/formattedDir
NEWHOSTNAME=$( echo $HOSTNAME | awk -F'.' '{ print $1 }' )
USER=fed
PASSWORD=fed
DB=sar

# Begin main

# Change into work directory

cd $WORKDIR

# Start formatting

for FILE in $( ls "$SARBACKUPS"/ )
do

echo "File: "$FILE
NEWFILE=$( echo $FILE | awk -F'.' '{ print $1 }' )
echo "New file: "$NEWFILE
DATESTAMP=$( awk '/Linux/{ print $4 }' $SARBACKUPS/$FILE )
echo " "

# Prepare and format designated hosts' sar log files to be loaded into MYSQL database:

sed -n "/proc/,/pswpin/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_taskCreationAndSystemSwitchingActivity.csv

sed -n "/CPU/,/proc/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '1,2d' | sed '$d' > "$FORMATDIR"/"$NEWFILE"_CPUUtilisation.csv

sed -n "/pswpin/,/pgpgin/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_swappingStatistics.csv

sed -n "/pgpgin/,/tps/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_pagingStatistics.csv

sed -n "/tps/,/frmpg/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_IOandTransferRateStatistics.csv

sed -n "/frmpg/,/kbmemfree/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_memoryStatistics.csv

sed -n "/kbmemfree/,/kbswpfree/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_memoryUtilisation.csv

sed -n "/kbswpfree/,/kbhugfree/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_swapSpaceUtilisation.csv

sed -n "/kbhugfree/,/dentunusd/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_hugepageUtilisation.csv

sed -n "/dentunusd/,/runq\-sz/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_inodeFileKernalTableStatistics.csv

sed -n "/runq\-sz/,/TTY/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_queueLengthAndLoadStatistics.csv

sed -n "/TTY/,/rxpck/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_TTYDeviceStatistics.csv

sed -n "/rxpck/,/rxerr/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_networkInterfaceStatistics.csv

sed -n "/rxerr/,/call/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_networkDeviceErrors.csv

sed -n "/call/,/scall/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_NFSClientActivity.csv

sed -n "/scall/,/totsck/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_NFSServerActivity.csv

sed -n "/totsck/,/Average/ p" ${SARBACKUPS}/${FILE} | sed "$ d" | tr -s [:blank:] | sed -n '1h;2,$H;${g;s/ /,/g;p}' | sed '/Average:/ d' | sed "s/^/$NEWHOSTNAME,$DATESTAMP /" | sed '$d' > "$FORMATDIR"/"$NEWFILE"_socketUtilisation.csv

done

# Kick off uploading formatted data into MYSQL database:

# Change into format directory.

cd $FORMATDIR

# Pushing data into MySQL via -e flag.

for FORMATTEDFILE in `dir -d *`;
do

mysql -u ${USER} --password=${PASSWORD} -D ${DB} -e "LOAD DATA LOCAL INFILE '/root/performanceStats/formattedDir/${FORMATTEDFILE}' INTO TABLE `echo $FORMATTEDFILE | sed 's/\.csv//g' | awk -F_ '{print $2}'` FIELDS TERMINATED BY ',' IGNORE 1 LINES;"

done
