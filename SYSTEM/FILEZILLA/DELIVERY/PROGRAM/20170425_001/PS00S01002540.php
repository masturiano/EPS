<?php
# ==========================================================================================
# [DATE]  : 2013.01.03				[AUTHOR]  : MIS) L.ACERA
# [SYS_ID]: GPRISM				[SYSTEM]  : CCD
# [SUB_ID]:					[SUBSYS]  : 
# [PRC_ID]:					[PROCESS] : 
# [PGM_ID]: PS00S01002540.php			[PROGRAM] : DELIVERY (CCD)
# [MDL_ID]:					[MODULE]  : 
# ------------------------------------------------------------------------------------------
# [COMMENT]
#
# $g_mode
#		1 : Main Screen
#		2 : Validation of Inputted Data
#		3 : Delivery Execution
#
# ------------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]	[UPDATE]		[COMMENT]
# ====================	======================	============================================
# DOS)H.Otsuka		I140411-0000003		返品ロット対応(UTAC対応)
# DOS)Mydel             2017.04.24              For CCD Department
# ------------------------------------------------------------------------------------------
#******************************************************************
#
# PROGRAM VERSION
#
#******************************************************************
$g_Version = "2.0";
$g_PrgCD = "PS00S01002540";
#******************************************************************
#
# ALL REQUEST(POST-GET) INITIALIALIZED VIA $gw_scr 
#
#******************************************************************
if ($REQUEST_METHOD == "GET") {
	$gw_scr = cnv_formstr($_GET);
} else {
	$gw_scr = cnv_formstr($_POST);
}
#******************************************************************
#
# LANGUAGE INITIALIZE
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
# REQUIRE FILES
#
#******************************************************************
#------------------------------------------------------------------
# 全ＰＧ共通
#------------------------------------------------------------------
require_once (getenv("GPRISM_HOME") . "/DirList_pf.php");		# パスリスト
require_once (getenv("GPRISM_HOME") . "/Func/Check.php");		# 入力値チェック共通関数
require_once ($g_func_dir . "/global.php");				# 共通変数
require_once ($g_func_dir . "/db_op.php");				# DB操作
require_once ($g_func_dir . "/xdb_op.php");				# DBIコネクト関数
require_once ($g_func_dir . "/xpt_err_msg.php");			# エラーメッセージ作成関数
#------------------------------------------------------------------
# COMMON FUNCTIONS
#------------------------------------------------------------------
require_once ($g_func_dir . "/cs_xgn_man.php");
require_once ($g_func_dir . "/xgt_lp2.php");
require_once ($g_func_dir . "/xgt_lp2_cd.php");
require_once ($g_func_dir . "/xgt_lot.php");
require_once ($g_func_dir . "/xgn_cd.php");
require_once ($g_func_dir . "/xgn_prd.php");
require_once ($g_func_dir . "/xgn_pkg.php");
require_once ($g_func_dir . "/xck_upd.php");
require_once ($g_func_dir . "/xgt_stp_cls.php");
require_once ($g_func_dir . "/xgt_use_equ.php");
require_once ($g_func_dir . "/xgt_stp.php");
require_once ($g_func_dir . "/xgt_ctg.php");
require_once ($g_func_dir . "/xck_lio.php");
require_once ($g_func_dir . "/xgt_nio.php");
require_once ($g_func_dir . "/xgt_npr.php");
require_once ($g_func_dir . "/xgt_cd_cnt.php");
require_once ($g_func_dir . "/xpt_lot_tmp.php");
require_once ($g_func_dir . "/cs_xexc_hold_rsv.php");
require_once ($g_func_dir . "/cs_xck_thd_jdg.php");
require_once ($g_func_dir . "/cs_xck_staff_ctrl.php");		# For Staff Process control
require_once ($g_func_dir . "/cs_xck_exst_child.php");		# For Parent Child Control
require_once ($g_Mfunc_dir . "/xgt_dvsn.php");			# For printer changes
require_once ($g_func_dir . "/cs_xpt_ccd_f0_test.php");         # F0 Test Control
require_once ($g_func_dir . "/cs_xgt_po_no.php");               # PO/BO Control
#------------------------------------------------------------------
# VERB
#------------------------------------------------------------------
require_once ($g_func_dir . "/ioin.php");
require_once ($g_func_dir . "/ioot.php");
require_once ($g_func_dir . "/iodi.php");
require_once ($g_func_dir . "/iosd.php");
require_once ($g_func_dir . "/mtsd.php");
require_once ($g_func_dir . "/mtcr.php");
require_once ($g_func_dir . "/mtrv.php");
require_once ($g_func_dir . "/mtad.php");
require_once ($g_func_dir . "/mtin.php");
require_once ($g_func_dir . "/mtot.php");
#------------------------------------------------------------------
# SCREEN INCLUSION
#------------------------------------------------------------------
require_once ($g_lang_dir . "/buttonM.php");
require_once ($g_lang_dir . "/PS00S01002540M.php");
require_once ($g_Gfunc_dir . "/xpt_screen.php");
require_once ($g_func_dir . "/cs_chk_delivery.php");
#------------------------------------------------------------------
# SNI
#------------------------------------------------------------------
require_once ($g_func_dir . "/cs_xpt_sni.php");

#******************************************************************
#
# CONSTANT
#
#******************************************************************
#------------------------------------------------------------------
# 表示系
#------------------------------------------------------------------
define("INI_BLC",			2);
define("INI_ROW",			50);
define("MAX_ROW",			constant("INI_BLC") * constant("INI_ROW"));
#------------------------------------------------------------------
### CE
define("CC_RET",				"CCSEM10");

### CE
define("CE_MAT",				"CE00S04");
define("CE_SLINF",				"CE00S08");
### CT
define("CT_DIFFLOTNO",				"CT00S0000001");
define("AW_WCSCHK",				"AW00S0000007");
define("AW_PRNCHL",                             "AW11S0000007");
define("AW_UTL_FLG",                            "AW00S0000048");

### SH
define("SH_SG",					"SH00S990");
define("SH_OTHR",				"SH00S991");
### E9
#define("E9_PCKING_BGA",			"E949S140");			# Change to Packing BGA (No Parent Control)
define("E9_PCKING_BGA",                         "E911S680");                    # Packing BGA 
define("E9_ALW",                                serialize(array(
	constant("E9_PCKING_BGA"),
        "E921S032", # ST21S0000032 (CCD) PACKING
        "E921S071", # ST21S0000071 (BCCD) PACKING
)));
### AW
define("AM_LDHU",				"AM00S995");
### CB
define("CB_MOTHER",				"CBSEM00");
### LOG_BIND検索用
define("DVS_LAMINATE",				"BGA_LAMINATE");
### 伝票番号採番時に使用
define("CNTDVS_SLIP",				"TRANS_NOTE");
### Transfer Note帳票雛形
define("TRNNOTE_HINA",				"PS00S03000030");


# Parent Child Control(E9)
define("E9_PARENT_CHILD_CONTROL",		serialize(array(
#	"E911S370",			# F2-TEST
#	"E911S410",			# APPLICATION TEST
	"E911S590",			# PACKING VI
#	"E911S640",			# AUTO VI
#	"E911S650",			# AUTO VI 2
	"E911S630",			# BAKING

	"E911S670",			# PRE PACKING
	"E911S680",			# PACKING
#	"E911S690",			# DELIVERY
        "E921S031",                     # ST21S0000031 (CCD) PRE PACKING
        "E921S070",                     # ST21S0000070 (BCCD) PRE PACKING
        "E921S032",                     # ST21S0000032 (CCD) PACKING
        "E921S071",                     # ST21S0000071 (BCCD) PACKING
)));

define("E9_PACKING_VI",		"E911S590");				# Packing VI
define("E9_HAIRLINE_CRACK",	"E911S600");				# Hairline crack
define("E9_BAKING",			"E911S630");				# Baking
define("E9_MANUAL_TFR_PROCESS",	"E911S300");			# MANUAL TRANSFER PROCESS
define("E9_DELIVERY",		"E911S680");				# DELIVERY
define("E9_F_TEST",			"E911S330");				# F-test
define("E9_POST_MOLD_CURE",	"E911S210");				# Post Mold Cure
define("AW_PARENT_CHILD_CONTROL",	"AW11S0000007");

##------------------------------------------------------------------
# F0-TEST Expiry Checking
##------------------------------------------------------------------
define("CT_F0_TEST_TRACKOUT",                   "CT2100000001");        # F0 test expiry checking
define("CE_LTINF",                              "CE00S02");             # Lot Information
##------------------------------------------------------------------
#Printer Modifications
##------------------------------------------------------------------
define("PGMID_PRINT",     "PS00S06000400");       


#******************************************************************
#
# 関数定義
#
#******************************************************************
#==================================================================
# 配列データを一括変換
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
##### 初期遷移処理                                            #####
#####                                                         #####
###################################################################
#==================================================================
# 初期処理
#==================================================================
function main_init()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	# 表示項目初期化処理
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

	# モード１にする
	scr_mode_chg(1);

	#------------------------------------------------------------------
	# 出力先取得
	#------------------------------------------------------------------
	### ロット票用プリンタ取得
	$w_rtn = xgt_lp2(1, $w_lp_cd, $w_lp_nm, $dmy, $dmy);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	}

	$gw_scr['s_lp_cd'] = trim($w_lp_cd);
	$gw_scr['s_lp_nm'] = trim($w_lp_nm);

	return 0;
}

###################################################################
#####                                                         #####
##### モード１                                                #####
#####                                                         #####
###################################################################
#==================================================================
# モード１
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
	case "ERASE":
		main_init();
		break;
	}

	return 0;
}

#==================================================================
# モード１ 確認ボタン押下処理
#==================================================================
function main_md1_chk()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# 入力チェック
	#------------------------------------------------------------------
	$w_rtn = check_input(1);
	if($w_rtn != 0) return 4000;

	#------------------------------------------------------------------
	# ユーザ名
	#------------------------------------------------------------------
	$w_rtn = cs_xgn_man($gw_scr['s_usr_id'], $w_usr_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
		return 4000;
	}

	# convert if specified PCL label format
	for($i=1; $i<=constant("MAX_ROW"); $i++){
		if($gw_scr['s_list_lot_id'][$i] == "") continue;
		$gw_scr['s_list_hdn_lot_id'][$i] = $gw_scr['s_list_lot_id'][$i];
		if(substr($gw_scr['s_list_lot_id'][$i], 0, 2) == "B "){
			$w_arr = explode(" ", $gw_scr['s_list_lot_id'][$i]);
			$gw_scr['s_list_hdn_lot_id'][$i] = $w_arr[1];
		}
	}

	$w_rtn = cs_chk_delivery($gw_scr['s_list_hdn_lot_id'], $w_mother_lot, $w_status);
        if($w_rtn != 0){
               $g_msg = xpt_err_msg($g_msg, "", __LINE__);
               return 4000;
        }
	if(!$w_status){
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                 return 4000;
        }


	#------------------------------------------------------------------
        # Check UTL Products and Normal Products
        #------------------------------------------------------------------
        $is_utl_trans_note = false;
        $cnt = 0;
	
	$b_dvsn_cd = "";
	$b_rdg_cd = "";

	for($i=1; $i<=constant("MAX_ROW"); $i++){
                if($gw_scr['s_list_lot_id'][$i] == ""){
			$gw_scr['s_list_hdn_lot_id'][$i] = "";
	
		 	continue;
		}

		$cnt++;
                #------------------------------------------------------------------
                # Get the lot
                #------------------------------------------------------------------
                $w_rtn = xgt_lot($gw_scr['s_list_hdn_lot_id'][$i], $w_lot_bas);
                if($w_rtn != 0){
                        $g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
                        return 4000;
                }

		# Checking Mother Lot
		# get the parent 	
		# find all child
		# check mother
		# check all child include

		# lot_id, lot_list, 
#		$w_rtn = cs_chk_delivery($gw_scr['s_list_lot_id'][$i], $gw_scr['s_list_lot_id'], $w_mother_lot, $w_status);

#		if($w_rtn != 0){
#			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
#			return 4000;
#		}

#		if(!$w_status){
#			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
#			return 4000;
#		}

                if(1 == $i) {
                        // Check UTL Product or Normal Product
                        $w_rtn = check_utl_product($w_lot_bas['PRD_CD'], $is_utl_trans_note);

			$b_dvsn_cd = trim($w_lot_bas['DVSN_CD_PRD']);
                        $b_rdg_cd = trim($w_lot_bas['RDG_CD_PRC']);

                }else {
                        // get UTL Product or Normal Product
                        $w_rtn = check_utl_product($w_lot_bas['PRD_CD'], $is_utl_trans_note_tmp);
                        // Check with is_utl_delivery status
                        if($is_utl_trans_note != $is_utl_trans_note_tmp) {
                                list($g_msg, $g_err_lv) = msg("err_not_allowed_combine");
                                $g_msg = xpt_err_msg(sprintf($g_msg, $w_arr_chk_lot_id['BIND_ID']), "", __LINE__);
                                return 4000;
                        }

			#------------------------------------------------------------------
		        # 複数行入力された際に、異なる商品コードが混入していればエラー
                	#------------------------------------------------------------------
 	                if($cnt >= 2
			&& $b_dvsn_cd != trim($w_lot_bas['DVSN_CD_PRD'])
	                ){
        	                list($g_msg, $g_err_lv) = msg("err_Plrl_Dvsn");
                	        $w_tg = get_tg($b_dvsn_cd, trim($w_lot_bas['DVSN_CD_PRD']));
	                        $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
        	                return 4000;
                	}

	                if($cnt >= 2
        	        && $b_rdg_cd != trim($w_lot_bas['RDG_CD_PRC']))
                	{
                        	list($g_msg, $g_err_lv) = msg("err_Rdg_Cd");
	                        $w_tg = get_tg($b_rdg_cd, trim($w_lot_bas['RDG_CD_PRC']));
        	                $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                	        return 4000;

	                }	

                }
        }

	#Process Staff control
	$w_rtn = xgt_lot($gw_scr['s_list_hdn_lot_id'][1], $w_lot_bas_psc);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][1], __LINE__);
		return 4000;
	}
	
	$w_rtn = cs_xck_staff_ctrl($w_lot_bas_psc['STP_CD'], $gw_scr['s_usr_id'], $w_allow);
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



	 #Checking the different product type for the SNI

         $inp_lot=count($gw_scr['s_list_hdn_lot_id']);

         $is_sni_pro='0';
	 $w_sni_po_arr = array();
         for ($j = 1; $j <= $inp_lot; $j++) {
                if($gw_scr['s_list_hdn_lot_id'][$j] =="")continue;

                $w_rtn = xgt_lot($gw_scr['s_list_hdn_lot_id'][$j], $w_lot_bas_sni);
                if($w_rtn != 0){
                        list($g_msg, $g_err_lv) = msg("err_Lot_Bas");
                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$j], __LINE__);
                        return 4000;
                }


                $w_rtn=cs_xpt_sni__is_sni(trim($w_lot_bas_sni['PRD_CD']), &$r_is_sni);

                if($w_rtn !='0') {
                        list($g_msg, $g_err_lv) = msg("err_Chk_Sni");
                        $g_msg = xpt_err_msg($g_msg, $w_lot_bas_sni['PRD_CD'], __LINE__);
                        return 4000;
                }

                if($r_is_sni) {

                        $is_sni_pro='1';
		
			$w_rtn = cs_xpt_sni__get_po_pol_ctg($gw_scr['s_list_hdn_lot_id'], $w_sni_pol, 1);
			if($w_rtn != 0 ){
				$g_err_lv = 0;
        			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'][$i], __LINE__);
	        		return 4000;
			}

			$w_rtn = cs_xpt_sni__chk_po($w_sni_pol);
			if($w_rtn != 0 ){
	        		$g_err_lv = 0;
			        list($g_msg, $g_err_lv) = msg("err_Po_Pol_merge");
			        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'][$i], __LINE__);
		        	return 4000;
			}	

			$w_sni_po = array();
                        # If Product is SNI
                        $w_rtn = cs_xpt_sni__get_po_no($w_lot_bas_sni['LOT_ID'], $w_sni_po, 1);
                        if($w_rtn != 0) {
                                $g_err_lv = 0;
                                $g_msg = xpt_err_msg($g_msg, $w_lot_bas_sni['LOT_ID'], __LINE__);
                                return 4000;
                        }

                        if(count($w_sni_po) <= 0) {
                                # Error no PO
                                list($g_msg, $g_err_lv) = msg("err_PO_Not_Found");
                                $g_msg = xpt_err_msg($g_msg, $w_lot_bas_sni['LOT_ID'], __LINE__);
                                return 4000;
                        }

			$w_sni_po_arr[] = $w_sni_po[0];
                }

                $get_all_sni_pro[]=$w_lot_bas_sni['PRD_CD'];

                unset($w_lot_bas_sni);

        }

	if(($is_sni_pro=='1') && (count(array_unique($get_all_sni_pro))>'1'))
        {
                        unset($is_sni_pro);
                        unset($get_all_sni_pro);
                        list($g_msg, $g_err_lv) = msg("err_Sni_pro");
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;

        }

        if(($is_sni_pro == "1") && (count(array_unique($w_sni_po_arr)) > 1)) {
                        unset($is_sni_pro);
                        unset($w_sni_po_arr);
                        list($g_msg, $g_err_lv) = msg("err_PO_Not_Same");
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
        }
	

        #end of the SNi different product type checking
	
	
	for ($i = 1; $i < count($gw_scr['s_list_hdn_lot_id']); $i++) {
		if($gw_scr['s_list_hdn_lot_id'][$i] =="")continue;
		$w_rtn = xgt_lot($gw_scr['s_list_hdn_lot_id'][$i], $w_lot_bas_psc);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
			return 4000;
		}
		
		$w_rtn = xgt_stp_cls($w_lot_bas_psc['STP_CD'], $w_stpcls2, $dmy);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, trim($w_lot_bas_psc['STP_CD']), __LINE__);
			return 4000;
		}
		
	
		#------------------------------------------------------------------
		# Parent Child Controlチェック
		#------------------------------------------------------------------
		if (in_array(trim($w_stpcls2),
			unserialize(constant('E9_PARENT_CHILD_CONTROL')))) {
			$w_rtn = cs_xck_exst_child($w_lot_bas_psc,
					constant('AW_PARENT_CHILD_CONTROL'), constant('E9_F_TEST'),
					$w_exst_lot_bas, 1);
			if ($w_rtn != 0) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
			}

                	$w_cnt_holdlot = 0;
                	for($b=1; $b<=count($w_exst_lot_bas); $b++){
				
                        	if($w_exst_lot_bas[$b]['LOT_ID'] != $w_lot_bas_psc['LOT_ID']){
                                 	$w_rtn = check_FtestStep(
                                                                     $w_exst_lot_bas[$b]['LOT_ID'],
                                                                     "IOOT",
                                                                      constant('E9_F_TEST'),
                                                                     $w_bln_check
                                                                );
                                  	if($w_rtn != 0){
                                        	$g_err_lv = 0;
                                        	$g_msg = xpt_err_msg($g_msg, "", __LINE__);
                                        	return 4000;
                                  	}
                                  	if($w_bln_check == FALSE) {
                                        	$w_cnt_holdlot++;
                                  	} else {
						if($w_exst_lot_bas[$b]['LOT_ST_DVS'] == 'HD') {
							 $w_cnt_holdlot++;
						}
					}

                        	}

                	}

			if ($w_cnt_holdlot > 0) {
				if ($w_stpcls2 == constant('E9_DELIVERY')) {
					# 着手不可
					list($g_msg, $g_err_lv) = msg("err_ParentChildControl");
					$g_msg = xpt_err_msg($g_msg, "", __LINE__);
					return 4000;
				} else {
					$w_rtn = has_usrgrp($gw_scr['s_usr_id'],
							constant('DG_ALLOWED_PARENT_CHILD_CONTROL'),
							$w_has_usrgrp);
					if ($w_rtn != 0) {
						$g_err_lv = 0;
						$g_msg = xpt_err_msg($g_msg, "", __LINE__);
						return 4000;
					}
					if ($w_has_usrgrp == 0) {
						# 着手不可
						list($g_msg, $g_err_lv) = msg("err_ParentChildControl");
						$g_msg = xpt_err_msg($g_msg, "", __LINE__);
						return 4000;
					} else {
						# 着手可
						list($w_warn[], $w_dmy) = msg("err_ParentChildControl");
					}
				}
			}
		}
	
	}
	
	
	#------------------------------------------------------------------
	# 出力先
	#------------------------------------------------------------------
	$w_rtn = xgt_lp2_cd($gw_scr['s_lp_cd'], $dmy, $dmy, $w_lp_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lp_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# 詰め処理
	#------------------------------------------------------------------
	for($i=1; $i<=constant("MAX_ROW"); $i++){
		if($gw_scr['s_list_lot_id'][$i] != "") continue;
		for($j=($i+1); $j<=constant("MAX_ROW"); $j++){
			if($gw_scr['s_list_lot_id'][$j] == "") continue;
			$gw_scr['s_list_lot_id'][$i]     = $gw_scr['s_list_lot_id'][$j];
			$gw_scr['s_list_hdn_lot_id'][$i] = $gw_scr['s_list_hdn_lot_id'][$j];
			$gw_scr['s_list_lot_id'][$j]     = "";
			$gw_scr['s_list_hdn_lot_id'][$j] = "";
			break;
		}
	}

	#------------------------------------------------------------------
	# 重複チェック
	#------------------------------------------------------------------
	for($i=1; $i<=constant("MAX_ROW"); $i++){
		if($gw_scr['s_list_hdn_lot_id'][$i] == "") continue;
		for($j=($i+1); $j<=constant("MAX_ROW"); $j++){
			if($gw_scr['s_list_hdn_lot_id'][$j] == "") continue;
			if($gw_scr['s_list_hdn_lot_id'][$i] == $gw_scr['s_list_hdn_lot_id'][$j]){
				list($g_msg, $g_err_lv) = msg("err_Dup");
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
				return 4000;
			}
		}
	}

	#------------------------------------------------------------------
	# ロットごとの処理
	#------------------------------------------------------------------
	$cnt = 0;
	$w_total = 0;
	$w_ret_cnt = 0;
	for($i=1; $i<=constant("MAX_ROW"); $i++){
		if($gw_scr['s_list_hdn_lot_id'][$i] == "") continue;
		$cnt++;
		#------------------------------------------------------------------
		# ロット基本情報取得
		#------------------------------------------------------------------
		$w_rtn = xgt_lot($gw_scr['s_list_hdn_lot_id'][$i], $w_lot_bas);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# 複数行入力された際に、異なる商品コードが混入していればエラー
		#------------------------------------------------------------------
		if($cnt >= 2
		&& $b_dvsn_cd != trim($w_lot_bas['DVSN_CD_PRD'])
		){
			list($g_msg, $g_err_lv) = msg("err_Plrl_Dvsn");
			$w_tg = get_tg($b_dvsn_cd, trim($w_lot_bas['DVSN_CD_PRD']));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# 状態チェック
		#------------------------------------------------------------------
		### LOT_ST_DVS
		$w_rtn = iodi_st_check($w_lot_bas['LOT_ST_DVS']);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i]."(".$w_lot_bas['LOT_ST_DVS'].")", __LINE__);
			return 4000;
		}
		### CRTR
		if($w_lot_bas['CRTR'] != "1"){
			list($g_msg, $g_err_lv) = msg("err_Not_Pck");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# 工程チェック
		#------------------------------------------------------------------
		$w_rtn = xgt_stp_cls($w_lot_bas['STP_CD'], $w_stpcls, $dmy);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $w_lot_bas['STP_CD'], __LINE__);
			return 4000;
		}
		if(!in_array($w_stpcls, unserialize(constant("E9_ALW")))){
			list($g_msg, $g_err_lv) = msg("err_Disabled");
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# LOG_BIND_INFに情報があれば、セットで入力されていること
		#------------------------------------------------------------------
		if($w_lot_bas['PLT_DVS_CD'] == constant("CB_MOTHER")){
			$w_rtn = chk_bind($gw_scr['s_list_hdn_lot_id'][$i], $w_arr_chk_lot_id);
			if($w_rtn != 0) return 4000;
			if(count($w_arr_chk_lot_id) > 0){
				### 取得できたロットＩＤがすべて含まれていること
				if(!in_array($w_arr_chk_lot_id['LOT1'], $gw_scr['s_list_hdn_lot_id'])
				|| !in_array($w_arr_chk_lot_id['LOT2'], $gw_scr['s_list_hdn_lot_id'])
				){
					list($g_msg, $g_err_lv) = msg("err_Bind_Contain");
					$g_msg = xpt_err_msg(sprintf($g_msg, $w_arr_chk_lot_id['BIND_ID']), "", __LINE__);
					return 4000;
				}
			}
		}

		#------------------------------------------------------------------
		# 名称/コード取得
		#------------------------------------------------------------------
		### 品種名
		$w_rtn = xgn_prd($w_lot_bas['PRD_CD'], $w_prd_nm, $dmy);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $w_lot_bas['PRD_CD'], __LINE__);
			return 4000;
		}

                $w_rtn = wcs_check($w_lot_bas['PRD_CD']);
                if($w_rtn != 0){
                        $g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, $w_prd_nm ." : ".$w_lot_bas['LOT_ID'], __LINE__);
                        return 4000;
                }


                $w_rtn = cs_xck_exst_child($w_lot_bas,constant('AW_PRNCHL'),constant('E9_PCKING_BGA'),$w_return,1);
                if($w_rtn != 0){
                        $g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, $w_lot_bas['LOT_ID'], __LINE__);
                        return 4000;
                }

                #-----------------------------------------------------------------
                # F0-TEST Expiry Checking
                #-----------------------------------------------------------------

                #Get f0-test Track In Date
                $w_rtn = get_lotinf($w_lot_bas['LOT_ID'],  constant("CE_LTINF"), constant("CT_F0_TEST_TRACKOUT"), $w_lot_f0_trkin_inf);
                if($w_rtn != 0){
                        $g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
                }
                if(count($w_lot_f0_trkin_inf)>0){
                        $w_rtn = cs_xpt_ccd_f0_test__is_expire($w_lot_bas['LOT_ID'], constant("CT_F0_TEST_TRACKOUT"), $w_expiry_status);
                        if($w_rtn != 0){
                                $g_err_lv = 0;
                                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                                return 4000;
                        }

                        if(!$w_expiry_status){
                                list($g_msg, $g_err_lv) = msg("err_F0_test_expired");
                                $g_msg = xpt_err_msg($g_msg, trim($w_stpcls), __LINE__);
                                return 4000;
                        }
                }

                #-----------------------------------------------------------------
                # PO/BO Control
                #-----------------------------------------------------------------

                # Check PO No
                $w_rtn = cs_xgt_po_no($w_lot_bas['LOT_ID'], $r_dat, 0);
                $r_dat_count = count($r_dat);
                if($r_dat_count > 1){
                        list($g_msg, $g_err_lv) = msg("err_mul_po_control");
                        $g_msg = xpt_err_msg($g_msg, trim($w_lot_bas['LOT_ID']), __LINE__);
                        return 4000;
                }
                else{
                        $po_no_list[] = $r_dat[0];
                }

		$w_list_prd_nm[$i]    = trim($w_prd_nm);
		$w_list_chp_qty[$i]   = $w_lot_bas['CHP_QTY'];
		$w_list_upd_lev[$i]   = $w_lot_bas['UPD_LEV'];
		$w_list_blk_cs_id[$i] = trim($w_blk_id);

		### 数量トータル
		$w_total += $w_lot_bas['CHP_QTY'];

		# 同種チェック用
		$b_dvsn_cd = trim($w_lot_bas['DVSN_CD_PRD']);

		### ロットタイプが"CCSEM10"であればカウント
		if (trim($w_lot_bas['LOT_TYP_CD']) == constant("CC_RET")) {
			$w_ret_cnt++;
		}
	}

        #-----------------------------------------------------------------
        # PO/BO Control
        #-----------------------------------------------------------------
        $po_no_list_duplicate = array_count_values($po_no_list);
        foreach($po_no_list_duplicate as $val){
                if($val > 1){
                        list($g_msg, $g_err_lv) = msg("err_dif_po_control");
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
                }
        }

	$w_retlot_flg = 0;
	### 一つでも返品ロットがある場合
	if ($w_ret_cnt > 0) {
		### 全ロットが"CCSEM10"であれば返品ロットとする
		if ($w_ret_cnt == $cnt) {
			$w_retlot_flg = 1;
		### 返品ロット以外が混ざっている場合エラー
		} else {
			list($g_msg, $g_err_lv) = msg("err_Mix_NormRetLot");
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
	}

	#------------------------------------------------------------------
	# 画面セット
	#------------------------------------------------------------------
	$gw_scr['s_usr_nm'] = trim($w_usr_nm);
	$gw_scr['s_lp_nm']  = trim($w_lp_nm);

	$gw_scr['s_list_prd_nm']    = $w_list_prd_nm;
	$gw_scr['s_list_chp_qty']   = $w_list_chp_qty;
	$gw_scr['s_list_upd_lev']   = $w_list_upd_lev;
	$gw_scr['s_list_blk_cs_id'] = $w_list_blk_cs_id;

	$gw_scr['s_total'] = $w_total;
	$gw_scr['s_dtl_total'] = $w_total;
	$gw_scr['s_retlot_flg'] = $w_retlot_flg;

	scr_mode_chg(2);

	return 0;
}

###################################################################
#####                                                         #####
##### モード２                                                #####
#####                                                         #####
###################################################################
#==================================================================
# モード２
#==================================================================
function main_md2()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){
	case "EXECUTE":
		main_md2_exe();
		break;
	case "BACK":
		set_init(2);
		scr_mode_chg(1);
		break;
	}

	return 0;
}

#==================================================================
# ユーザが指定したユーザグループに属しているかチェック
#==================================================================
function has_usrgrp($w_usr_id, $w_usr_grp_cd, &$r_result)
{
        global $g_msg;
        global $g_err_lv;

        $r_result = 0;

        $w_sql = <<<_SQL
SELECT COUNT(*) as CNT FROM USR_GRP_MST
WHERE
DEL_FLG='0'
AND USR_ID='{$w_usr_id}'
AND USR_GRP_CD='{$w_usr_grp_cd}'
_SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "USR_GRP_MST", __LINE__);
                return 4000;
        }

        if ($w_row = db_fetch_row($w_stmt)) {
                $r_result = ($w_row['CNT'] == 0) ? 0 : 1;
        }
        db_res_free($w_stmt);

        return 0;
}


#==================================================================
# モード２ 実行ボタン押下処理
#==================================================================
function main_md2_exe()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# トランザクション開始
	#------------------------------------------------------------------
	db_begin();

	#------------------------------------------------------------------
	# 実行処理
	#------------------------------------------------------------------
	$w_rtn = main_exe();
	if($w_rtn != 0){
		db_rollback();
		return 4000;
	}

	#------------------------------------------------------------------
	# コミット
	#------------------------------------------------------------------
	db_commit();
#	db_rollback();

	#------------------------------------------------------------------
	# Transfer Note 発行
	#------------------------------------------------------------------
	### １行目のロットを指定
	
	for($i=0; $i < $gw_scr['s_prt_no']; $i++) {
		$w_rtn = xpt_lot_tmp($gw_scr['s_list_hdn_lot_id'][1],
						constant("TRNNOTE_HINA"),
						$gw_scr['s_lp_cd']);
	}
	if($w_rtn != 0){
		$gw_scr['s_prnt_lv'] = 0;
		$gw_scr['s_prnt_msg'] = $g_msg;
		$g_msg = "";
		$g_err_lv = "";
	}

	list($g_msg, $g_err_lv) = msg("end_Update");
	$g_msg = xpt_err_msg($g_msg, "", "");

	if($gw_scr['s_prnt_msg'] == ""){
		list($gw_scr['s_prnt_msg'], $gw_scr['s_prnt_lv']) = msg("end_Print_TrnNote");
		$gw_scr['s_prnt_msg'] = xpt_err_msg($gw_scr['s_prnt_msg'], "", "");
	}

	scr_mode_chg(3);

	return 0;
}

#==================================================================
# Checking UTL Product
#==================================================================
function check_utl_product($w_prdcd, &$r_is_utl_product)
{
        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        $r_is_utl_product = false;
        $w_utl_flg = constant("AW_UTL_FLG");

        # Prepare the SQL
        $w_sql = <<<_SQL
SELECT
        *
FROM
        PRD_INF_MST
WHERE
        PRD_CD = '{$w_prdcd}' AND
        DAT_CD = '{$w_utl_flg}' AND
        TXT_DAT = '1' AND
        DEL_FLG = '0'
_SQL;

        # Execute the Query
        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);

        # Check the Result
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
                return 4000;
        }

        # get the Data
        $cnt = 0;
        while($w_row = db_fetch_row($w_stmt)){
                $cnt++;
        }

        # Check the row count
        if( $cnt > 0 ) {
                $r_is_utl_product = true;
        }else{
                $r_is_utl_product = false;
        }

        db_res_free($w_stmt);

        return 0;
}

#==================================================================
# Verify if Finished with Ftest step
#==================================================================
function check_FtestStep($w_lot_id,
                                                                        $w_verb,
                                                                        $w_e9_cd,
                                                                        &$r_bln_check
                                                                        )
{
        global $g_msg;
        global $g_err_lv;
        $r_bln_check = FALSE;

        $w_sql = <<<_SQL
SELECT
        count(*) as COUNTA
FROM
        LOT_LOG
WHERE
        LOT_ID = '{$w_lot_id}'
        AND VERB = '{$w_verb}'
        AND STP_CD IN (select stp_cd from stp_mst where stp_cls_2 = '{$w_e9_cd}' and del_flg = '0')
        AND DEL_FLG = '0'
_SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                $g_msg = cs_xck_exst_child_msg("err_Sel");
                return 4000;
        }

        $w_row = db_fetch_row($w_stmt);
        db_res_free($w_stmt);

        #echo $w_sql;

        if($w_row['COUNTA'] > 0){
                $r_bln_check = TRUE;
        }


        return 0;
}

#==================================================================
# 実行処理
#==================================================================
function main_exe()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;

	#------------------------------------------------------------------
	# テーブルロック
	#------------------------------------------------------------------
	$w_rtn = db_lock('CD_USE_CNT_TBL');
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# １行目のロット基本情報取得(伝票番号取得用)
	#------------------------------------------------------------------
	$w_rtn = xgt_lot($gw_scr['s_list_hdn_lot_id'][1], $w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# 伝票番号取得
	#------------------------------------------------------------------
	$w_cd = trim($w_lot_bas['DVSN_CD_PRD']) . "_" . date("dmy");
	$w_cntdvs = constant("CNTDVS_SLIP");
	$w_rtn = xgt_cd_cnt($gw_scr['s_usr_id'], $w_cd, $w_cntdvs, $w_cnt);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}
	### 商品コード(後2桁) + DDMMYY + 4桁連番
	$w_slip_no = substr(trim($w_lot_bas['DVSN_CD_PRD']), -2)
				. date("dmy")
				. sprintf("%04s", $w_cnt);
	### 返品ロットの場合は先頭に"RE"を付与
	if ($gw_scr['s_retlot_flg'] == 1) $w_slip_no = "RE" . $w_slip_no;

	#------------------------------------------------------------------
	# ロットごとの処理
	#------------------------------------------------------------------
	for($i=1; $i<=constant("MAX_ROW"); $i++){
		if($gw_scr['s_list_hdn_lot_id'][$i] == "") continue;
		#------------------------------------------------------------------
		# ロット基本情報取得
		#------------------------------------------------------------------
		$w_rtn = xgt_lot($gw_scr['s_list_hdn_lot_id'][$i], $w_lot_bas);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
			return 4000;
		}
		#------------------------------------------------------------------
		# 更新レベルチェック
		#------------------------------------------------------------------
		$w_rtn = xck_upd($gw_scr['s_list_upd_lev'][$i], $w_lot_bas['UPD_LEV']);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_list_hdn_lot_id'][$i], __LINE__);
			return 4000;
		}

		$w_rtn = xgt_stp_cls($w_lot_bas['STP_CD'], $w_stpcls, $dmy);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $w_lot_bas['STP_CD'], __LINE__);
			return 4000;
		}

			$w_rtn = main_verb_iodi($gw_scr['s_usr_id'], $w_lot_bas);
			if($w_rtn != 0) return 4000;
		#------------------------------------------------------------------
		# LOG_BIND_TBL登録
		#------------------------------------------------------------------
		$w_rtn = ins_log_bind_tbl($gw_scr['s_usr_id'],
								$w_slip_no,
								$i,
								$gw_scr['s_list_hdn_lot_id'][$i],
								"IODI",
								$w_lot_bas['STP_CD']);
		if($w_rtn != 0) return 4000;
	}

	#------------------------------------------------------------------
	# LOG_BIND_INF登録
	#------------------------------------------------------------------
	$w_rtn = ins_log_bind_inf($gw_scr['s_usr_id'],
							$w_slip_no,
							constant("TRNNOTE_HINA"),
							"IODI");
	if($w_rtn != 0) return 4000;


	return 0;
}

#==================================================================
# LOG_BIND検索
#==================================================================
function chk_bind($w_lot_id, &$r_dat)
{
	global $g_msg;
	global $g_err_lv;

	$r_dat = array();

	$w_ctgdvs  = constant("CE_SLINF");
	$w_ctgcd   = constant("CT_DIFFLOTNO");
	$w_binddvs = constant("DVS_LAMINATE");

	$w_sql = <<<_SQL
SELECT
	BND.BIND_ID,
	BND.BIND_TXT_1,
	BND.BIND_TXT_2
FROM
	LOT_INF_TBL LIT,
	LOG_BIND_INF BND
WHERE
	LIT.LOT_ID = '{$w_lot_id}'
	AND LIT.CTG_DVS_CD = '{$w_ctgdvs}'
	AND LIT.CTG_CD = '{$w_ctgcd}'
	AND LIT.DEL_FLG = '0'
	AND BND.BIND_ID = LIT.CTG_DAT_TXT
	AND BND.BIND_DVS = '{$w_binddvs}'
	AND BND.DEL_FLG = '0'
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "LOG_BIND_INF", __LINE__);
		return 4000;
	}

	$w_row = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	if($w_row){
		$r_dat['BIND_ID'] = trim($w_row['BIND_ID']);
		$r_dat['LOT1']    = trim($w_row['BIND_TXT_1']);
		$r_dat['LOT2']    = trim($w_row['BIND_TXT_2']);
	}


	return 0;
}

#==================================================================
# LOG_BIND検索
#==================================================================
function wcs_check($w_prd_cd)
{
        global $g_msg;
        global $g_err_lv;

        $r_dat = "";

        $w_awcode  = constant("AW_WCSCHK");

        $w_sql = <<<_SQL
SELECT 
     TXT_DAT
FROM 
     PRD_INF_MST INF
WHERE 
      DAT_CD = '{$w_awcode}' AND     
      PRD_CD = '{$w_prd_cd}' AND 
      DEL_FLG = '0' 
_SQL;
        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "PRD_INF_MST", __LINE__);
                return 4000;
        }

        $w_row = db_fetch_row($w_stmt);
        db_res_free($w_stmt);

        if($w_row){
                $r_dat = trim($w_row['TXT_DAT']);
		$w_prdnm = trim($w_row['PRD_NM']);
        }
	if($r_dat == "") {
                list($g_msg, $g_err_lv) = msg("err_Wcs_Chk");
                $g_msg = xpt_err_msg($g_msg, "","");
                return 4000;		
	}
        return 0;
}

# ログまとめテーブル(詳細)登録
#==================================================================
function ins_log_bind_tbl($w_usr_id,
						$w_bind_id,
						$w_line_no,
						$w_lot_id,
						$w_verb,
						$w_stp_cd)
{
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;

	#------------------------------------------------------------------
	# ログまとめテーブル(詳細)
	#------------------------------------------------------------------
	$w_ins = array
	(
		'DEL_FLG'		=> '0',
		'BIND_ID'		=> $w_bind_id,
		'LINE_NO'		=> $w_line_no,
		'LOT_ID'		=> $w_lot_id,
		'VERB'			=> $w_verb,
		'STP_CD'		=> $w_stp_cd,
		'LOG_CRT_DTS'	=> $g_cpu_dts,
		'CRT_DTS'		=> $g_cpu_dts,
		'USR_ID_CRT'	=> $w_usr_id,
		'UPD_DTS'		=> $g_low_dts,
		'USR_ID_UPD'	=> ' ',
		'UPD_LEV'		=> 1,
	);
	$w_rtn = db_insert("LOG_BIND_TBL", $w_ins);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Ins");
		$g_msg = xpt_err_msg($g_msg, "LOG_BIND_TBL", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# ログまとめテーブル(情報)登録
#==================================================================
function ins_log_bind_inf($w_usr_id,
						$w_bind_id,
						$w_form_id,
						$w_verb)
{
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;

	#------------------------------------------------------------------
	# ログまとめテーブル(情報)
	#------------------------------------------------------------------
	$w_ins = array
	(
		'DEL_FLG'		=> '0',
		'BIND_ID'		=> $w_bind_id,
		'BIND_DVS'		=> $w_verb,
		'BIND_TXT_1'	=> ' ',
		'BIND_TXT_G_1'	=> ' ',
		'BIND_TXT_2'	=> ' ',
		'BIND_TXT_G_2'	=> ' ',
		'BIND_TXT_3'	=> ' ',
		'BIND_TXT_G_3'	=> ' ',
		'FORM_ID'		=> $w_form_id,
		'BIND_QTY_1'	=> 0,
		'BIND_QTY_2'	=> 0,
		'BIND_QTY_3'	=> 0,
		'CRT_DTS'		=> $g_cpu_dts,
		'USR_ID_CRT'	=> $w_usr_id,
		'UPD_DTS'		=> $g_low_dts,
		'USR_ID_UPD'	=> ' ',
		'UPD_LEV'		=> 1,
	);
	$w_rtn = db_insert("LOG_BIND_INF", $w_ins);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Ins");
		$g_msg = xpt_err_msg($g_msg, "LOG_BIND_INF", __LINE__);
		$gw_scr['s_msg']    = $g_msg;
		$gw_scr['s_err_lv'] = $g_err_lv;
		return 4000;
	}

	return 0;
}

#==================================================================
# 入力チェック処理
#==================================================================
function check_input($w_mode)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch ($w_mode) {
	case 1:
		# トリム＆大文字化
		$gw_scr['s_usr_id'] = strtoupper(trim($gw_scr['s_usr_id']));
		$gw_scr['s_lp_cd']  = strtoupper(trim($gw_scr['s_lp_cd']));
		$exst = 0;
		for($i=1; $i<=constant("MAX_ROW"); $i++){
			$gw_scr['s_list_lot_id'][$i] = strtoupper(trim($gw_scr['s_list_lot_id'][$i]));
			if($exst == 0 && $gw_scr['s_list_lot_id'][$i] != "") $exst = 1;
		}

		# 必須
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		if($gw_scr['s_usr_id'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("UsrId"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_lp_cd'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("LpCd"), __LINE__);
			return 4000;
		}
                if($gw_scr['s_prt_no'] == ""){
                        $g_msg = xpt_err_msg($g_msg, itm("PrintNo"), __LINE__);
                        return 4000;
                }

		if($exst == 0){
			$g_msg = xpt_err_msg($g_msg, itm("LotId"), __LINE__);
			return 4000;
		}
		list($g_msg, $g_err_lv) = msg("err_Inp_Num");
                if(!is_numeric($gw_scr['s_prt_no'])){
		   	$g_msg = xpt_err_msg($g_msg, itm("PrintNo"), __LINE__);
			return 4000;
		}
		list($g_msg, $g_err_lv) = msg("err_Inp_Par");
                if(($gw_scr['s_prt_no'] < 1) || ($gw_scr['s_prt_no'] > 10)){
                        $g_msg = xpt_err_msg($g_msg, itm("PrintNo"), __LINE__);
                        return 4000;
                }


		# 禁止文字
		list($g_msg, $g_err_lv) = msg("err_Inp_Char");
		if(!check_eisu($gw_scr['s_usr_id'])){
			$g_msg = xpt_err_msg($g_msg, itm("UsrId"), __LINE__);
			return 4000;
		}
		if(!check_err_code($gw_scr['s_lp_cd'])){
			$g_msg = xpt_err_msg($g_msg, itm("LpCd"), __LINE__);
			return 4000;
		}
		for($i=1; $i<=constant("MAX_ROW"); $i++){
			if(substr($gw_scr['s_list_lot_id'][$i], 0, 2) == "LT"){
				if(!check_err_lot($gw_scr['s_list_lot_id'][$i])){
					$w_tg = get_tg($i, itm("LotId"));
					$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
					return 4000;
				}
			} elseif(substr($gw_scr['s_list_lot_id'][$i], 0, 2) == "B ") {
				if(!preg_match("/^[A-Z0-9\.\$\/\+\<\>\- ]+$/", $gw_scr['s_list_lot_id'][$i])){
					$w_tg = get_tg(itm("LotId"), $gw_scr['s_list_lot_id'][$i]);
					$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
					return 4000;
				}
			}
		}

		break;

	case 2:
		# トリム
		$gw_scr['s_mth_chp_qty'] = trim($gw_scr['s_mth_chp_qty']);
		$gw_scr['s_mth_bar_qty'] = trim($gw_scr['s_mth_bar_qty']);
		$gw_scr['s_rej_chp_qty'] = trim($gw_scr['s_rej_chp_qty']);
		$gw_scr['s_rej_bar_qty'] = trim($gw_scr['s_rej_bar_qty']);
		for($i=1; $i<=$gw_scr['s_nxt_prd_cnt']; $i++){
			$gw_scr['s_list_plt_chp_qty'][$i] = trim($gw_scr['s_list_plt_chp_qty'][$i]);
			$gw_scr['s_list_plt_bar_qty'][$i] = trim($gw_scr['s_list_plt_bar_qty'][$i]);
		}
		for($i=1; $i<=$gw_scr['s_bad_cnt']; $i++){
			$gw_scr['s_list_bad_ctg_qty'][$i] = trim($gw_scr['s_list_bad_ctg_qty'][$i]);
		}
		for($i=1; $i<=$gw_scr['s_mng_cnt']; $i++){
			$gw_scr['s_list_mng_ctg_qty'][$i] = trim($gw_scr['s_list_mng_ctg_qty'][$i]);
		}

		# 必須
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		if($gw_scr['s_mth_chp_qty'] == ""){
			$w_tg = get_tg(itm("MthrQty"), itm("ChpQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($gw_scr['s_mth_bar_qty'] == ""){
			$w_tg = get_tg(itm("MthrQty"), itm("BarQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($gw_scr['s_bad_cnt'] > 0){
			if($gw_scr['s_rej_chp_qty'] == ""){
				$w_tg = get_tg(itm("RjctQty"), itm("ChpQty"));
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			if($gw_scr['s_rej_bar_qty'] == ""){
				$w_tg = get_tg(itm("RjctQty"), itm("BarQty"));
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		$chpexst = 0;
		$barexst = 0;
		for($i=1; $i<=$gw_scr['s_nxt_prd_cnt']; $i++){
			if($gw_scr['s_list_plt_chp_qty'][$i] != ""){
				$chpexst = 1;
			}
			if($gw_scr['s_list_plt_bar_qty'][$i] != ""){
				$barexst = 1;
			}
		}
		if($chpexst == 0){
			$w_tg = get_tg(itm("PltLot"), itm("ChpQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($barexst == 0){
			$w_tg = get_tg(itm("PltLot"), itm("BarQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}

		# 禁止文字
		list($g_msg, $g_err_lv) = msg("err_Inp_Char");
		if(!check_num($gw_scr['s_mth_chp_qty'])){
			$w_tg = get_tg(itm("MthrQty"), itm("ChpQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_num($gw_scr['s_mth_bar_qty'])){
			$w_tg = get_tg(itm("MthrQty"), itm("BarQty"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if($gw_scr['s_bad_cnt'] > 0){
			if(!check_num($gw_scr['s_rej_chp_qty'])){
				$w_tg = get_tg(itm("RjctQty"), itm("ChpQty"));
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			if(!check_num($gw_scr['s_rej_bar_qty'])){
				$w_tg = get_tg(itm("RjctQty"), itm("BarQty"));
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		for($i=1; $i<=$gw_scr['s_nxt_prd_cnt']; $i++){
			if($gw_scr['s_list_plt_chp_qty'][$i] != ""
			&& !check_num($gw_scr['s_list_plt_chp_qty'][$i])
			){
				$w_tg = get_tg(itm("PltLot"), $gw_scr['s_list_plt_prd_nm'][$i], itm("ChpQty"));
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			if($gw_scr['s_list_plt_bar_qty'][$i] != ""
			&& !check_num($gw_scr['s_list_plt_bar_qty'][$i])
			){
				$w_tg = get_tg(itm("PltLot"), $gw_scr['s_list_plt_prd_nm'][$i], itm("BarQty"));
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		for($i=1; $i<=$gw_scr['s_bad_cnt']; $i++){
			if($gw_scr['s_list_bad_ctg_qty'][$i] != ""
			&& !check_num($gw_scr['s_list_bad_ctg_qty'][$i])
			){
				$w_tg = get_tg(itm("RjctDtl"), $gw_scr['s_list_bad_ctg_nm'][$i]);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		}
		for($i=1; $i<=$gw_scr['s_mng_cnt']; $i++){
			if($gw_scr['s_list_mng_num_flg'][$i] == "1"){
				if($gw_scr['s_list_mng_ctg_qty'][$i] != ""
				&& !check_num($gw_scr['s_list_mng_ctg_qty'][$i])
				){
					$w_tg = get_tg(itm("CtrlItm"), $gw_scr['s_list_mng_ctg_nm'][$i]);
					$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
					return 4000;
				}
			} else {
				if($gw_scr['s_list_mng_ctg_qty'][$i] != ""
				&& !check_err_meisho($gw_scr['s_list_mng_ctg_qty'][$i])
				){
					$w_tg = get_tg(itm("CtrlItm"), $gw_scr['s_list_mng_ctg_nm'][$i]);
					$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
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

###################################################################
#####                                                         #####
##### VERB                                                    #####
#####                                                         #####
###################################################################
#==================================================================
# [VERB] IOIN(着手)
#==================================================================
function main_verb_ioin($w_usr_id, $w_equ_cd, &$w_lot_bas)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# ロット状態のチェック
	#------------------------------------------------------------------
	$w_rtn = ioin_st_check($w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}

	#------------------------------------------------------------------
	# 装置の指定が無い場合は、使用可能な装置取得
	#------------------------------------------------------------------
	if($w_equ_cd == ""){
		$w_rtn = xgt_use_equ($w_lot_bas, $w_equ_cd);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
	}

	#------------------------------------------------------------------
	# 装置構成マスタのチェック
	#------------------------------------------------------------------
	$w_rtn = ioin_equ_check($w_equ_cd, $w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}

	#------------------------------------------------------------------
	# ＩＯＩＮ
	# 戻り値：$w_lot_bas
	#------------------------------------------------------------------
	$w_rtn = ioin($w_lot_bas['LOT_ID'],			# ロットＩＤ
					$w_usr_id,					# ユーザＩＤ
					$w_lot_bas['UPD_LEV'],		# 更新レベル
					$w_equ_cd,					# 装置コード
					"",							# コメント
					$w_lot_bas);				# 戻り値：ロット基本情報

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# 完了 ＩＯＯＴ
#==================================================================
function main_verb_ioot($w_usr_id, $w_ctg_dvs_cd, $w_ctg_cd,
			$w_ctg_qty, $w_ctg_dat_txt, $w_ctg_slid, $w_sl_qty_ok,
			$w_chp_qty_ok, $w_lf_qty_ok, $w_cmt, &$w_lot_bas)
{
	global $g_msg;
	global $g_err_lv;


	#------------------------------------------------------------------
	# 最終ＩＯブロック確認 xck_lio
	# 戻り値：	$w_lot_st_dvs	ロット状態区分
	#			$w_io_blc_cs	ＩＯブロックコード
	#			$w_stp_cd		ステップコード
	#			$w_stp_no		ステップ番号
	#------------------------------------------------------------------
	$w_rtn = xck_lio(
					$w_lot_bas['PRC_CD'],
					$w_lot_bas['IO_BLC_CD'],
					$w_lot_bas['PLT_DVS_CD'],
					$w_lot_st_dvs,				# 戻り値：ロット状態区分
					$w_io_blc_cd,				# 戻り値：ＩＯブロックコード
					$w_stp_cd,					# 戻り値：ステップコード
					$w_stp_no);					# 戻り値：ステップ番号

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, '', __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# カテゴリ設定
	#------------------------------------------------------------------
	$w_ctg_flg = 0;
	if(is_array($w_ctg_cd)){
		for($i=1; $i<=count($w_ctg_cd); $i++){
			$w_arr_cnt = $i;
			$w_arr_ctg_dvs_cd[$i] = $w_ctg_dvs_cd[$i];
			$w_arr_ctg_cd[$i] = $w_ctg_cd[$i];
			$w_arr_txt[$i] = $w_ctg_dat_txt[$i];
			$w_arr_equ_cd[$i] = $w_lot_bas['EQU_CD'];
			$w_arr_sl_id[$i] = $w_ctg_slid[$i];
			$w_arr_qty[$i] = $w_ctg_qty[$i];
		}
		$w_ctg_flg = 1;
	}

	if($w_ctg_flg == 0){
		$w_arr_cnt = 0;
		$w_arr_ctg_dvs_cd = '';
		$w_arr_ctg_cd = '';
		$w_arr_equ_cd = '';
		$w_arr_sl_id = '';
		$w_arr_qty = '';
		$w_arr_txt = '';
	}

	#------------------------------------------------------------------
	# ＩＯＯＴ
	# 戻り値：$w_lot_bas
	#------------------------------------------------------------------
	$w_rtn = ioot(
				$w_lot_bas['LOT_ID'],			# ロットＩＤ
				$w_usr_id,						# ユーザＩＤ
				$w_lot_bas['UPD_LEV'],			# 更新レベル
				$w_cmt,							# コメント
				$w_lot_st_dvs,					# ロット状態区分
				$w_sl_qty_ok,					# 良品SL_QTY
				$w_chp_qty_ok,					# 良品チップ数
				$w_lf_qty_ok,					# 良品スライス数
				$w_lot_bas['SECRET_NO'],		# 密番
				$w_arr_cnt,						# カテゴリ数
				$w_arr_ctg_dvs_cd,				# カテゴリ区分コード
				$w_arr_ctg_cd,					# カテゴリコード
				$w_arr_equ_cd,					# カテゴリ装置コード
				$w_arr_sl_id,					# カテゴリスライスＩＤ
				$w_arr_qty,						# カテゴリ数量
				$w_arr_txt,						# カテゴリ収集データ
				$w_lot_bas);					# 戻り値：ロット基本情報

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, '', __LINE__);
		return 4000;
	}

	return 0;
}

#=========================================================================
# ＩＯＤＩ
#=========================================================================
function main_verb_iodi($w_usr_id, $w_lot_bas)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# 状態チェック
	#------------------------------------------------------------------
	$w_rtn = iodi_st_check($w_lot_bas['LOT_ST_DVS']);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		$gw_scr['s_msg']    = $g_msg;
		$gw_scr['s_err_lv'] = $g_err_lv;
		return 4000;
	}

	#------------------------------------------------------------------
	# Ｖｅｒｂ（倉入）実行
	#------------------------------------------------------------------
	$w_rtn = iodi($w_lot_bas['LOT_ID'], $w_usr_id, 
						$w_lot_bas['UPD_LEV'], " ", "", $w_lot_bas);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		$gw_scr['s_msg']    = $g_msg;
		$gw_scr['s_err_lv'] = $g_err_lv;
		return 4000;
	}

	return 0;
}

#=========================================================================
# Ｖｅｒｂ処理 他部門渡し
# 引数：$w_lot_id		I	ロットＩＤ
#		$w_bu_cd_gp		I	[AM]予算単位コード(受け渡し先)
#		$w_cmt			I	コメント
#		$w_lot_bas		I/O	ロット基本情報
#
# Ｖｅｒｂ処理後のロット基本情報が戻ることに注意
#=========================================================================
function main_verb_iosd($w_usr_id, $w_lot_id, $w_bu_cd_gp, $w_cmt, &$w_lot_bas)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#----------------------------------------------------------
	# 状態チェック
	#----------------------------------------------------------
	$w_rtn = iosd_st_check($w_lot_bas['LOT_ST_DVS']);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#----------------------------------------------------------
	# ＩＯＳＤ
	# 戻り値：$w_lot_bas
	#----------------------------------------------------------
	$w_rtn = iosd($w_lot_id,
				  $w_usr_id,
				  $w_lot_bas['UPD_LEV'],
				  $w_bu_cd_gp,
				  $w_cmt,
				  $w_lot_bas);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#=========================================================================
# 材料ＶＥＲＢ処理：材料渡し
# 引数：$w_shp_cd_t		I			ショップコード（受け位置）
#		$w_qty			I			渡し数量
#		$w_req_id		I			要求ＩＤ
#		$w_cmt			I			コメント
#		$w_prt_wip		I/O			材料在庫情報
#=========================================================================
function main_verb_mtsd($w_usr_id, $w_shp_cd_t, $w_qty, $w_req_id, $w_cmt, &$w_prt_wip)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------
	# 材料渡し（ＭＴＳＤ）状態チェック
	#------------------------------------------------------
	$w_rtn = mtsd_st_check($w_prt_wip['PRT_ST_DVS']);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	if($w_req_id == ""){
		$w_req_id = " ";
	}

	#------------------------------------------------------
	# 材料渡し（ＭＴＳＤ）
	# 戻り値：$w_prt_wip
	#------------------------------------------------------
	$w_rtn = mtsd($w_shp_cd_t,
				  $w_usr_id,
				  $w_prt_wip['UPD_LEV'],
				  $w_qty,
				  $w_req_id,
				  $w_cmt,
				  $w_prt_wip);

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#=========================================================================
# 材料ＶＥＲＢ処理：材料クリエイト
# 引数：$w_mtcr			I			PRT_WIP_TBL情報事前にセット
#		$w_prt_wip		I/O			材料在庫情報
#=========================================================================
function main_verb_mtcr($w_usr_id, $w_mtcr, &$w_prt_wip)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------
	# ＭＴＣＲ
	# 戻り値：$w_prt_wip
	#------------------------------------------------------
	$w_rtn = mtcr(
				$w_mtcr['PRT_CD'],
				$w_mtcr['SHP_CD'],
				$w_mtcr['MT_LOT_ID'],
				$w_mtcr['MT_LOT_NO'],
				$w_mtcr['QTY'],
				$w_mtcr['EQU_CD'],
				$w_mtcr['REQ_ID'],
				$w_usr_id,
				$w_mtcr['CMT'],
				$w_prt_wip);

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#=========================================================================
# 材料ＶＥＲＢ処理：材料受け
# 引数：$w_shp_cd_t		I			ショップコード（受け位置）
#		$w_req_id		I			伝票Ｎｏ
#		$w_cmt			I			コメント（部門コード）
#		$w_prt_wip		I/O			材料在庫情報
#=========================================================================
function main_verb_mtrv($w_usr_id, $w_shp_cd_t, $w_req_id, $w_cmt, &$w_prt_wip)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------
	# ロット状態チェック
	#------------------------------------------------------
	$w_rtn = mtrv_st_check($w_prt_wip['PRT_ST_DVS']);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	if($w_req_id == ""){
		$w_req_id = " ";
	}

	#------------------------------------------------------
	# ＭＴＲＶ
	# 戻り値：$w_prt_wip
	#------------------------------------------------------
	$w_rtn = mtrv(
				$w_shp_cd_t,
				$w_usr_id,
				$w_prt_wip['UPD_LEV'],
				$w_req_id,
				$w_cmt,
				$w_prt_wip);

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#=========================================================================
# 材料ＶＥＲＢ処理：材料追加投入
# 引数：$w_shp_cd_t		I			ショップコード（受け位置）
#		$w_req_id		I			伝票Ｎｏ
#		$w_cmt			I			コメント（部門コード）
#		$r_prt_wip		I/O			材料在庫情報
#=========================================================================
function main_verb_mtad($w_usr_id, $w_shp_cd_t, $w_qty, $w_req_id, $w_cmt, &$r_prt_wip)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------
	# ロット状態チェック
	#------------------------------------------------------
	$w_rtn = mtad_st_check($r_prt_wip['PRT_ST_DVS']);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}

	if($w_req_id == "") {
		$w_req_id = " ";
	}

	#------------------------------------------------------
	# ＭＴＡＤ
	# 戻り値：$r_prt_wip
	#------------------------------------------------------
	$w_rtn = mtad(
				$w_shp_cd_t,
				$w_qty,
				$w_usr_id,
				$r_prt_wip['UPD_LEV'],
				$w_req_id,
				$w_cmt,
				$r_prt_wip);

	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}

	return 0;
}

#=========================================================================
# 材料使用開始
#=========================================================================
function main_verb_mtin($w_usr_id, $w_lot_id, $w_prtwip)
{
	global $g_msg;
	global $g_err_lv;

    #------------------------------------------------------------------
    # 材料状態チェック
    #------------------------------------------------------------------
    $w_rtn = mtin_st_check($w_prtwip);
    if($w_rtn != 0){
        $g_err_lv = 0;
        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
        return 4000;
    }
         
    #------------------------------------------------------------------
    # MTIN 材料使用開始
    #------------------------------------------------------------------
	$w_rtn = mtin($w_lot_id,
				  $w_prtwip['MT_LOT_ID'],
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

#=========================================================================
# 材料使用終了
#=========================================================================
function main_verb_mtot($w_usr_id, $w_lot_id, &$w_prtwip)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# 材料在庫チェック
	#------------------------------------------------------------------
	$w_rtn = mtot_st_check($w_prtwip);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# MTOT 材料使用終了
	#------------------------------------------------------------------
	$w_rtn = mtot($w_lot_id,
				  $w_prtwip['MT_LOT_ID'],
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


###################################################################
#####                                                         #####
##### その他 画面系関数                                       #####
#####                                                         #####
###################################################################
#==================================================================
# 表示項目初期化処理
#==================================================================
function set_init($w_mode)
{
	global $gw_scr;

	### モード１
	if($w_mode == 1){
		$gw_scr['s_usr_id']      = "";
		$gw_scr['s_lp_cd']       = "";
		$gw_scr['s_prt_no']      = 2;
	}

	### モード２
	if($w_mode <= 2){
		$gw_scr['s_list_hdn_lot_id'] = array();
		$gw_scr['s_usr_nm']  = "";
		$gw_scr['s_lp_nm']   = "";
		$gw_scr['s_prt_no']  = 2;
		$gw_scr['s_list_prd_nm']  = array();
		$gw_scr['s_list_chp_qty'] = array();
		$gw_scr['s_list_upd_lev'] = array();
		$gw_scr['s_list_blk_cs_id'] = array();

		$gw_scr['s_total'] = "";
		$gw_scr['s_dtl_total'] = "";
	}


	return 0;
}

#==================================================================
# 画面表示直前処理
#==================================================================
function scr_setting()
{
	global $gw_scr;
	global $g_mode;

	return;
}

#==================================================================
#==================================================================
function set_space($w_var)
{
	if($w_var == "" || is_null($w_var)){
		$w_var = " ";
	}
	return $w_var;
}
#==================================================================
# 配列をシリアライズ
#==================================================================
function userialize($w_arr)
{
	return bin2hex(serialize($w_arr));
}
#==================================================================
# シリアライズ化した文字列を配列に復帰
#==================================================================
function uunserialize($w_serial)
{
	return unserialize(pack("H*", $w_serial));
}
#==================================================================
# エラー時の対象文字列生成
#==================================================================
function get_tg()
{
	$w_arr = func_get_args();
	return implode("/", $w_arr);
}
#==================================================================
#
#==================================================================
function itm($var)
{
	return PS00S01002540_item($var);
}
function msg($var)
{
	return PS00S01002540_msg($var);
}
#******************************************************************
#******************************************************************
#******************************************************************
#******************************************************************
#******************************************************************
#
# MAIN処理開始
#
#******************************************************************
#==================================================================
# DB接続
#==================================================================
$w_rtn = xdb_op_conndb();
if ($w_rtn != 0) {
	$g_err_lv = 0;
	$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	return;
}
#==================================================================
# 認証
#==================================================================
# 再認証(要Scr記述 session含む セッション取得後に記述)
$refe_flg=1;
require_once (getenv("GPRISM_HOME") . "/renzheng.php");
$bak_s_renzheng_t = $gw_scr['s_renzheng_t'];	# 一時退避
$bak_s_renzheng   = $gw_scr['s_renzheng'];		# 一時退避
#==================================================================
# モードごとの処理
#==================================================================
# 関数名定義
$w_func = "main_md" . $g_mode;
if(function_exists($w_func)){
	$w_func();
} else {
	main_init();
}

$gw_scr['s_renzheng']   = $bak_s_renzheng;		# 認証用
$gw_scr['s_renzheng_t'] = $bak_s_renzheng_t;	# 認証用

scr_setting();
get_screen(1);


$g_js_i ="
<script type='text/javascript'>
function check(element,display){
        var user_id = document.getElementsByName('s_usr_id')[0].value;
        if(element.value != ''){
                var status = check_lot(element.value,user_id);
//	alert(status.success.message);
                if(status.error){
                        display.value = status.error.message;
                        changeInputType(display,'text');
                        element.style.borderColor = '#ff0000';
                }else{
                        display.value = status.success.message;
                        changeInputType(display,'text');
                        element.style.borderColor = '';
                }
        }else{
                display.value ='';
                changeInputType(display,'text');
                element.style.borderColor = '';
        }
}

function check_lot(lot_id,user_id){
        var xmlhttp;
        var response;
        if (window.XMLHttpRequest){
                // code for IE7+, Firefox, Chrome, Opera, Safari
                xmlhttp=new XMLHttpRequest();
        }else{
                // code for IE6, IE5
                xmlhttp=new ActiveXObject('Microsoft.XMLHTTP');
        }
        xmlhttp.onreadystatechange = function(){
                if (xmlhttp.readyState == 4 && xmlhttp.status == 200){
                        var resp = eval('('+xmlhttp.responseText+')');
                        response = resp;
                }
        }
	wholeUrl = '/Gprism/Lot2/Func/cs_chk_sni_delivery.php?l=/Lang/en&c=x-euc-jp&user_id='+user_id;
	for(var i=1;i<=100;i++){
		var index = i;
//		wholeUrl += '&lot_id'+index+'='+lot_id;
		wholeUrl +=  '&lot_id'+index+'='+document.getElementsByName('s_list_lot_id['+index+']')[0].value;
	}
	//alert('http://". $_SERVER['SERVER_NAME'] ."'+wholeUrl);
	tmpurl = 'http://". $_SERVER['SERVER_NAME'] ."'+wholeUrl;
//alert(tmpurl);
	xmlhttp.open('GET', tmpurl, false);
//	xmlhttp.open('GET', 'http://". $_SERVER['SERVER_NAME'] ."'+wholeUrl,false);
//        xmlhttp.open('GET', 'http://". $_SERVER['SERVER_NAME'] ."/Gprism/Lot2/Func/cs_chk_sni_delivery.php?l=/Lang/en&c=x-euc-jp&lot_id='+lot_id+'&user_id='+user_id,false);
//        alert('http://10.81.162.193/Gprism/Lot2/Func/cs_chk_sni_delivery.php?l=/Lang/en&c=x-euc-jp&lot_id='+lot_id+'&user_id='+user_id');
	xmlhttp.send();
        return response;
}

for(var i=1;i<=100;i++){
         document.getElementsByName('s_list_lot_id['+i+']')[0].onblur = (function(){
                var index = i;
                 return function(){
                        check(document.getElementsByName('s_list_lot_id['+index+']')[0],
                                document.getElementsByName('s_list_prd_nm['+index+']')[0])
                        }
                })();
}

function changeInputType(oldObject, oType) {
        var newObject = document.createElement('span');
        //newObject.type = oType;
        if(oldObject.size) newObject.size = oldObject.size;
        if(oldObject.value) newObject.innerHTML = oldObject.value;
        if(oldObject.name) newObject.name = oldObject.name;
        if(oldObject.id) newObject.id = oldObject.id;
        if(oldObject.className) newObject.className = oldObject.className;
        newObject.className = 'dis_text';
        newObject.readonly = 'true';
        if(oldObject.parentNode.childNodes.length < 3)
                oldObject.parentNode.insertBefore(newObject,oldObject);
        else
                oldObject.parentNode.replaceChild(newObject,oldObject.parentNode.childNodes[0]);
        return newObject;
}
</script>
";

echo $g_js_i;
#==================================================================
# 処理終了
#==================================================================
xdb_op_closedb();
?>
