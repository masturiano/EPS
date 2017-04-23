<?php
# ======================================================================================
# [DATE]  : 2017.04.07          		[AUTHOR]  : MIS) Mydel
# [SYS_ID]: GPRISM						[SYSTEM]  : CCD
# [SUB_ID]:								[SUBSYS]  : 
# [PRC_ID]:								[PROCESS] : 
# [PGM_ID]: PS00S01001960.php			[PROGRAM] : Machine Downtime Email Registration
# [MDL_ID]:								[MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
# 
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]		[UPDATE]			[COMMENT]
# ====================	==================	============================================
# ®
# --------------------------------------------------------------------------------------
#******************************************************************
#
# Program Version
#
#******************************************************************
$g_Version = "2.0";
$g_PrgCD = "PS00S01001960";
#******************************************************************
#
# Assign GET Data to $gw_scr
#
#******************************************************************
if ($REQUEST_METHOD == "GET") {
	$gw_scr = cnv_formstr($_GET);
} else {
	$gw_scr = cnv_formstr($_POST);
}
#******************************************************************
#
# Language, User ID and character set use
#
#******************************************************************
$g_lang_path    = $gw_scr['g_lang_path'];
$g_CharSet      = $gw_scr['g_CharSet'];
$g_usrId        = $gw_scr['usrId'];
$g_menuNo1      = $gw_scr['menuNo1'];
$g_menuNo2      = $gw_scr['menuNo2'];
$g_menuNo3      = $gw_scr['menuNo3'];
$g_menuNo4      = $gw_scr['menuNo4'];
#******************************************************************
#
# Include files needed
#
#******************************************************************
#------------------------------------------------------------------
# DB con, Directory/Path config
#------------------------------------------------------------------
require_once (getenv("GPRISM_HOME") . "/DirList_pf.php");			#	 
require_once (getenv("GPRISM_HOME") . "/Func/Check.php");			# 
require_once ($g_func_dir . "/global.php");					# 
require_once ($g_func_dir . "/db_op.php");					# 
require_once ($g_func_dir . "/xdb_op.php");					# 
require_once ($g_func_dir . "/xpt_err_msg.php");				# 
require_once ($g_Mfunc_dir . "/xgt_dvsn.php");				#for printer changes
#------------------------------------------------------------------
# Function related
#------------------------------------------------------------------
require_once ($g_func_dir . "/cs_xgn_man.php");					# 
require_once ($g_func_dir . "/xgn_cd.php");						# 
require_once ($g_func_dir . "/xgn_prd.php");					#	 
require_once ($g_func_dir . "/xgc_prd.php");					# 
require_once ($g_func_dir . "/xgn_pkg.php");					# 
require_once ($g_func_dir . "/xgt_stp_cls.php");				# 
require_once ($g_func_dir . "/xgt_lot.php");					# 
require_once ($g_func_dir . "/xgt_nio.php");					#
require_once ($g_func_dir . "/xgt_npr.php");					# 
require_once ($g_func_dir . "/xgt_stp.php");					# 
require_once ($g_func_dir . "/xck_upd.php");					# 
require_once ($g_func_dir . "/xck_lio.php");					# 
require_once ($g_func_dir . "/xck_rnk.php");					# 
require_once ($g_func_dir . "/cs_xpt_wfrlsi_label.php");		#
require_once ($g_func_dir . "/xgt_lp2.php");					# 
require_once ($g_func_dir . "/xgt_lp2_cd.php");					# 
require_once ($g_func_dir . "/xgt_use_equ.php");				# 
require_once ($g_func_dir . "/cs_xgt_sap_rcv.php");				# cs_xgt_sap_rcv
require_once ($g_func_dir . "/cs_xck_staff_ctrl.php");				# For Staff Process control
require_once ($g_func_dir . "/xpt_1sec_dts.php");

require_once ($g_Mfunc_dir . "/xcnt_lev.php");								# ¹¹¿·¥ì¥Ù¥ë¥«¥¦¥ó¥È¥¢¥Ã¥×´Ø¿ô
require_once ($g_Mfunc_dir . "/xgt_prev_page.php");
require_once ($g_Mfunc_dir . "/xgt_code.php");
require_once ($g_Mfunc_dir . "/xgt_ctg.php");								# ¥«¥Æ¥´¥ê¼èÆÀ´Ø¿ô
require_once ($g_Mfunc_dir . "/xgt_cdnm.php");								# ¥³¡¼¥ÉÌ¾¾Î¼èÆÀ´Ø¿ô
require_once ($g_Mfunc_dir . "/xglob_pm.php");								# ¶¦ÄÌÊÑ¿ô(¹©Äø´ÉÍý)

#------------------------------------------------------------------
# VERB
#------------------------------------------------------------------
require_once ($g_func_dir . "/pdcr.php");					# PDCR
require_once ($g_func_dir . "/iorv.php");					# IORV
require_once ($g_func_dir . "/ioin.php");					# IOIN
require_once ($g_func_dir . "/ioot.php");					# IOOT
require_once ($g_func_dir . "/iomv.php");					# IOMV
require_once ($g_func_dir . "/prpt.php");					# PRPT
require_once ($g_func_dir . "/prpc.php");					# PRPC
require_once ($g_func_dir . "/prgt.php");					# PRGT
require_once ($g_func_dir . "/iohd.php");					# IOHD
require_once ($g_func_dir . "/iopc.php");					# IOPC
require_once ($g_func_dir . "/rtcg.php");					# RTCG
#------------------------------------------------------------------
# µòÅÀÀìÍÑ
#------------------------------------------------------------------
require_once($g_func_dir . "/cs_xexc_hold_rsv.php");				# Í½Ìó¥Û¡¼¥ë¥É¸¡º÷¡¦¼Â¹Ô´Ø¿ô
#------------------------------------------------------------------
# ¥¹¥¯¥ê¡¼¥ó
#------------------------------------------------------------------
require_once ($g_lang_dir . "/buttonM.php");					# ¥Ü¥¿¥óÌ¾¾Î
require_once ($g_lang_dir . "/PS00S01001960M.php");				# ¥á¥Ã¥»¡¼¥¸
require_once ($g_Gfunc_dir . "/xpt_screen.php");				# ¥×¥í¥°¥é¥à¥Õ¥ì¡¼¥à¸Æ¤Ó½Ð¤·
require_once ($g_func_dir . "/cs_xgt_po_data.php");
require_once ($g_func_dir . "/xgt_to220_cd_cnt.php");
require_once ($g_func_dir . "/cs_xgt_secno.php");
require_once ($g_func_dir . "/xgt_cd_cnt.php");
#******************************************************************
#
# Äê¿ôÄêµÁ
#
#******************************************************************
#------------------------------------------------------------------
# É½¼¨·Ï
#------------------------------------------------------------------
#------------------------------------------------------------------
# ½é´üÃÍ
#------------------------------------------------------------------
define("INI_CCCD",					"CCSEM01");		# ¥í¥Ã¥È¶èÊ¬¥³¡¼¥É
define("INI_CDCD",					"CDSEM01");		# ¥í¥Ã¥È¼±ÊÌ¥³¡¼¥É
#------------------------------------------------------------------
# ¥«¥Æ¥´¥ê¶èÊ¬
#------------------------------------------------------------------
define("CE_LTINF",					"CE00S02");		# Lot Information
#------------------------------------------------------------------
# ¥«¥Æ¥´¥ê¥³¡¼¥É
#------------------------------------------------------------------
define("CT_SLCINF",                                     "CT00S0000098");        # Slice information
define("CT_EXPDTE",                                     "CT00S0000099");        # Expired Date
define("CT_MFGDTE",                                     "CT00S0000100");        # Manufacturing Date
#------------------------------------------------------------------
# ¥¿¥°ÄêµÁ
#------------------------------------------------------------------
define("TG_MA",					"MA");				# User Tag
define("TG_CC",					"CC");				# ¥í¥Ã¥È¶èÊ¬¥³¡¼¥É
define("TG_CD",					"CD");				# ¥í¥Ã¥È¼±ÊÌ¥³¡¼¥É
define("TG_LP",					"LP");				# ¥í¥Ã¥ÈÉ¼½ÐÎÏÀè
#------------------------------------------------------------------		
# ¤½¤ÎÂ¾ÄêµÁ
#------------------------------------------------------------------
define("GR_IT_AIMS",			"GRSEM01");				# ÁÀ¤¤¥é¥ó¥¯
define("DG_QA_MEMBER",			"DG00S020");				# QA Member
define("D6_BGA_INF",			"D6SEM003");				# Please change to actual BGA D6
define("AM_PARTNER",                    "AM00S0001");
define("AU_STR_PT_OK",                  "AUSEM01");
define("PGM_LBL",			"PS00S03000090");
define("DT_BLANK",			"0001-01-01 00:00:00");
#------------------------------------------------------------------
# ¹©Äø
#------------------------------------------------------------------
define("E9_WAFER_RCV_BGA",		"E911S020");				# WAFER_RECEIVE_BGA (E911S020)
# µö²Ä¹©Äø
define("E9_ALLOWED",			serialize(array(
	constant("E9_WAFER_RCV_BGA"),
	"E931S002",	#IPD WAFER INCOMING
	"E931S042",	#MAT WAFER INCOMING
	"E931S079",	#QFN WAFER INCOMING
	"E931S129",	#SOB WAFER INCOMING
	"E941S002"	#TR WAFER INCOMING
)));
##------------------------------------------------------------------
#Printer Modifications
##------------------------------------------------------------------
define("PGMID_PRINT",     "PS00S06000400");       
#==================================================================
# ÇÛÎó¥Ç¡¼¥¿¤ò°ì³çÊÑ´¹
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

#=============================================
# ½é´üÀßÄê
#=============================================
# ºÇÂç¹Ô
define("MAXROW", 30);
define("PAGE_MAXROW", MAXROW);
define("PAGE_MAXCOL", 3);
# ¥¿¥°
define("TAG_TAG", "TG");
define("TAG_PREFIX", "TGSEM");

# ·å¿ô
define("CNT_PGM_ID", 13);
define("CNT_NMFLL", 40);
define("CNT_PGMKBN", 2);
define("CNT_SSYSKBN", 2);

//define("DEFAULT_PACK_MAXCOL", 10);
define("DEFAULT_INPUT_ROW", 10);
//define("DEFAULT_MAX_INPLOT", 10);

#===========================================
# Ìá¤êÀè¤ÎÀßÄê
#===========================================
xgt_prev_page($gw_scr['s_prev_page']);
if($gw_scr['s_prev_page'] == ""){
	$gw_scr['s_dsbl_prev'] = "true";
} else {
	$gw_scr['s_prev_page'] .= ".php";
}
#=================================================
# Get the division
#=================================================
function get_division_cd(&$r_dvsn_cd_opt) {

        # -- Initialize variables for query
        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        # -- SQL query for Division code and name
        $w_sql = "
        	SELECT
				NM_FLL,CD 
			FROM
				NM_MST
			WHERE
				TAG = 'AB'
				AND DEL_FLG = '0' 
			ORDER BY 
				CD 
		";

        # -- Prepare sql statement
        $w_stmt = db_res_set($w_sql);

        # -- Exceute sql statement
        $w_rtn  = db_do($w_stmt);
        if ($w_rtn != 0) {
                list($g_msg, $g_err_lv) = PS00S02000340_msg("err_Sel_Nm_Mst");
                return 4000;
        }

        # -- Initialize empty array to hold query results
        $r_dvsn_cd_opt[''] = "";

        # -- Fetch results and put into an array
        while($w_row = db_fetch_row($w_stmt)) {

                $w_cd = trim($w_row['CD']);
                if(strlen($w_cd) < 1) {
                        continue;
                }

                # Pre format results into a string
                $w_opt_nm = $w_cd . '(' . trim($w_row['NM_FLL']) . ')';

                $r_dvsn_cd_opt[$w_cd] = $w_opt_nm;
        }

  		# -- Release resources for the sql query
        db_res_free($w_stmt);

        return 0;
}


#==================================================================
# ½é´ü½èÍý
#==================================================================
function main_init()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	# Division Code Dropdown
	# Call this function automatically
	$w_rtn = get_division_cd($w_dvsn_cd_opt);
    if($w_rtn != 0){
        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
        return 4000;
    }
	# Pass to screen
	$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

	# Time range
	$s_hour_cd = array(
		"" => "",
		"2HR" 	=> "2HR",
		"4HR" 	=> "4HR",
		"6HR" 	=> "6HR",
		"8HR" 	=> "8HR",
		"24HR" 	=> "24HR"
	);
	# Pass to screen
	$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

	# Input default value on form
	input_default();

	# Mode 1 changes	
	scr_mode_chg(1);

	return 0;
}
###################################################################
#==================================================================
# ¥â¡¼¥É1
#==================================================================
function main_md1()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;


	switch($gw_scr['s_act']){

		case "CLEAR";
			set_init(1);
			main_init();
			scr_mode_chg(1);
		break;
		case "SEARCH";
			# Division Code Dropdown
			# Call this function automatically
			$w_rtn = get_division_cd($w_dvsn_cd_opt);
		    if($w_rtn != 0){
		        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		        return 4000;
		    }
			# Pass to screen
			$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

			# Time range
			$s_hour_cd = array(
				"" => "",
				"2HR" 	=> "2HR",
				"4HR" 	=> "4HR",
				"6HR" 	=> "6HR",
				"8HR" 	=> "8HR",
				"24HR" 	=> "24HR"
			);
			# Pass to screen
			$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

			$w_rtn = check_input(1);
			if($w_rtn != 0){
				return $w_rtn;
			}
			else{
				$division_code = $gw_scr['s_dvsn_cd'];
				$ridge_no = $gw_scr['s_rdg_nm'];
				$time_range = $gw_scr['s_hour_cd'];
				$par_id = $ridge_no."_".$time_range;

				$w_sql = <<<_SQL
select * 
from par_mst 
where DEL_FLG=0
and PAR_CLS_CD='P000S016'
and PAR_ID = '{$par_id}'
order by RDG_CD
_SQL;
				#---------------
				# SQL.....
				#---------------
				$w_stmt = db_res_set($w_sql);
				#---------------
				# SQL...
				#---------------
				$w_rtn  = db_do($w_stmt);
				if ($w_rtn != 0){
					list($g_msg, $g_err_lv) = msg("err_Sel_ParMst");
					$g_msg = xpt_err_msg($g_msg, "", __LINE__);
					return $w_rtn;
				}
				#--------------------
				# SQL......
				#--------------------
				$row_num = 1;
				while($w_row = db_fetch_row($w_stmt)){
					$row_increment = $row_num++;
					# Pass data to text row
					$gw_scr['s_lst_eqp_id_'][$row_increment] = trim($w_row['PAR_TXT']);
				}
				#--------------------
				# .......
				#--------------------
				db_res_free($w_stmt);
				if($row_increment > 0){
					scr_mode_chg(2);
				}
			}
		break;
		case "REDISP";
			# Division Code Dropdown
			# Call this function automatically
			$w_rtn = get_division_cd($w_dvsn_cd_opt);
		    if($w_rtn != 0){
		        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		        return 4000;
		    }
			# Pass to screen
			$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

			# Time range
			$s_hour_cd = array(
				"" => "",
				"2HR" 	=> "2HR",
				"4HR" 	=> "4HR",
				"6HR" 	=> "6HR",
				"8HR" 	=> "8HR",
				"24HR" 	=> "24HR"
			);
			# Pass to screen
			$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

			main_md1_redisp();
		break;
	}

	return 0;
}
#==================================================================
# Main Mode 1 Redisplay
#==================================================================
function main_md1_redisp() {
	global $gw_scr;
	
	$gw_scr['s_inp_row'] = $gw_scr['s_inp_row'];

	# Clear filtering
	$gw_scr['s_dvsn_cd'] = $gw_scr['s_dvsn_cd'];
	$gw_scr['s_rdg_cd'] = $gw_scr['s_rdg_cd'];
	$gw_scr['s_hour_cd'] = $gw_scr['s_hour_cd'];
	$gw_scr['s_rdg_nm'] = $gw_scr['s_rdg_nm'];
	//$gw_scr['s_inp_row'] = "";
	# Clear rows
	// $gw_scr['s_list_eqp_id_1'] = "";
	// $gw_scr['s_list_tnm_id_1'] = "";
	// $gw_scr['s_list_prn_id_1'] = "";

	return 0;
}
#==================================================================
# ¥â¡¼¥É£±
#==================================================================
function main_md2()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){

		case "CLEAR";
			set_init(1);
			main_init();
			scr_mode_chg(1);
		break;
		case "SEARCH";
			# Division Code Dropdown
			# Call this function automatically
			$w_rtn = get_division_cd($w_dvsn_cd_opt);
		    if($w_rtn != 0){
		        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		        return 4000;
		    }
			# Pass to screen
			$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

			# Time range
			$s_hour_cd = array(
				"" => "",
				"2HR" 	=> "2HR",
				"4HR" 	=> "4HR",
				"6HR" 	=> "6HR",
				"8HR" 	=> "8HR",
				"24HR" 	=> "24HR"
			);
			# Pass to screen
			$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

			$w_rtn = check_input(1);
			if($w_rtn != 0){
				return $w_rtn;
			}
			else{
				$division_code = $gw_scr['s_dvsn_cd'];
				$ridge_no = $gw_scr['s_rdg_nm'];
				$time_range = $gw_scr['s_hour_cd'];
				$par_id = $ridge_no."_".$time_range;

				$w_sql = <<<_SQL
select * 
from par_mst 
where DEL_FLG=0 
and PAR_CLS_CD='P000S016'
and PAR_ID = '{$par_id}'
order by RDG_CD
_SQL;
				#---------------
				# SQL.....
				#---------------
				$w_stmt = db_res_set($w_sql);
				#---------------
				# SQL...
				#---------------
				$w_rtn  = db_do($w_stmt);
				if ($w_rtn != 0){
					list($g_msg, $g_err_lv) = msg("err_Sel_ParMst");
					$g_msg = xpt_err_msg($g_msg, "", __LINE__);
					return $w_rtn;
				}
				#--------------------
				# SQL......
				#--------------------
				$row_num = 1;
				while($w_row = db_fetch_row($w_stmt)){
					$row_increment = $row_num++;
					# Pass data to text row
					$gw_scr['s_lst_eqp_id_'][$row_increment] = trim($w_row['PAR_TXT']);
				}
				#--------------------
				# .......
				#--------------------
				db_res_free($w_stmt);
				if($row_increment > 0){
					scr_mode_chg(2);
				}
			}
		break;
		case "REDISP";
			# Division Code Dropdown
			# Call this function automatically
			$w_rtn = get_division_cd($w_dvsn_cd_opt);
		    if($w_rtn != 0){
		        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		        return 4000;
		    }
			# Pass to screen
			$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

			# Time range
			$s_hour_cd = array(
				"" => "",
				"2HR" 	=> "2HR",
				"4HR" 	=> "4HR",
				"6HR" 	=> "6HR",
				"8HR" 	=> "8HR",
				"24HR" 	=> "24HR"
			);
			# Pass to screen
			$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

			main_md1_redisp();
		break;
		case "BACK";
			main_init();
			scr_mode_chg(1);
		break;
		case "CHECK";
			# Division Code Dropdown
			# Call this function automatically
			$w_rtn = get_division_cd($w_dvsn_cd_opt);
		    if($w_rtn != 0){
		        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		        return 4000;
		    }
			# Pass to screen
			$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

			# Time range
			$s_hour_cd = array(
				"" => "",
				"2HR" 	=> "2HR",
				"4HR" 	=> "4HR",
				"6HR" 	=> "6HR",
				"8HR" 	=> "8HR",
				"24HR" 	=> "24HR"
			);
			# Pass to screen
			$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

			$w_rtn = check_input(2);
			if($w_rtn != 0){
				return $w_rtn;
			}
			scr_mode_chg(3);
		break;
	}

	return 0;
}
#==================================================================
# ¥â¡¼¥É£²
#==================================================================
function main_md3()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){
		case "SEARCH";
			# Division Code Dropdown
			# Call this function automatically
			$w_rtn = get_division_cd($w_dvsn_cd_opt);
		    if($w_rtn != 0){
		        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		        return 4000;
		    }
			# Pass to screen
			$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

			# Time range
			$s_hour_cd = array(
				"" => "",
				"2HR" 	=> "2HR",
				"4HR" 	=> "4HR",
				"6HR" 	=> "6HR",
				"8HR" 	=> "8HR",
				"24HR" 	=> "24HR"
			);
			# Pass to screen
			$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

			$w_rtn = check_input(1);
			if($w_rtn != 0){
				return $w_rtn;
			}
			else{
				$division_code = $gw_scr['s_dvsn_cd'];
				$ridge_no = $gw_scr['s_rdg_nm'];
				$time_range = $gw_scr['s_hour_cd'];
				$par_id = $ridge_no."_".$time_range;

				$w_sql = <<<_SQL
select * 
from par_mst 
where DEL_FLG=0 
and PAR_CLS_CD='P000S016'
and PAR_ID = '{$par_id}'
order by RDG_CD
_SQL;
				#---------------
				# SQL.....
				#---------------
				$w_stmt = db_res_set($w_sql);
				#---------------
				# SQL...
				#---------------
				$w_rtn  = db_do($w_stmt);
				if ($w_rtn != 0){
					list($g_msg, $g_err_lv) = msg("err_Sel_ParMst");
					$g_msg = xpt_err_msg($g_msg, "", __LINE__);
					return $w_rtn;
				}
				#--------------------
				# SQL......
				#--------------------
				$row_num = 1;
				while($w_row = db_fetch_row($w_stmt)){
					$row_increment = $row_num++;
					# Pass data to text row
					$gw_scr['s_lst_eqp_id_'][$row_increment] = trim($w_row['PAR_TXT']);
				}
				#--------------------
				# .......
				#--------------------
				db_res_free($w_stmt);
				if($row_increment > 0){
					scr_mode_chg(3);
				}
			}
		break;
		case "REDISP";
			# Division Code Dropdown
			# Call this function automatically
			$w_rtn = get_division_cd($w_dvsn_cd_opt);
		    if($w_rtn != 0){
		        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		        return 4000;
		    }
			# Pass to screen
			$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

			# Time range
			$s_hour_cd = array(
				"" => "",
				"2HR" 	=> "2HR",
				"4HR" 	=> "4HR",
				"6HR" 	=> "6HR",
				"8HR" 	=> "8HR",
				"24HR" 	=> "24HR"
			);
			# Pass to screen
			$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

			main_md1_redisp();
		break;
		case "BACK";
			main_init();
			scr_mode_chg(2);
		break;
		case "EXECUTE";
			# Division Code Dropdown
			# Call this function automatically
			$w_rtn = get_division_cd($w_dvsn_cd_opt);
		    if($w_rtn != 0){
		        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		        return 4000;
		    }
			# Pass to screen
			$gw_scr['s_dvsn_cd_opt'] = $w_dvsn_cd_opt;

			# Time range
			$s_hour_cd = array(
				"" => "",
				"2HR" 	=> "2HR",
				"4HR" 	=> "4HR",
				"6HR" 	=> "6HR",
				"8HR" 	=> "8HR",
				"24HR" 	=> "24HR"
			);
			# Pass to screen
			$gw_scr['s_hour_cd_opt'] = $s_hour_cd;

			$w_rtn = check_input(2);
			if($w_rtn != 0){
				return $w_rtn;
			}
			main_md3_exe();
		break;
	}

	return 0;
}

#==================================================================
# ¥â¡¼¥É£² ¼Â¹Ô¥Ü¥¿¥ó²¡²¼½èÍý
#==================================================================
function main_md3_exe()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	global $g_cpu_dts;

	# Variable for inserting to table
	$division_code = $gw_scr['s_dvsn_cd'];
	$ridge_code = $gw_scr['s_rdg_cd'];
	$ridge_no = $gw_scr['s_rdg_nm'];
	$time_range = $gw_scr['s_hour_cd'];
	$par_id = $ridge_no."_".$time_range;
	$user_id = $_GET['usrId']; # GPRISM User Id

	# Get the list of data from unblank row text boxes
	for($i=1; $i<=$gw_scr['s_inp_row']; $i++){
		# For email rows of EQP
		if($gw_scr['s_lst_eqp_id_'][$i] != ""){
			$integer_email_eqp[] = $gw_scr['s_lst_eqp_id_'][$i];
		}
		# For email rows of TNM
		if($gw_scr['s_lst_tnm_id_'][$i] != ""){
			$integer_email_tnm[] = $gw_scr['s_lst_tnm_id_'][$i];
		}
		# For email rows of PRN
		if($gw_scr['s_lst_prn_id_'][$i] != ""){
			$integer_email_prn[] = $gw_scr['s_lst_prn_id_'][$i];
		}
	}

	# Get data from Par Master use to compare the data
	$w_sql = <<<_SQL
select * 
from par_mst 
where DEL_FLG=0 
and PAR_CLS_CD='P000S016'
and PAR_ID = '{$par_id}'
order by RDG_CD
_SQL;
	#---------------
	# SQL.....
	#---------------
	$w_stmt = db_res_set($w_sql);
	#---------------
	# SQL...
	#---------------
	$w_rtn  = db_do($w_stmt);
	if ($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel_ParMst");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}
	#--------------------
	# SQL......
	#--------------------
	while($w_row = db_fetch_row($w_stmt)){
		$par_master_value[] = trim($w_row['PAR_TXT']);
	}
	#--------------------
	# .......
	#--------------------
	db_res_free($w_stmt);

	# Merge Integer Textbox Values
	$integer_email_merge = array_merge((array)$integer_email_eqp,(array)$integer_email_tnm,(array)$integer_email_prn);
	
	# Check existing data
	foreach($integer_email_merge as $value){
		if (!in_array($value, (array)$par_master_value)){
			$subject_to_insert[] = $value;
		}
	}

	# Insert unique data
	foreach($subject_to_insert as $row_user_id){
		$w_ins   = array(
			'DEL_FLG'		=> '0', 						# Default: 0
			'PAR_CLS_CD'	=> 'P000S016',					# Default: P000S016
			'PAR_ID'		=> $par_id,						# PAR_ID: Ridge plus Time Range
			'DVSN_CD'		=> $division_code,				# DVSN_CD
			'FCT_CD'		=> 'FCSEMS',					# Default: FCSEMS
			'RDG_CD'		=> $ridge_code,					# RDG_CD
			'LST_NO'		=> '0',							# Default: 0
			'PAR_TXT'    	=> $row_user_id,				# PAR_TXT: Row User Id
			'PAR_NUM'		=> '1',							# Default: 0
			'CRT_DTS'		=> $g_cpu_dts,					# CRT_DTS: $g_cpu_dts
			'USR_ID_CRT'	=> $user_id,					# USR_ID_CRT: $_GET['usrId']
			'UPD_DTS' 		=> '0001-01-01 00:00:00',		# Default: 0001-01-01 00:00:00
			'USR_ID_UPD' 	=> ' ',							# Default: Blank
			'UPD_LEV'		=> '1'							# Default: 1
		);
		$w_rtn = db_insert("PAR_MST", $w_ins);
	    if ($w_rtn != 0) {
	            list($g_msg, $g_err_lv) = msg("err_Ins");
	            $g_msg = xpt_err_msg($g_msg, "PAR_MST", __LINE__);
	            return 4000;
	    }
	}

	return 0;
}

#==================================================================
# ¥â¡¼¥É£±
#==================================================================
function check_hrdata( $w_usr_ids, &$r_resp ){

	$r_resp = array();

	$w_usr_ids = implode(',', $w_usr_ids);

	$w_url = "http://pscsggpapp3.mscs.intra/ums-hr/api.php?users=". $w_usr_ids;

	// build query
	$data = http_build_query(
	        array(
               		'data' => $w_message
        	)
	);

	// prep header
	$optional_headers = null;
	$params = array('http' => array(
              'method' => 'POST',
       	      'content' => $data
        ));
	if ($optional_headers !== null) {
		$params['http']['header'] = $optional_headers;
	}

	// prepare parameter
	$ctx = stream_context_create($params);
	$fp = @fopen($w_url, 'rb', false, $ctx);
 	if (!$fp) {
		list($g_msg, $g_err_lv) = cs_xck_backfill_comm_msg("err_chk_hr_data");
       		$r_response = $g_msg;
		return 4000;
 	}
 	$r_response = @stream_get_contents($fp);
 	if ($r_response === false) {
		list($g_msg, $g_err_lv) = cs_xck_backfill_comm_msg("err_chk_hr_data");
       		$r_response = $g_msg;
		return 4000;
	}

	$r_resp = json_decode( $r_response, true );
	
	fclose($fp);

	return 0;
}
#==================================================================
# ¥Á¥§¥Ã¥¯½èÍý
#==================================================================
function check_input($w_mode)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;

	switch ($w_mode) {
		#------------------------------------------------------------------
		# Validation for Mode 1
		#------------------------------------------------------------------
		case 1:
			#------------------------------------------------------------------
			# Assign the values
			#------------------------------------------------------------------
			$gw_scr['s_dvsn_cd'] = strtoupper(trim($gw_scr['s_dvsn_cd']));
			$gw_scr['s_rdg_cd'] = strtoupper(trim($gw_scr['s_rdg_cd']));
			$gw_scr['s_hour_cd'] = strtoupper(trim($gw_scr['s_hour_cd']));
			#------------------------------------------------------------------
			# Display error
			#------------------------------------------------------------------
			# For empty values
			list($g_msg, $g_err_lv) = msg("err_Nec_Input");
			if($gw_scr['s_dvsn_cd'] == ""){
				$g_msg = xpt_err_msg($g_msg, itm("DivisionCode"), __LINE__);
				return 4000;
			}
			if($gw_scr['s_rdg_cd'] == ""){
				$g_msg = xpt_err_msg($g_msg, itm("Ridge"), __LINE__);
				return 4000;
			}
			if($gw_scr['s_hour_cd'] == ""){
				$g_msg = xpt_err_msg($g_msg, itm("HourCode"), __LINE__);
				return 4000;
			}
			# For illegal characters
			list($g_msg, $g_err_lv) = msg("err_Inp_Char");
			if(!check_eisu($gw_scr['s_dvsn_cd'])){
				$w_tg = get_tg(itm("DivisionCode"), $gw_scr['s_dvsn_cd']);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			if(!check_eisu($gw_scr['s_rdg_cd'])){
				$w_tg = get_tg(itm("Ridge"), $gw_scr['s_rdg_cd']);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			if(!check_eisu($gw_scr['s_hour_cd'])){
				$w_tg = get_tg(itm("HourCode"), $gw_scr['s_hour_cd']);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			# Check for tag
			list($g_msg, $g_err_lv) = msg("err_Inp_Tag");
			if(substr($gw_scr['s_dvsn_cd'], 0, 2) != "AB"){
				$g_msg = xpt_err_msg($g_msg, itm("DivisionCode"), __LINE__);
				return 4000;
			}
			if(substr($gw_scr['s_rdg_cd'], 0 ,2) != "RD"){
				$g_msg = xpt_err_msg($g_msg, itm("Ridge"), __LINE__);
				return 4000;
			}
		break;

		#------------------------------------------------------------------
		# Validation for Mode 2
		#------------------------------------------------------------------
		case 2:
			# Check Blank Text Box
			$blank_email_eqp = 1;
			$blank_email_tnm = 1;
			$blank_email_prn = 1;
			for($i=1; $i<=$gw_scr['s_inp_row']; $i++){
				# For email rows of EQP
				if($gw_scr['s_lst_eqp_id_'][$i] == ""){
					$blank_email_eqp++;
				}
				# For email rows of TNM
				if($gw_scr['s_lst_tnm_id_'][$i] == ""){
					$blank_email_tnm++;
				}
				# For email rows of PRN
				if($gw_scr['s_lst_prn_id_'][$i] == ""){
					$blank_email_prn++;
				}
			}
			$blank_eqp_email = $gw_scr['s_inp_row'] - ($blank_email_eqp-1);
			$blank_tnm_email = $gw_scr['s_inp_row'] - ($blank_email_tnm-1);
			$blank_prn_email = $gw_scr['s_inp_row'] - ($blank_email_prn-1);

			if($blank_eqp_email == 0 && $blank_tnm_email == 0 && $blank_prn_email == 0){
				list($g_msg, $g_err_lv) = msg("err_Nec_Input");
				$g_msg = xpt_err_msg($g_msg, itm("UserId"), __LINE__);
				return 4000;
			}

			# Get the list of data from unblank row text boxes
			for($i=1; $i<=$gw_scr['s_inp_row']; $i++){
				# For email rows of EQP
				if($gw_scr['s_lst_eqp_id_'][$i] != ""){
					$integer_email_eqp[] = $gw_scr['s_lst_eqp_id_'][$i];
				}
				# For email rows of TNM
				if($gw_scr['s_lst_tnm_id_'][$i] != ""){
					$integer_email_tnm[] = $gw_scr['s_lst_tnm_id_'][$i];
				}
				# For email rows of PRN
				if($gw_scr['s_lst_prn_id_'][$i] != ""){
					$integer_email_prn[] = $gw_scr['s_lst_prn_id_'][$i];
				}
			}

			# Check Not Integer Textbox Values
			$integer_email_merge = array_merge((array)$integer_email_eqp,(array)$integer_email_tnm,(array)$integer_email_prn);
			foreach($integer_email_merge as $val){
				if(is_numeric($val) == false){
					list($g_msg, $g_err_lv) = msg("err_Int_User_Id");
					$g_msg = xpt_err_msg($g_msg, itm("UserId"), __LINE__);
					return 4000;
				}
			}

			# Check Duplicate Textbox Values
			$integer_email_merge_count = array_count_values($integer_email_merge);
			foreach($integer_email_merge_count as $val){
				if($val > 1){
					list($g_msg, $g_err_lv) = msg("err_Dup_User_Id");
					$g_msg = xpt_err_msg($g_msg, itm("UserId"), __LINE__);
					return 4000;
				}
			}

			# Check email with employee master data
			$temp_arr = $integer_email_merge;
			$w_rtn = check_hrdata( $temp_arr, $r_response );
			if ( $w_rtn != 0 ) {
				list($g_msg, $g_err_lv) = msg("err_chk_hr_data");
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
			}
			if($r_response['status'] == "invalid"){ // valid or invalid
				foreach($r_response['invalid_ids'] as $value){
					$invalid_ids[] = $value;
				}
				$list_user_id = implode(',', $invalid_ids);
				list($g_msg, $g_err_lv) = msg("err_chk_Email_NotFound");
				$g_msg = xpt_err_msg($g_msg, $list_user_id, __LINE__);
				return 4000;
			} 
		break;
	}

	$g_msg    = "";
	$g_err_lv = "";

	return 0;
}
#=================================================
# ¥Ç¥Õ¥©¥ë¥ÈÃÍ¤Î¥»¥Ã¥È
#=================================================
function input_default() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$gw_scr['s_inp_row'] = DEFAULT_INPUT_ROW;

	return 0;
}

function print_datecodeinfo($w_fg_prd, $w_lot_no_str, $w_to220,$w_slice_no, $w_date, $w_digit, $w_secno) {
		echo "Product : " . $w_fg_prd . "<BR>";
                echo "Diffusion : ". $w_lot_no_str . "<BR>";
		echo "Slice no : ". $w_slice_no . "<BR>";
                echo "New Datecode Logic : " . $w_to220 . "<BR>";
		echo "Date : ". $w_date . "<BR>";
                echo "Datecode digit : " . $w_digit ."<BR>";
                echo "Datecode : " . $w_secno . "<BR>";
		
		echo "....................................<BR><BR>";

}

#==================================================================
# É½¼¨¹àÌÜ½é´ü²½½èÍý
#==================================================================
function set_init($w_mode)
{
	global $gw_scr;
	# Clear
	if($w_mode == 1){
		# Clear filtering
		$gw_scr['s_dvsn_cd'] = "";
		$gw_scr['s_rdg_cd'] = "";
		$gw_scr['s_hour_cd'] = "";
		$gw_scr['s_rdg_nm'] = "";

		# Clear rows
		for($i=1; $i<=$gw_scr['s_inp_row']; $i++){
			$gw_scr['s_lst_eqp_id_'][$i] = "";
			$gw_scr['s_lst_tnm_id_'][$i] = "";
			$gw_scr['s_lst_prn_id_'][$i] = "";
		}

		$gw_scr['s_inp_row'] = DEFAULT_INPUT_ROW;
	}
	return 0;
}
#==================================================================
# ²èÌÌÉ½¼¨Ä¾Á°½èÍý
#==================================================================
function scr_setting()
{
	global $gw_scr;
	global $g_mode;

	return;
}
#==================================================================
# £±¤«¤é¤ÎÇÛÎó¤Ë¿¶¤êÄ¾¤¹
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
# ÇÛÎó¤ò¥·¥ê¥¢¥é¥¤¥º
#==================================================================
function userialize($w_arr)
{
	return str_replace("\"", "~", serialize($w_arr));
}
#==================================================================
# ¥·¥ê¥¢¥é¥¤¥º²½¤·¤¿Ê¸»úÎó¤òÇÛÎó¤ËÉüµ¢
#==================================================================
function uunserialize($w_serial)
{
	return unserialize(str_replace("~", "\"", $w_serial));
}
#==================================================================
# SQL¤Î¥ï¥¤¥ë¥É¥«¡¼¥É¤¬´Þ¤Þ¤ì¤Æ¤¤¤¿¾ì¹ç¡¢¥¨¥¹¥±¡¼¥×Ê¸»ú($)¤òÉÕÍ¿¤·¤ÆÊÖ¤¹
#==================================================================
function str_escape($str){
	return str_replace(array('%', '_', '*'), array('$%', '$_', ''), $str);
}
#==================================================================
# ¥¨¥é¡¼»þ¤ÎÂÐ¾ÝÊ¸»úÎóÀ¸À®
#==================================================================
function get_tg()
{
	$w_arr = func_get_args();
	return implode("/", $w_arr);
}
#==================================================================
# Lang¥Ç¡¼¥¿¼èÆÀ´Ø¿ô´ÊÎ¬²½
#==================================================================
function itm($var)
{
	return PS00S01001960_item($var);
}
function msg($var)
{
	return PS00S01001960_msg($var);
}
#******************************************************************
#******************************************************************
#******************************************************************
#******************************************************************
#******************************************************************
#
# MAIN½èÍý³«»Ï
#
#******************************************************************
#==================================================================
# DBÀÜÂ³
#==================================================================
$w_rtn = xdb_op_conndb();
if ($w_rtn != 0) {
	$g_err_lv = 0;
	$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	return;
}
#==================================================================
# ¥»¥Ã¥·¥ç¥ó
#==================================================================
if($gw_scr['s_rtn_flg']){
	get_session_convert();
}
# ¥»¥Ã¥·¥ç¥óÆâ¤Î¥â¡¼¥É¤ò¼èÆÀ
get_session_mode();
#==================================================================
# Ç§¾Ú
#==================================================================
# ºÆÇ§¾Ú(Í×Scrµ­½Ò session´Þ¤à ¥»¥Ã¥·¥ç¥ó¼èÆÀ¸å¤Ëµ­½Ò)
$refe_flg=1;
require_once (getenv("GPRISM_HOME") . "/renzheng.php");
$bak_s_renzheng_t = $gw_scr['s_renzheng_t'];	# °ì»þÂàÈò
$bak_s_renzheng   = $gw_scr['s_renzheng'];		# °ì»þÂàÈò
#==================================================================
# ¥â¡¼¥É¤´¤È¤Î½èÍý
#==================================================================
# ´Ø¿ôÌ¾ÄêµÁ

$w_func = "main_md" . $g_mode;
if(function_exists($w_func)){
	$w_func();
} else {
	main_init();
}

$gw_scr['s_renzheng']   = $bak_s_renzheng;		# Ç§¾ÚÍÑ
$gw_scr['s_renzheng_t'] = $bak_s_renzheng_t;	# Ç§¾ÚÍÑ

scr_setting();
get_screen(1, null, 1);

#==================================================================
# ½èÍý½ªÎ»
#==================================================================
xdb_op_closedb();
?>

