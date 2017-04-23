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
# Main Mode 1
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
and usr_id_crt='MASEMC30049' 
and PAR_CLS_CD='P000S016'
and DVSN_CD = '{$division_code}'
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
	}

	return 0;
}

#==================================================================
# ¥â¡¼¥É£± ³ÎÇ§¥Ü¥¿¥ó²¡²¼½èÍý
#==================================================================
function main_md2_chk()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# ÆþÎÏ¥Á¥§¥Ã¥¯
	#------------------------------------------------------------------
	$w_rtn = check_input(1);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# ¥æ¡¼¥¶Ì¾¾Î¼èÆÀ
	#------------------------------------------------------------------
	$w_rtn = cs_xgn_man($gw_scr['s_usr_id'], $w_usr_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
		return 4000;
	}

        #------------------------------------------------------------------
        # get sap rcv data
        #-----------------------------------------------------------------
        $w_rtn = cs_xgt_sap_rcv(trim($gw_scr['s_sap_lot_id']),$w_sap_data);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_sap_lot_id'], __LINE__);
                return 4000;
        }

	#------------------------------------------------------------------
	# ¥×¥í¥À¥¯¥È¥³¡¼¥É¼èÆÀ
	#------------------------------------------------------------------
	### ¥Á¥Ã¥×ÉÊÌ¾
	$w_rtn = xgc_prd($w_sap_data['ITEM_CD'], $w_prd_cd, $w_bnd_dvs);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_prd_nm'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ¥é¥ó¥¯¥Ñ¥¿¡¼¥ó¥Á¥§¥Ã¥¯
	#------------------------------------------------------------------
	$w_rtn = xck_rnk($gw_scr['s_rnk'], $w_prd_cd, constant("GR_IT_AIMS"));
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_rnk'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ¥í¥Ã¥È¶èÊ¬Ì¾¼èÆÀ
	#------------------------------------------------------------------
	$w_rtn = xgn_cd($gw_scr['s_lot_typ_cd'], 1, $w_lot_typ_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_typ_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ¥í¥Ã¥È¼±ÊÌÌ¾¼èÆÀ
	#------------------------------------------------------------------
	$w_rtn = xgn_cd($gw_scr['s_lot_dsc_cd'], 1, $w_lot_dsc_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_dsc_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ¥í¥Ã¥ÈÉ¼½ÐÎÏÀè¼èÆÀ
	#------------------------------------------------------------------
	$w_rtn = xgt_lp2_cd($gw_scr['s_lp_cd'], $w_dmy, $w_dmy, $w_lp_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lp_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ¥×¥í¥À¥¯¥È¾ðÊó¤Î¼èÆÀ
	#------------------------------------------------------------------
	$w_rtn = get_prdinf($w_prd_cd, "", $w_prd_inf);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# ¥í¥Ã¥È¶èÊ¬ÊÑ¹¹ÉÔ²Ä¥Á¥§¥Ã¥¯
	#------------------------------------------------------------------
	if($w_prd_inf['TMP_FLG'] == "0"){
		if($gw_scr['s_lot_typ_cd'] != constant("INI_CCCD")){
			list($g_msg, $g_err_lv) = msg("err_PrdSmpl");
			$w_tg = get_tg(itm("LotClsCd"), $gw_scr['s_lot_typ_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
	}

	#------------------------------------------------------------------
	# QA¥á¥ó¥Ð¡¼¤«¥Á¥§¥Ã¥¯
	#------------------------------------------------------------------
	$w_rtn = chk_qa_member($gw_scr['s_usr_id'], $w_qa_flg);
	if($w_rtn != 0){
		return 4000;
	}
	### QA¥á¥ó¥Ð¡¼°Ê³°¤Î¾ì¹ç
	if($w_qa_flg == 0){
		if($gw_scr['s_lot_typ_cd'] != constant("INI_CCCD")){
			list($g_msg, $g_err_lv) = msg("err_NotQASmpl");
			$w_tg = get_tg(itm("LotClsCd"), $gw_scr['s_lot_typ_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($gw_scr['s_lot_dsc_cd'] != constant("INI_CDCD")){
			list($g_msg, $g_err_lv) = msg("err_NotQASmpl");
			$w_tg = get_tg(itm("LotDecCd"), $gw_scr['s_lot_dsc_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
	}

	#------------------------------------------------------------------
	# ¥í¥Ã¥È¶èÊ¬/¼±ÊÌ¥Á¥§¥Ã¥¯
	#------------------------------------------------------------------
	if($gw_scr['s_lot_typ_cd'] != constant("INI_CCCD") &&
	   $gw_scr['s_lot_dsc_cd'] == constant("INI_CDCD")){
		list($g_msg, $g_err_lv) = msg("err_DifLotClsDec");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}
	if($gw_scr['s_lot_typ_cd'] == constant("INI_CCCD") &&
	   $gw_scr['s_lot_dsc_cd'] != constant("INI_CDCD")){
		list($g_msg, $g_err_lv) = msg("err_DifLotClsDec");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ´ÉÍý¥Õ¥é¥°
	#------------------------------------------------------------------
	$w_mng_flg = 1;
	if($gw_scr['s_lot_typ_cd'] == constant("INI_CCCD") &&
	   $gw_scr['s_lot_dsc_cd'] == constant("INI_CDCD")){
		$w_mng_flg = 0;
	}

	#------------------------------------------------------------------
	# ·ÐÏ©¾ðÊó¤Î¼èÆÀ
	#------------------------------------------------------------------
	$w_rtn = get_rt_info_lsi($gw_scr['s_prd_cd_fin'], $w_prd_cd, constant("D6_BGA_INF"),
						 $w_rt_cd_cairn, $w_prc_cd_cairn, $w_io_blc_cd_cairn, $w_stp_cd_cairn, $w_stp_no_cairn, $w_blc_cls_3_cairn, $w_prd_cd_cairn,
						 $w_rt_cd_mcp, $w_prc_cd_mcp, $w_io_blc_cd_mcp, $w_stp_cd_mcp, $w_stp_no_mcp, $w_blc_cls_3, $w_prd_cd_mcp);
	if($w_rtn != 0){
		return 4000;
	}

	#Process Staff control
	$w_rtn = cs_xck_staff_ctrl($w_stp_cd_cairn, $gw_scr['s_usr_id'], $w_allow);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
		return 4000;
	}else{
		if(!$w_allow){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
			return 4000;
		}
	}
	
	#------------------------------------------------------------------
	# µö²Ä¹©Äø¥Á¥§¥Ã¥¯
	#------------------------------------------------------------------
	# ¥¹¥Æ¥Ã¥×Ê¬Îà¼èÆÀ
	$w_rtn = xgt_stp_cls($w_stp_cd_cairn, $w_stpcls2, $dmy);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $w_stp_cd_cairn, __LINE__);
		return 4000;
	}
	if(!in_array(trim($w_stpcls2), unserialize(constant('E9_ALLOWED')))){
		list($g_msg, $g_err_lv) = msg("err_Disabled");
		$g_msg = xpt_err_msg($g_msg, trim($w_stpcls2), __LINE__);
		return 4000;
	}
	
	#------------------------------------------------------------------
	# ScrÊÑ¿ô¤ØÅ¸³«
	#------------------------------------------------------------------
	$gw_scr['s_usr_nm']     = trim($w_usr_nm);
	$gw_scr['s_prd_cd']     = trim($w_prd_cd);
	$gw_scr['s_prd_nm']     = $w_sap_data['ITEM_CD'];
#	}
	$gw_scr['s_rnk']        = strtoupper(trim($gw_scr['s_rnk']));
	$gw_scr['s_dif_lot_no'] = $w_sap_data['DIF_LOT_NO'];            # DIF_LOT_NO
	$gw_scr['s_mt_lot_id']  = $w_sap_data['SAP_LOT_NO'];           # SAP_LOT_NO
	$gw_scr['s_sl_inf']     = $w_sap_data['SL_INF'];                # SL_INF
	$gw_scr['s_sl_qty']     = $w_sap_data['SL_QTY'];                # SL_QTY
	$gw_scr['s_chp_qty']    = $w_sap_data['QTY'];                   # QTY
	$gw_scr['s_mfg_dte']    = $w_sap_data['MFG_DTS'];               # MFG_DTS
	$gw_scr['s_exp_dte']    = $w_sap_data['EXP_DTS'];               # EXP_DTS


	$gw_scr['s_rt_cd_cairn']      = trim($w_rt_cd_cairn);
	$gw_scr['s_prc_cd_cairn']     = trim($w_prc_cd_cairn);
	$gw_scr['s_prd_cd_cairn']     = trim($w_prd_cd_cairn);
	$gw_scr['s_io_blc_cd_cairn']  = trim($w_io_blc_cd_cairn);
	$gw_scr['s_stp_cd_cairn']     = trim($w_stp_cd_cairn);
	$gw_scr['s_stp_no_cairn']     = trim($w_stp_no_cairn);
	
	$gw_scr['s_rt_cd_mcp']      = trim($w_rt_cd_mcp);
	$gw_scr['s_prc_cd_mcp']     = trim($w_prc_cd_mcp);
	$gw_scr['s_prd_cd_mcp']     = trim($w_prd_cd_mcp);
	$gw_scr['s_io_blc_cd_mcp']  = trim($w_io_blc_cd_mcp);
	$gw_scr['s_stp_cd_mcp']     = trim($w_stp_cd_mcp);
	$gw_scr['s_stp_no_mcp']     = trim($w_stp_no_mcp);
	
	
	
	$gw_scr['s_mng_flg']    = trim($w_mng_flg);


	list($g_msg, $g_err_lv) = msg("guid_Execute");
	$g_msg = xpt_err_msg($g_msg, "", "");

	scr_mode_chg(3);

	return 0;
}

#==================================================================
# ¥â¡¼¥É£± ³ÎÇ§¥Ü¥¿¥ó²¡²¼½èÍý
#==================================================================
function main_md1_chk()
{
        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        #------------------------------------------------------------------
        # ÆþÎÏ¥Á¥§¥Ã¥¯
        #------------------------------------------------------------------
        $w_rtn = check_input(3);
        if($w_rtn != 0){
                return 4000;
        }

        #------------------------------------------------------------------
        # Ã¼Ëö¥Î¡¼¥É¤«¤é¥×¥ê¥ó¥¿¾ðÊó¤Î³ÍÆÀ
        #------------------------------------------------------------------
        # ¥í¥Ã¥ÈÉ¼½ÐÎÏÀè
	$w_rtn = xgt_lp2(2, $w_lp_cd, $w_lp_nm, $w_lp_id, $w_lp_type);
	if($w_rtn != 0){
       		$g_err_lv = 0;
       		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	}
        $gw_scr['s_lp_cd']      = trim($w_lp_cd);
        $gw_scr['s_lp_nm']      = trim($w_lp_nm);


        #------------------------------------------------------------------
        # ¥æ¡¼¥¶Ì¾¾Î¼èÆÀ
        #------------------------------------------------------------------
        $w_rtn = cs_xgn_man($gw_scr['s_usr_id'], $w_usr_nm);
        if($w_rtn != 0){
            $g_err_lv = 0;
            $g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
            return 4000;
        }

	#------------------------------------------------------------------
	# get sap rcv data
	#------------------------------------------------------------------
	$w_rtn = cs_xgt_sap_rcv(trim($gw_scr['s_sap_lot_id']),$w_sap_data);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_sap_lot_id'], __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # Validate if use or not
        #------------------------------------------------------------------		
        if($w_sap_data['RCV_FLG'] != "0"){
                        list($g_msg, $g_err_lv) = msg("err_SapUse");
                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_sap_lot_id'], __LINE__);
                        return 4000;
        }
	
        #------------------------------------------------------------------
        # ¥×¥í¥À¥¯¥È¥³¡¼¥É¼èÆÀ
        #------------------------------------------------------------------
        ### ¥Á¥Ã¥×ÉÊÌ¾
        $w_rtn = xgc_prd($w_sap_data['ITEM_CD'],$w_prd_cd, $w_bnd_dvs);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_prd_nm'], __LINE__);
                return 4000;
        }
        ### ÁÈÎ©´°À®ÉÊÌ¾
        $w_rtn = get_finnm($w_prd_cd,$w_prd_cd_fin);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_prd_nm_fin'], __LINE__);
                return 4000;
        }
	$w_fin_flg =  count($w_prd_cd_fin);
	if($w_fin_flg >= 1)
	{
		$w_prd_cd_fin_cd = $w_prd_cd_fin[0]['PRD_CD'];
		$w_prd_cd_fin_nm = $w_prd_cd_fin[0]['PRD_NM'];
	}
        #------------------------------------------------------------------
        # ¥é¥ó¥¯¥Ñ¥¿¡¼¥ó¥Á¥§¥Ã¥¯
        #------------------------------------------------------------------
        $w_rtn = xck_rnk($gw_scr['s_rnk'], $w_prd_cd, constant("GR_IT_AIMS"));
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_rnk'], __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # ¥í¥Ã¥È¶èÊ¬Ì¾¼èÆÀ
        #------------------------------------------------------------------
        $w_rtn = xgn_cd($gw_scr['s_lot_typ_cd'], 1, $w_lot_typ_nm);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_typ_cd'], __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # ¥í¥Ã¥È¼±ÊÌÌ¾¼èÆÀ
        #------------------------------------------------------------------
        $w_rtn = xgn_cd($gw_scr['s_lot_dsc_cd'], 1, $w_lot_dsc_nm);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_dsc_cd'], __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # ¥×¥í¥À¥¯¥È¾ðÊó¤Î¼èÆÀ
        #------------------------------------------------------------------
        $w_rtn = get_prdinf($w_prd_cd, "", $w_prd_inf);
        if($w_rtn != 0){
                return 4000;
        }

        #------------------------------------------------------------------
        # ¥í¥Ã¥È¶èÊ¬ÊÑ¹¹ÉÔ²Ä¥Á¥§¥Ã¥¯
        #------------------------------------------------------------------
        if($w_prd_inf['TMP_FLG'] == "0"){
                if($gw_scr['s_lot_typ_cd'] != constant("INI_CCCD")){
                        list($g_msg, $g_err_lv) = msg("err_PrdSmpl");
                        $w_tg = get_tg(itm("LotClsCd"), $gw_scr['s_lot_typ_cd']);
                        $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                        return 4000;
                }
        }
        #------------------------------------------------------------------
        # QA¥á¥ó¥Ð¡¼¤«¥Á¥§¥Ã¥¯
        #------------------------------------------------------------------
        $w_rtn = chk_qa_member($gw_scr['s_usr_id'], $w_qa_flg);
        if($w_rtn != 0){
                return 4000;
        }
        ### QA¥á¥ó¥Ð¡¼°Ê³°¤Î¾ì¹ç
        if($w_qa_flg == 0){
                if($gw_scr['s_lot_typ_cd'] != constant("INI_CCCD")){
                        list($g_msg, $g_err_lv) = msg("err_NotQASmpl");
                        $w_tg = get_tg(itm("LotClsCd"), $gw_scr['s_lot_typ_cd']);
                        $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                        return 4000;
                }
                if($gw_scr['s_lot_dsc_cd'] != constant("INI_CDCD")){
                        list($g_msg, $g_err_lv) = msg("err_NotQASmpl");
                        $w_tg = get_tg(itm("LotDecCd"), $gw_scr['s_lot_dsc_cd']);
                        $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                        return 4000;
                }
        }

        #------------------------------------------------------------------
        # ¥í¥Ã¥È¶èÊ¬/¼±ÊÌ¥Á¥§¥Ã¥¯
        #------------------------------------------------------------------
        if($gw_scr['s_lot_typ_cd'] != constant("INI_CCCD") &&
           $gw_scr['s_lot_dsc_cd'] == constant("INI_CDCD")){
                list($g_msg, $g_err_lv) = msg("err_DifLotClsDec");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }
        if($gw_scr['s_lot_typ_cd'] == constant("INI_CCCD") &&
           $gw_scr['s_lot_dsc_cd'] != constant("INI_CDCD")){
                list($g_msg, $g_err_lv) = msg("err_DifLotClsDec");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # ´ÉÍý¥Õ¥é¥°
        #------------------------------------------------------------------
        $w_mng_flg = 1;
        if($gw_scr['s_lot_typ_cd'] == constant("INI_CCCD") &&
           $gw_scr['s_lot_dsc_cd'] == constant("INI_CDCD")){
                $w_mng_flg = 0;
        }
        #------------------------------------------------------------------
        # ·ÐÏ©¾ðÊó¤Î¼èÆÀ
        #------------------------------------------------------------------
        $w_rtn = get_rt_info_lsi($w_prd_cd_fin_cd, $w_prd_cd, constant("D6_BGA_INF"),
                            $w_rt_cd_cairn, $w_prc_cd_cairn, $w_io_blc_cd_cairn, $w_stp_cd_cairn, $w_stp_no_cairn, $w_blc_cls_3_cairn, $w_prd_cd_cairn,
							$w_rt_cd_mcp, $w_prc_cd_mcp, $w_io_blc_cd_mcp, $w_stp_cd_mcp, $w_stp_no_mcp, $w_blc_cls_3, $w_prd_cd_mcp);
        if($w_rtn != 0){
                return 4000;
        }

        #Process Staff control
        $w_rtn = cs_xck_staff_ctrl($w_stp_cd_cairn, $gw_scr['s_usr_id'], $w_allow);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
                return 4000;
        }else{
                if(!$w_allow){
                        $g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
                        return 4000;
                }
        }

        #------------------------------------------------------------------
        # µö²Ä¹©Äø¥Á¥§¥Ã¥¯
        #------------------------------------------------------------------
        # ¥¹¥Æ¥Ã¥×Ê¬Îà¼èÆÀ
        $w_rtn = xgt_stp_cls($w_stp_cd_cairn, $w_stpcls2, $dmy);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $w_stp_cd_cairn, __LINE__);
                return 4000;
        }
        if(!in_array(trim($w_stpcls2), unserialize(constant('E9_ALLOWED')))){
                list($g_msg, $g_err_lv) = msg("err_Disabled");
                $g_msg = xpt_err_msg($g_msg, trim($w_stpcls2), __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # ScrÊÑ¿ô¤ØÅ¸³«
        #------------------------------------------------------------------
        $gw_scr['s_usr_nm']     = trim($w_usr_nm);
        $gw_scr['s_prd_cd']     = trim($w_prd_cd);
		$gw_scr['s_prd_nm']     = $w_sap_data['ITEM_CD'];
		if($w_fin_flg == 1) {
				$gw_scr['s_prd_cd_fin'] = trim($w_prd_cd_fin_cd);
			$gw_scr['s_prd_nm_fin'] = trim($w_prd_cd_fin_nm);
		}
        $gw_scr['s_rnk']        = strtoupper(trim($gw_scr['s_rnk']));
        $gw_scr['s_dif_lot_no'] = $w_sap_data['DIF_LOT_NO']; 		# DIF_LOT_NO
        $gw_scr['s_mt_lot_id']  = $w_sap_data['SAP_LOT_NO'];  		# SAP_LOT_NO
        $gw_scr['s_sl_inf']     = $w_sap_data['SL_INF'];		# SL_INF	
        $gw_scr['s_sl_qty']     = $w_sap_data['SL_QTY'];		# SL_QTY
        $gw_scr['s_chp_qty']    = $w_sap_data['QTY'];			# QTY
        $gw_scr['s_mfg_dte']    = $w_sap_data['MFG_DTS'];		# MFG_DTS
        $gw_scr['s_exp_dte']    = $w_sap_data['EXP_DTS'];		# EXP_DTS

        $gw_scr['s_lot_typ_nm'] = trim($w_lot_typ_nm);
        $gw_scr['s_lot_dsc_nm'] = trim($w_lot_dsc_nm);
        $gw_scr['s_lp_nm']      = $gw_scr['s_lp_nm'];

        $gw_scr['s_rt_cd_cairn']      = trim($w_rt_cd_cairn);
		$gw_scr['s_prc_cd_cairn']     = trim($w_prc_cd_cairn);
		$gw_scr['s_prd_cd_cairn']     = trim($w_prd_cd_cairn);
		$gw_scr['s_io_blc_cd_cairn']  = trim($w_io_blc_cd_cairn);
		$gw_scr['s_stp_cd_cairn']     = trim($w_stp_cd_cairn);
		$gw_scr['s_stp_no_cairn']     = trim($w_stp_no_cairn);
		
		$gw_scr['s_rt_cd_mcp']      = trim($w_rt_cd_mcp);
		$gw_scr['s_prc_cd_mcp']     = trim($w_prc_cd_mcp);
		$gw_scr['s_prd_cd_mcp']     = trim($w_prd_cd_mcp);
		$gw_scr['s_io_blc_cd_mcp']  = trim($w_io_blc_cd_mcp);
		$gw_scr['s_stp_cd_mcp']     = trim($w_stp_cd_mcp);
		$gw_scr['s_stp_no_mcp']     = trim($w_stp_no_mcp);
		
        $gw_scr['s_mng_flg']    = trim($w_mng_flg);
		$gw_scr['s_fin_flg']    = $w_fin_flg;

        scr_mode_chg(2);

        return 0;
}

###################################################################
#####                                                         #####
##### ¥â¡¼¥É£²                                                #####
#####                                                         #####
###################################################################
#==================================================================
# ¥â¡¼¥É£²
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
		scr_mode_chg(2);
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

	#------------------------------------------------------------------
	# ¥È¥é¥ó¥¶¥¯¥·¥ç¥ó³«»Ï
	#------------------------------------------------------------------
	db_begin();

	#------------------------------------------------------------------
	# ¼Â¹Ô½èÍý
	#------------------------------------------------------------------
	$w_rtn = main_exe();
	if($w_rtn != 0){
		db_rollback();
		return 4000;
	}

	#------------------------------------------------------------------
	# ¥³¥ß¥Ã¥È
	#------------------------------------------------------------------
	db_commit();

	if($gw_scr['s_iohd_flg'] != "1"){
		#------------------------------------------------------------------
		# ¥í¥Ã¥ÈÉ¼°õºþ
		#------------------------------------------------------------------
		$w_rtn = cs_xpt_wfrlsi_label($gw_scr['s_new_lot_id'], $gw_scr['s_lp_cd'],1);
		if($w_rtn != 0){
			$gw_scr['s_prnt_msg'] = xpt_err_msg($g_msg, $gw_scr['s_new_lot_id'], __LINE__);
			$gw_scr['s_prnt_lv']  = 0;
		}

		if($g_msg == ""){
			list($gw_scr['s_prnt_msg'], $gw_scr['s_prnt_lv']) = msg("end_Print");
			$gw_scr['s_prnt_msg'] = xpt_err_msg($gw_scr['s_prnt_msg'], $gw_scr['s_new_lot_id'], "");
		}
		$g_msg    = "";
		$g_err_lv = "";
	}

	if($g_msg == ""){
		list($g_msg, $g_err_lv) = msg("end_Execute");
		$g_msg = xpt_err_msg($g_msg, "", "");
	}

	scr_mode_chg(4);

	return 0;
}

#==================================================================
# ¼Â¹Ô½èÍý
#==================================================================
function main_exe()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;

	#------------------------------------------------------------------
	# ½é´ü²½
	#------------------------------------------------------------------
	$gw_scr['s_iohd_flg']   = "";
	$gw_scr['s_new_lot_id'] = "";

	#------------------------------------------------------------------
	# LOT_NUM_TBL ÇÓÂ¾É½¥í¥Ã¥¯
	#------------------------------------------------------------------
	$w_rtn = db_lock("LOT_NUM_TBL");
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ÀßÄê
	#------------------------------------------------------------------
	$w_rnk_ptn = $gw_scr['s_rnk'];
	if ($w_rnk_ptn == "") {
		$w_rnk_ptn = ' ';
	}

	#------------------------------------------------------------------
	# PDCRÍÑ¤Î¥Ç¡¼¥¿ÀßÄê
	#------------------------------------------------------------------
	$w_bas = array();
	$w_bas['lot_id_rec']     = $gw_scr['s_mt_lot_id'];
	$w_bas['lot_id_dif_str'] = ' ';
	$w_bas['lot_no_str']     = $gw_scr['s_dif_lot_no'];
	$w_bas['lot_no']         = $gw_scr['s_dif_lot_no'];
	$w_bas['lot_st_dvs']     = 'PD';
	$w_bas['lot_st_dvs_b']   = 'WT';
	$w_bas['lot_dvs_fin']    = '00';
	$w_bas['prd_cd']         = $gw_scr['s_prd_cd'];
	$w_bas['rt_cd']          = $gw_scr['s_rt_cd_mcp'];
	$w_bas['prc_cd']         = $gw_scr['s_prc_cd_mcp'];
	$w_bas['io_blc_cd']      = $gw_scr['s_io_blc_cd_mcp'];
	$w_bas['pkt_cd']         = ' ';
	$w_bas['sl_qty']         = $gw_scr['s_sl_qty'];
	$w_bas['chp_qty']        = $gw_scr['s_chp_qty'];
	$w_bas['str_pln_dts']    = $g_low_dts;
	$w_bas['secret_no']      = ' ';
	$w_bas['prio']           = '9999';
	$w_bas['lot_typ_cd']     = $gw_scr['s_lot_typ_cd'];
	$w_bas['lot_dsc_cd']     = $gw_scr['s_lot_dsc_cd'];
	$w_bas['bln_flg']        = '0';
	$w_bas['plt_dvs_cd']     = 'CBSEM00';
	$w_bas['mng_flg']        = $gw_scr['s_mng_flg'];
	$w_bas['bu_cd_ast']      = ' ';
	$w_bas['bu_cd_cns']      = ' ';
	$w_bas['lf_qty']         = 0;
	$w_bas['rnk_ptn']        = $w_rnk_ptn;
	$w_bas['shp_fct_cd']     = ' ';
	$w_bas['usr_id']         = $gw_scr['s_usr_id'];
	$w_bas['cmt']            = '';

	#------------------------------------------------------------------
	# ¥í¥Ã¥ÈÀ¸À®(£Ð£Ä£Ã£Ò)
	#------------------------------------------------------------------
	$w_rtn = pdcr($w_bas, $w_new_lot_id, $w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# Â¾ÉôÌç¼õ¤±(£É£Ï£Ò£Ö)
	#------------------------------------------------------------------
	$w_rtn = iorv($w_new_lot_id, $w_bas['usr_id'], $w_lot_bas['UPD_LEV'],
				  constant("AM_PARTNER"), $w_bas['cmt'], $w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	if($gw_scr['s_rt_cd_mcp'] != $gw_scr['s_rt_cd_cairn']){
		
		
		$w_rtn = iopc_st_check($w_lot_bas['LOT_ST_DVS']);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}

		$w_rtn = iopc($w_lot_bas['LOT_ID'], $gw_scr['s_usr_id'],
					  $w_lot_bas['UPD_LEV'], $gw_scr['s_prd_cd_cairn'],$gw_scr['s_rt_cd_cairn'], $w_lot_bas['RNK_PTN'],
					  $w_cmt, $w_lot_bas);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
		
		/* rtcg is within iopc */
		/*
		$w_rtn = rtcg_st_check($gw_scr['s_rt_cd_cairn'], $w_lot_bas);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return $w_rtn;
		}

		$w_rtn = rtcg(
					$w_lot_bas['LOT_ID'],
					$gw_scr['s_usr_id'],
					$w_lot_bas['UPD_LEV'],
					"",
					$gw_scr['s_rt_cd_cairn'],
					$w_lot_bas
				);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return $w_rtn;
		}
		*/
		
		#if(trim($w_lot_bas['PRD_CD']) != $gw_scr['s_prd_cd_cairn']){
		#	$w_rtn = main_verb_iopc($gw_scr['s_usr_id'],
		#						$gw_scr['s_prd_cd_cairn'],
		#						$w_lot_bas['RNK_PTN'],
		#						"",
		#						$w_lot_bas);
		#}
	}
	
	
	#------------------------------------------------------------------
	# ¥í¥Ã¥È¾ðÊó¥Æ¡¼¥Ö¥ë¤ÎÅÐÏ¿
	#------------------------------------------------------------------
	### ¥ê¥ó¥°¿ôÎÌ(Áí¿ô)
	if (trim($gw_scr['s_sl_inf']) != "") {
		$w_rtn = ins_lot_inf_tbl($gw_scr['s_usr_id'],
								 $w_new_lot_id,
								 constant("CE_LTINF"),
								 constant("CT_SLCINF"),
								 1,
								 $gw_scr['s_sl_inf'],
								 "");
		if($w_rtn != 0){
			return 4000;
		}
	}

        ### ¥ê¥ó¥°¿ôÎÌ(Áí¿ô)
        if ((trim($gw_scr['s_exp_dte']) != "") && (trim($gw_scr['s_exp_dte']) != constant('DT_BLANK'))) {
		##changed 26-07-2013
		$gw_scr['s_exp_dte'] = substr($gw_scr['s_exp_dte'], 0, -9);
		$gw_scr['s_exp_dte'] = $gw_scr['s_exp_dte']. " 23:59:59"; ##changed 26-07-2013
                $w_rtn = ins_lot_inf_tbl($gw_scr['s_usr_id'],
                                                                 $w_new_lot_id,
                                                                 constant("CE_LTINF"),
                                                                 constant("CT_EXPDTE"),
                                                                 1,
                                                                 $gw_scr['s_exp_dte'],
								 "");
                if($w_rtn != 0){
                        return 4000;
                }
        }

        ### ¥ê¥ó¥°¿ôÎÌ(Áí¿ô)
        if ((trim($gw_scr['s_mfg_dte']) != "") && (trim($gw_scr['s_mfg_dte']) != constant('DT_BLANK'))) {
                $w_rtn = ins_lot_inf_tbl($gw_scr['s_usr_id'],
                                                                 $w_new_lot_id,
                                                                 constant("CE_LTINF"),
                                                                 constant("CT_MFGDTE"),
                                                                 1,
                                                                 $gw_scr['s_mfg_dte'],
								 "");
                if($w_rtn != 0){
                        return 4000;
                }
        }


	#------------------------------------------------------------------
	# ¼¡¹©Äø¾ðÊó¤Î¼èÆÀ
	#------------------------------------------------------------------
	$w_rtn = get_next_process(trim($w_lot_bas['RT_CD']),
							  trim($w_lot_bas['PRD_CD']),
							  trim($w_lot_bas['STP_CD']),
							  $w_next_dat);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# Ãå¼ê
	#------------------------------------------------------------------
	$w_rtn = main_ioin_verb($gw_scr['s_usr_id'], "", "", $w_lot_bas);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# ´°Î»
	#------------------------------------------------------------------
	$w_arr_ctgdvs  = array();
	$w_arr_ctgcd   = array();
	$w_arr_ctgqty  = array();
	$w_arr_ctgtxt  = array();
	$w_arr_ctgslid = array();
	$w_rtn = main_verb_ioot($gw_scr['s_usr_id'],
							$w_arr_ctgdvs,
							$w_arr_ctgcd,
							$w_arr_ctgqty,
							$w_arr_ctgtxt,
							$w_arr_ctgslid,
							$w_lot_bas['SL_QTY'],
							$w_lot_bas['CHP_QTY'],
							$w_lot_bas['LF_QTY'],
							trim(strtoupper($gw_scr['s_lot_rmks'])),
							$w_lot_bas);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# ¼¡¹©ÄøÅÏ¤·
	#------------------------------------------------------------------
	$w_rtn = main_verb_iomv($gw_scr['s_usr_id'], "", $w_lot_bas);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# sleep 1 sec
	#------------------------------------------------------------------
	sleep(1);
	xpt_1sec_dts();

	
	
	
	#------------------------------------------------------------------
	# ¼õ¤±¹©Äø¤ÎÉÊÌ¾¤È¼¡¥¹¥Æ¥Ã¥×¤ÎÉÊÌ¾¤¬°ã¤¦¾ì¹ç
	#------------------------------------------------------------------
	if(trim($w_lot_bas['PRD_CD']) != $w_next_dat['PRD_CD']){
		#------------------------------------------------------------------
		# ÉÊ¼ïÊÑ¹¹
		#------------------------------------------------------------------
		$w_rtn = main_verb_iopc($gw_scr['s_usr_id'],
								$w_next_dat['PRD_CD'],
								$w_lot_bas['RNK_PTN'],
								"",
								$w_lot_bas);
		if($w_rtn != 0){
			return 4000;
		}
	}

	#------------------------------------------------------------------
	# Í½Ìó¥Û¡¼¥ë¥É¸¡º÷/¼Â¹Ô
	#------------------------------------------------------------------
	$w_rtn = cs_xexc_hold_rsv($gw_scr['s_usr_id'], $w_lot_bas, $w_hold_exc_flg,
							  $w_set_day, $w_rsn, $w_tel);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}
	### ¥Û¡¼¥ë¥É»þ¤Î¥á¥Ã¥»¡¼¥¸
	if($w_hold_exc_flg == 1){
		list($w_hdmsg, $w_hdlv) = msg("End_Rsv_Hold");
		$w_hdmsg = xpt_err_msg($w_hdmsg, trim($w_lot_bas['LOT_ID']), "");
		$w_hdmsg .= "<br>";
		$w_hdmsg .= itm("RsvHoldInfo");
		$w_hdmsg = sprintf($w_hdmsg, $w_rsn, $w_tel, $w_set_day);

		$g_err_lv = $w_hdlv;
		$g_msg    = $w_hdmsg;

		$gw_scr['s_iohd_flg'] = "1";
	}

	$gw_scr['s_new_lot_id']      = $w_new_lot_id;
	

        #------------------------------------------------------------------
        # Update SAP data
        #------------------------------------------------------------------
        $w_rtn = cs_xgt_sap_rcv_rcv($gw_scr['s_sap_lot_id'] ,$gw_scr['s_prd_nm']);
        if($w_rtn != 0){
                return 4000;
        }

	$w_rtn = cs_xgt_po_data( $gw_scr['s_usr_id'], $w_new_lot_id );
		if ( $w_rtn != 0 ) {
		return 4000;
	}

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
		break;

		#------------------------------------------------------------------
		# ¥â¡¼¥É£²
		#------------------------------------------------------------------
		// case 2:

		// break;
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
	// $gw_scr['s_h_inp_row'] = DEFAULT_INPUT_ROW;
	// $gw_scr['s_h_pack_maxcol'] = DEFAULT_PACK_MAXCOL;

	// $gw_scr['s_inp_row'] = $gw_scr['s_h_inp_row'];
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
		for($i=1; $i<$gw_scr['s_inp_row']; $i++){
			$gw_scr['s_lst_eqp_id_'][$i] = "";
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

