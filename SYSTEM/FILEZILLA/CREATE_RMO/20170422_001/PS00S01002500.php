<?php
# ======================================================================================
# [DATE]  : 2013.02.27				[AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM				[SYSTEM]  : CIM
# [SUB_ID]:					[SUBSYS]  : 
# [PRC_ID]:					[PROCESS] : 
# [PGM_ID]: PS00S01000990.php			[PROGRAM] : Create RMO Lot
# [MDL_ID]:					[MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
#
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]		[UPDATE]			[COMMENT]
# ====================	==================	============================================
# --------------------------------------------------------------------------------------
#******************************************************************
#
# PROGRAM SETTINGS
#
#******************************************************************
$g_Version = "2.0";
$g_PrgCD = "PS00S01000990";
#******************************************************************
#
# SET GLOBAL VARIABLE $gw_scr FROM <FORM> CONTENTS
#
#******************************************************************
if ($REQUEST_METHOD == "GET") {
	$gw_scr = cnv_formstr($_GET);
} else {
	$gw_scr = cnv_formstr($_POST);
}
#******************************************************************
#
# SET LANGUAGE AND ENCODING
#
#******************************************************************
$g_lang_path = $gw_scr['g_lang_path'];
$g_CharSet   = $gw_scr['g_CharSet'];
$g_usrId     = $gw_scr['usrId'];
$g_menuNo1   = $gw_scr['menuNo1'];
$g_menuNo2   = $gw_scr['menuNo2'];
$g_menuNo3   = $gw_scr['menuNo3'];
$g_menuNo4   = $gw_scr['menuNo4'];
#******************************************************************
#
# READ COMMON EXTERNAL FUNCTIONS
#
#******************************************************************
#------------------------------------------------------------------
# use among entire programs
#------------------------------------------------------------------
require_once (getenv("GPRISM_HOME") . "/DirList_pf.php");		# global variables of path list
require_once (getenv("GPRISM_HOME") . "/Func/Check.php");		# Input check
require_once ($g_func_dir . "/global.php");				# global variables
require_once ($g_func_dir . "/db_op.php");				# DB control
require_once ($g_func_dir . "/xdb_op.php");				# wrapper functions of DB control
require_once ($g_func_dir . "/xpt_err_msg.php");			# format of error message
#------------------------------------------------------------------
# for tracking
#------------------------------------------------------------------
require_once ($g_Mfunc_dir . "/xgt_dvsn.php");
require_once ($g_func_dir . "/xgn_cd.php");				# get common code from name
require_once ($g_func_dir . "/xgn_prd.php");				# get prd_nm from prd_cd
require_once ($g_func_dir . "/xgc_prd.php");				# get prd_cd from prd_nm
require_once ($g_func_dir . "/xgn_pkg.php");				# get pkg_cd & pkg_nm from prd_cd
require_once ($g_func_dir . "/xgt_stp_cls.php");			# get stp_cls_2 & stp_cls_4 from stp_cd
require_once ($g_func_dir . "/xgt_lot.php");				# get lot_bas_tbl info from lot_id
require_once ($g_func_dir . "/xgt_lp2.php");				# get default printer
require_once ($g_func_dir . "/xgt_lp2_cd.php");				# get printer info
#------------------------------------------------------------------
# VERB
#------------------------------------------------------------------
require_once ($g_func_dir . "/iocr.php");				# Re-Inspection Lot Create
require_once ($g_func_dir . "/iomg.php");				# Merge
require_once ($g_func_dir . "/mtin.php");
require_once ($g_func_dir . "/iosd.php");                               # ¶¦ÄÌ´Ø¿ô¡Êverb iosd¡Ë
require_once ($g_func_dir . "/mtot.php");				# test
require_once ($g_func_dir . "/pdcr.php");                      	 	# ¶¦ÄÌ´Ø¿ô¡Êverb pdcr¡Ë
require_once ($g_func_dir . "/iorv.php");                       	# ¶¦ÄÌ´Ø¿ô¡Êverb iorv¡Ë
#------------------------------------------------------------------
# local functions
#------------------------------------------------------------------
require_once ($g_func_dir . "/cs_xgn_man.php");				# get user name(and check login user)
require_once ($g_func_dir . "/cs_xck_jig.php");				# get user name(and check login user)
require_once ($g_func_dir . "/cs_xpt_etag_db.php");			# get user name(and check login user)
require_once ($g_func_dir . "/cs_xpt_etag_lsi.php");                     # get user name(and check login user)
require_once ($g_func_dir . "/cs_xgt_inhrt_po_data.php");              # Ensure PO CTG_CD is inherited to child.
require_once ($g_func_dir . "/cs_xpt_sni.php");              # Ensure SNI PO CTG_CD is inherited to child/children.

#------------------------------------------------------------------
# for screen
#------------------------------------------------------------------
require_once ($g_lang_dir . "/buttonM.php");				# button name
require_once ($g_lang_dir . "/PS00S01000990M.php");			# message
require_once ($g_Gfunc_dir . "/xpt_screen.php");			# create screen
#******************************************************************
#
# DEFINITION
#
#******************************************************************
#------------------------------------------------------------------
# for display
#------------------------------------------------------------------
define("INI_LOT_CNT",			10);
define("MAX_LOT_CNT",			100);
define("INI_MGZN_CNT",			5);
define("MAX_MGZN_CNT",			10);
#------------------------------------------------------------------
# unallowed step
#------------------------------------------------------------------
define("E9_UNALW",			serialize(array(
							"E911S140",
							"E911S150",
							"E911S140",
							"E911S150",
							"E911S020",
							"E911S080",
							"E911S090",
							"E911S110",
							"E911S720", # POST ROM ASSIGNMENT
							"E911S720", #POST ROM ASSIGNMENT 2
							"E911S710"
)));

#------------------------------------------------------------------
# category division
#------------------------------------------------------------------
define("CE_LTINF",			"CE00S02");
define("CT_NOCONSUMEHIST",					"CT00S0000025");
#------------------------------------------------------------------
# category code
#------------------------------------------------------------------
define("CT_SECNO",			"CT00S0000021");
define("CT_PLTNO",			"CT00S0000141");
define("CT_BATCHID",			"CT00S0000266");
#------------------------------------------------------------------
# tag
#------------------------------------------------------------------
define("TG_MA",				"MA");						# User ID
define("TG_EQ",				"EQ");						# Equipment Code
define("TG_LT",				"LT");						# Lot ID
define("TG_LP",				"LP");						# Printer Code
#------------------------------------------------------------------
# etc
#------------------------------------------------------------------
define("AU_STROK",			"AUSEM01");
define("AM_TRN",                        "AM00S001");
define("AM_FGWH", "AM00S5860");

#------------------------------------------------------------------
# for jig control
#------------------------------------------------------------------
define("CE_MGZN",			"CE00S04");
define("CT_MGZN",			"CT00S0000024");
define("JI_MGZN",			"JI00S001");
define("SH_MGZN",			"SH00S998");

define("PGMID_PRINT",     		"PS00S06000400");

define("CT_RMO", "CT00S0000381");
#******************************************************************
#
# FUNCTIONS
#
#******************************************************************
#==================================================================
# convert form data
#==================================================================
function cnv_formstr($w_req)
{
	reset($w_req);
	while(list($key, $val) = each($w_req)){
		if(is_array($val)){
			$w_arr = cnv_formstr($val);
			$w_scr[$key] = $w_arr;
		} else {
			if(get_magic_quotes_gpc()){
				$val = stripslashes($val);
			}
			$w_scr[$key] = $val;
		}
	}

	return $w_scr;
}
###################################################################
#####                                                         #####
##### FIRST PROCESS                                           #####
#####                                                         #####
###################################################################
#==================================================================
# initialize
#==================================================================
function main_init()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	set_init(1);
	scr_mode_chg(1);

	#------------------------------------------------------------------
	# get division code from menu registration info.
	#------------------------------------------------------------------
	$w_rtn = xgt_dvsn($w_dvsn_cd, $_, $_);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# get default printer
	#------------------------------------------------------------------
	$w_rtn = xgt_lp2(2, $w_lbl_cd, $w_lbl_nm, $_, $_);
	if($w_rtn != 0){
		$gw_scr['s_prnt_lv'] = 0;
		$gw_scr['s_prnt_msg'] = xpt_err_msg($g_msg, "", __LINE__);

		$g_msg    = "";
		$g_err_lv = "";
	}

	$gw_scr['s_dvsn_cd'] = trim($w_dvsn_cd);
	$gw_scr['s_lbl_cd']  = trim($w_lbl_cd);
	$gw_scr['s_lbl_nm']  = trim($w_lbl_nm);

	return 0;
}

###################################################################
#####                                                         #####
##### MODE1                                                   #####
#####                                                         #####
###################################################################
#==================================================================
# mode1
#==================================================================
function main_md1()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){
	case "CHECK":
		main_md1_chk();
		break;
	case "REDISP":
		main_md1_rdsp();
		break;
	case "ERASE":
		main_init();
		break;
	}

	return 0;
}

#==================================================================
# mode1 [Redisp]
#==================================================================
function main_md1_rdsp()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# check input
	#------------------------------------------------------------------
	### trim
	$gw_scr['s_inp_cnt'] = trim($gw_scr['s_inp_cnt']);
	### require
	if($gw_scr['s_inp_cnt'] == ""){
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		$g_msg = xpt_err_msg($g_msg, itm("InpLotCnt"), __LINE__);
		return 4000;
	}
	### number
	if(!check_num($gw_scr['s_inp_cnt'])){
		list($g_msg, $g_err_lv) = msg("err_Inp_Char");
		$g_msg = xpt_err_msg($g_msg, itm("InpLotCnt"), __LINE__);
		return 4000;
	}
	### range
	if($gw_scr['s_inp_cnt'] < 1
	|| $gw_scr['s_inp_cnt'] > constant("MAX_LOT_CNT")
	){
		list($g_msg, $g_err_lv) = msg("err_Inp_Over");
		$g_msg = xpt_err_msg($g_msg, itm("InpLotCnt"), __LINE__);
		return 4000;
	}

	$gw_scr['s_hdn_inp_cnt'] = $gw_scr['s_inp_cnt'];


	return 0;
}

#==================================================================
# mode1 [Check] process
#==================================================================
function main_md1_chk()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;


	#------------------------------------------------------------------
	# input check
	#------------------------------------------------------------------
	$w_rtn = check_input(1);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# get code/name
	#------------------------------------------------------------------
	### user name
	$w_rtn = cs_xgn_man($gw_scr['s_usr_id'], $w_usr_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
		return 4000;
	}
	### product code
	$w_rtn = xgc_prd($gw_scr['s_prd_nm'], $w_prd_cd, $_);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_prd_nm'], __LINE__);
		return 4000;
	}
	$gw_scr['s_prd_cd'] = $w_prd_cd;
        ### io cd get stp cd
        $w_rtn = get_stinf($w_prd_cd,$gw_scr['s_process_cd'],$gw_scr['s_io_block_cd'], $w_stp_cd);
        if($w_rtn != 0){
                $g_err_lv = 0;
		list($g_msg, $g_err_lv) = msg("err_wrong_process_code");
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_process_cd'], __LINE__);
                return 4000;
        }
        $gw_scr['s_stp_cd'] = $w_stp_cd;
	### step name
	$w_rtn = xgn_cd($gw_scr['s_stp_cd'], 1, $w_stp_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_stp_cd'], __LINE__);
		return 4000;
	}
	### printer
	$w_rtn = xgt_lp2_cd($gw_scr['s_lbl_cd'], $_, $_, $w_lbl_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lbl_cd'], __LINE__);
		return 4000;
	}
	### stp_cls
	$w_rtn = xgt_stp_cls($gw_scr['s_stp_cd'], $w_stpcls, $_);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_stp_cd'], __LINE__);
		return 4000;
	}

	### check unallowed step
	if(in_array(trim($w_stpcls), unserialize(constant("E9_UNALW")))){
		list($g_msg, $g_err_lv) = msg("err_Dsbl_Stp");
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_stp_cd'], __LINE__);
		return 4000;
	}


		$w_rtn = xgt_lot($gw_scr['s_lot_id'], $w_lot_bas);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
			return 4000;
		}
	
        	### product name
        	$w_rtn = xgn_prd($w_lot_bas['PRD_CD'], $w_typ_nm, $dmy);
        	if($w_rtn != 0){
                	$g_err_lv = 0;
                	$g_msg = xpt_err_msg($g_msg, $w_lot_bas['PRD_CD'], __LINE__);
                	return 4000;
        	}

		$gw_scr['s_typ_nm'] = $w_typ_nm;
		$gw_scr['s_chp_qty'] = $w_lot_bas['CHP_QTY'];


        	### stp_cls
        	$w_rtn = xgt_stp_cls($w_lot_bas['STP_CD'], $w_stp_cls, $_);
        	if($w_rtn != 0){
                	$g_err_lv = 0;
                	$g_msg = xpt_err_msg($g_msg, $w_lot_bas['STP_CD'], __LINE__);
                	return 4000;
        	}

        	### check unallowed step
        	if(in_array(trim($w_stp_cls), unserialize(constant("E9_UNALW")))){
                	list($g_msg, $g_err_lv) = msg("err_Dsbl_Stp");
                	$g_msg = xpt_err_msg($g_msg, $w_lot_bas['STP_CD'], __LINE__);
                	return 4000;
        	}
	
		###Checking should be based on lot's current routing
		$w_rtn = get_log_current_routing_info($w_lot_bas['RT_CD'],$w_log_routing_info,$w_log_routing_pr_info);
		$s_db_stp_cd = $w_lot_bas['IO_BLC_CD_B'];		
		$s_given_block_cd = $gw_scr['s_io_block_cd'];	
		$key_of_given_stp_cd = array_search($s_given_block_cd, $w_log_routing_info);		
		$key_of_db_stp_cd = array_search($s_db_stp_cd, $w_log_routing_info);  	
	#	if ($key_of_given_stp_cd > $key_of_db_stp_cd)
	#	{
	#		list($g_msg, $g_err_lv) = msg("err_wrong_Stp");
	#		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_io_block_cd'], __LINE__);
	#		return 4000;
	#	
	#	}
		$s_given_prod_code =  $gw_scr['s_prd_cd'];	
		$s_db_prod_code =  $w_log_routing_pr_info[$key_of_given_stp_cd];
		
		if ($s_given_prod_code != $s_db_prod_code )
		{
			list($g_msg, $g_err_lv) = msg("err_wrong_product_code");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_prd_cd'], __LINE__);
			return 4000;
		}
		

		
	
		#exit;
		#------------------------------------------------------------------
		# get Log
		#------------------------------------------------------------------
		$w_rtn = get_log($w_lot_bas,$gw_scr['s_prd_cd'],$gw_scr['s_process_cd'],
						$gw_scr['s_io_block_cd'],
						$w_log);
		if($w_rtn != 0) return 4000;

        	#------------------------------------------------------------------
        	# check IOSD status
        	#------------------------------------------------------------------
        	$w_rtn = iosd_st_check($w_lot_bas['LOT_ST_DVS']);
        	if ($w_rtn != 0) {
                	$g_err_lv = 0;
                	$g_msg = xpt_err_msg($g_msg,$gw_scr['s_lot_id'], __LINE__);
                	return 4000;
        	}

		#------------------------------------------------------------------
		# get date code
		#------------------------------------------------------------------
		$w_rtn = get_lotinf($gw_scr['s_lot_id'],
							constant("CE_LTINF"),
							constant("CT_SECNO"),
							$w_secdat);
		if($w_rtn != 0) return 4000;
		### error if cannot acquire
		if(count($w_secdat) == 0){
			list($g_msg, $g_err_lv) = msg("err_Get_Sec");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# get plate no
		#------------------------------------------------------------------

	#------------------------------------------------------------------
	# get route info
	#------------------------------------------------------------------
	$w_rtn = get_rtinf($w_prd_cd, $gw_scr['s_stp_cd'], $w_rtinf);
	if($w_rtn != 0) return 4000;

	#------------------------------------------------------------------
	# set screen array variable
	#------------------------------------------------------------------
	$gw_scr['s_usr_nm'] = trim($w_usr_nm);
	$gw_scr['s_prd_cd'] = trim($w_prd_cd);
	$gw_scr['s_stp_nm'] = trim($w_stp_nm);
	$gw_scr['s_lbl_nm'] = trim($w_lbl_nm);

	$gw_scr['s_srlz_rtinf'] = userialize($w_rtinf);

	scr_mode_chg(3);

	return 0;
}

###################################################################
#####                                                         #####
##### MODE2                                                   #####
#####                                                         #####
###################################################################
#==================================================================
# mode2
#==================================================================
function main_md2()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){
	case "CHECK":
		main_md2_chk();
		break;
	case "REDISP_MGZN":
		main_md2_rdsp();
		break;
	case "ERASE":
		set_init(2);
		main_md1_chk();
		break;
	case "BACK":
		set_init(2);
		scr_mode_chg(1);
		break;
	}

	return 0;
}

#==================================================================
# mode2 [redisp]
#==================================================================
function main_md2_rdsp()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# trim
	#------------------------------------------------------------------

	return 0;
}

#==================================================================
# mode2 [check]
#==================================================================
function main_md2_chk()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_warn = array();

	#------------------------------------------------------------------
	# DO NOT be changed the number of Line by [check] process
	#------------------------------------------------------------------


	$w_rtinf = uunserialize($gw_scr['s_srlz_rtinf']);
	#------------------------------------------------------------------
	# magazine check
	#------------------------------------------------------------------

	list($g_msg, $g_err_lv) = msg("guid_Execute");
	$g_msg = xpt_err_msg($g_msg, "", "");

	scr_mode_chg(3);

	return 0;
}

###################################################################
#####                                                         #####
##### MODE3                                                   #####
#####                                                         #####
###################################################################
#==================================================================
# mode3
#==================================================================
function main_md3()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){
	case "EXECUTE":
		main_md3_exe();
		break;
	case "BACK":
		set_init(3);
		scr_mode_chg(1);
		break;
	}

	return 0;
}

#==================================================================
# mode3 [Execute] process
#==================================================================
function main_md3_exe()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

        define("AB_LSI","ABSEM31");
        define("AB_TR","ABSEM41");

	$w_warn = array();
	#------------------------------------------------------------------
	# start transaction
	#------------------------------------------------------------------
	db_begin();

	#------------------------------------------------------------------
	# execute
	#------------------------------------------------------------------
	$w_rtn = main_exe($w_new_lot_id);
	if($w_rtn != 0){
		db_rollback();
		return 4000;
	}

	#------------------------------------------------------------------
	# commit
	#------------------------------------------------------------------
	db_commit();

        $w_rtn = xgt_lot($w_new_lot_id, $w_lot_bas);

        if ($w_rtn != 0) {
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
                return 4000;
        }

         
 	if($w_lot_bas['DVSN_CD_PRC']==AB_LSI || $w_lot_bas['DVSN_CD_PRC']==AB_TR) {

          	
		$w_rtn = cs_xpt_etag_lsi(1,array($w_new_lot_id), $gw_scr['s_lbl_cd']);

	}
        else {

          	$w_rtn = cs_xpt_etag_db($w_new_lot_id, $gw_scr['s_lbl_cd']);

        }

	//$w_rtn = cs_xpt_etag_db($w_new_lot_id, $gw_scr['s_lbl_cd']);
	if($w_rtn != 0){
		$gw_scr['s_prnt_lv']  = 0;
		$gw_scr['s_prnt_msg'] = xpt_err_msg($g_msg, "", __LINE__);
		$g_err_lv = "";
		$g_msg    = "";
	}

	if($g_msg == ""){
		list($g_msg, $g_err_lv) = msg("end_NewLot");
		$g_msg = xpt_err_msg($g_msg, $w_new_lot_id, "");
	}
	if($gw_scr['s_prnt_msg'] == ""){
		list($gw_scr['s_prnt_msg'], $gw_scr['s_prnt_lv']) = msg("end_Print");
	}

	scr_mode_chg(4);

	return 0;
}

#==================================================================
# execute process
#==================================================================
function main_exe(&$r_new_lot_id)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;

	#------------------------------------------------------------------
	# db lock
	#------------------------------------------------------------------
	$w_rtn = db_lock("LOT_NUM_TBL");
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "LOT_NUM_TBL", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# check multiple input
	#------------------------------------------------------------------

	$w_rtinf = uunserialize($gw_scr['s_srlz_rtinf']);

	$w_mgcnt = 0;
	$w_unq_secno = array();

                #------------------------------------------------------------------
                # get Lot Base Information
                #------------------------------------------------------------------
                $w_rtn = xgt_lot($gw_scr['s_lot_id'], $w_lot_bas);
                if ($w_rtn != 0) {
                        $g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
                }

		$w_lot_bas_before_split = $w_lot_bas;
				#save to ctg_log
				$w_rtn =ins_ctg_log(
							constant('CE_LTINF'),
							constant('CT_NOCONSUMEHIST'),
							$gw_scr['s_usr_id'],
							" ",
							"",
							$w_lot_bas
							);
				if ($w_rtn != 0) {
					return 4000;
				}	
	
         	#------------------------------------------------------------------
                # IOSD
                #------------------------------------------------------------------
                $w_rtn = iosd($w_lot_bas['LOT_ID'], $gw_scr['s_usr_id'], $w_lot_bas['UPD_LEV'],
                                                  constant('AM_TRN'), $gw_scr['s_cmt'], $w_lot_bas);
                if ($w_rtn != 0) {
                	$g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, "IOSD", __LINE__);
                        return 4000;
		}

                #------------------------------------------------------------------
                # PDCR
                #------------------------------------------------------------------

        	# £Ð£Ä£Ã£ÒÍÑÇÛÎó¥»¥Ã¥È
        	$w_bas['lot_id_rec']            = $w_lot_bas['LOT_ID_REC'];                             # Â¾¹©¾ì¼õ¤±¥í¥Ã¥È£É£Ä
        	$w_bas['lot_id_dif_str']        = $w_lot_bas['LOT_ID'];                         # ³È»¶¿¶½Ð¥í¥Ã¥È£É£Ä
		$w_bas['lot_no']                = $w_lot_bas['LOT_NO'];					# date("YmdHis")."RMO";
                $w_bas['lot_no_str']    	= $w_lot_bas['LOT_NO_STR'];                             # ¥í¥Ã¥ÈÈÖ¹æ(³È»¶)
        	$w_bas['lot_st_dvs']            = "PD";                                                 # ¥í¥Ã¥È¾õÂÖ¶èÊ¬
        	$w_bas['lot_st_dvs_b']          = "WT";                                                 # ¥í¥Ã¥È¾õÂÖ¶èÊ¬(Ä¾Á°)
        	$w_bas['lot_dvs_fin']           = "00";                                                 # ¥í¥Ã¥È¶èÊ¬(»Å¾å)
        	$w_bas['prd_cd']                = $gw_scr['s_prd_cd'];                  		# [PD]¥×¥í¥À¥¯¥È¥³¡¼¥É
        	$w_bas['rt_cd']                 = $w_lot_bas['RT_CD'];                   		# [RT]¥ë¡¼¥È¥³¡¼¥É
        	$w_bas['prc_cd']                = $gw_scr['s_process_cd'];              		# [PR]¥×¥í¥»¥¹¥³¡¼¥É
        	$w_bas['io_blc_cd']             = $gw_scr['s_io_block_cd'];             		# [IO]£É£Ï¥Ö¥í¥Ã¥¯¥³¡¼¥É
        	$w_bas['pkt_cd']                = $w_lot_bas['PKT_CD'];                                     	# [PA]¥Ñ¥±¥Ã¥È¥³¡¼¥É [ÊÝÀÇ¼êÄ¢ÈÖ¹æ]
               	$w_bas['sl_qty']                = $w_lot_bas['SL_QTY'];                                 # ¥¹¥é¥¤¥¹¿ôÎÌ
               	$w_bas['chp_qty']               = $w_lot_bas['CHP_QTY'];                                # ¥Á¥Ã¥×¿ôÎÌ
        	$w_bas['str_pln_dts']           = $g_low_dts;                                   	# ¿¶½ÐÍ½ÄêÆü»þ
               	$w_bas['secret_no']             = $w_lot_bas['SECRET_NO'];               		# Ì©ÈÖ
        	$w_bas['prio']                  = "9999";                                       	# Í¥Àè½ç°Ì
        	$w_bas['lot_typ_cd']            = $w_lot_bas['LOT_TYP_CD'];              		# [CC]¥í¥Ã¥È¥¿¥¤¥×¥³¡¼¥É
        	$w_bas['lot_dsc_cd']            = $w_lot_bas['LOT_DSC_CD'];              		# [CD]¥í¥Ã¥È¼±ÊÌ¥³¡¼¥É
        	$w_bas['bln_flg']               = $w_lot_bas['BLN_FLG'];                		# °úÅöÂÐ½è³°¥Õ¥é¥°
        	$w_bas['plt_dvs_cd']            = $w_lot_bas['PLT_DVS_CD'];                             # [CB]¥Ñ¥¤¥í¥Ã¥È¶èÊ¬¥³¡¼¥
        	$w_bas['mng_flg']               = $w_lot_bas['MNG_FLG']; 				#´ÉÍý¥Õ¥é¥°
                $w_bas['bu_cd_ast']             = $w_lot_bas['BU_CD_AST'];                              # [AM]Í½»»Ã±°Ì¥³¡¼¥É(±þ±ç)
        	$w_bas['bu_cd_cns']             = $w_lot_bas['BU_CD_CNS'];                              # [AM]Í½»»Ã±°Ì¥³¡¼¥É(°ÑÂ÷Àè)
               	$w_bas['lf_qty']                = $w_lot_bas['LF_QTY'];                                 # ¥ê¡¼¥É¥Õ¥ì¡¼¥àËç¿ô
        	#UPD 040708 DOS)Fujita
                $w_bas['rnk_ptn']               = $w_lot_bas['RNK_PTN'];                                     # ¥é¥ó¥¯¥Ñ¥¿¡¼¥ó
        	$w_bas['shp_fct_cd']            = $w_lot_bas['SHP_FCT_CD'];                          # [SF]Æâº­
        	$w_bas['usr_id']                = $gw_scr['s_usr_id'];                  	# [MA]¥æ¡¼¥¶¡¼£É£Ä
        	$w_bas['cmt']                   = $gw_scr['s_cmt'];                                         # ¥³¥á¥ó¥È

        	#------------------------------------------------------------------
        	# £Ö£å£ò£â¡Ê£Ð£Ä£Ã£Ò¡Ë¤Îµ¯Æ° (pdcr)
        	# [ Ìá¤êÃÍ¡§w_lot_id, w_lot_bas ]
        	#------------------------------------------------------------------
        	$w_rtn = pdcr($w_bas, $w_lot_id, $w_lot_bas);
        	if ($w_rtn != 0) {
                	db_rollback();          # ¥í¡¼¥ë¥Ð¥Ã¥¯
                	$g_msg = xpt_err_msg($g_msg, "PDCR", __LINE__);
                	$g_err_lv = 0;

                	return $w_rtn;
        	}

		#echo "<pre>"; var_dump($w_lot_bas); echo  "</pre>";
        	# -- ¥³¥á¥ó¥ÈÅÐÏ¿    ZXS)K.Maeda 2006-08-01 ÄÉ²Ã
        	if (strlen($gw_scr['s_cmt']) > 1) {
                	$w_cmt          = $gw_scr['s_cmt'];                             # ¥³¥á¥ó¥È
        	} else {
                	$w_cmt          = null;                                                 # ¥³¥á¥ó¥È
        	}

        	#------------------------------------------------------------------
        	# £Ö£å£ò£â¡Ê£É£Ï£Ò£Ö¡Ë¤Îµ¯Æ° (iorv)
        	# [ Ìá¤êÃÍ¡§w_lot_bas ]
        	#------------------------------------------------------------------
		$w_am_code =  constant('AM_TRN');
		if ( $w_bas['lot_typ_cd'] == 'CCSEM10' ) {
			$w_am_code = constant('AM_FGWH' );
		}

		$w_rtn = iorv($w_lot_id, $w_bas['usr_id'],
                                  $w_lot_bas['UPD_LEV'], $w_am_code, $gw_scr['s_cmt'], $w_lot_bas);
        	if ($w_rtn != 0) {
                	db_rollback();          # ¥í¡¼¥ë¥Ð¥Ã¥¯
                	$g_msg = xpt_err_msg($g_msg, "IORV", __LINE__);
                	$g_err_lv = 0;

                	return $w_rtn;
        	}

                $w_rtn = inhrt_lot_inf_tbl_for_rework($gw_scr['s_usr_id'],$gw_scr['s_lot_id'],$w_lot_id);
                if ($w_rtn != 0) {
                        return $w_rtn;
                }
		
		$r_new_lot_id = trim($w_lot_bas['LOT_ID']);
		
		# Ensure PO CTG_CD is inherited to child.
	        $w_rtn = cs_xgt_inhrt_po_data($gw_scr['s_usr_id'],$w_lot_bas_before_split,$r_new_lot_id);
        	if ($w_rtn != 0) {
        	        $g_err_lv = 0;
	                $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
        	        return 4000;
	        }

		 # Check if SNI Product
     		$w_rtn = cs_xpt_sni__is_sni($w_lot_bas_before_split['PRD_CD'], $r_is_sni);
	        if( $w_rtn != 0 ){
        	        db_rollback();
                	$g_err_lv = 0;
	                $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
        	        return;

	        }


	        if($r_is_sni){  # IF SNI PRODUCT
        	        $w_rtn = cs_xpt_sni__inhrt_ctg_tbl_for_iosp($gw_scr['s_usr_id'], $gw_scr['s_lot_id'], $r_new_lot_id); #r new_lot_id can be array
                	if ($w_rtn != 0) {
	                        db_rollback();
        	                $g_err_lv = 0;
                	        $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
                        	return;
	                }
        	}

		

		# Save RMO Data in ctg_tbl
		$w_ctg_cd = CT_RMO;
		$w_rtn = ins_ctg_tbl( $w_lot_bas['LOT_ID'], $w_ctg_cd, $w_lot_bas['LOT_ID_DIF_STR'] );
		if ( $w_rtn != 0 ) {
			db_rollback();
			$g_err_lv = 0;
                        $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
                        return 4000;
		}

	return 0;
}

function ins_ctg_tbl( $w_lot_id, $w_ctg_cd, $w_ctg_dat_txt ){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
        global $g_cpu_dts;
        global $g_low_dts;

	$w_ins = array(
                "DEL_FLG" => "0",
                "LOT_ID" => $w_lot_id,
                "CTG_DVS_CD" => CE_LTINF,
                "CTG_CD" => $w_ctg_cd,
                "SL_ID" => " ",
                "CTG_DAT_TXT" => $w_ctg_dat_txt,
                "CTG_DAT_VAL" => null,
                "CRT_DTS" => $g_cpu_dts,
                "USR_ID_CRT" => $gw_scr['s_usr_id'],
                "UPD_DTS" => $g_low_dts,
                "USR_ID_UPD" => " ",
                "UPD_LEV" => "1"
	);

        $w_rtn = db_insert("CTG_TBL", $w_ins);
        if ($w_rtn != 0) {
                list($g_msg, $g_err_lv) = msg("err_Ins");
                $g_msg = xpt_err_msg($g_msg, "CTG_TBL", __LINE__);
                return $w_rtn;
        }

	return 0;
}

#=================================================
# CTG_LOG ÅÐÏ¿
#=================================================
function ins_ctg_log($w_ctg_dvs_cd, $w_ctg_cd, $w_ctg_dat_txt, $w_equ_cd, $w_qty, $w_lot_bas){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;

        #------------------------------------------------------------------
        # get code/name
        #------------------------------------------------------------------
        ### user name
        $w_rtn = cs_xgn_man($w_ctg_dat_txt, $w_usr_nm);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $w_usr_id, __LINE__);
                return 4000;
        }

	# ÅÐÏ¿ÀßÄê
	$w_ins = array(
				"DEL_FLG"               => "0",
				"CTG_DVS_CD"    		=> $w_ctg_dvs_cd,
				"PRD_CD"                => $w_lot_bas['PRD_CD'],
				"PRC_CD"                => $w_lot_bas['PRC_CD'],
				"STP_NO"                => $w_lot_bas['STP_NO'],
				"PRC_CLS_4"             => $w_lot_bas['PRC_CLS_4'],
				"STP_CD"                => $w_lot_bas['STP_CD'],
				"CTG_CD"                => $w_ctg_cd,
				"LOT_ID"                => $w_lot_bas['LOT_ID'],
				"EQU_CD"                => $w_equ_cd,
				"SL_ID"                 => " ",
				"QTY"                   => $w_qty,
				"CTG_DAT_TXT"   	=> $w_usr_nm,
				"CRT_DTS"               => $g_cpu_dts,
				"USR_ID_CRT"    		=> $gw_scr['s_usr_id'],
				"UPD_DTS"               => $g_low_dts,
				"USR_ID_UPD"    		=> " ",
				"UPD_LEV"               => "1"
		);

	$w_rtn = db_insert("CTG_LOG", $w_ins);
	if ($w_rtn != 0) {
			#list($g_msg, $g_err_lv) = PSSEM01001301_msg("err_Ins_CtgLog");
			#$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			list($g_msg, $g_err_lv) = msg("err_Upd");
			$g_msg = xpt_err_msg($g_msg, "CTG_LOG", __LINE__);
			return $w_rtn;
	}

	return 0;
}
#==================================================================
# get route info
#==================================================================

function get_stinf($w_prd_cd, $w_prc_cd,$w_io_cd,&$r_st_cd)
{
        global $g_msg;
        global $g_err_lv;

        $r_dat = array();
        $w_au = constant("AU_STROK");
        $w_sql = <<<_SQL
SELECT
        DISTINCT STP_CD
FROM
        PRD_ORG_MST
WHERE
        PRD_CD = '{$w_prd_cd}'
        AND PRC_CD = '{$w_prc_cd}'
        AND IO_BLC_CD = '{$w_io_cd}'
        AND DEL_FLG = '0'
_SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
                return 4000;
        }

        $w_row = db_fetch_row($w_stmt);
        db_res_free($w_stmt);

        if(!$w_row){
                list($g_msg, $g_err_lv) = msg("err_Get_StInf");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        $r_st_cd = $w_row['STP_CD'];
        return 0;
}
#==================================================================
#get_log_current_routing_info
#==================================================================
function get_log_current_routing_info($w_rt_cd,&$r_log_routing_info,&$r_log_routing_pr_info)
{
	global $g_msg;
	global $g_err_lv;
	$w_sql = "
	select * from prd_org_mst where rt_cd = '$w_rt_cd' and del_flg = '0'
order by seq_no_rt, seq_no_prc, stp_no";

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
		return $w_rtn;
	}
	$r_log_routing_info = array();
	$cnt = 0;
	while($w_row = db_fetch_row($w_stmt)){
		
		$r_log_routing_info[$cnt] = trim($w_row['IO_BLC_CD']);
		$r_log_routing_pr_info[$cnt] = trim($w_row['PRD_CD']);		
		$cnt++;		
	}	
	db_res_free($w_stmt);

	return 0;

}


#==================================================================
# get Log
#==================================================================
function get_log($w_lot_bas,$w_prd_cd,$w_prc_cd,$w_io_cd, &$r_dat)
{
	global $g_msg;
	global $g_err_lv;

	$w_lot_rt_cd = $w_lot_bas['RT_CD'];
	$w_lot_pr_cd = $w_lot_bas['PRC_CD'];
	$w_lot_pd_cd = $w_lot_bas['PRD_CD'];
	$w_lot_io_cd = $w_lot_bas['IO_BLC_CD'];
	$w_au = constant("AU_STROK");

	$w_sql = <<<_SQL
SELECT
        PRD.PRD_NM,
	PRD.PKG_CD,
	NM.NM_FLL AS PKG_NM
FROM
        PRD_ORG_MST POM,
        PRC_MST PRC,
	PRD_MST PRD,
	NM_MST NM
WHERE
        POM.PRD_CD = '{$w_prd_cd}'
        AND POM.IO_BLC_CD = '{$w_io_cd}'
	AND POM.PRC_CD = '{$w_prc_cd}'
	AND POM.RT_CD = '{$w_lot_rt_cd}'
	AND POM.SEQ_NO_RT < (SELECT SEQ_NO_RT from PRD_ORG_MST where RT_CD='{$w_lot_rt_cd}' AND PRD_CD='{$w_lot_pd_cd}' AND PRC_CD='{$w_lot_pr_cd}' AND IO_BLC_CD='{$w_lot_io_cd}' AND DEL_FLG=0)
        AND POM.IO_FLG = '1'
        AND POM.DEL_FLG = '0'
        AND PRC.PRC_CD= POM.PRC_CD
        AND PRC.ST_DVS_CD = '{$w_au}'
        AND PRC.DEL_FLG = '0'
	AND PRD.PRD_CD = POM.PRD_CD
	AND PRD.DEL_FLG = '0'
	AND PRD.PKG_CD = NM.CD
	AND NM.DEL_FLG = '0'
ORDER BY
        M_RT_FLG_SCH DESC,
        RT_CD DESC

_SQL;
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "LOT_LOG", __LINE__);
		return 4000;
	}

	$w_row = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	if(!$w_row){
		list($g_msg, $g_err_lv) = msg("err_Mis_Log");
		$g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
		return 4000;
	}

	$r_dat = $w_row;

	return 0;
}

#==================================================================
# get LOT_INF_TBL
#==================================================================
function get_lotinf($w_lot_id, $w_ctgdvs, $w_ctgcd, &$r_dat)
{
	global $g_msg;
	global $g_err_lv;

	$r_dat = array();

	$w_sql = <<<_SQL
SELECT
	*
FROM
	LOT_INF_TBL
WHERE
	LOT_ID = '{$w_lot_id}'
	AND CTG_DVS_CD = '{$w_ctgdvs}'
	AND CTG_CD = '{$w_ctgcd}'
	AND DEL_FLG = '0'
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
		return 4000;
	}

	$cnt = 0;
	$w_tmp = array();
	while($w_row = db_fetch_row($w_stmt)){
		$cnt++;
		$w_tmp[$cnt] = array_map("trim", $w_row);
	}
	db_res_free($w_stmt);

	if($cnt == 1){
		$r_dat = $w_tmp[1];
	} else {
		$r_dat = $w_tmp;
	}

	return 0;
}

#==================================================================
# LOT_INF_TBL - Inherit all the information
#==================================================================
function inhrt_lot_inf_tbl_for_rework($w_usr_id, $w_lot_id, $w_lot_id_mg) {
        global $g_msg;
        global $g_err_lv;
        global $g_cpu_dts;
        global $g_low_dts;

	$w_ct_batch_id = constant("CT_BATCHID");

        $w_verb = "IOSD";

        #------------------------------------------------------------------
        # °ú·Ñ¤®¸µ¤Î¥Ç¡¼¥¿¼èÆÀ()
        #------------------------------------------------------------------
        $w_sql = "";
        $w_sql .= " SELECT * FROM LOT_INF_TBL";
        $w_sql .= " WHERE";
        $w_sql .= " LOT_ID = '{$w_lot_id}'";
        $w_sql .= " AND DEL_FLG = '0'";
	$w_sql .= " AND CTG_CD != '$w_ct_batch_id'";

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if ($w_rtn != 0) {
                list($g_msg, $g_err_lv) = msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
                return 4000;
        }

        $w_dat = array();
        $cnt = 0;
        while ($w_row = db_fetch_row($w_stmt)) {
                $cnt++;
                $w_dat[$cnt] = $w_row;
        }
        db_res_free($w_stmt);

        #------------------------------------------------------------------
        # get code/name
        #------------------------------------------------------------------
        ### user name
        $w_rtn = cs_xgn_man($w_usr_id, $w_usr_nm);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $w_usr_id, __LINE__);
                return 4000;
        }


        #------------------------------------------------------------------
        # ¿·µ¬¥í¥Ã¥È¤Ø°ú·Ñ¤®
        #------------------------------------------------------------------
        $w_strip = 0;
        $w_dte_cd = " ";
        $w_crt_dts = " ";
        for ($i = 1; $i <= $cnt; $i++) {
                $w_ins = array(
                                "DEL_FLG" 		=> "0",
                                "LOT_ID" 		=> $w_lot_id_mg,
                                "CTG_DVS_CD" 		=> $w_dat[$i]['CTG_DVS_CD'],
                                "CTG_CD" 		=> $w_dat[$i]['CTG_CD'],
                                "SL_ID" 		=> $w_dat[$i]['SL_ID'],
                                "CTG_DAT_TXT" 		=> $w_dat[$i]['CTG_DAT_TXT'],
                                "CTG_DAT_VAL" 		=> $w_dat[$i]['CTG_DAT_VAL'],
                                "CRT_VERB" 		=> $w_verb,
                                "CRT_DTS" 		=> $g_cpu_dts,
                                "USR_ID_CRT" 		=> $w_usr_id,
                                "UPD_VERB" 		=> " ",
                                "UPD_DTS" 		=> $g_low_dts,
                                "USR_ID_UPD" 		=> " ",
                                "UPD_LEV" 		=> "1"
                );
                $w_rtn = db_insert("LOT_INF_TBL", $w_ins);
                if ($w_rtn != 0) {
                        list($g_msg, $g_err_lv) = msg("err_Ins");
                        $g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
                        return 4000;
                }
        }
        return 0;
}

#==================================================================
# get route info
#==================================================================
function get_rtinf($w_prd_cd, $w_stp_cd, &$r_dat)
{
	global $g_msg;
	global $g_err_lv;

	$r_dat = array();
	$w_au = constant("AU_STROK");
	$w_sql = <<<_SQL
SELECT
	POM.*
FROM
	PRD_ORG_MST POM,
	PRC_MST PRC
WHERE
	POM.PRD_CD = '{$w_prd_cd}'
	AND POM.STP_CD = '{$w_stp_cd}'
	AND POM.IO_FLG = '1'
	AND POM.DEL_FLG = '0'
	AND PRC.PRC_CD= POM.PRC_CD
	AND PRC.ST_DVS_CD = '{$w_au}'
	AND PRC.DEL_FLG = '0'
ORDER BY
	M_RT_FLG_SCH DESC,
	RT_CD DESC
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
		return 4000;
	}

	$w_row = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	if(!$w_row){
		list($g_msg, $g_err_lv) = msg("err_Get_RtInf");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	$r_dat = $w_row;

	return 0;
}

#==================================================================
# Jig Check
# (exception handling of only for this program)
#==================================================================
function chk_jig_in($w_prc_cd, $w_stp_cd, $w_prd_cd)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_ctgdvs     = constant("CE_MGZN");
	$w_ctgcd      = constant("CT_MGZN");
	$w_jig_dvs_cd = constant("JI_MGZN");
	$w_shp_cd     = constant("SH_MGZN");

	$cnt = 0;
	$w_arr_mgzn_id = array();
	for($i=1; $i<=$gw_scr['s_hdn_mgzn_row']; $i++){
		if($gw_scr['s_list_mgzn_id'][$i] == "") continue;
		$cnt++;
		$w_arr_mgzn_id[$cnt] = $gw_scr['s_list_mgzn_id'][$i];
	}

	#------------------------------------------------------------------
	# get jig_org_mst
	#------------------------------------------------------------------
	$w_sql = <<<_SQL
SELECT DISTINCT
	'1' AS DVS,
	JOM.PRT_GRP_CD,
	JOM.PRT_CD
FROM
	JIG_FLW_MST JFM,
	JIG_ORG_MST JOM
WHERE
	JFM.JIG_DVS_CD = '{$w_jig_dvs_cd}'
	AND JFM.PRC_CD = '{$w_prc_cd}'
	AND JFM.STP_CD = '{$w_stp_cd}'
	AND JFM.DEL_FLG = '0'
	AND JOM.PRD_CD = '{$w_prd_cd}'
	AND JOM.PRT_GRP_CD = JFM.PRT_GRP_CD_B
	AND JOM.NO_USE_FLG = '0'
	AND JOM.DEL_FLG = '0'

UNION ALL

SELECT DISTINCT
	'2' AS DVS,
	JOM.PRT_GRP_CD,
	JOM.PRT_CD
FROM
	JIG_FLW_MST JFM,
	PRD_MST PRD,
	JIG_ORG_MST JOM
WHERE
	JFM.JIG_DVS_CD = '{$w_jig_dvs_cd}'
	AND JFM.PRC_CD = '{$w_prc_cd}'
	AND JFM.STP_CD = '{$w_stp_cd}'
	AND JFM.DEL_FLG = '0'
	AND PRD.PRD_CD = '{$w_prd_cd}'
	AND PRD.DEL_FLG = '0'
	AND JOM.PKG_CD = PRD.PKG_CD
	AND JOM.PRD_CD = ' '
	AND JOM.PRT_GRP_CD = JFM.PRT_GRP_CD_B
	AND JOM.NO_USE_FLG = '0'
	AND JOM.DEL_FLG = '0'

ORDER BY 1
_SQL;

	#echo $w_sql;
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "JIG_ORG_MST", __LINE__);
		return 4000;
	}

	$cnt = 0;
	$w_jomdat = array();
	while($w_row = db_fetch_row($w_stmt)){
		$w_dvs = $w_row['DVS'];
		if($b_dvs != "" && $w_dvs != $w_b_dvs){
			break;
		}
		$cnt++;
		$w_jomdat['PRT_GRP_CD'][$cnt] = trim($w_row['PRT_GRP_CD']);
		$w_jomdat['PRT_CD'][$cnt]     = trim($w_row['PRT_CD']);

		$b_dvs = $w_dvs;
	}
	db_res_free($w_stmt);

	#------------------------------------------------------------------
	# get prt_wip info in specified magazine
	# and check whether can be used
	#------------------------------------------------------------------
	$w_tmpsql = <<<_SQL
SELECT
	PWT.*,
	PBM.PRT_GRP_CD
FROM
	PRT_WIP_TBL PWT,
	PRT_BAS_MST PBM
WHERE
	PWT.MT_LOT_ID = '%s'
	AND PWT.SHP_CD = '{$w_shp_cd}'
	AND PBM.PRT_CD = PWT.PRT_CD
	AND PBM.DEL_FLG = '0'
_SQL;

	$w_prtwip = array();
	for($i=1; $i<=count($w_arr_mgzn_id); $i++){
		$w_sql = sprintf($w_tmpsql, $w_arr_mgzn_id[$i]);
		$w_stmt = db_res_set($w_sql);
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
			list($g_msg, $g_err_lv) = msg("err_Sel");
			$g_msg = xpt_err_msg($g_msg, "PRT_WIP_TBL", __LINE__);
			return 4000;
		}
		$w_row = db_fetch_row($w_stmt);
		db_res_free($w_stmt);

		if(!$w_row){
			list($g_msg, $g_err_lv) = msg("err_Get_Mgzn");
			$g_msg = xpt_err_msg($g_msg, $w_arr_mgzn_id[$i], __LINE__);
			return 4000;
		}

		### error if different from setting data
		#var_dump($w_row); var_dump($w_jomdat);
		if(!in_array(trim($w_row['PRT_GRP_CD']), $w_jomdat['PRT_GRP_CD'])
		|| !in_array(trim($w_row['PRT_CD']), $w_jomdat['PRT_CD'])
		){
			list($g_msg, $g_err_lv) = msg("err_Dsbl_Jig");
			$g_msg = xpt_err_msg($g_msg, $w_arr_mgzn_id[$i], __LINE__);
			return 4000;
		}

		### error if parts state doesn't Wait
		if($w_row['PRT_ST_DVS'] != "WT"){
			list($g_msg, $g_err_lv) = msg("err_Dsbl_JigSt");
			$g_msg = xpt_err_msg(sprintf($g_msg, $w_arr_mgzn_id[$i]), "", __LINE__);
			return 4000;
		}
	}


	return 0;
}

###################################################################
#####                                                         #####
##### VERB                                                    #####
#####                                                         #####
###################################################################
#==================================================================
# VERB::IOCR
#==================================================================
function main_verb_iocr($w_usr_id, $w_dat, &$w_lot_bas)
{
	global $g_msg;
	global $g_err_lv;

	array_push($w_dat, &$w_lot_bas);
	$w_rtn = call_user_func_array("iocr", $w_dat);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}
#==================================================================
# VERB::IOMG
#==================================================================
function main_verb_iomg($w_usr_id, $w_arr_mg_lot, &$w_lot_bas) {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# calc after merge
	#------------------------------------------------------------------
	$w_sum_sl_qty = array_sum($w_arr_mg_lot['sl_qty']);
	$w_sum_chp_qty = array_sum($w_arr_mg_lot['chp_qty']);
	$w_sum_lf_qty = array_sum($w_arr_mg_lot['lf_qty']);

	$w_array_cnt = count($w_arr_mg_lot['lot_id']);

	#------------------------------------------------------------------
	# check Lot state
	#------------------------------------------------------------------
	for ($i = 1; $i <= $w_array_cnt; $i++) {
		if ($w_arr_mg_lot['lot_id'][$i] == "") {
			continue;
		}
		$w_rtn = iomg_st_check($w_arr_mg_lot['lot_st_dvs'][$i]);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
	}

	#------------------------------------------------------------------
	# IOMG
	#------------------------------------------------------------------
	$w_rtn = iomg($w_usr_id, $w_sum_sl_qty, $w_sum_chp_qty, $w_sum_lf_qty,
			$w_arr_mg_lot['lot_no_str'][1], $w_arr_mg_lot['lot_no'][1],
			$w_arr_mg_lot['secret_no'][1], $w_array_cnt,
			$w_arr_mg_lot['lot_id'], $w_arr_mg_lot['upd_lev'],
			$w_arr_mg_lot['cmt'], $w_lot_bas);

	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# VERB::MTIN
#==================================================================
function main_verb_mtin($w_usr_id, $w_lot_id, $w_mt_lot_id)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# get Parts WIP info
	#------------------------------------------------------------------
	$w_sql = <<<_SQL
SELECT
	*
FROM
	PRT_WIP_TBL
WHERE
	MT_LOT_ID = '{$w_mt_lot_id}'
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRT_WIP_TBL", __LINE__);
		return 4000;
	}
	$w_prtwip = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	#------------------------------------------------------------------
	# check the material state
	#------------------------------------------------------------------
	$w_rtn = mtin_st_check($w_prtwip);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# MTIN
	#------------------------------------------------------------------
	$w_rtn = mtin($w_lot_id,
				  $w_mt_lot_id,
				  " ",
				  $w_usr_id,
				  $w_prtwip['UPD_LEV'],
				  "",
				  $w_prtwip);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# magazine control
#==================================================================
function main_exe_mgzn($w_lot_bas)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	for($i=1; $i<=$gw_scr['s_mgzn_row']; $i++){
		if($gw_scr['s_list_mgzn_id'][$i] == ""){
			continue;
		}

		#------------------------------------------------------------------
		# MTIN
		#------------------------------------------------------------------
		$w_rtn = main_verb_mtin($gw_scr['s_usr_id'],
								$w_lot_bas['LOT_ID'],
								$gw_scr['s_list_mgzn_id'][$i]);
		if($w_rtn != 0){
			return 4000;
		}

		#------------------------------------------------------------------
		# insert new magaine ID
		#------------------------------------------------------------------
		$w_rtn = ins_mgzn($gw_scr['s_usr_id'],
						$w_lot_bas['LOT_ID'],
						$gw_scr['s_list_mgzn_id'][$i],
						"IOCR");
		if($w_rtn != 0){
			return 4000;
		}
	}


	return 0;
}


#==================================================================
# insert magazine ID
#==================================================================
function ins_mgzn($w_usr_id, $w_lot_id, $w_mgznid, $w_verb)
{
	global $g_msg;
	global $g_err_lv;

	$w_ce_mag = constant("CE_MGZN");
	$w_ct_mag = constant("CT_MGZN");

	$w_sql = <<<_SQL
SELECT
	COUNT(*) AS CNT
FROM
	LOT_INF_TBL
WHERE
	LOT_ID = '{$w_lot_id}'
	AND CTG_CD = '{$w_ct_mag}'
	AND CTG_DVS_CD = '{$w_ce_mag}'
	AND CTG_DAT_TXT = '{$w_mgznid}'
	AND DEL_FLG = '0'
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
		return 4000;
	}

	$w_insflg = 1;
	if($w_row = db_fetch_row($w_stmt)){
		if($w_row['CNT'] > 0){
			$w_insflg = 0;
		}
	}
	db_res_free($w_stmt);

	if($w_insflg == 1){
		$w_rtn = ins_lotinf($w_usr_id,
						 $w_lot_id,
						 $w_ce_mag,
						 $w_ct_mag,
						 " ",
						 $w_mgznid,
						 "",
						 $w_verb);
		if($w_rtn != 0){
			return 4000;
		}
	}

	return 0;
}

#==================================================================
# insert LOT_INF_TBL
#==================================================================
function ins_lotinf($w_usr_id,
					 $w_lot_id,
					 $w_ctg_dvs_cd,
					 $w_ctg_cd,
					 $w_sl_id,
					 $w_ctg_dat_txt,
					 $w_ctg_dat_val,
					 $w_crt_verb)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;

	$w_arr = array
	(
		"DEL_FLG"		=> "0",
		"LOT_ID"		=> $w_lot_id,
		"CTG_DVS_CD"	=> $w_ctg_dvs_cd,
		"CTG_CD"		=> $w_ctg_cd,
		"SL_ID"			=> $w_sl_id,
		"CTG_DAT_TXT"	=> $w_ctg_dat_txt,
		"CTG_DAT_VAL"	=> $w_ctg_dat_val,
		"CRT_VERB"		=> $w_crt_verb,
		"CRT_DTS"		=> $g_cpu_dts,
		"USR_ID_CRT"	=> $w_usr_id,
		"UPD_VERB"		=> " ",
		"UPD_DTS"		=> $g_low_dts,
		"USR_ID_UPD"	=> " ",
		"UPD_LEV"		=> 1
	);

	$w_rtn = db_insert("LOT_INF_TBL", $w_arr);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Ins");
		$g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# inherit date code
#==================================================================
function unq_datecode($w_usr_id, $w_old_lot_id, $w_new_lot_id, &$r_unq_secno)
{
	global $g_msg;
	global $g_err_lv;

	$w_ctgdvs = constant("CE_LTINF");
	$w_ctgcd  = constant("CT_SECNO");

	$w_sql = <<<_SQL
SELECT
	CTG_DAT_TXT
FROM
	LOT_INF_TBL
WHERE
	LOT_ID = '{$w_old_lot_id}'
	AND CTG_DVS_CD = '{$w_ctgdvs}'
	AND CTG_CD = '{$w_ctgcd}'
	AND DEL_FLG = '0'
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
		return 4000;
	}

	while($w_row = db_fetch_row($w_stmt)){
		$w_secno = trim($w_row['CTG_DAT_TXT']);
		if(!in_array($w_secno, $r_unq_secno)){
			$r_unq_secno[] = $w_secno;
		}
	}
	db_res_free($w_stmt);

	return 0;
}

#==================================================================
# input check
#==================================================================
function check_input($w_mode)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch ($w_mode) {
	#------------------------------------------------------------------
	# MODE1
	#------------------------------------------------------------------
	case 1:
		#------------------------------------------------------------------
		# trim & upper
		#------------------------------------------------------------------
		$gw_scr['s_usr_id'] = strtoupper(trim($gw_scr['s_usr_id']));
		$gw_scr['s_prd_nm'] = strtoupper(trim($gw_scr['s_prd_nm']));
		$gw_scr['s_prd_cd'] = strtoupper(trim($gw_scr['s_prd_cd']));
		$gw_scr['s_io_block_cd'] = strtoupper(trim($gw_scr['s_io_block_cd']));
		$gw_scr['s_plt_no'] = trim($gw_scr['s_plt_no']);
		$gw_scr['s_lbl_cd'] = strtoupper(trim($gw_scr['s_lbl_cd']));
			$gw_scr['s_lot_id'] = strtoupper(trim($gw_scr['s_lot_id']));

		#------------------------------------------------------------------
		# required
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		if($gw_scr['s_usr_id'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("UsrId"), __LINE__);
			return 4000;
		}
                if($gw_scr['s_lot_id'] == ""){
                        $g_msg = xpt_err_msg($g_msg, itm("LotId"), __LINE__);
                        return 4000;
                }

		if($gw_scr['s_prd_nm'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("PrdNm"), __LINE__);
			return 4000;
		}
                if($gw_scr['s_io_block_cd'] == ""){
                        $g_msg = xpt_err_msg($g_msg, itm("StpCd"), __LINE__);
                        return 4000;
                }
		if($gw_scr['s_lbl_cd'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("LblPrinter"), __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# prohibited characters
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Inp_Char");
		if(!check_eisu($gw_scr['s_usr_id'])){
			$w_tg = get_tg(itm("UsrId"), $gw_scr['s_usr_id']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_prdnm($gw_scr['s_prd_nm'])){
			$w_tg = get_tg(itm("PrdNm"), $gw_scr['s_prd_nm']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
#		if(!check_err_code($gw_scr['s_stp_cd'])){
#			$w_tg = get_tg(itm("StpCd"), $gw_scr['s_stp_cd']);
#			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
#			return 4000;
#		}
                if(!check_err_code($gw_scr['s_io_block_cd'])){
                        $w_tg = get_tg(itm("StpCd"), $gw_scr['s_io_block_cd']);
                        $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                        return 4000;
                }
		if(!check_err_meisho($gw_scr['s_lbl_cd'])){
			$w_tg = get_tg(itm("LblPrinter"), $gw_scr['s_lbl_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
#		for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
#			if($gw_scr['s_list_lot_id'][$i] == "") continue;
			if(!check_err_lot($gw_scr['s_lot_id'])){
				$w_tg = get_tg(itm("LotId"), $gw_scr['s_lot_id']);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
#		}
		break;
	#------------------------------------------------------------------
	# MODE2
	#------------------------------------------------------------------
	case 2:
		#------------------------------------------------------------------
		# trim
		#------------------------------------------------------------------
		break;
	#------------------------------------------------------------------
	# MODE3
	#------------------------------------------------------------------
	case 3:
		#------------------------------------------------------------------
		# trim
		#------------------------------------------------------------------
		break;
	}

	$g_msg    = "";
	$g_err_lv = "";

	return 0;
}

#==================================================================
# convert to unix time
#==================================================================
function cnvepc($w_dts)
{
	list($y,$m,$d,$h,$i,$s) = preg_split("/[\-: ]/", $w_dts);
	return mktime($h,$i,$s,$m,$d,$y);
}

#==================================================================
# initialize
#==================================================================
function set_init($w_mode, $w_rtn = 0)
{
	global $gw_scr;
	global $g_page_stp;
	
	### MODE1
	if($w_mode == 1){
		$gw_scr['s_usr_id'] = "";
		$gw_scr['s_prd_nm'] = "";
		$gw_scr['s_stp_cd'] = "";
		$gw_scr['s_process_cd'] = "";
		$gw_scr['s_io_block_cd'] = "";
                $gw_scr['s_process_nm'] = "";
                $gw_scr['s_io_block_nm'] = "";
		$gw_scr['s_prd_cd'] = "";
		$gw_scr['s_prd_nm'] = "";
		$gw_scr['s_lbl_cd'] = "";
		$gw_scr['s_chp_qty'] = "";
		$gw_scr['s_typ_nm'] = "";
		$gw_scr['s_cmt'] = "";
		$gw_scr['s_lot_id']  = "";
		$gw_scr['s_mgzn_row']     = constant("INI_MGZN_CNT");
		$gw_scr['s_hdn_mgzn_row'] = constant("INI_MGZN_CNT");
	}

	### MODE2
	if($w_mode <= 2){
		$gw_scr['s_usr_nm'] = "";
		$gw_scr['s_lbl_nm'] = "";
		$gw_scr['s_srlz_rtinf'] = "";

		$gw_scr['s_mgzn_flg']     = "";
		$gw_scr['s_mgzn_row']     = constant("INI_MGZN_CNT");
		$gw_scr['s_hdn_mgzn_row'] = constant("INI_MGZN_CNT");
		$gw_scr['s_list_mgzn_id'] = array();
	}

	### MODE3
	if($w_mode <= 3){
	}

	### MODE4
	if($w_mode == 4){
		$gw_scr['s_list_sl_no'] = $gw_scr['s_list_ex_sl_no'];
	}

	return 0;
}
#==================================================================
# screen setting(before disp)
#==================================================================
function scr_bf_setting()
{
	global $gw_scr;
	global $g_mode;

	return;
}
#==================================================================
# screen setting(after disp)
#==================================================================
function scr_af_setting()
{
	global $gw_scr;
	global $g_mode;

	return;
}
#==================================================================
# set space
#==================================================================
function set_space($w_var)
{
	$w_rtn = $w_var;
	if($w_rtn == ""){
		$w_rtn = " ";
	}
	return $w_rtn;
}
#==================================================================
# Array re-index from 1
#==================================================================
function rearray(&$arr)
{
	if(isset($arr[0])){
		$tmp = array();
		$cnt = 0;
		for($i=0; $i<count($arr); $i++){
			$cnt++;
			$tmp[$cnt] = $arr[$i];
		}
		$arr = $tmp;
	}
	return;
}
#==================================================================
# serialize
#==================================================================
function userialize($w_arr)
{
	return bin2hex(serialize($w_arr));
}
#==================================================================
# unserialize
#==================================================================
function uunserialize($w_serial)
{
	return unserialize(pack("H*", $w_serial));
}
#==================================================================
# put an escape character($) if include wild card of SQL
#==================================================================
function str_escape($str){
	return str_replace(array('%', '_', '*'), array('$%', '$_', ''), $str);
}
#==================================================================
# round up to six decimal places
#==================================================================
function ceil_7($w_val){
	$w_str = (string)$w_val;
	list($w_int, $w_dec) = explode(".", $w_str);

	if(strlen($w_dec) > 6){
		if(substr($w_dec, 6, 1) != 0){
			$w_val = $w_val + 0.000001;
		}

		unset($w_int, $w_dec, $w_str);
		$w_str = (string)$w_val;
		list($w_int, $w_dec) = explode(".", $w_str);
		$w_dec = rtrim(substr($w_dec, 0, 6), "0");

		if(strlen($w_dec) > 1){
			$w_rtn_val = $w_int . "." . $w_dec;
		} else {
			$w_rtn_val = $w_int;
		}
	} else {
		$w_rtn_val = $w_val;
	}

	return $w_rtn_val;

}
#==================================================================
# set optional info in error message
#==================================================================
function get_tg()
{
	$w_arr = func_get_args();
	return implode("/", $w_arr);
}
#==================================================================
# simplified Lang function call
#==================================================================
function itm($var)
{
	return PS00S01000990_item($var);
}
function msg($var)
{
	return PS00S01000990_msg($var);
}
#******************************************************************
#******************************************************************
#******************************************************************
#******************************************************************
#******************************************************************
#
# MAIN START
#
#******************************************************************
#==================================================================
# DB connect
#==================================================================
$w_rtn = xdb_op_conndb();
if ($w_rtn != 0) {
	$g_err_lv = 0;
	$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	return;
}

#==================================================================
# authentification
#==================================================================
if($gw_scr['s_act'] != "DOWNLOAD"){
$refe_flg=1;
require_once (getenv("GPRISM_HOME") . "/renzheng.php");
$bak_s_renzheng_t = $gw_scr['s_renzheng_t'];
$bak_s_renzheng   = $gw_scr['s_renzheng'];
}
#==================================================================
# process of each mode
#==================================================================
### screen setting befor disp
scr_bf_setting();
### check the function exists
$w_func = "main_md" . $g_mode;
if(function_exists($w_func)){
	$w_func();
} else {
	main_init();
}

$gw_scr['s_renzheng']   = $bak_s_renzheng;
$gw_scr['s_renzheng_t'] = $bak_s_renzheng_t;

scr_af_setting();
get_screen(1);

#==================================================================
# DB close
#==================================================================
xdb_op_closedb();
?>
