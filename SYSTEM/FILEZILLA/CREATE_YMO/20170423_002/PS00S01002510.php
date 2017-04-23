<?php
# ======================================================================================
# [DATE]  : 2013.02.27		[AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM		[SYSTEM]  : CCD
# [SUB_ID]:			[SUBSYS]  : 
# [PRC_ID]:			[PROCESS] : 
# [PGM_ID]: PS00S01002510.php	[PROGRAM] : Create YMO Lot
# [MDL_ID]:			[MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
#
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]	[UPDATE]		[COMMENT]
# ====================	====================	========================================
# DOS)MYDEL		2017.04.23		FOR CCD DEPARTMENT WITH ADDITIONAL E9 UNALLOWED
# --------------------------------------------------------------------------------------
#******************************************************************
#
# PROGRAM SETTINGS
#
#******************************************************************
$g_Version = "2.0";
$g_PrgCD = "PS00S01002510";
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
require_once (getenv("GPRISM_HOME") . "/DirList_pf.php");	# global variables of path list
require_once (getenv("GPRISM_HOME") . "/Func/Check.php");	# Input check
require_once ($g_func_dir . "/global.php");			# global variables
require_once ($g_func_dir . "/db_op.php");			# DB control
require_once ($g_func_dir . "/xdb_op.php");			# wrapper functions of DB control
require_once ($g_func_dir . "/xpt_err_msg.php");		# format of error message

#------------------------------------------------------------------
# for tracking
#------------------------------------------------------------------
require_once ($g_Mfunc_dir . "/xgt_dvsn.php");
require_once ($g_func_dir . "/xgn_cd.php");			# get common code from name
require_once ($g_func_dir . "/xgn_prd.php");			# get prd_nm from prd_cd
require_once ($g_func_dir . "/xgc_prd.php");			# get prd_cd from prd_nm
require_once ($g_func_dir . "/xgn_pkg.php");			# get pkg_cd & pkg_nm from prd_cd
require_once ($g_func_dir . "/xgt_stp_cls.php");		# get stp_cls_2 & stp_cls_4 from stp_cd
require_once ($g_func_dir . "/xgt_lot.php");			# get lot_bas_tbl info from lot_id
require_once ($g_func_dir . "/xgt_lp2.php");			# get default printer
require_once ($g_func_dir . "/xgt_lp2_cd.php");			# get printer info
#------------------------------------------------------------------
# VERB
#------------------------------------------------------------------
require_once ($g_func_dir . "/iocr.php");			# Re-Inspection Lot Create
require_once ($g_func_dir . "/iomg.php");			# Merge
require_once ($g_func_dir . "/mtin.php");
#------------------------------------------------------------------
# local functions
#------------------------------------------------------------------
require_once ($g_func_dir . "/cs_xgn_man.php");			# get user name(and check login user)
require_once ($g_func_dir . "/cs_xck_jig.php");
require_once ($g_func_dir . "/cs_xpt_etag_db.php");
require_once($g_func_dir . "/cs_xgt_po_no.php");           	# For PO Number Checking
require_once ($g_func_dir . "/cs_xgt_inhrt_po_data.php");	# Ensure PO CTG_CD is inherited to child.
require_once($g_func_dir . "/cs_xpt_sni.php");			#Ensure SNI PO is inherited to merged lot


#------------------------------------------------------------------
# for screen
#------------------------------------------------------------------
require_once ($g_lang_dir . "/buttonM.php");			# button name
require_once ($g_lang_dir . "/PS00S01002510M.php");		# message
require_once ($g_Gfunc_dir . "/xpt_screen.php");		# create screen
#******************************************************************
#
# DEFINITION
#
#******************************************************************
#------------------------------------------------------------------
# for display
#------------------------------------------------------------------
define("INI_LOT_CNT",				10);
define("MAX_LOT_CNT",				100);
define("INI_MGZN_CNT",				5);
define("MAX_MGZN_CNT",				10);
#------------------------------------------------------------------
# unallowed step
#------------------------------------------------------------------
define("E9_UNALW",serialize(array(
	"E911S140",
	"E911S150",
	"E921S010", # ST21S0000010 (CCD) ASSEMBLY CLEAN LINE		
	"E921S011", # ST21S0000011 (CCD) ASSEMBLY STREAM LINE		
	"E921S062"  # ST21S0000062 (BCCD) ASSEMBLY CLEAN LINE		
)));

#------------------------------------------------------------------
# category division
#------------------------------------------------------------------
define("CE_LTINF",		"CE00S02");
#------------------------------------------------------------------
# category code
#------------------------------------------------------------------
define("CT_SECNO",		"CT00S0000021");
define("CT_PLTNO",		"CT00S0000141");
#------------------------------------------------------------------
# tag
#------------------------------------------------------------------
define("TG_MA",			"MA");				# User ID
define("TG_EQ",			"EQ");				# Equipment Code
define("TG_LT",			"LT");				# Lot ID
define("TG_LP",			"LP");				# Printer Code
#------------------------------------------------------------------
# etc
#------------------------------------------------------------------
define("AU_STROK",		"AUSEM01");
define("CC_RTRN",		"CCSEM10");			# RETURN LOT

#------------------------------------------------------------------
# for jig control
#------------------------------------------------------------------
define("CE_MGZN",		"CE00S04");
define("CT_MGZN",		"CT00S0000024");
define("JI_MGZN",		"JI00S001");
define("SH_MGZN",		"SH00S998");
#------------------------------------------------------------------
# for YMO
#------------------------------------------------------------------
define("CT_YMO",		"CT11S0000234");
##------------------------------------------------------------------
#Printer Modifications
##------------------------------------------------------------------
define("PGMID_PRINT",		"PS00S06000400");   
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
	#------------------------------------------------------------------
    # get division code from menu registration info.
    #------------------------------------------------------------------
    $w_rtn = xgt_dvsn($w_dvsn_cd, $w_fct_cd, $w_rdg_cd);
    if($w_rtn != 0){
        $g_err_lv = 0;
        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
        return 4000;
    }

    $gw_scr['s_dvsn_cd'] = trim($w_dvsn_cd);
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
	# DO NOT be changed the Number of Liens by [check] process
	#------------------------------------------------------------------
	$gw_scr['s_inp_cnt'] = $gw_scr['s_hdn_inp_cnt'];

	#------------------------------------------------------------------
	# input check
	#------------------------------------------------------------------
	$w_rtn = check_input(1);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# close up blank line
	#------------------------------------------------------------------
	for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
		if($gw_scr['s_list_lot_id'][$i] != "") continue;
		for($j=($i+1); $j<=$gw_scr['s_hdn_inp_cnt']; $j++){
			if($gw_scr['s_list_lot_id'][$j] == "") continue;
			$gw_scr['s_list_lot_id'][$i] = $gw_scr['s_list_lot_id'][$j];
			$gw_scr['s_list_lot_id'][$j] = "";
			break;
		}
	}

	#------------------------------------------------------------------
	# duplicate input check
	#------------------------------------------------------------------
	for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
		if($gw_scr['s_list_lot_id'][$i] == "") continue;
		for($j=($i+1); $j<=$gw_scr['s_hdn_inp_cnt']; $j++){
			if($gw_scr['s_list_lot_id'][$j] == "") continue;
			if($gw_scr['s_list_lot_id'][$i] == $gw_scr['s_list_lot_id'][$j]){
				list($g_msg, $g_err_lv) = msg("err_Dup");
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
				return 4000;
			}
		}
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


	$w_ttl_qty = 0;
	$w_lot_type_cnt = 0;
	$w_lot_inp_cnt = 0;
	$base_po_no="";
	$r_po_no="";

	$w_base_sni_po = "";
        $w_sni_po_cnt = 0;

	for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
		if($gw_scr['s_list_lot_id'][$i] == "") continue;
		#------------------------------------------------------------------
		# get lot_bas_tbl
		#------------------------------------------------------------------
		$w_rtn = xgt_lot($gw_scr['s_list_lot_id'][$i], $w_lot_bas);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
			return 4000;
		}

		$w_lot_inp_cnt++;
		if($w_lot_bas['LOT_TYP_CD'] == CC_RTRN){
			$w_lot_type_cnt++;
		}
		if($w_lot_type_cnt != 0){
			if($w_lot_inp_cnt != $w_lot_type_cnt){
				list($g_msg,$g_err_lv) = PS00S01002510_msg("err_lot_type_cd");
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
			}
		}


		#-------------------------------------------------------------------
                #check PO if same
                #-------------------------------------------------------------------
		$r_po_no = array();
	
                $w_rtn = cs_xgt_po_no($gw_scr['s_list_lot_id'][$i], $r_po_no);

		if($w_rtn !=0){
                        $g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
                        return 4000;
                }


			if(count(array_unique($r_po_no)) > 1 )	{
				$g_err_lv = 0;
                                list($g_msg, $g_err_lv) = msg("err_PoNo_lot");
                                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
                                return 4000;

			}
	
                if($base_po_no==""){
                        $base_po_no = $r_po_no[0];
                }else{


	       	    	if($base_po_no != $r_po_no[0]){
						
        	               	$g_err_lv = 0;
	               	        list($g_msg, $g_err_lv) = msg("err_PoNo");
	                       	$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
		                return 4000;
        	      	}	
		}

		#=================
                # 1.2 SNI CHECK
                #=================

		# Check if SNI Product
                $r_is_sni = null;
                $w_rtn = cs_xpt_sni__is_sni($w_lot_bas['PRD_CD'], $r_is_sni);
                if( $w_rtn != 0 ){
                	db_rollback();
                        $g_err_lv = 0;
                        $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
                        return;
                }

		 if($r_is_sni){  # IF SNI PRODUCT


			$w_rtn = cs_xpt_sni__get_po_pol_ctg($gw_scr['s_list_lot_id'], $w_sni_pol);
			
			if($w_rtn != 0 ){
				$g_err_lv = 0;
			        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'][$i], __LINE__);
			        return 4000;
			}
			
			
			$w_rtn = cs_xpt_sni__chk_po_poline($w_sni_pol);
			
			if($w_rtn != 0 ){
			
			        $g_err_lv = 0;
			        list($g_msg, $g_err_lv) = msg("err_Po_Pol_merge");
			        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'][$i], __LINE__);
			        return 4000;
			}

	                 #Get the SNI PO and SNI PO Line NO
                         $w_rtn = cs_xpt_sni__get_po_no($gw_scr['s_list_lot_id'][$i], $w_sni_po_no );
                         if($w_rtn != 0 ){
        	                $g_err_lv = 0;
                	        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
                                return 4000;
                         }
                         if(count($w_sni_po_no) > 1){
                         	list($g_msg, $g_err_lv) = PS00S01002510_msg("err_Multiple_SNI_PO");
                                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
                                return 4000;
                         }


                         if($w_sni_po_cnt == 0){
                         	$w_base_sni_po = $w_sni_po_no[0];
                                $w_sni_po_cnt++;
                         }else{
                         	if($w_base_sni_po != $w_sni_po_no[0]){
                                	list($g_msg, $g_err_lv) = PS00S01002510_msg("err_SNI_PO_notMatch");
                                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i] . " - " . $w_base_sni_po . " - " . $w_sni_po_no[0], __LINE__);
                                        return 4000;
                                }
                         }
                 }




                 #=================
                 # END SNI CHECK
                 #=================
		#------------------------------------------------------------------
		# get Log
		#------------------------------------------------------------------
		$w_rtn = get_log($gw_scr['s_list_lot_id'][$i],
						$gw_scr['s_stp_cd'],
						$w_log);
		if($w_rtn != 0) return 4000;
		### if cannot get the product name
		if(trim($w_log['PRD_NM']) == ""){
			list($g_msg, $g_err_lv) = msg("err_Get_prdNm");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
			return 4000;
		}
		### if cannot get the package name
		if(trim($w_log['PKG_NM']) == ""){
			list($g_msg, $g_err_lv) = msg("err_Get_pkgNm");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# error if product code of acquired log doesn't match
		#------------------------------------------------------------------
		if(trim($w_log['PRD_CD']) != $w_prd_cd){
			list($g_msg, $g_err_lv) = msg("err_Dis_PrdCd");
			$w_tg = get_tg($gw_scr['s_list_lot_id'][$i], trim($w_log['PRD_NM']));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}


		#------------------------------------------------------------------
		# get date code
		#------------------------------------------------------------------
		$w_rtn = get_lotinf_dc($gw_scr['s_list_lot_id'][$i],
							constant("CE_LTINF"),
							constant("CT_SECNO"),
							$w_secdat);


#		echo "<pre>"; print_r($w_secdat); echo "</pre>";   exit();
		if($w_rtn != 0) return 4000;
		### error if cannot acquire
		if(count($w_secdat) == 0){
			list($g_msg, $g_err_lv) = msg("err_Get_Sec");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
			return 4000;
		}


			
		if(sizeof($w_secdat)> 1) {
			$w_rtn = get_dc_verb($w_secdat, $r_datecode);


			if($w_rtn!='0') {
				$r_datecode='';	
			}

			$w_list_sec_no[$i] = $r_datecode;
		} else {

			if(isset($w_secdat['CTG_DAT_TXT'])){
				$w_list_sec_no[$i] = $w_secdat['CTG_DAT_TXT'];
			}
		}

		#------------------------------------------------------------------
		# get plate no
		#------------------------------------------------------------------
		$w_rtn = get_lotinf($gw_scr['s_list_lot_id'][$i],
							constant("CE_LTINF"),
							constant("CT_PLTNO"),
							$w_pltdat);
		if($w_rtn != 0) return 4000;
		$w_list_plt_no[$i] = "";
		if(isset($w_pltdat['CTG_DAT_TXT'])){
			$w_list_plt_no[$i] = $w_pltdat['CTG_DAT_TXT'];
		}

		$w_rej_qty = $w_log['CHP_QTY'] - $w_log['CHP_QTY_T'];
		if($w_rej_qty == 0){
			list($g_msg, $g_err_lv) = msg("err_Get_RejQty");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
			return 4000;
		}

		$w_list_prd_cd[$i]  = trim($w_log['PRD_CD']);
		$w_list_prd_nm[$i]  = trim($w_log['PRD_NM']);
		$w_list_pkg_nm[$i]  = trim($w_log['PKG_NM']);
		$w_list_chp_qty[$i] = $w_rej_qty;

		$w_ttl_qty += $w_rej_qty;
	}




	#------------------------------------------------------------------
	# get route info
	#------------------------------------------------------------------
	$w_rtn = get_rtinf($w_prd_cd, $gw_scr['s_stp_cd'], $w_rtinf);
	if($w_rtn != 0) return 4000;

	#------------------------------------------------------------------
	# check need to magazine control
	#------------------------------------------------------------------
	$w_rtn = cs_xck_jig_srch($w_rtinf['PRC_CD'],
							$gw_scr['s_stp_cd'],
							$w_prd_cd,
							constant("JI_MGZN"),
							$w_prt_grp_b, $w_prt_grp_a, $w_jig_chg_id);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	### exception handling of only for this program
	### if PRT_GRP_B(=Before viz Start) exists, enable magazine control
	### (normally PRT_GRP_A exists)
	$w_mgzn_flg = 0;
	if($w_prt_grp_b != ""){
		$w_mgzn_flg = 1;
	}

	#------------------------------------------------------------------
	# set screen array variable
	#------------------------------------------------------------------
	$gw_scr['s_usr_nm'] = trim($w_usr_nm);
	$gw_scr['s_prd_cd'] = trim($w_prd_cd);
	$gw_scr['s_stp_nm'] = trim($w_stp_nm);
	$gw_scr['s_lbl_nm'] = trim($w_lbl_nm);

	$gw_scr['s_list_pkg_nm'] = $w_list_pkg_nm;
	$gw_scr['s_list_prd_cd'] = $w_list_prd_cd;
	$gw_scr['s_list_prd_nm'] = $w_list_prd_nm;
	$gw_scr['s_list_sec_no'] = $w_list_sec_no;
	$gw_scr['s_list_plt_no'] = $w_list_plt_no;
	$gw_scr['s_list_hdn_chp_qty'] = $w_list_chp_qty;
	$gw_scr['s_list_chp_qty'] = $w_list_chp_qty;

	$gw_scr['s_ttl_qty'] = $w_ttl_qty;

	$gw_scr['s_mgzn_flg'] = $w_mgzn_flg;

	$gw_scr['s_srlz_rtinf'] = userialize($w_rtinf);

	scr_mode_chg(2);

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
	$gw_scr['s_mgzn_row'] = trim($gw_scr['s_mgzn_row']);
	#------------------------------------------------------------------
	# require check
	#------------------------------------------------------------------
	if($gw_scr['s_mgzn_row'] == ""){
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		$g_msg = xpt_err_msg($g_msg, itm("NumDsp"), __LINE__);
		return 4000;
	}
	#------------------------------------------------------------------
	# check number
	#------------------------------------------------------------------
	if(!check_num($gw_scr['s_mgzn_row'])){
		list($g_msg, $g_err_lv) = msg("err_Inp_Char");
		$g_msg = xpt_err_msg($g_msg, itm("NumDsp"), __LINE__);
		return 4000;
	}
	#------------------------------------------------------------------
	# check range
	#------------------------------------------------------------------
	if($gw_scr['s_mgzn_row'] < 1
	|| $gw_scr['s_mgzn_row'] > constant("MAX_MGZN_CNT")
	){
		list($g_msg, $g_err_lv) = msg("err_Inp_Over");
		$g_msg = xpt_err_msg($g_msg, itm("NumDsp"), __LINE__);
		return 4000;
	}

	$gw_scr['s_hdn_mgzn_row'] = $gw_scr['s_mgzn_row'];

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




	$temp=array_filter($gw_scr['s_list_lot_id']);

        for($i=1; $i<=sizeof($temp); $i++) {
        	 if($temp[$i] !='' && $gw_scr['s_list_sec_no'][$i]=='') {
                   	 list($g_msg, $g_err_lv) = msg("err_Datecode");
                      	 $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                         return 4000;

                 }

	
		$w_rtn = get_lotinf_dc_chk($temp[$i], $gw_scr['s_list_sec_no'][$i],
							constant("CE_LTINF"),
							constant("CT_SECNO"),
							$w_tempdc);


		if($w_rtn != 0) return 4000;
		if(count($w_tempdc) == 0){
			list($g_msg, $g_err_lv) = msg("err_Get_Sec");
			$g_msg = xpt_err_msg($g_msg, $temp[$i], __LINE__);
			return 4000;
		}






        }


	




	#------------------------------------------------------------------
	# DO NOT be changed the number of Line by [check] process
	#------------------------------------------------------------------
	$gw_scr['s_mgzn_row'] = $gw_scr['s_hdn_mgzn_row'];

	#------------------------------------------------------------------
	# check input
	#------------------------------------------------------------------
	$w_rtn = check_input(2);
	if($w_rtn != 0) return 4000;


	#------------------------------------------------------------------
	# Lot process
	#------------------------------------------------------------------
	$w_ttl_qty = 0;
	for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
		if($gw_scr['s_list_lot_id'][$i] == "") continue;
		if($gw_scr['s_list_chp_qty'][$i] > $gw_scr['s_list_hdn_chp_qty'][$i]){
			list($g_msg, $g_err_lv) = msg("err_Ovr_RejQty");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
			return 4000;
		}

		$w_ttl_qty += $gw_scr['s_list_chp_qty'][$i];
	}


	$w_rtinf = uunserialize($gw_scr['s_srlz_rtinf']);
	#------------------------------------------------------------------
	# magazine check
	#------------------------------------------------------------------
	if($gw_scr['s_mgzn_flg'] == "1"){
		### close up
		for($i=1; $i<=$gw_scr['s_hdn_mgzn_row']; $i++){
			if($gw_scr['s_list_mgzn_id'][$i] != "") continue;
			for($j=($i+1); $j<=$gw_scr['s_hdn_mgzn_row']; $j++){
				if($gw_scr['s_list_mgzn_id'][$j] == "") continue;
				$gw_scr['s_list_mgzn_id'][$i] = $gw_scr['s_list_mgzn_id'][$j];
				$gw_scr['s_list_mgzn_id'][$j] = "";
				continue;
			}
		}
		### duplicate check
		for($i=1; $i<=$gw_scr['s_hdn_mgzn_row']; $i++){
			if($gw_scr['s_list_mgzn_id'][$i] == "") continue;
			for($j=($i+1); $j<=$gw_scr['s_hdn_mgzn_row']; $j++){
				if($gw_scr['s_list_mgzn_id'][$j] == "") continue;
				if($gw_scr['s_list_mgzn_id'][$i] == $gw_scr['s_list_mgzn_id'][$j]){
					list($g_msg, $g_err_lv) = msg("err_Inp_Dup");
					$w_tg = get_tg(itm("MgznId"), $gw_scr['s_list_mgzn_id'][$i]);
					$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
					return 4000;
				}
			}
		}

		$w_rtn = chk_jig_in($w_rtinf['PRC_CD'],
							$w_rtinf['STP_CD'],
							$w_rtinf['PRD_CD']);
		if($w_rtn != 0) return 4000;
	}


	$gw_scr['s_ttl_qty'] = $w_ttl_qty;


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
		scr_mode_chg(2);
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
#	db_rollback();

	$w_rtn = cs_xpt_etag_db($w_new_lot_id, $gw_scr['s_lbl_cd']);
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
	$w_mgflg = 0;
	$w_inpcnt = 0;
	for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
		if($gw_scr['s_list_lot_id'][$i] != "") $w_inpcnt++;
	}
	if($w_inpcnt >= 2) $w_mgflg = 1;

	$w_rtinf = uunserialize($gw_scr['s_srlz_rtinf']);

	$w_mgcnt = 0;
	$w_unq_secno = array();
	$w_lot_bas_parent = "";
	for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
		if($gw_scr['s_list_lot_id'][$i] == "") continue;
		### get log

		$w_rtn = get_log($gw_scr['s_list_lot_id'][$i],
						$gw_scr['s_stp_cd'],
						$w_log);
		if($w_rtn != 0) return 4000;

		$w_dmy_lot_no = trim($w_log['LOT_NO']) . date("YmdHis")."YMO";
		if(strlen($w_dmy_lot_no) > 30){
			$w_dmy_lot_no = trim($w_log['LOT_NO']) . date("ynjgis")."YMO";
			if(strlen($w_dmy_lot_no) > 30){
				$w_dmy_lot_no = substr(trim($w_log['LOT_NO']),0,15) . date("ynjgis")."YMO";
			}
	
		}


		#------------------------------------------------------------------
		# get lot_bas_tbl
		#------------------------------------------------------------------
		$w_rtn = xgt_lot($gw_scr['s_list_lot_id'][$i], $w_lot_bas);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_lot_id'][$i], __LINE__);
			return 4000;
		}

		if($w_lot_bas_parent == "")
			$w_lot_bas_parent = $w_lot_bas;

		#var_dump($w_dmy_lot_no); return 4000;
		$w_iocrdat = array
		(
			$w_lot_bas['LOT_ID_REC'],
			$w_log['LOT_ID_DIF_STR'],
			#$w_log['LOT_NO'] . date("YmdHis")."YMO",
			$w_dmy_lot_no,
			$w_log['LOT_NO_STR'],
			"00",
			$gw_scr['s_list_prd_cd'][$i],
			$w_rtinf['RT_CD'],
			$w_rtinf['PRC_CD'],
			$w_rtinf['IO_BLC_CD'],
			" ",
		    0,
			$gw_scr['s_list_chp_qty'][$i],
			$g_low_dts,
			$gw_scr['s_list_sec_no'][$i],
			"9999",
			$w_lot_bas['LOT_TYP_CD'],
			"CDSEM01",
			"0",
			"CBSEM01",
			" ",
			"0",
			" ",
			" ",
			0,
			" ",
			" ",
			$gw_scr['s_usr_id'],
			null,
		);
		$w_rtn = main_verb_iocr($gw_scr['s_usr_id'], $w_iocrdat, $w_lot_bas);
		if($w_rtn != 0) return 4000;




		#------------------------------------------------------------------
		# inherit LOT_INF_TBL(Date Code)
		#------------------------------------------------------------------
		$w_rtn = unq_datecode($gw_scr['s_usr_id'],
							$gw_scr['s_list_lot_id'][$i],
							$w_lot_bas['LOT_ID'],
							$w_log['SECRET_NO'],
							$w_unq_secno);
		if($w_rtn != 0) return 4000;

		### for IOMG
		$w_mgcnt++;
		$w_arr_lot_bas[$w_mgcnt] = $w_lot_bas;
	}

	if($w_mgcnt >= 2){
		for($i=1; $i<=$w_mgcnt; $i++){
			$w_arr_mg_lot['lot_id'][$i]     = $w_arr_lot_bas[$i]['LOT_ID'];
			$w_arr_mg_lot['lot_no'][$i]     = $w_arr_lot_bas[$i]['LOT_NO'];
			$w_arr_mg_lot['lot_no_str'][$i] = $w_arr_lot_bas[$i]['LOT_NO_STR'];
			$w_arr_mg_lot['lot_st_dvs'][$i] = $w_arr_lot_bas[$i]['LOT_ST_DVS'];
			$w_arr_mg_lot['chp_qty'][$i]    = $w_arr_lot_bas[$i]['CHP_QTY'];
			$w_arr_mg_lot['lf_qty'][$i]     = $w_arr_lot_bas[$i]['LF_QTY'];
			$w_arr_mg_lot['sl_qty'][$i]     = $w_arr_lot_bas[$i]['SL_QTY'];
			$w_arr_mg_lot['secret_no'][$i]  = $w_arr_lot_bas[$i]['SECRET_NO'];
			$w_arr_mg_lot['cmt'][$i]        = null;
			$w_arr_mg_lot['upd_lev'][$i]    = $w_arr_lot_bas[$i]['UPD_LEV'];
		}
		if(count($w_unq_secno) >= 2){
			$w_arr_mg_lot['secret_no'][1] = "MIX";
		}

		$w_mg_lot_bas = $w_arr_lot_bas[1];
		$w_rtn = main_verb_iomg($gw_scr['s_usr_id'], $w_arr_mg_lot, $w_mg_lot_bas);
		if($w_rtn != 0) return 4000;

		$w_lot_bas = $w_mg_lot_bas;
	} else {
		$w_lot_bas = $w_arr_lot_bas[1];
		
	}


        # Ensure PO CTG_CD is inherited to child.
        $w_rtn = cs_xgt_inhrt_po_data($gw_scr['s_usr_id'],$w_lot_bas_parent,$w_lot_bas['LOT_ID']);
        if ($w_rtn != 0) {
                $g_err_lv = 0;
                $g_msg  = xpt_err_msg($g_msg, "Error inheriting from " . $w_lot_bas_parent . " to " . $w_lot_bas['LOT_ID'], __LINE__);
                return 4000;
        }


	 # Check if SNI Product
                $r_is_sni = null;
                $w_rtn = cs_xpt_sni__is_sni($w_lot_bas_parent['PRD_CD'], $r_is_sni);
		#var_dump($r_is_sni);

                if( $w_rtn != 0 ){
                        db_rollback();
                        $g_err_lv = 0;
                        $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
                        return;

                }



                if($r_is_sni){  # IF SNI PRODUCT
			$w_new_lot_id =  $w_lot_bas['LOT_ID'];
			$w_lot_id = $w_lot_bas_parent['LOT_ID'];
                        $w_rtn = cs_xpt_sni__inhrt_ctg_tbl_for_iosp($gw_scr['s_usr_id'], $w_lot_id, $w_new_lot_id);
                        if ($w_rtn != 0) {
                                return $w_rtn;
                        }
                }


	#------------------------------------------------------------------
	# insert Date Code
	#------------------------------------------------------------------
	rearray($w_unq_secno);
	for($i=1; $i<=count($w_unq_secno); $i++){
		$w_rtn = ins_lotinf($gw_scr['s_usr_id'],
							$w_lot_bas['LOT_ID'],
							constant("CE_LTINF"),
							constant("CT_SECNO"),
							" ",
							$w_unq_secno[$i],
							null,
							"IOCR");
		if($w_rtn != 0) return 4000;
	}

	#------------------------------------------------------------------
	# insert Plate No
	#------------------------------------------------------------------
	$w_rtn = ins_lotinf($gw_scr['s_usr_id'],
						$w_lot_bas['LOT_ID'],
						constant("CE_LTINF"),
						constant("CT_PLTNO"),
						" ",
						$gw_scr['s_plt_no'],
						null,
						"IOCR");
	if($w_rtn != 0) return 4000;


	#------------------------------------------------------------------
        # insert YMO Lot No
        #------------------------------------------------------------------
	$ymo_lots=array_filter($gw_scr['s_list_lot_id']);
	for($i='1'; $i<= sizeof($ymo_lots); $i++){

		$w_ctg_cd = CT_YMO;
	
                $w_rtn = ins_ctg_tbl( $w_lot_bas['LOT_ID'], $w_ctg_cd, $ymo_lots[$i] );
                if ( $w_rtn != 0 ) {
                        db_rollback();
                        $g_err_lv = 0;
                        $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
                        return 4000;
                }
	}

	#------------------------------------------------------------------
	# magazine
	#------------------------------------------------------------------
	$w_rtn = main_exe_mgzn($w_lot_bas);
	if($w_rtn != 0) return 4000;

	$r_new_lot_id = trim($w_lot_bas['LOT_ID']);

	return 0;
}

#==================================================================
# get Log
#==================================================================
function get_log($w_lot_id, $w_stp_cd, &$r_dat)
{
	global $g_msg;
	global $g_err_lv;



	$w_sql = <<<_SQL
SELECT
	LLG.*,
	PRD.PRD_NM,
	PRD.PKG_CD,
	NMM.NM_FLL AS PKG_NM
FROM
	LOT_LOG LLG
	LEFT JOIN PRD_MST PRD ON
	(
		PRD.PRD_CD = LLG.PRD_CD
		AND PRD.DEL_FLG = '0'
	)
	LEFT JOIN NM_MST NMM ON
	(
		NMM.TAG = 'PG'
		AND NMM.CD = PRD.PKG_CD
		AND NMM.DEL_FLG = '0'
	)
WHERE
	LLG.LOT_ID = '{$w_lot_id}'
	AND LLG.VERB = 'IOOT'
	AND LLG.STP_CD = '{$w_stp_cd}'
	AND LLG.DEL_FLG = '0'
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
function get_lotinf_dc($w_lot_id, $w_ctgdvs, $w_ctgcd, &$r_dat)
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
	AND CTG_DAT_TXT !=' '
	AND DEL_FLG = '0'
	AND CRT_VERB IN ('IOIN','IORV',
	'PRGT','IOCR',
	'IOSD','IOOT',
	'IOMI','IOMG',
	'IOSP','IOMV',
	'IOPC')
_SQL;


	#AND CRT_VERB IN ('IOIN','IOSP')


	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
		return 4000;
	}

	while($w_row = db_fetch_row($w_stmt)){
		$r_dat[] = array_map("trim", $w_row);
	}
	db_res_free($w_stmt);


	return 0;
}



function get_lotinf_dc_chk($w_lot_id,$dat_cd, $w_ctgdvs, $w_ctgcd, &$r_dat)
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
	AND CTG_DAT_TXT ='{$dat_cd}'
	AND DEL_FLG = '0'
	AND CRT_VERB IN ('IOIN','IORV',
	'PRGT','IOCR',
	'IOSD','IOOT',
	'IOMI','IOMG',
	'IOSP','IOMV',
	'IOPC')
_SQL;


	#AND CRT_VERB IN ('IOIN','IOSP')


	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
		return 4000;
	}

	while($w_row = db_fetch_row($w_stmt)){
		$r_dat[] = array_map("trim", $w_row);
	}
	db_res_free($w_stmt);


	return 0;
}



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
        AND CTG_DAT_TXT ='{$w_dat_cd}'
        AND DEL_FLG = '0'
_SQL;


        #AND CRT_VERB IN ('IOIN','IOSP')


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



		function get_dc_verb($w_secdat, &$r_dtcode) {
			
			foreach($w_secdat as $id => $val) {
				$r_datecode[$val['CRT_VERB']][]= $val['CTG_DAT_TXT'];
			}



			foreach($r_datecode as $id => $val) {	
				if($id !='IOMG') {	
				if(sizeof(array_unique($val))> 1) {
				return 4000;
				}}
				$verbs[]=$id;
			}
#print_r($r_datecode);
			if(sizeof($r_datecode)>2 && $r_datecode!='IOMG') {
				 return 4000;
			}





				switch ($r_datecode) {
						    case (in_array('IOIN',$verbs) && in_array('IOMI',$verbs)) :
					           	$dtcode=$r_datecode['IOMI'][0];
						    	break;

						    case (in_array('IOIN',$verbs) && in_array('IOMG',$verbs)) :
						       	$dtcode=$r_datecode['IOIN'][0]; 
							break;

						    case (in_array('IOIN',$verbs) && in_array('IOSP',$verbs)) :
							$dtcode=$r_datecode['IOIN'][0];
                                                        break;

						    case (in_array('PRGT',$verbs) && in_array('IOMG',$verbs)) :

							$dtcode=$r_datecode['PRGT'][0];
                                                        break;
                                                    case (in_array('IOCR',$verbs) && in_array('IOMG',$verbs)) :

							$dtcode=$r_datecode['IOCR'][0];
                                                        break;
                                                    case (in_array('IOSD',$verbs) && in_array('IOMG',$verbs)) :

							$dtcode=$r_datecode['IOMG'][0];
                                                        break;
						    case (in_array('IOSD',$verbs) && in_array('IOMV',$verbs)) :

							$dtcode=$r_datecode['IOMV'][0];
                                                        break;
                                                    case (in_array('IOOT',$verbs) && in_array('IORV',$verbs)) :

							$dtcode=$r_datecode['IORV'][0];
                                                        break;
                                                    case (in_array('IOOT',$verbs) && in_array('IOMG',$verbs)) :

							$dtcode=$r_datecode['IOMG'][0];
                                                        break;
                                                    case (in_array('IOMI',$verbs) && in_array('IOMG',$verbs)) :

							$dtcode=$r_datecode['IOMI'][0];
                                                        break;
                                                    case (in_array('IOSP',$verbs) && in_array('IOMG',$verbs)) :

							$dtcode=$r_datecode['IOSP'][0];
                                                        break;
						    case (in_array('IOMV',$verbs) && in_array('IORV',$verbs)) :

							$dtcode=$r_datecode['IORV'][0];
                                                        break;
                                                    case (in_array('IOMV',$verbs) && in_array('IOMG',$verbs)) :

							$dtcode=$r_datecode['IOMV'][0];
                                                        break;
                                                    case (in_array('IOPC',$verbs) && in_array('IOMG',$verbs)) :

							$dtcode=$r_datecode['IOPC'][0];
                                                        break;
						    case (in_array('IORV',$verbs) && in_array('IOMG',$verbs)) :

                                                        $dtcode=$r_datecode['IORV'][0];
                                                        break;
						    default:
							$dtcode='';
				}

		$r_dtcode=$dtcode;

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

	/*
	 #------------------------------------------------------------------
         # get lot_bas_tbl of the parent
         #------------------------------------------------------------------
         $w_rtn = xgt_lot($w_lot_bas['LOT_ID_REC'], $w_lot_bas_parent);
         if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $w_lot_bas['LOT_ID_REC'], __LINE__);
                return 4000;
         }

	# Ensure PO CTG_CD is inherited to child.
        $w_rtn = cs_xgt_inhrt_po_data($w_usr_id,$w_lot_bas_parent,$w_lot_bas['LOT_ID']);
        if ($w_rtn != 0) {
                $g_err_lv = 0;
                $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
                return 4000;
        }
*/
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
function unq_datecode($w_usr_id, $w_old_lot_id, $w_new_lot_id,$w_dat_cd, &$r_unq_secno)
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
	AND CTG_DAT_TXT ='{$w_dat_cd}'
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
		$gw_scr['s_stp_cd'] = strtoupper(trim($gw_scr['s_stp_cd']));
		$gw_scr['s_plt_no'] = trim($gw_scr['s_plt_no']);
		$gw_scr['s_lbl_cd'] = strtoupper(trim($gw_scr['s_lbl_cd']));
		$w_inpcnt = 0;
		for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
			if($gw_scr['s_list_lot_id'][$i] == "") continue;
			$gw_scr['s_list_lot_id'][$i] = strtoupper(trim($gw_scr['s_list_lot_id'][$i]));
			$w_inpcnt++;
		}

		#------------------------------------------------------------------
		# required
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		if($gw_scr['s_usr_id'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("UsrId"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_prd_nm'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("PrdNm"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_stp_cd'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("StpCd"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_plt_no'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("PltNo"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_lbl_cd'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("LblPrinter"), __LINE__);
			return 4000;
		}
		if($w_inpcnt == 0){
			list($g_msg, $g_err_lv) = msg("err_Inp_LotID");
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
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
		if(!check_err_code($gw_scr['s_stp_cd'])){
			$w_tg = get_tg(itm("StpCd"), $gw_scr['s_stp_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_eisu($gw_scr['s_plt_no'])){
			$w_tg = get_tg(itm("PltNo"), $gw_scr['s_plt_no']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_err_meisho($gw_scr['s_lbl_cd'])){
			$w_tg = get_tg(itm("LblPrinter"), $gw_scr['s_lbl_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
			if($gw_scr['s_list_lot_id'][$i] == "") continue;
			if(!check_err_lot($gw_scr['s_list_lot_id'][$i])){
				$w_tg = get_tg(itm("LotId"), $gw_scr['s_list_lot_id'][$i]);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		break;
	#------------------------------------------------------------------
	# MODE2
	#------------------------------------------------------------------
	case 2:
		#------------------------------------------------------------------
		# trim
		#------------------------------------------------------------------
		for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
			$gw_scr['s_list_chp_qty'][$i] = trim($gw_scr['s_list_chp_qty'][$i]);
		}
		for($i=1; $i<=$gw_scr['s_hdn_mgzn_row']; $i++){
			$gw_scr['s_list_mgzn_id'][$i] = strtoupper(trim($gw_scr['s_list_mgzn_id'][$i]));
		}
		#------------------------------------------------------------------
		# require input
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
			if($gw_scr['s_list_lot_id'][$i] == "") continue;
			if($gw_scr['s_list_chp_qty'][$i] == ""){
				$w_tg = get_tg(itm("ChpQty"), $gw_scr['s_list_lot_id'][$i]);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		if($gw_scr['s_mgzn_flg'] == "1"){
			$w_exst = 0;
			for($i=1; $i<=$gw_scr['s_hdn_mgzn_row']; $i++){
				if($gw_scr['s_list_mgzn_id'][$i] != ""){
					$w_exst = 1;
					break;
				}
			}
			if($w_exst == 0){
				$g_msg = xpt_err_msg($g_msg, itm("MgznId"), __LINE__);
				return 4000;
			}
		}

		#------------------------------------------------------------------
		# prohibited characters
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Inp_Char");
		for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
			if($gw_scr['s_list_lot_id'][$i] == "") continue;
			if(!check_num($gw_scr['s_list_chp_qty'][$i])){
				$w_tg = get_tg(itm("ChpQty"), $gw_scr['s_list_lot_id'][$i]);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		for($i=1; $i<=$gw_scr['s_hdn_mgzn_row']; $i++){
			if($gw_scr['s_list_mgzn_id'][$i] == "") continue;
			if(!check_err_code($gw_scr['s_list_mgzn_id'][$i])){
				$w_tg = get_tg(itm("MgznId"), $gw_scr['s_list_mgzn_id'][$i]);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		break;
	#------------------------------------------------------------------
	# MODE3
	#------------------------------------------------------------------
	case 3:
		#------------------------------------------------------------------
		# trim
		#------------------------------------------------------------------
		for($i=1; $i<=$gw_scr['s_sl_inp_cnt']; $i++){
			$gw_scr['s_list_sl_no'][$i] = trim($gw_scr['s_list_sl_no'][$i]);
		}
		$gw_scr['s_aft_base_qty'] = trim($gw_scr['s_aft_base_qty']);
		$gw_scr['s_aft_bad_qty']  = trim($gw_scr['s_aft_bad_qty']);
		$w_mgzn = 0;
		for($i=1; $i<=$gw_scr['s_hdn_mgzn_row']; $i++){
			$gw_scr['s_list_mgzn_id'][$i] = strtoupper(trim($gw_scr['s_list_mgzn_id'][$i]));
			if($gw_scr['s_list_mgzn_id'][$i] != ""){
				$w_mgzn++;
			}
		}

		#------------------------------------------------------------------
		# require input
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		for($i=1; $i<=$gw_scr['s_sl_inp_cnt']; $i++){
			if($gw_scr['s_list_sl_no'][$i] == ""){
				$w_tg = get_tg(itm("UseSlNo"), $gw_scr['s_list_sl_m_lot_id'][$i]);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		if($gw_scr['s_aft_base_qty'] == ""){
			$w_tg = get_tg(itm("AftMultiLot"), itm("BaseQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($gw_scr['s_aft_bad_ctg_cnt'] > "0"
		&& $gw_scr['s_aft_bad_qty'] == ""
		){
			$w_tg = get_tg(itm("AftMultiLot"), itm("BadQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($gw_scr['s_mgzn_flg'] == "1" && $w_mgzn == 0){
			$g_msg = xpt_err_msg($g_msg, itm("FinMgznId"), __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# prohibited characters
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Inp_Char");
		for($i=1; $i<=$gw_scr['s_sl_inp_cnt']; $i++){
			if(!preg_match("/^[0-9\-\,]+$/", $gw_scr['s_list_sl_no'][$i])){
				$w_tg = get_tg(itm("UseSlNo"), $gw_scr['s_list_sl_m_lot_id'][$i]);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		if(!check_num($gw_scr['s_aft_base_qty'])){
			$w_tg = get_tg(itm("AftMulitLot"), itm("BaseQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($gw_scr['s_aft_bad_ctg_cnt'] > "0"
		&& !check_num($gw_scr['s_aft_bad_qty'])
		){
			$w_tg = get_tg(itm("AftMultiLot"), itm("BadQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($gw_scr['s_mgzn_flg'] == "1"){
			for($i=1; $i<=$gw_scr['s_hdn_mgzn_row']; $i++){
				if($gw_scr['s_list_mgzn_id'][$i] == "") continue;
				if(!check_err_code($gw_scr['s_list_mgzn_id'][$i])){
					$g_msg = xpt_err_msg($g_msg, itm("FinMgznId"), __LINE__);
					return 4000;
				}
			}
		}

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
		$gw_scr['s_plt_no'] = "";
		$gw_scr['s_lbl_cd'] = "";
		$gw_scr['s_inp_cnt']      = constant("INI_LOT_CNT");
		$gw_scr['s_hdn_inp_cnt']  = constant("INI_LOT_CNT");
		$gw_scr['s_list_lot_id']  = array();
		$gw_scr['s_mgzn_row']     = constant("INI_MGZN_CNT");
		$gw_scr['s_hdn_mgzn_row'] = constant("INI_MGZN_CNT");
	}

	### MODE2
	if($w_mode <= 2){
		$gw_scr['s_usr_nm'] = "";
		$gw_scr['s_prd_cd'] = "";
		$gw_scr['s_stp_nm'] = "";
		$gw_scr['s_lbl_nm'] = "";
		$gw_scr['s_srlz_rtinf'] = "";

		$gw_scr['s_list_pkg_nm']  = array();
		$gw_scr['s_list_prd_nm']  = array();
		$gw_scr['s_list_sec_no']  = array();
		$gw_scr['s_list_plt_no']  = array();
		$gw_scr['s_list_chp_qty'] = array();
		$gw_scr['s_list_prd_cd']  = array();
		$gw_scr['s_list_stp_cd']  = array();
		$gw_scr['s_list_upd_lev'] = array();
		$gw_scr['s_ttl_qty']      = "";

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
# Ins YMO Lot Id
#==================================================================
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
	return PS00S01002510_item($var);
}
function msg($var)
{
	return PS00S01002510_msg($var);
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
