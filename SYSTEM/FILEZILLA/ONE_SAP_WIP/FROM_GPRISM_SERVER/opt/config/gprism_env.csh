setenv HULFT_DIR /dat/hulft
setenv HULFT_DATA_DIR /dat/hulft
setenv DMI_FACTORY_CD 1023
setenv DMI_FILE_HEADER cbbga
setenv DMI_HULFT_ID xxxxxxx
setenv DMI_HULFT_TRNS OFF
setenv DMI_LOG_MODE OFF
setenv DMI_EXCEPT_KEY 
#setenv DMI_EXCEPT_KEY_T ABSEM49_RD00SA0
setenv DMI_EXCEPT_TEST ON

setenv USG1_FTP /dat/hulft/USG1
setenv UDG_FTP /dat/hulft/UDG

#setenv SNI_INTERFACE_TN_SUPPORT_B_FILE_HEADER SNI_INTERFACE_TN_SUPPORT_gprism_data
#setenv SNI_INTERFACE_TN_SUPPORT_B_DIR /dat/Gprism/
#setenv SNI_INTERFACE_TN_SUPPORT_B_FILE_LOCATION FTP/SNI_INTERFACE_TN_SUPPORT
#setenv SNI_INTERFACE_TN_SUPPORT_B_UPLOAD_FILE SNI_INTERFACE_TN_SUPPORT_gprism.dat
#setenv SNI_INTERFACE_TN_SUPPORT_B_FTP_USER gprismftpuser
#setenv SNI_INTERFACE_TN_SUPPORT_B_FTP_PASS gprismftpus3r
#setenv SNI_INTERFACE_TN_SUPPORT_B_FTP_HOST 10.100.246.88
#setenv SNI_INTERFACE_TN_SUPPORT_B_FTP_DIR /opt/htdocs/interfaces/receive/sni_interface_tn_support/
#setenv SNI_INTERFACE_TN_SUPPORT_UTC_UPD_SLEEP_TIME 60
#setenv SNI_INTERFACE_TN_SUPPORT_UTC_UPD_BREAK_TIME 7200
#setenv SNI_INTERFACE_TN_SUPPORT_UTC_UPD_UPLOAD_TIME 5
#setenv SNI_INTERFACE_TN_SUPPORT_UTC_UPD_TIME_LOG /var/log/PIDSCSG/Gprism/Bat/log/SNI_INTERFACE_TN_SUPPORT_upload_nextime.log

setenv WM_FACTORY_CD 1022
setenv WM_FILE_HEADER test
setenv WM_HULFT_ID xxxxxx
setenv WM_HULFT_TRNS OFF
setenv WM_LOG_MODE OFF
setenv WM_WAIT_TIME 60      # By second
setenv WM_EXCEPT_KEY #ABSEM11_RD00SB0
setenv WM_EXCEPT_KEY_T ABSEM49_RD00SA0,ABSEM11_RD00SB0,ABSEM31_RD00SC0,ABSEM31_RD00SC1,ABSEM31_RD00SC2,ABSEM31_RD00SC3,ABSEM41_RD00SD0 #ABSEM49_RD00SA0
setenv WM_EXCEPT_TEST OFF

setenv WM_U_FACTORY_CD 1131                       # TRANSFER NOTE FOR UTAC
setenv WM_U_FILE_HEADER cbbul
setenv WM_U_HULFT_ID DCBUL1CB
setenv WM_U_HULFT_TRNS OFF
setenv WM_U_LOG_MODE OFF
setenv WM_U_WAIT_TIME 60 # By second
setenv WM_U_EXCEPT_KEY
setenv WM_U_EXCEPT_KEY_T #ABSEM49_RD00SA0,ABSEM11_RD00SB0,ABSEM31_RD00SC0,ABSEM31_RD00SC1,ABSEM31_RD00SC2,ABSEM31_RD00SC3,ABSEM41_RD00SD0
setenv WM_U_EXCEPT_TEST ON

setenv UTL_FACTORY_CD 1022
setenv UTL_FILE_HEADER test
setenv UTL_HULFT_ID xxxxxx
setenv UTL_HULFT_TRNS OFF
setenv UTL_LOG_MODE OFF
setenv UTL_WAIT_TIME 60      # By second
setenv UTL_EXCEPT_KEY ABSEM11_RD00SB0
setenv UTL_EXCEPT_KEY_T ABSEM49_RD00SA0
setenv UTL_EXCEPT_TEST OFF 
setenv UTL_TRNS_DIR /SAP/UTL/TNS
setenv UTL_TRNS_FILE fg_tn_utl

setenv VMI_FACTORY_CD 1022		# added by leo
setenv VMI_FILE_HEADER cbbqu1000	# added by leo
setenv VMI_HULFT_ID xxxxxx		# added by leo
setenv VMI_HULFT_TRNS OFF		# added by leo
setenv VMI_LOG_MODE OFF			# added by leo
setenv VMI_WAIT_TIME 60      		# added by leo
setenv VMI_EXC 

setenv WIP_UPD_FLE as400wip_00.dat
setenv WIP_TRN_FLE trnwip_00.dat
setenv WIP_UPD_EXC ABSEM11_,ABSEM49_


setenv SAP_PCK_NAME cbb0000_11.dat
setenv PCK_FACTORY_CD 1022              # added by leo
setenv PCK_FILE_HEADER cbbqu1011        # added by leo
setenv PCK_HULFT_ID xxxxxx              # added by leo
setenv PCK_HULFT_TRNS OFF               # added by leo
setenv PCK_LOG_MODE OFF                 # added by leo
setenv PCK_WAIT_TIME 60                 # added by leo
setenv PCK_EXC 5530,5562,5568,5567,5564,5550 
setenv PCK_SAP_LOC 5530,5540,5562,5568,5567,5564,5550


setenv UTL_PCK_FILE_HEADER pick_order_utl   # added by Soe
setenv UTL_LOCATION /SAP/UTL/PODR

setenv PAN_PCK_FILE_HEADER cbbul1155   # added by Soe
setenv PAN_LOCATION /SAP/PAN/PODR
setenv PAN_PCK_HULFT_TRNS OFF
setenv PAN_PCK_HULFT_ID TESTPLP
setenv PAN_PCK_LOG_MODE ON
setenv PAN_LOG_DIR /var/log/PIDSCSG/Gprism

setenv RP_FILE_HEADER reportingPoint    # added by Sheetal
setenv VMI_DIV_CODE CCD_5580,IC_5560,HL_5590,IPD_5562,MI_5570,TR_5550,BGA_5540,LD_5530,MAT_5564,FP_5564,QFN_5568,SOB_5567 # added by leo
setenv GPRISM_HOME_DIR /opt/PIDSCSG/Gprism
setenv GPRISM_LOG_DIR /var/log/PIDSCSG/Gprism
setenv GPUSER gprismd
setenv GPPASS gprismd
setenv GPREAD gpread
setenv GPRDPS gpread
setenv DATA_DIR /dat/Gprism
#setenv HULFT_DATA_DIR /dat/hulft
setenv CAIRN_SETTING_DIR /opt/config/Cairn
setenv CAIRN_FACTORY_CD S
setenv CAIRN_HULFT_ID 'EEAAG010 EEAAG011'         # Æü¡¹Äù¤á½èÍý´°Î»»þ
setenv CAIRN_HULFT_FILE1 'KV_BLK_MST.dat KV_MCH_MST.dat KV_PRC_FLW_MST.dat KV_PRC_MST.dat KV_PRD_MST.dat KV_PRD_ORG_MST.dat KV_PRD_PRC_MST.dat KV_STP_MST.dat KV_SCRP_RSN_MST.dat'
setenv CAIRN_HULFT_FILE2 'PKV_IO_BLK_MST.dat PKV_PRC_MST.dat PKV_PRD_MST.dat PKV_PRD_ORG_MST.dat PKV_STP_MST.dat PKV_SCRP_RSN_MST.dat'
setenv CAIRN_HULFT_FILE3 'KKV_IO_BLK_MST.dat KKV_LOT_DSC_MST.dat KKV_LOT_TYPE_MST.dat KKV_PACKING_MST.dat KKV_PKG_MST.dat KKV_PRC_MST.dat KKV_PRD_MST.dat KKV_PRD_ORG_MST.dat KKV_PRD_RT_MST.dat KKV_PRT_CLS_MST.dat KKV_STP_MST.dat KKV_SCRP_RSN_MST.dat KKV_MAT_BAS_MST.dat KKV_YLD_MST.dat KKV_UNT_BAS_MST.dat'
setenv CAIRN_HULFT_SHIME_ID EEAAG004   # ·îÄù¤á½èÍý´°Î»»þ
setenv CAIRN_HULFT_TRNS OFF
setenv WIP_HULFT_TRNS ON
setenv DB_CONN_SID GPRISM
setenv DB_CONN_USR gprismd
setenv DB_CONN_PAS gprismd
setenv DB_SERVICE_NAME GPRISMD
setenv E9_CODES E949S020_E949S160_E949S170_E911S020_E911S080_E911S110
setenv CAIRN_TRACE_OPTION 1
setenv DBI_TRACE_OPTION 0
setenv CAIRN_ABSEM 11-29288_31-29313_40-29269
setenv CAIRN_DEFAULT_TIME_TO 11:59:59
setenv CAIRN_DEFAULT_TIME_FROM 12:00:00
setenv CAIRN_MAX_DATE_FROM 2004/01/01_00:00:00
setenv CAIRN_WAIT_TIME 1       # By second
setenv CAIRN_MONITOR_FLG 0     # Nagios monitor flg
setenv WIP_MONITOR_FLG 0
setenv ABSEM_CODE ABSEM11_ABSEM31_ABSEM40_ABSEM48_ABSEM49_ABSEM51_ABSEM90_ABSEM91_ABSEM92_ABSEM93_ABSEM94_ABSEM95
setenv BU_CODE BGA_LD_IC_TR_HL_CCD_QFN
setenv FCT_CODE FCSEMS
setenv RDG_CODE RD00SA0_RD00SB0 
setenv KKV_START -c_1_-e_0_-all_1
setenv KKV_END -c_0_-e_0_-all_1
setenv SHIME_DG_CODE DGSEM999_DG00S997_DG00S090
setenv CAIRN_AS400_DAILY_WAIT OFF
setenv CAIRN_AS400_MASTER_WAIT OFF
setenv CAIRN_AS400_SHIME_WAIT OFF
setenv CAIRN_AS400_DAILY_TIME 300
setenv CAIRN_AS400_MASTER_TIME 60
setenv CAIRN_AS400_SHIME_TIME 60
setenv CAIRN_AS400_DAILY_FLG cairn_as400_daily.flg
setenv CAIRN_AS400_MASTER_FLG cairn_as400_master.flg
setenv CAIRN_AS400_SHIME_FLG cairn_as400_monthly.flg
setenv CAIRN_HULFT_DAILY_ID EEAAG003
setenv CAIRN_HULFT_MASTER_ID EEAAG005
setenv CAIRN_HULFT_SHIME_ID EEAAG004
setenv WIP_HULFT_MASTER_ID GPRM001
#setenv CAIRN_EXCEPT_KEY ABSEM11_RD00SB0
#setenv CAIRN_EXCEPT_KEY_T ABSEM49_RD00SA0
setenv UNIT_PACKAGE_MAINTENANCE ON
setenv CAIRN_STG_CD P0SEM005
setenv CAIRN_RAT_CNV 1
setenv WIP_MONITOR_FLG 0
setenv WIP_HULFT_TRNS ON
setenv WIP_HULFT_MASTER_ID GPRM001

setenv SAP_MIV_DATA_U cbblb1138_00.dat

setenv NSCA_BIN /usr/local/nagios/bin/send_nsca
setenv NSCA_CFG /usr/local/nagios/etc/send_nsca.cfg
setenv NAGIOS_HOST 10.185.129.143
setenv GPRISM_HOST PSCSGGPAPP3
setenv MCP_PSI_DIR /dat/Partner/PSI
setenv MCP_RESULT_DIR /dat/Partner/Result
setenv MCP_LOG_DIR /dat/Partner/log
#setenv SAP_PLAN_NAME cbbqu1008_00.dat
setenv SAP_PLAN_NAME cbblu1008_00.dat
setenv SAP_SHIP_DATA cbbqu1060_00.dat
setenv SAP_MTRL_DATA cbbag1061_00.dat 
setenv WFR_MTRL_DATA cbbls3135_00.dat
setenv SAP_MIV_DATA cbbag1000_00.dat		# MIV DATA
setenv SAP_MIV_FLD /dat/hulft/SAP/MIV		# MIV FOLDER
setenv SAP_WIP_NAME sap_wip.dat
setenv SAP_WIP_HULFT xxxxxx
setenv SAP_WIP_TRN OFF
setenv SAP_WIP_EXC ABSEM49_RD00SA0
setenv SAP_MIV_DATA_UTL miv_riv_utl.dat
setenv SAP_MIV_FLD_UTL /dat/hulft/SAP/UTL/MIV


setenv TRG_INFO_DAT train_info.dat

setenv SAP_EXP_NAME cbblg1111_00.dat
setenv SAP_EXP_FILE cbbgl1109_00.dat
setenv SAP_EXP_HULFT xxxxxx
setenv SAP_EXP_TRN OFF
setenv SAP_EXP_EXC ABSEM49_RD00SA0

setenv SAP_RPT_FILE cbbgl1118_00.dat
setenv SAP_RPT_HULFT xxxxxx
setenv SAP_RPT_TRN ON
setenv SAP_RPT_EXC ABSEM49_RD00SA0,ABSEM11_RD00SB0


#Space Function
setenv SPACE_FUNC 0
setenv SPACE_SERVER SPC05
setenv SPACE_USER camline
setenv SPACE_PASSWORD camline
setenv SPACE_PROGRAM '~/./spcgw_broker.sh'
setenv SPACE_TEMP_FOLDER /tmp/SPACE/messages
setenv SPACE_RETRY_WAIT 1
setenv SPACE_MESSAGE_MAX_RETRY 3
setenv SPACE_IP 133.183.230.29

#setenv GPRISM_RSALOCATION /opt/config/SPACE/.ssh/id_rsa
#setenv GPRISM_SENT_FOLDER /tmp/SPACE/messages/sent
#setenv GPRISM_RECEIVED_FOLDER /tmp/SPACE/messages/received
#setenv GPRISM_TEMP_FOLDER /tmp/SPACE/messages
setenv GPRISM_RSALOCATION /opt/config/SPACE/.ssh/id_rsa
setenv GPRISM_SENT_FOLDER /dat/SPACE/messages/sent
setenv GPRISM_RECEIVED_FOLDER /dat/SPACE/messages/received
setenv GPRISM_TEMP_FOLDER /dat/SPACE/messages

#Access Log Function
setenv GPRISM_ACCESS_LOG 0



#SemiFG variables
setenv SEMIFG_DATA_SOURCE_DIR /cygdrive/e/SemiFG_Data
setenv SEMIFG_DATA_TARGET_DIR /dat/Gprism/SemiFG/data
setenv SEMIFG_SOURCE_HOST nas04
setenv SEMIFG_SOURCE_USER gprism
setenv SEMIFG_RSALOCATION /opt/config/SemiFG/.ssh/id_rsa

#WIP Utac Upload
#setenv WIP_UTC_UPD_FTP_HOST 10.81.162.193
#setenv WIP_UTC_UPD_FTP_USER gprism
#setenv WIP_UTC_UPD_FTP_PASS Gprism

setenv WIP_UTC_UPD_FILE_DIR /dat/hulft/
setenv WIP_UTC_UPD_AS400_FILE_NM_INIT AS400_ePROJ.dat
setenv WIP_UTC_UPD_GPRISM_FILE_NM_INIT GPRISM_ePROJ.dat
setenv WIP_UTC_UPD_USG2_FILE_NM_INIT USG2_ePROJ_
setenv WIP_UTC_UPD_LOG_FILE_NM_INIT /var/log/PIDSCSG/Gprism/Bat/log/utac_wip_upload_log_
setenv REMOVE_PROCESS D6SEM001

setenv WIP_UTC_UPD_SLEEP_TIME 60
setenv WIP_UTC_UPD_BREAK_TIME 7200
setenv WIP_UTC_UPD_UPLOAD_TIME 5
setenv WIP_UTC_UPD_TIME_LOG /var/log/PIDSCSG/Gprism/Bat/log/utac_wip_upload_nextime.log

setenv WIP_UTC_UPD_SIDE_ID USG2
setenv WIP_UTC_UPD_FTP_HOST 168.232.220.20
setenv WIP_UTC_UPD_FTP_DIR  USG2/eProjection
setenv WIP_UTC_UPD_FTP_USER 22113
setenv WIP_UTC_UPD_FTP_PASS turn-off#\!utac2014
setenv WIP_UTC_EXC ABSEM31_,ABSEM41_


setenv MTL_RCV_RDG LD,BGA
setenv MTL_RCV_GRP E000S005,E000S006,E000S008,E000S010,E000S019

#setenv WWR_UTC_E9_CODES E949S020_E949S160_E949S170_E911S020_E911S110_E931S042_E931S195_E931S106_E931S002_E931S106_E931S079_E931S129_E941S002_E911S080
setenv WWR_UTC_E9_CODES E949S020_E949S160_E949S170_E911S020_E911S110_E931S042_E931S195_E931S106_E931S002_E931S106_E931S079_E931S129_E941S002_E911S080_E931S217_E921S052_E921S059_E921S002_E921S007_E921S013
setenv WWR_UTC_EXCEPT_KEY
setenv FTEST_DIR /dat/Gprism/WWR

setenv AS400_PILOT_RESULT_DATA as400_result.txt

setenv PRC_RSLT_SLSI /opt/PIDSCSG/Gprism/Bat/cgi-bin
setenv PRC_RSLT_SLSI_DIR /dat/Gprism
setenv PRC_RSLT_SLSI_LOG_DIR /var/log/PIDSCSG/Gprism/Bat/slsi
setenv PRC_RSLT_SLSI_PROGRAM_ID PS00S05000250.pl
setenv PRC_RSLT_SLSI_HULFT_TRNS OFF
setenv PRC_RSLT_SLSI_HULFT_ID FS000092
setenv PRC_RSLT_SLSI_EXCEPT_KEY ABSEM31_RD00SC0

#setenv PANASONIC_RIDGE ABSEM31_RD00SC3,ABSEM31_RD00SC2,ABSEM31_RD00SC0,ABSEM41_RD00SD0,ABSEM49_RD00SA0
setenv PANASONIC_RIDGE ABSEM11_RD00SB0,ABSEM31_RD00SC3,ABSEM31_RD00SC2,ABSEM31_RD00SC1,ABSEM31_RD00SC0,ABSEM41_RD00SD0,ABSEM49_RD00SA0,ABSEM31_RD00SC4,ABSEM21_RD00SE0
#setenv PANASONIC_RIDGE ABSEM11_RD00SB0,ABSEM31_RD00SC3,ABSEM31_RD00SC2,ABSEM31_RD00SC1,ABSEM31_RD00SC0,ABSEM41_RD00SD0
#setenv PANASONIC_RIDGE ABSEM11_RD00SB0,ABSEM31_RD00SC3

#Transfer Note For Backfill
setenv BF_FACTORY_CD 1022
setenv BF_FILE_HEADER bf_tn
setenv BF_HULFT_ID xxxxxx
setenv BF_HULFT_TRNS OFF
setenv BF_LOG_MODE OFF
setenv BF_WAIT_TIME 60      # By second
setenv BF_EXCEPT_KEY ABSEM11_RD00SB0
setenv BF_EXCEPT_KEY_T ABSEM49_RD00SA0
setenv BF_EXCEPT_TEST OFF
setenv BF_TRNS_DIR /SAP/UTL/TNS
setenv BF_TRNS_FILE fg_tn_bf
setenv TRANS_UPLOAD_FILE transfer_note_gprism.dat
setenv TRANS_FTP_USER gprismftpuser
setenv TRANS_FTP_PASS gprismftpus3r
setenv TRANS_FTP_HOST 10.100.246.88
setenv TRANS_FTP_DIR /opt/htdocs/interfaces/receive/trans_note


setenv RT_FILE_HEADER rt_gprism_data
setenv RT_DIR /dat/Gprism/
setenv RT_FILE_LOCATION FTP/RT_INTERFACE
setenv RT_UPLOAD_FILE route_gprism.dat
setenv RT_FTP_USER gprismftpuser
setenv RT_FTP_PASS gprismftpus3r
setenv RT_FTP_HOST 10.100.246.88
setenv RT_FTP_DIR /opt/htdocs/interfaces/receive/route/#/dat/hulft/SAP/MIV/RCV/
#setenv RT_EXC_STPS ST11S0000001_ST11S0000007_ST11S0000050_ST11S0000016_ST11S0000052_ST11S0000075_ST11S0000049_ST11S0000010_ST49S0000001_ST49S0000028_ST49S0000026_ST49S0000015_ST49S0000027_ST31S0000187_ST31S0000188_ST31S0000194_ST31S0000195_ST31S0000182_ST31S0000025_ST31S0000041_ST31S0000198_ST31S0000192_ST31S0000196_ST31S0000001_ST31S0000036_ST31S0000073_ST31S0000078_ST31S0000128_ST31S0000158_ST31S0000105_ST31S0000124_ST41S0000027_ST41S0000028_ST41S0000032_ST41S0000035_ST41S0000036_ST41S0000001_ST41S0000015

setenv RT_EXC_STPS ST11S0000001_ST11S0000007_ST11S0000050_ST11S0000016_ST11S0000052_ST11S0000075_ST11S0000049_ST11S0000010_ST49S0000001_ST49S0000028_ST49S0000026_ST49S0000015_ST49S0000027_ST31S0000187_ST31S0000188_ST31S0000194_ST31S0000195_ST31S0000182_ST31S0000025_ST31S0000041_ST31S0000198_ST31S0000192_ST31S0000196_ST31S0000001_ST31S0000036_ST31S0000073_ST31S0000078_ST31S0000128_ST31S0000158_ST31S0000105_ST31S0000124_ST41S0000027_ST41S0000028_ST41S0000032_ST41S0000035_ST41S0000036_ST41S0000001_ST41S0000015_ST21S0000051_ST21S0000058_ST21S0000072_ST21S0000001_ST21S0000006_ST21S0000012_ST21S0000033

setenv PRT_FILE_HEADER prt_gprism_data
setenv PRT_DIR /dat/Gprism/
setenv PRT_FILE_LOCATION FTP/PRT_INTERFACE
setenv PRT_UPLOAD_FILE parts_gprism.dat
setenv PRT_FTP_USER gprismftpuser
setenv PRT_FTP_PASS gprismftpus3r
setenv PRT_FTP_HOST 10.100.246.88
setenv PRT_FTP_DIR /opt/htdocs/interfaces/receive/parts/

setenv FLW_TYP_FILE_HEADER flw_typ_gprism_data
setenv FLW_TYP_DIR /dat/Gprism/
setenv FLW_TYP_FILE_LOCATION FTP/FLW_TYP_INTERFACE
setenv FLW_TYP_UPLOAD_FILE flow_types_gprism.dat
setenv FLW_TYP_FTP_USER gprismftpuser
setenv FLW_TYP_FTP_PASS gprismftpus3r
setenv FLW_TYP_FTP_HOST 10.100.246.88
setenv FLW_TYP_FTP_DIR /opt/htdocs/interfaces/receive/flow_types/

setenv BOM_FILE_HEADER bom_gprism_data
setenv BOM_DIR /dat/Gprism/
setenv BOM_FILE_LOCATION FTP/BOM_INTERFACE
setenv BOM_UPLOAD_FILE bom_gprism.dat
setenv BOM_FTP_USER gprismftpuser
setenv BOM_FTP_PASS gprismftpus3r
setenv BOM_FTP_HOST 10.100.246.88
setenv BOM_FTP_DIR /opt/htdocs/interfaces/receive/bom

setenv TRAINING_DATAFILE training_data.csv
setenv TRAINING_DATAFOLDER /dat/Gprism/Training/

setenv LM_FILE_HEADER lm_gprism_data
setenv LM_DIR /dat/Gprism/
setenv LM_FILE_LOCATION FTP/LM_INTERFACE
setenv LM_UPLOAD_FILE merge_gprism.dat
setenv LM_FTP_USER gprismftpuser
setenv LM_FTP_PASS gprismftpus3r
setenv LM_FTP_HOST 10.100.246.88
setenv LM_FTP_DIR /opt/htdocs/interfaces/receive/merge/
setenv LM_OPTION 2                                              # 1 - by interval OR 2 - by start date - end date
setenv LM_INTERVAL_H 1                                          # For option 1 interval hour
setenv LM_STR_DTS "2016/12/20_19:00:00"                         # For option 2 start date
setenv LM_END_DTS "2016/12/20_21:59:59"                         # For option 2 end date

setenv LS_FILE_HEADER ls_gprism_data
setenv LS_DIR /dat/Gprism/
setenv LS_FILE_LOCATION FTP/LS_INTERFACE
setenv LS_UPLOAD_FILE split_gprism.dat
setenv LS_FTP_USER gprismftpuser
setenv LS_FTP_PASS gprismftpus3r
setenv LS_FTP_HOST 10.100.246.88
setenv LS_FTP_DIR /opt/htdocs/interfaces/receive/split/
setenv LS_OPTION 2                                              # 1 - by interval OR 2 - by start date - end date
setenv LS_INTERVAL_H 1                                          # For option 1 interval hour
setenv LS_STR_DTS "2017/03/20_19:00:00"                         # For option 2 start date
setenv LS_END_DTS "2017/04/20_21:59:59"                         # For option 2 end date

setenv LH_FILE_HEADER lh_gprism_data
setenv LH_DIR /dat/Gprism/
setenv LH_FILE_LOCATION FTP/LH_INTERFACE
setenv LH_UPLOAD_FILE lot_gprism.dat
setenv LH_FTP_USER gprismftpuser
setenv LH_FTP_PASS gprismftpus3r
setenv LH_FTP_HOST 10.100.246.88
setenv LH_FTP_DIR /opt/htdocs/interfaces/receive/lot/
setenv LH_OPTION 2                                              # 1 - by interval OR 2 - by start date - end date
setenv LH_INTERVAL_H 1                                          # For option 1 interval hour
setenv LH_STR_DTS "2016/12/20_19:00:00"                         # For option 2 start date
setenv LH_END_DTS "2016/10/12_23:59:59"                         # For option 2 end date

setenv TRG_MST_FILE_HEADER trg_mst_gprism_data
setenv TRG_MST_DIR /dat/Gprism/
setenv TRG_MST_FILE_LOCATION FTP/TRG_MST_INTERFACE
setenv TRG_MST_UPLOAD_FILE trg_mst_gprism.dat
setenv TRG_MST_FTP_USER gprismftpuser
setenv TRG_MST_FTP_PASS gprismftpus3r
setenv TRG_MST_FTP_HOST 10.100.246.88
setenv TRG_MST_FTP_DIR /opt/htdocs/interfaces/receive/trg_mst/

setenv IB_FILE_HEADER ib_gprism_data
setenv IB_DIR /dat/Gprism/
setenv IB_FILE_LOCATION FTP/IB_INTERFACE
setenv IB_UPLOAD_FILE inbox_gprism.dat
setenv IB_FTP_USER gprismftpuser
setenv IB_FTP_PASS gprismftpus3r
setenv IB_FTP_HOST 10.100.246.88
setenv IB_FTP_DIR /opt/htdocs/interfaces/receive/inbox/
setenv IB_OPTION 2                                              # 1 - by interval OR 2 - by start date - end date
setenv IB_INTERVAL_H 1                                          # For option 1 interval hour
setenv IB_STR_DTS "2016/03/01_15:00:00"                         # For option 2 start date
setenv IB_END_DTS "2016/03/05_23:59:59"                         # For option 2 end date

setenv TS_FILE_HEADER ts_gprism_data
setenv TS_DIR /dat/Gprism/
setenv TS_FILE_LOCATION FTP/TS_INTERFACE
setenv TS_UPLOAD_FILE transaction_gprism.dat
setenv TS_FTP_USER gprismftpuser
setenv TS_FTP_PASS gprismftpus3r
setenv TS_FTP_HOST 10.100.246.88
setenv TS_FTP_DIR /opt/htdocs/interfaces/receive/transaction/
setenv TS_RJ_CE CESEM01_CESEM02
setenv TS_EXC_CT CT00S0000027_CT49S0000063
setenv TS_OPTION 2                                              # 1 - by interval OR 2 - by start date - end date
setenv TS_INTERVAL_H 1                                          # For option 1 interval hour
setenv TS_STR_DTS "2016/12/20_00:00:00"                         # For option 2 start date
setenv TS_END_DTS "2016/12/20_23:59:59"                         # For option 2 end date

setenv CR_FILE_HEADER cr_gprism_data
setenv CR_DIR /dat/Gprism/
setenv CR_FILE_LOCATION FTP/CR_INTERFACE
setenv CR_UPLOAD_FILE creation_gprism.dat
setenv CR_FTP_USER gprismftpuser
setenv CR_FTP_PASS gprismftpus3r
setenv CR_FTP_HOST 10.100.246.88
setenv CR_FTP_DIR /opt/htdocs/interfaces/receive/creation/
setenv CR_RJ_CE CESEM01_CESEM02
setenv CR_EXC_CT CT00S0000027_CT49S0000063
setenv CR_OPTION 2 						# 1 - by interval OR 2 - by start date - end date
setenv CR_INTERVAL_H 1						# For option 1 interval hour 
setenv CR_STR_DTS "2017/03/20_19:00:00" 			# For option 2 start date
setenv CR_END_DTS "2017/04/20_21:59:59" 			# For option 2 end date

setenv WIP_B_FILE_HEADER wip_gprism_dat
setenv WIP_B_DIR /dat/Gprism/
setenv WIP_B_FILE_LOCATION FTP/WIP_INTERFACE
setenv WIP_B_UPLOAD_FILE wip_gprism.dat
setenv WIP_B_FTP_USER gprismftpuser
setenv WIP_B_FTP_PASS gprismftpus3r
setenv WIP_B_FTP_HOST 10.100.246.88
setenv WIP_B_FTP_DIR /opt/htdocs/interfaces/receive/wip/
setenv WIP_OPTION 2                                              # 1 - by interval OR 2 - by start date - end date
setenv WIP_INTERVAL_H 1                                          # For option 1 interval hour
setenv WIP_STR_DTS "2016/04/01_00:00:00"                         # For option 2 start date
setenv WIP_END_DTS "2016/04/23_23:59:59"                         # For option 2 end date


setenv RJ_FILE_HEADER rj_gprism_data
setenv RJ_DIR /dat/Gprism/
setenv RJ_FILE_LOCATION FTP/RJ_INTERFACE
setenv RJ_UPLOAD_FILE reject_gprism.dat
setenv RJ_FTP_USER gprismftpuser
setenv RJ_FTP_PASS gprismftpus3r
setenv RJ_FTP_HOST 10.100.246.88
setenv RJ_FTP_DIR /opt/htdocs/interfaces/receive/reject/
setenv RJ_OPTION 2                                              # 1 - by interval OR 2 - by start date - end date
setenv RJ_INTERVAL_H 1                                          # For option 1 interval hour
setenv RJ_STR_DTS "2016/03/21_15:00:00"                         # For option 2 start date
setenv RJ_END_DTS "2016/03/29_23:59:59"                         # For option 2 end date

setenv SM_FILE_HEADER semifg_gprism_data
setenv SM_DIR /dat/Gprism/
setenv SM_FILE_LOCATION FTP/SEMIFG_INTERFACE
setenv SM_UPLOAD_FILE semifg_gprism.dat
setenv SM_FTP_USER gprismftpuser
setenv SM_FTP_PASS gprismftpus3r
setenv SM_FTP_HOST 10.100.246.88
setenv SM_FTP_DIR /opt/htdocs/interfaces/receive/semifg/

#setenv PCK_E9_CODES E911S680_E949S250_E949S140_E931S072_E931S123_E931S035_E931S157_E941S031  
setenv PCK_E9_CODES E911S680_E949S250_E949S140_E931S072_E931S123_E931S035_E931S157_E941S031_E921S071_E921S032

setenv SH_JP_FTP_USER usg2_gprismuser
setenv SH_JP_FTP_PASS Gprismuser
setenv SH_JP_FTP_HOST 10.81.186.11

setenv JP_PI_FILE_HEADER pilot_jdg
setenv JP_PI_DIR /USG2_FTP_Upload/GPRISM/PILOT/

setenv JP_PRC_FILE_HEADER process_detail
setenv JP_PRC_DIR /USG2_FTP_Upload/GPRISM/PRC_DTL/

setenv GP_SPACE_SENT_IN /dat/SPACE/messages/sent/
setenv GP_SPACE_SENT_OU /opt/SPACE/sentfiles/
setenv GP_SPACE_RECE_IN /dat/SPACE/messages/received/
setenv GP_SPACE_RECE_OU /opt/SPACE/receivedfiles/

setenv GP_SPACE_BACK_P01 /dat/Gprism/FTP/BOM_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P02 /dat/Gprism/FTP/IB_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P03 /dat/Gprism/FTP/LH_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P04 /dat/Gprism/FTP/LM_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P05 /dat/Gprism/FTP/LS_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P06 /dat/Gprism/FTP/RJ_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P07 /dat/Gprism/FTP/RT_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P08 /dat/Gprism/FTP/SEMIFG_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P09 /dat/Gprism/FTP/TS_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P10 /dat/Gprism/FTP/WIP_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P11 /dat/Gprism/FTP/CR_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P12 /dat/Gprism/FTP/PRT_INTERFACE/BACKUP/
setenv GP_SPACE_BACK_P13 /dat/Gprism/FTP/FLW_TYP_INTERFACE/BACKUP/

setenv GP_SPACE_TRAI_P01 /dat/Gprism/Training/Success/

setenv LD_PILOT_INFO_FILE_NM Pilot_Bar_Inf.csv
setenv LD_SHIPMENT_FILE_NM ShipmentData.csv

setenv PRE_PACK_STEPS ST11S0000067_ST49S0000024_ST49S0000022_ST31S0000033_ST31S0000070_ST31S0000121_ST31S0000155_ST41S0000029

setenv CL_LOGS_DIR /var/log/PIDSCSG/Gprism/Bat
setenv CL_LOGS_FILES sa_wip2.log:cr_bf.log:ls_bf.log:lm_bf.log:ib_bf.log:rj_bf.log # " : " is the seperator of files
setenv CL_LOGS_LIFE_TIME 90 # How many days want to keep log files

setenv ONESAP_LOG_FILE wip_onesap.txt
setenv WIP_ONESAP_REM_DIR /opt/htdocs/interfaces/receive/wip_onesap/
setenv WIP_ONESAP_CURR_DIR /dat/Gprism/FTP/wip_onesap_interface/
setenv WIP_ONESAP_UPLOAD_FILE gprism_onesap_wip.dat
setenv WIP_ONESAP_FTP_USER gprismftpuser
setenv WIP_ONESAP_FTP_PASS gprismftpus3r

setenv iMES_FUNC 1
setenv iMES_URL http://10.100.246.80/backfill/index.php/services/Check


#======================
# SNI
#======================
setenv SNI_WO_DATA_WFR_FLD /dat/Gprism/FTP/SNI_WO_WAFER_INTERFACE
setenv SNI_WO_DATA_PKG_FLD /dat/Gprism/FTP/SNI_WO_PKG_INTERFACE
setenv SNI_WO_ASSY_DATA WORKORDER_ASSY
setenv SNI_WO_ASSY_DATA_MIG WORKORDER_ASSY_MIG
setenv SNI_WO_ASSY_DATA_MIG_NL WORKORDER_ASSY_MIG_NL
setenv SNI_WO_ASSY_DATA_MIG_SF WORKORDER_ASSY_MIG_SF
setenv SNI_WO_ASSY_DATA_MIG_NL_SF WORKORDER_ASSY_MIG_NL_SF
setenv SNI_WO_PACK_DATA WORKORDER_FT
setenv SNI_SPL_DIR /dat/Gprism/FTP/SNI_SPL_FILE
#setenv SNI_RCV_DIR /dat/SNI/RCV
#setenv SNI_PRC_DIR /dat/SNI/PRC
#setenv SNI_ERR_DIR /dat/SNI/ERR
#setenv SNI_FIN_DIR /dat/SNI/FIN

setenv SNI_SUP_PLN_B_FILE_HEADER SUPPLY_PLAN
setenv SNI_SUP_PLN_B_DIR /dat/Gprism/
setenv SNI_SUP_PLN_B_FILE_LOCATION FTP/SNI_SUP_PLN_INTERFACE
#setenv SNI_SUP_PLN_B_UPLOAD_FILE SNI_SUP_PLN_gprism.dat
#setenv SNI_SUP_PLN_B_FTP_USER gprismftpuser
#setenv SNI_SUP_PLN_B_FTP_PASS gprismftpus3r
#setenv SNI_SUP_PLN_B_FTP_HOST 10.100.246.88
#setenv SNI_SUP_PLN_B_FTP_DIR /opt/htdocs/interfaces/receive/sni_sup_pln/

setenv SNI_WF_INPUT_B_FILE_HEADER WfInput
setenv SNI_WF_INPUT_B_DIR /dat/Gprism/
setenv SNI_WF_INPUT_B_FILE_LOCATION FTP/SNI_WF_INPUT_INTERFACE
#setenv SNI_WF_INPUT_B_UPLOAD_FILE WfInput.csv
#setenv SNI_WF_INPUT_B_FTP_USER gprismftpuser
#setenv SNI_WF_INPUT_B_FTP_PASS gprismftpus3r
#setenv SNI_WF_INPUT_B_FTP_HOST 10.100.246.88
#setenv SNI_WF_INPUT_B_FTP_DIR /opt/htdocs/interfaces/receive/sni_wf_input/

setenv SNI_PKG_INPUT_B_FILE_HEADER PkgInput
setenv SNI_PKG_INPUT_B_DIR /dat/Gprism/
setenv SNI_PKG_INPUT_B_FILE_LOCATION FTP/SNI_PKG_INPUT_INTERFACE
#setenv SNI_PKG_INPUT_B_UPLOAD_FILE SNI_PKG_INPUT_gprism.dat
#setenv SNI_PKG_INPUT_B_FTP_USER gprismftpuser
#setenv SNI_PKG_INPUT_B_FTP_PASS gprismftpus3r
#setenv SNI_PKG_INPUT_B_FTP_HOST 10.100.246.88
#setenv SNI_PKG_INPUT_B_FTP_DIR /opt/htdocs/interfaces/receive/sni_pkg_input/

setenv SNI_INTERFACE_WIP_B_FILE_HEADER WIP
setenv SNI_INTERFACE_WIP_B_DIR /dat/Gprism/
setenv SNI_INTERFACE_WIP_B_FILE_LOCATION FTP/SNI_WIP_INTERFACE
setenv SNI_INTERFACE_WIP_PACKING_STEPS ST31S0000233_ST11S0000068_ST49S0000014_ST31S0000072_ST31S0000123_ST31S0000035_ST31S0000157_ST41S0000031
#setenv SNI_INTERFACE_WIP_B_UPLOAD_FILE SNI_INTERFACE_WIP_gprism.dat
#setenv SNI_INTERFACE_WIP_B_FTP_USER gprismftpuser
#setenv SNI_INTERFACE_WIP_B_FTP_PASS gprismftpus3r
#setenv SNI_INTERFACE_WIP_B_FTP_HOST 10.100.246.88
#setenv SNI_INTERFACE_WIP_B_FTP_DIR /opt/htdocs/interfaces/receive/sni_interface_wip/


setenv TN_SP_FILE_HEADER cbbul1169   # added by Soe
setenv TN_SP_LOCATION /SAP/PAN/TN_SP
setenv TN_SP_HULFT_TRNS OFF
setenv TN_SP_HULFT_ID DCBUQ1EE
setenv TN_SP_LOG_MODE ON
setenv TN_SP_LOG_DIR /var/log/PIDSCSG/Gprism

setenv SNI_SUP_PLN_LOG_MODE OFF
setenv SNI_WF_INPUT_LOG_MODE OFF
setenv SNI_PKG_INPUT_LOG_MODE OFF
setenv SNI_INTERFACE_WIP_LOG_MODE OFF
setenv SNI_INTERFACE_TN_SUPPORT_LOG_MODE OFF

