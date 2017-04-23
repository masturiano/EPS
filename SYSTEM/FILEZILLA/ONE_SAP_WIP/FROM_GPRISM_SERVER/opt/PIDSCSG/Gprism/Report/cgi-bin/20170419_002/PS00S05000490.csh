#!/bin/csh -vx
source '/opt/oracle/oracle_env.csh'
source '/opt/config/gprism_env.csh'




set WIP_ONESAP_ROOT_DIR=${GPRISM_HOME_DIR}/Report/cgi-bin                                          ##### WM Home DIRECTORY
set WIP_ONESAP_LOG_DIR=${GPRISM_LOG_DIR}/Bat/log                                                ##### Log DIRECTORY



#---------------------------------------------------------------------
# Set the path of the local dir
#---------------------------------------------------------------------
set PATH = ${WIP_ONESAP_CURR_DIR} 
set FILE1 = $PATH"onesap_wip.dat"
set UPLOAD_FILE = ${WIP_ONESAP_UPLOAD_FILE}
set REM_DIR = ${WIP_ONESAP_REM_DIR}
set CURR_DIR = ${WIP_ONESAP_CURR_DIR}
set LOCAL_FILE = 'gprism_onesap_wip.dat'

alias gettime "`echo date +%Y-%m-%d_%H:%M:%S`"
set YYYYMMDD = `date +'%Y%m%d'`
set TIME=`date +%Y-%m-%d_%H:%M:%S`



echo "#################################################          " >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}
echo "Processing start time                             : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}




##################################################################################################
### Capture WIP Data
##################################################################################################
echo "Running php for capture wip data..."
/usr/local/httpd2220/bin/php ${GPRISM_HOME_DIR}/Report/cgi-bin/PS00S05000490.php

# Checking the generated file
# If file is generated, copy to share folder
if(-f $FILE1) then
        echo "WIP data file is generated             : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}


else
       echo "WIP data file is not generated            : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}
endif






#---------------------------------------------------------------------
# PROGRAM STARTED TWO PROCESS CHECK
#---------------------------------------------------------------------
set TIME=`date +%Y-%m-%d_%H:%M:%S`
echo "Processing program started two process check time : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}

set COLUMNS=1024
set PROCESS=`ps aux | grep $PROGRAM_ID | wc -l`

if ( ${PROCESS} >= 2 ) then
    set TIME=`date +%Y-%m-%d_%H:%M:%S`
    echo "ERROR: Program started two process.           : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}
    exit 1
endif




# Init Variables




#---------------------------------------------------------------------
# PROGRAM STARTED TWO PROCESS CHECK
#---------------------------------------------------------------------
cd $CURR_DIR;
echo "Processing setup date period to time              : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}
set file_exist=0
set counter = 0
set error=""

set TIME=`date -d '2 seconds' '+%Y/%m/%d %H:%M:%S'`



while(1)
        if($counter >= ${WIP_UTC_UPD_BREAK_TIME}) then
           break
        endif

        if(-f $FILE1) then
           set file_exist=1
        else
           set error="File does not exist."
        endif

        if($file_exist == 1) then
           break
        endif

        set counter=`expr $counter + 60`
        sleep ${WIP_UTC_UPD_SLEEP_TIME}
end

echo "WIP data file is generation completed            : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}

cd $PATH


#---------------------------------------------------------------------
# RENAME GENERATED FILE
#---------------------------------------------------------------------

cat $FILE1 > $UPLOAD_FILE 

echo "WIP data file is renamed to gprism_onesap_wip.dat           : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}

#---------------------------------------------------------------------
# RENAME GENERATED FILE
#---------------------------------------------------------------------
ftp -n ${WIP_B_FTP_HOST} << END_SCRIPT
passive
quote USER ${WIP_ONESAP_FTP_USER}
quote PASS ${WIP_ONESAP_FTP_PASS}
cd $REM_DIR
put $UPLOAD_FILE 
END_SCRIPT
echo "File updloaded to the IMES dev successfully           : ${TIME}" >>${WIP_ONESAP_LOG_DIR}/${ONESAP_LOG_FILE}




if(-f $FILE1) then
       rm $FILE1
endif



exit 0
