<?php
# ======================================================================================
# [DATE]  : 2013.01.15		  	[AUTHOR]  : MIS) L.Acera
# [SYS_ID]: GPRISM			[SYSTEM]  : 
# [SUB_ID]:				[SUBSYS]  :
# [PRC_ID]:				[PROCESS] :
# [PGM_ID]: PS00S01001400.php   	[PROGRAM] : CreatePackingLot(LSI)
# [MDL_ID]:				[MODULE]  :
# --------------------------------------------------------------------------------------
# [COMMENT]
#
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
#
# [UPDATE_PERSON]	[UPDATE]		[COMMENT]
# ====================	=================	============================================
# MIS)L.Acera		2013-01-02		Copy from Create Packing Lot(LD)	
# MIS)L.Acera		2013-01-27		Update Verb Flow
# MIS)L.Acera		2013-05-06		
# --------------------------------------------------------------------------------------

#******************************************************************
#
# プログラム設定
#
#******************************************************************
$g_Version = "2.0";
$g_PrgCD = "PS00S01001400";

#******************************************************************
#
# 共通関数読込
#
#******************************************************************
require_once(getenv("GPRISM_HOME") . "/DirList_pf.php"); 	# パスリスト
require_once(getenv("GPRISM_HOME") . "/Func/Check.php"); 	# 入力値チェック共通関数
require_once($g_func_dir . "/global.php"); 			# 共通変数
require_once($g_func_dir . "/db_op.php"); 			# DB操作
require_once($g_func_dir . "/xdb_op.php"); 			# DBIコネクト関数
require_once($g_func_dir . "/xpt_err_msg.php"); 		# エラーメッセージ作成関数

require_once($g_func_dir . "/cs_xgn_man.php"); 			# ユーザ名称の獲得関数
require_once($g_func_dir . "/xgn_cd.php"); 			# 名称取得
require_once($g_func_dir . "/xgt_lp2.php"); 			# プリンター情報の獲得関数（端末ノード）
require_once($g_func_dir . "/xgt_lp2_cd.php"); 			# プリンター情報の獲得関数（プリンタＩＤ）
require_once($g_func_dir . "/xck_tag.php"); 			# タグのチェック関数
require_once($g_func_dir . "/xgt_stp_cls.php");
require_once($g_func_dir . "/xgn_prd.php");
require_once($g_func_dir . "/xck_upd.php"); 			# 更新レベルチェック
require_once($g_func_dir . "/xgt_lot.php");
require_once($g_func_dir . "/xgt_nio.php");
require_once($g_func_dir . "/xgt_npr.php");
require_once($g_func_dir . "/xgt_use_equ.php"); 		# 使用可能な装置の獲得関数
require_once($g_func_dir . "/xck_lio.php"); 
require_once($g_func_dir . "/cs_xck_prt_ctrl.php");
require_once($g_func_dir . "/xgt_prt_wip.php");
require_once($g_func_dir . "/xgc_prd.php");
require_once($g_func_dir . "/xpt_1sec_dts.php");
require_once($g_func_dir . "/cs_xck_equ_prt.php");
require_once($g_func_dir . "/cs_xck_staff_ctrl.php");		# For Staff Process control
require_once($g_func_dir . "/cs_xck_baking.php"); 
require_once($g_func_dir . "/cs_xck_exst_child.php");		# 
require_once($g_func_dir . "/cs_xck_trk_snd_lot.php");		 # SPACEチェック関数

require_once($g_func_dir . "/cs_xpt_pcllsi_label.php");
require_once($g_func_dir . "/cs_xgt_pcs_ctrl.php");
require_once($g_func_dir . "/iosp.php");
require_once($g_func_dir . "/iomg.php");
require_once($g_func_dir . "/ioin.php");
require_once($g_func_dir . "/ioot.php");
require_once($g_func_dir . "/iomv.php");
require_once($g_func_dir . "/iopc.php");
require_once($g_func_dir . "/prpt.php");
require_once($g_func_dir . "/prpc.php");
require_once($g_func_dir . "/prgt.php");
require_once($g_func_dir . "/mtin.php");
require_once($g_func_dir . "/mtot.php");
require_once($g_func_dir . "/mtcs.php");
require_once ($g_Mfunc_dir . "/xgt_dvsn.php");				#for printer changes
require_once($g_lang_dir . "/buttonM.php"); 			# ボタン名称
require_once($g_lang_dir . "/PS00S01001400M.php"); 		# メッセージ
require_once($g_Gfunc_dir . "/xpt_screen.php"); 		# プログラムフレーム呼び出し
require_once($g_func_dir . "/cs_xgt_po_no.php");
require_once($g_func_dir . "/cs_xgt_inhrt_po_data.php");
require_once($g_func_dir . "/cs_chk_po_req.php");
#******************************************************************
#
# フォーム情報をグローバル変数へ格納
#
#******************************************************************
if ($REQUEST_METHOD == "GET") {
	$gw_scr = cnv_formstr($_GET);
} else {
	$gw_scr = cnv_formstr($_POST);
}
#******************************************************************
#
# 言語パス／エンコードの決定
#
#******************************************************************
$g_lang_path	= $gw_scr['g_lang_path'];
$g_CharSet	  = $gw_scr['g_CharSet'];
$g_usrId		= $gw_scr['usrId'];
$g_menuNo1	  = $gw_scr['menuNo1'];
$g_menuNo2	  = $gw_scr['menuNo2'];
$g_menuNo3	  = $gw_scr['menuNo3'];
$g_menuNo4	  = $gw_scr['menuNo4'];
#******************************************************************
#
# 定数定義
#
#******************************************************************
#------------------------------------------------------------------
# タグ定義
#------------------------------------------------------------------
define("DEFAULT_INPUT_ROW", 			"5"); #NEW: Change from 5 to 2
define("DEFAULT_MAX_INPLOT", 			"7");
define("DEFAULT_MAG_ROW", 			"0");
define("DEFAULT_MAG_COL", 			"2");
define("DEFAULT_PACK_ROW", 			"1"); 
define("DEFAULT_PACK_MAXCOL", 			"7");
define("BOX_QTY_ALW_DIFF_DATE_CODE",		"1"); #NEW
#define("SFX_PILOT_LOT_NO",			"PZ");
### E9
define("E9_ALW", serialize(array(
	"E931S072", #(MAT) PACKING
	"E931S070", #(MAT) PRE PACKING
	"E931S121", #(QFN) PRE PACKING
	"E931S123", #(QFN) PACKING
	"E931S035", #(IPD) PACKING
	"E931S155", #(SOB) PRE PACKING
	"E931S157", #(SOB) PACKING
	"E941S029", #(TR) PRE PACKING
	"E941S031", #(TR) PACKING
	"E931S033", #(IPD) PRE PACKING
	"E931S232",   #(QFP) PRE PACKING             
	"E931S233",   #(QFP) PACKING 	

)));

define("PACK_PCS",                    serialize(array(
        0 => "",
        1 => "OK",
        2 => "NG"
)));

define("CT_BAKING_TIME_1",		"CT00S0000143");
define("CT_BAKING_TIME_2",		"CT00S0000144");
define("CT_BAKING_TIME_3",		"CT00S0000147");
define("CT_BAKING_TIME_BAKING",		"CT00S0000148");
define("CT_PACKING_VI",                 "CT00S0000387");
### P0
define("P0_DTE_CD", 			"P0SEM002"); 				# Date Code Variety
define("P0_PAR_ID", 			"DATE_CD");					# Date Code Par ID
define("P0_PCS_TRACK_IN",               "P000S011");

### CE
define("CE_SLINF",			"CE00S08");
define("CE_DVSCD",			"CE00S02");

### CT
define("CT_DATECD",		  	"CT00S0000021");
define("CT_MOTHER",                     "CT00S0000378");

### AW CHANGE TO PACKING LOT QTY (AW11S0000006)
define("AW_PKGCHIP",			"AW00S0000025"); #NEW
define("AW_DT_CDMAX",			"AW00S0000036"); #NEW
define("DT_CDMAX",			1);
### BIND_DVS
define("DVS_LAMINATE",			"LSI_LAMINATE");
#------------------------------------------------------------------
# 材料管理
#------------------------------------------------------------------
define("PGM_MTMNG", 			"PS00S04000030");
define("AW_SEALING_CONTROL",		"AW00S0000045");
define("AW_PARENT_CHILD_CONTROL",	"AW11S0000007");
define("E9_PARENT_CHILD_CONTROL",			serialize(array(
	"E911S370",			# F2-TEST
	"E911S410",			# APPLICATION TEST
	"E911S590",			# PACKING VI
	"E911S640",			# AUTO VI
	"E911S650",			# AUTO VI 2
	"E911S630",			# BAKING

	"E911S670",			# PRE PACKING
	"E911S680",			# PACKING
	"E911S690",			# DELIVERY
)));
define("E9_PACKING",			"E911S680");
define("E9_DELIVERY",			"E911S690");				# DELIVERY
define("E9_F_TEST",			"E911S330");				# F-test
define("E9_BAKING",			"E911S630");				# Baking
#define("E9_PREPACKING",				 "E911S670");
define("E9_PACKING_VI", serialize( array(
	"E931S037", #(IPD) PACKING VI
        "E931S074", #(MAT) PACKING VI
        "E931S159", #(SOB) PACKING VI
)));


define("ST_PACKING",			"ST11S0000068");			
define("ST_PREPACK",			"ST11S0000067");


#define("PGM_MTMNG",			 "PS00S04000120");
### 状態区分コード
define("AU_STR_PT_OK",			"AUSEM01");

define("DEBUG_MODE", 0); 					# デバッグモード 0：本番、1：デバッグ中
### デバッグ用
$debug_verb_color = array(
		'LOT_ID' 	=> '#0000ff',
		'CHP_QTY' 	=> '#0000ff',
		'LOT_ST_DVS' 	=> '#ff0000',
		'PRD_CD' 	=> '#0000ff',
);

define("DG_ALLOWED_PARENT_CHILD_CONTROL",	"DG00S170");

define("DG_SUP",                       serialize(array(
	"DG00S290",
	"DG00S330",
	"DG00S370",
	"DG00S250",
	"DG00S210"
)));
##------------------------------------------------------------------
#Printer Modifications
##------------------------------------------------------------------
define("PGMID_PRINT",	 		"PS00S06000400");	 

define("CC_RTRN",               "CCSEM10");                             # RETURN LOT 
#======================================================================
# サブルーチン定義
#======================================================================
function cnv_formstr($array) {
	foreach ($array as $k => $v) {
		if (is_array($v)) {

			foreach ($v as $kk => $vv) {

				if (is_array($vv)) {
					foreach ($vv as $kkk => $vvv) {
						if (get_magic_quotes_gpc()) {
							$vvv = stripslashes($vvv);
						}
						$array[$k][$kk][$kkk] = $vvv;
					}
				} else {
					if (get_magic_quotes_gpc()) {
						$vv = stripslashes($vv);
					}
					$array[$k][$kk] = $vv;
				}
			}
		} else {
			# 「magic_quotes_gpc = On」のときはエスケープ解除
			if (get_magic_quotes_gpc()) {
				$v = stripslashes($v);
			}
			$array[$k] = $v;
		}
	}

	return $array;
}
#======================================================================
function vdump($obj) {
	ob_start();
	var_dump($obj);
	$dump = ob_get_contents();
	ob_end_clean();
	return $dump;
}
function dbg_vdump($obj) {
	ob_start();
	var_dump($obj);
	$dump = ob_get_contents();
	ob_end_clean();
	print "<pre>";
	print $dump;
	print "</pre>";
	print "<hr>";
}
#======================================================================
function itm($w_key) {
	return PS00S01001400_item($w_key);
}
#======================================================================
function msg($w_key) {
	return PS00S01001400_msg($w_key);
}
#=================================================
# デバッグ用関数
#=================================================
function dbg($w_lot_bas) {
	global $gw_scr;

	print "<B>[" . $w_lot_bas['VERB_CAN'] . "]</B><BR>";
	foreach ($w_lot_bas as $key => $val) {
		print 
				"[" . $key . "] => &lt;<font color='ff0000'>" . $val
						. "</font>&gt; <b>|</b> ";
	}
	print "<HR>";

	return;
}

#=================================================
# デバッグ用関数（SQL）
#=================================================
function dbg_sql($w_title, $w_sql) {
	global $gw_scr;

	print "<B>[" . $w_title . "]</B><BR>";
	print "$w_sql";
	print "<HR>";

	return;
}

#=================================================
# デバッグ用関数（ARRAY）
#=================================================
function dbg_array($w_title, $w_arr_dat) {
	global $gw_scr;

	print "<B>[" . $w_title . "]</B><BR>";
	foreach ($w_arr_dat as $key => $val) {
		print "[" . $key . "] => &lt;";
		if (is_array($val)) {
			print_r($val);
		} else {
			print "<font color='ff0000'>$val</font>";
		}
		print "&gt; <b>|</b>";
	}
	print "<HR>";

	return;
}

#=================================================
# デバッグ用関数（VERB）
#=================================================
function dbg_vreb($w_lot_bas) {
	global $gw_scr;
	global $debug_verb_color;

	print "<B>[" . $w_lot_bas['VERB_CAN'] . "]</B><BR>";
	foreach ($w_lot_bas as $key => $val) {
		$col = '#000000';
		foreach ($debug_verb_color as $dk => $dv) {
			if ($dk == $key) {
				$col = $dv;
				break;
			}
		}
		print "[";
		print "<font color='" . $col . "'>" . $key . "</font>";
		print "] => &lt;";
		print "<font color='" . $col . "'>" . $val . "</font>&gt; <b>|</b>";
	}
	print "<HR>";

	return;
}

#=========================================================================
# 画面設定
#=========================================================================
function scr_setting() {
	global $gw_scr;
	global $g_mode;

	return 0;
}
#=================================================
# デフォルト値のセット
#=================================================
function input_default() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$gw_scr['s_h_inp_row'] = DEFAULT_INPUT_ROW;
	$gw_scr['s_h_mag_row'] = DEFAULT_MAG_ROW;
	$gw_scr['s_h_mag_col'] = DEFAULT_MAG_COL;
	$gw_scr['s_h_pack_row'] = DEFAULT_PACK_ROW;
	$gw_scr['s_h_pack_maxcol'] = DEFAULT_PACK_MAXCOL;

	$gw_scr['s_inp_row'] = $gw_scr['s_h_inp_row'];
	$gw_scr['s_mag_col'] = $gw_scr['s_h_mag_col'];

	#==========================================================
	# 端末ノードからプリンタ情報の獲得(xgt_lp2)
	# 戻り値：s_lp_cd, s_lp_nm, s_lp_id, s_lp_type
	#==========================================================

	#ラベル出力先
	$w_rtn = xgt_lp2(2, $gw_scr['s_lbl_cd'], $gw_scr['s_lbl_nm'],
			$gw_scr['s_lbl_id'], $gw_scr['s_lbl_type']);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	}

	return 0;
}
#======================================================================
# 表示項目初期化処理
# $w_mode	[I]		初期化モード
#======================================================================
function set_init($w_mode) {
	global $gw_scr;
	global $g_page_stp;

	if ($w_mode == 1) {
		$gw_scr['s_usr_id'] = "";
		$gw_scr['s_equ_cd'] = "";
		$gw_scr['s_equ_nm'] = "";
		$gw_scr['s_lst_lot_id'] = array();
		$gw_scr['s_pck_prd_qty'] = "";
		$gw_scr['s_rem_chp_qty'] = array();
		$gw_scr['s_rem_chp_qty_ttl'] = array();
		$gw_scr['s_inp_row'] = DEFAULT_INPUT_ROW;
		$gw_scr['s_h_inp_row'] = DEFAULT_INPUT_ROW;
		$gw_scr['s_box_qty_cd'] = "1"; #NEW
 
				 
	}
	if ($w_mode <= 2) {
		$gw_scr['s_usr_nm'] = "";
		$gw_scr['s_lbl_nm'] = "";
		$gw_scr['s_lbl_id'] = "";
		$gw_scr['s_lbl_type'] = "";
		$gw_scr['s_lst_prd_nm'] = array();
		$gw_scr['s_lst_lot_no_str'] = array();
		$gw_scr['s_lst_chp_qty'] = array();
		$gw_scr['s_lst_dte_cd_str'] = array();
		$gw_scr['s_lst_upd_lev'] = array();
		$gw_scr['s_lst_prc_cd'] = array();
		$gw_scr['s_lst_stp_cd'] = array();
		$gw_scr['s_lst_prd_cd'] = array();
		$gw_scr['s_lst_dif_lot_no'] = array();
		$gw_scr['s_lst_rmn_qty'] = array();
		$gw_scr['s_lot_id'] = "";
		$gw_scr['s_rt_cd']  = "";
		$gw_scr['s_prc_cd'] = "";
		$gw_scr['s_stp_cd'] = "";
		$gw_scr['s_stp_no'] = "";
		$gw_scr['s_io_blc_cd'] = "";
		$gw_scr['s_prd_cd'] = "";
		$gw_scr['s_sum_chp_qty'] = "";
		$gw_scr['s_plt_dvs_cd'] = "";
		$gw_scr['s_pck_prd_qty'] = "";
		$gw_scr['s_h_mag_col'] = DEFAULT_MAG_COL;
		$gw_scr['s_mag_col'] = $gw_scr['s_h_mag_col'];
		$gw_scr['s_h_mag_row'] = DEFAULT_MAG_ROW;

		$gw_scr['s_mag_lot_id'] = array();
		$gw_scr['s_mag_lot_id_idx'] = array();

		$gw_scr['s_h_pack_row'] = DEFAULT_PACK_ROW;
	  
		$gw_scr['s_h_pack_maxcol'] = DEFAULT_PACK_MAXCOL;
		$gw_scr['s_mag_prt_grp_b'] = "";
		$gw_scr['s_mag_prt_grp_a'] = "";

		$gw_scr['s_pack_lot_id'] = array();
		$gw_scr['s_pack_chp_qty'] = array();
		$gw_scr['s_pack_chp_qty_ttl'] = array();
		$gw_scr['s_pack_prc_cd'] = array();
		$gw_scr['s_pack_stp_cd'] = array();
		$gw_scr['s_pack_prd_cd'] = array();

		$gw_scr['s_pckd']		 = "";
		$gw_scr['s_pck_prd_cd']   = "";
		$gw_scr['s_pck_prd_nm']   = "";
		$gw_scr['s_pck_rmks'] = "";

		$gw_scr['s_plt_flg'] = "";

		$gw_scr['s_box_qty_nm'] = ""; #NEW
	}

	if ($w_mode == 3) {
			$gw_scr['s_pck_prd_cd'] = "";
			$gw_scr['s_pck_prd_nm'] = "";
			$gw_scr['s_pck_rmks'] = "";
			$gw_scr['s_mag_magid'] = array();

                	$w_rtn = refresh_pcs_ctrl_ui();

	}


	if ($w_mode <= 4) {
		$gw_scr['s_mag_magid'] = array();
	}

	if($w_mode <= 5){
		$gw_scr['s_rem_chp_qty'] = array();
		$gw_scr['s_rem_chp_qty_ttl'] = array();
		$g_page_stp = "";
		$gw_scr['s_prt_ctrl'] = "";
		$gw_scr['s_nxt_stp_cd'] = "";
	}

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

		if($w_row['COUNTA'] > 0){
				$r_bln_check = TRUE;
		}


		return 0;
}

#=========================================================================
# ..........
#=========================================================================
function check_equ_succession_in($w_lot_id, $w_equ_cd)
{
		 global $gw_scr;
		 global $g_msg;
		 global $g_err_lv;

		 #------------------------------------------------------------------
		 # get EQU_MST
		 #------------------------------------------------------------------
		 $w_rtn = get_equ_mst($w_equ_cd, $w_equ_dat);
		 if ($w_rtn !=  0) {
		 		 return $w_rtn;
		 }

		 if ($w_equ_dat['BTC_FRE_FLG'] == "1" && $w_equ_dat['MIX_FLG'] == "0") {

		 		 #------------------------------------------------------------------
		 		 # ................
		 		 #------------------------------------------------------------------
		 		 $w_rtn = get_equin_lot_bas($w_equ_cd, $w_equin_lot_bas);
		 		 if ($w_rtn !=  0) {
		 		 		 return $w_rtn;
		 		 }

		 		 if (count($w_equin_lot_bas['LOT_ID']) > 0) {

		 		 		 #------------------------------------------------------------------
		 		 		 # ..........(.....)
		 		 		 #------------------------------------------------------------------
		 		 		 $w_rtn = xgt_lot($w_lot_id, $w_lot_bas);
		 		 		 if($w_rtn){
		 		 		 		 $g_err_lv = 0;
		 		 		 		 $g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
		 		 		 		 return $w_rtn;
		 		 		 }

		 		 		 #------------------------------------------------------------------
		 		 		 # ..........
		 		 		 #------------------------------------------------------------------
		 		 		 if ($w_equin_lot_bas['PRD_CD'][0] != trim($w_lot_bas['PRD_CD'])) {
		 		 		 		 list($g_msg, $g_err_lv) = PS00S01001400_msg("err_Diff_Suc_PrdCd");
		 		 		 		 $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		 		 		 		 return 4000;
		 		 		 }
		 		 		 if ($w_equin_lot_bas['RE_INS_FLG'][0] != trim($w_lot_bas['RE_INS_FLG'])) {
		 		 		 		 list($g_msg, $g_err_lv) = PS00S01001400_msg("err_Diff_Suc_ReInsFlg");
		 		 		 		 $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		 		 		 		 return 4000;
		 		 		 }
		 		 		 if ($w_equin_lot_bas['MNG_FLG'][0] != trim($w_lot_bas['MNG_FLG'])) {
		 		 		 		 list($g_msg, $g_err_lv) = PS00S01001400_msg("err_Diff_Suc_MngFlg");
		 		 		 		 $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		 		 		 		 return 4000;
		 		 		 }
		 		 }
		 }

		 if ($w_equ_dat['BTC_FRE_FLG'] == "0") {

		 		 #------------------------------------------------------------------
		 		 # ........
		 		 #------------------------------------------------------------------
		 		 $w_rtn = get_equin_lot_bas($w_equ_cd, $w_equin_lot_bas);
		 		 if ($w_rtn !=  0) {
		 		 		 return $w_rtn;
		 		 }

		 }

		 return 0;
}

#=================================================
# ..........
#=================================================
function get_equ_mst($w_equ_cd, &$r_dat)
{
		 global $g_msg;
		 global $g_err_lv;
		 global $gw_scr;

		 #---------------
		 # ....
		 #---------------
		 $r_dat = array();

		 #---------------
		 # SQL..
		 #---------------
		 $w_sql = <<<SQL

SELECT
		 BTC_FRE_FLG,
		 MIX_FLG
FROM
		 EQU_MST
WHERE
		 EQU_CD = '{$w_equ_cd}'
		 AND DEL_FLG = '0'

SQL;

		 #---------------
		 # SQL.....
		 #---------------
		 $w_stmt = db_res_set($w_sql);

		 #---------------
		 # SQL...
		 #---------------
		 $w_rtn  = db_do($w_stmt);
		 if ($w_rtn != 0){
		 		 list($g_msg, $g_err_lv) = PS00S01001400_msg("err_Sel_EquMst");
		 		 $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		 		 return $w_rtn;
		 }

		 #--------------------
		 # SQL......
		 #--------------------
		 $w_row = db_fetch_row($w_stmt);

		 $r_dat['BTC_FRE_FLG']		 = trim($w_row['BTC_FRE_FLG']);
		 $r_dat['MIX_FLG']		 		 = trim($w_row['MIX_FLG']);

		 #--------------------
		 # .......
		 #--------------------
		 db_res_free($w_stmt);

		 #---------------
		 # ....
		 #---------------
		 return 0;

}

#=================================================
# ................
#=================================================
function get_equin_lot_bas($w_equ_cd, &$r_dat)
{
		 global $g_msg;
		 global $g_err_lv;
		 global $gw_scr;

		 #---------------
		 # ....
		 #---------------
		 $r_dat = array();
		 $i = 0;

		 #---------------
		 # SQL..
		 #---------------
		 $w_sql = <<<SQL

SELECT
		 LOT_ID,
		 PRD_CD,
		 RE_INS_FLG,
		 MNG_FLG
FROM
		 LOT_BAS_TBL
WHERE
		 EQU_CD = '{$w_equ_cd}'

SQL;

		 #---------------
		 # SQL.....
		 #---------------
		 $w_stmt = db_res_set($w_sql);

		 #---------------
		 # SQL...
		 #---------------
		 $w_rtn  = db_do($w_stmt);
		 if ($w_rtn != 0){
		 		 list($g_msg, $g_err_lv) = PS00S01001400_msg("err_Sel_LotBasTbl");
		 		 $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		 		 return $w_rtn;
		 }

		 #--------------------
		 # SQL......
		 #--------------------
		 while ($w_row = db_fetch_row($w_stmt)) {

		 		 $r_dat['LOT_ID'][$i]		 		 = trim($w_row['LOT_ID']);
		 		 $r_dat['PRD_CD'][$i]		 		 = trim($w_row['PRD_CD']);
		 		 $r_dat['RE_INS_FLG'][$i]		 = trim($w_row['RE_INS_FLG']);
		 		 $r_dat['MNG_FLG'][$i]		 		 = trim($w_row['MNG_FLG']);

		 		 $i++;
		 }

		 #--------------------
		 # .......
		 #--------------------
		 db_res_free($w_stmt);

		 #---------------
		 # ....
		 #---------------
		 return 0;

}


#=======================================================================
# エラー時の対象文字列生成
#=======================================================================
function get_tg() {
	$arr = func_get_args();
	return implode("/", array_map("trim", $arr));
}

#======================================================================
# 初期処理
#======================================================================
function main_init() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# 表示項目初期化処理
	#------------------------------------------------------------------
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
	input_default();

	# モード１にする
	scr_mode_chg(1);

	return 0;
}
#======================================================================
# モード1
#======================================================================
function main_md1() {
	global $gw_scr;

	switch ($gw_scr['s_act']) {
	case "CHECK":
#		$gw_scr['s_inp_row'] = $gw_scr['s_h_inp_row'];
		$w_rtn = main_md1_check();
		if ($w_rtn == 0) {
			scr_mode_chg(2);
		}
		break;
	case "REDISP":
		main_md1_redisp();
		break;
	case "ERASE":
		$gw_scr['s_inp_row'] = $gw_scr['s_h_inp_row'];
		set_init(1);
		main_init();
		break;
	}
	return 0;
}
#======================================================================
function check_input($w_mode) {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch ($w_mode) {
		case 1:
			$gw_scr['s_usr_id'] = strtoupper(trim($gw_scr['s_usr_id']));
			$gw_scr['s_lbl_cd'] = strtoupper(trim($gw_scr['s_lbl_cd']));
			$gw_scr['s_equ_cd'] = strtoupper(trim($gw_scr['s_equ_cd']));

			for ($i = 1; $i <= $gw_scr['s_h_inp_row']; $i++) {
				$gw_scr['s_lst_lot_id'][$i] = strtoupper(trim($gw_scr['s_lst_lot_id'][$i]));
			}

			# 必須入力チェック
			list($g_msg, $g_err_lv) = msg("err_Nec_Input");
			if ($gw_scr['s_usr_id'] == "") {
				$g_msg = xpt_err_msg($g_msg, itm("UsrId"), __LINE__);
				return 4000;
			}
			if ($gw_scr['s_lbl_cd'] == "") {
				$g_msg = xpt_err_msg($g_msg, itm("PrinterCode"), __LINE__);
				return 4000;
			}

			if ($gw_scr['s_equ_cd'] == "") {
				$g_msg = xpt_err_msg($g_msg, itm("EquCd"), __LINE__);
				return 4000;
			}
			if ($gw_scr['s_box_qty_cd'] == "") {
				$g_msg = xpt_err_msg($g_msg, itm("BoxQty"), __LINE__);
				return 4000;
			}

			$w_chk_lotid = false;
			for ($i = 1; $i <= $gw_scr['s_h_inp_row']; $i++) {
				if ($gw_scr['s_lst_lot_id'][$i] != "") {
					$w_chk_lotid = true;
					break;
				}
			}
			if (!$w_chk_lotid) {
				$g_msg = xpt_err_msg($g_msg, itm("LotId"), __LINE__);
				return 4000;
			}

			# 禁止文字チェック	  
			list($g_msg, $g_err_lv) = msg("err_Inp_Char");
			if (!check_eisu($gw_scr['s_usr_id'])) {
				$g_msg = xpt_err_msg($g_msg, itm("UsrId"), __LINE__);
				return 4000;
			}
			if (!check_err_code($gw_scr['s_lbl_cd'])) {
				$g_msg = xpt_err_msg($g_msg, itm("PrinterCode"), __LINE__);
				return 4000;
			}
			for ($i = 1; $i <= $gw_scr['s_h_inp_row']; $i++) {
				if ($gw_scr['s_lst_lot_id'][$i] != "") {
					if (!check_err_lot($gw_scr['s_lst_lot_id'][$i])) {
						$g_msg = xpt_err_msg($g_msg, itm("LotId") . "($i)",
							__LINE__);
						return 4000;
					}
				}
			}

			if($gw_scr['s_inp_row'] > constant("DEFAULT_MAX_INPLOT")) {
                        	list($g_msg, $g_err_lv) = msg("err_MaxLot");
                        	$w_tg = get_tg(itm("InputRow"), htmlspecialchars($gw_scr['s_inp_row']));
                        	$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                        	return 4000;
			}
			# タグチェック
			$w_rtn = xck_tag($gw_scr['s_usr_id'], 'MA');
			if ($w_rtn != 0) {
				list($g_msg, $g_err_lv) = msg("err_Inp_Tag");
				$w_tg = get_tg(itm("UsrId"), htmlspecialchars($gw_scr['s_usr_id']));
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			$w_rtn = xck_tag($gw_scr['s_lbl_cd'], 'LP');
			if ($w_rtn != 0) {
				list($g_msg, $g_err_lv) = msg("err_Inp_Tag");
				$w_tg = get_tg(itm("PrinterCode"),
					htmlspecialchars($gw_scr['s_lbl_cd']));
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			for ($i = 1; $i <= $gw_scr['s_h_inp_row']; $i++) {
				if ($gw_scr['s_lst_lot_id'][$i] != "") {
					$w_rtn = xck_tag($gw_scr['s_lst_lot_id'][$i], 'LT');
					if ($w_rtn != 0) {
						list($g_msg, $g_err_lv) = msg("err_Inp_Tag");
						$w_tg = get_tg(itm("LotId") . "($i)",
							htmlspecialchars($gw_scr['s_lst_lot_id'][$i]));
						$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
						return 4000;
					}

					#------------------------------------------------------------------
					# 鍛誼鍛蔵誕鈎
 					#------------------------------------------------------------------
					$w_rtn = check_equ_succession_in($gw_scr['s_lst_lot_id'][$i], $gw_scr['s_equ_cd']);
					if ($w_rtn !=  0) {
							return $w_rtn;
					}
				}
			}

			# ロットID重複チェック
			$w_dup_lotid = array();
			for ($i = 1; $i <= $gw_scr['s_h_inp_row']; $i++) {
				if ($gw_scr['s_lst_lot_id'][$i] != "") {
					if ($w_dup_lotid[$gw_scr['s_lst_lot_id'][$i]] != "") {
						list($g_msg, $g_err_lv) = msg("err_Inp_Dup");
						$g_msg = xpt_err_msg($g_msg, itm("LotId") . "($i)",
							__LINE__);
						return 4000;
					}
					$w_dup_lotid[$gw_scr['s_lst_lot_id'][$i]] = "1";
				}
			}
		break;

		#------------------------------------------------------------------
		case 2:
			$gw_scr['s_pck_prd_nm'] = strtoupper(trim($gw_scr['s_pck_prd_nm']));
			$gw_scr['s_pck_rmks'] = strtoupper(trim($gw_scr['s_pck_rmks']));	

			list($g_msg, $g_err_lv) = msg("err_Nec_Input");
			if ($gw_scr['s_pck_prd_nm'] == "") {
				$g_msg = xpt_err_msg($g_msg, itm("PckTypNm"), __LINE__);
				return 4000;
			}
		break;
	}

	$g_msg = "";
	$g_err_lv = "";

	return 0;
}
#======================================================================
function main_md1_check() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# 入力チェック
	#------------------------------------------------------------------
	$w_rtn = check_input(1);
	if ($w_rtn != 0) {
		return 4000;
	}

	#------------------------------------------------------------------
	# 空行除去
	#------------------------------------------------------------------
	count($gw_scr['s_h_inp_row']);
	
	for($i=1; $i<=$gw_scr['s_h_inp_row']; $i++){
		if($gw_scr['s_lst_lot_id'][$i] != "") continue;
		for($j=($i+1); $j<=$gw_scr['s_h_inp_row']; $j++){
			if($gw_scr['s_lst_lot_id'][$j] == "") continue;
			$gw_scr['s_lst_lot_id'][$i] = $gw_scr['s_lst_lot_id'][$j];
			$gw_scr['s_lst_lot_id'][$j] = "";
			break;
		}
	}

  
	$w_usr_id = $gw_scr['s_usr_id'];
	$w_lbl_cd = $gw_scr['s_lbl_cd'];
	$w_equ_cd = $gw_scr['s_equ_cd'];
	$w_box_qty_cd = $gw_scr['s_box_qty_cd']; #NEW
	$w_lot_id_qty = $gw_scr['s_h_inp_row']; 
	$w_lot_id_qty_less1 = $w_lot_id_qty - 1;
	$w_usr_nm = "";
	$w_lbl_id = "";
	$w_lbl_type = "";
	$w_lbl_nm = "";
 

	# ユーザＩＤから、ユーザ名称を取得する
	$w_rtn = cs_xgn_man($w_usr_id, $w_usr_nm);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $w_usr_id, __LINE__);
		return 4000;
	}
	# プリンタコードからプリンタ名称を取得する
	$w_rtn = xgt_lp2_cd($w_lbl_cd, $w_lbl_id, $w_lbl_type, $w_lbl_nm);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $w_lbl_cd, __LINE__);
		return $w_rtn;
	}

	#------------------------------------------------------------------
	# 装置名の獲得
	#------------------------------------------------------------------
	$w_rtn = xgn_cd($gw_scr['s_equ_cd'], 1, $w_equ_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_equ_cd'], __LINE__);
		return 4000;
	}
	
	# ロット情報取得
	$w_lotinfo_list = array();	
	$w_base_po = "";
	$w_po_cnt = 0;
	for ($i = 1; $i <= $gw_scr['s_h_inp_row']; $i++) {
		if ($gw_scr['s_lst_lot_id'][$i] != "") {
			$w_rtn = xgt_lot($gw_scr['s_lst_lot_id'][$i], $w_lot_bas);
			if ($w_rtn != 0) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
				return 4000;
			}
			$w_lotinfo_list[$i]['lot_bas'] = $w_lot_bas;  #Lot_bas info for non-blank LOTID key-in

				#------------------------------------------------------------------
				# 装置構成マスターのチェック
				#------------------------------------------------------------------
				$w_rtn = ioin_equ_check($gw_scr['s_equ_cd'], $w_lot_bas);
				if($w_rtn){
						$g_err_lv = 0;
						$g_msg = xpt_err_msg($g_msg, $gw_scr['s_equ_cd'], __LINE__);
						return 4000;
				}

			#======================
                        #check for duplicates
                        $w_rtn = cs_xgt_po_no($gw_scr['s_lst_lot_id'][$i], $w_po_no );
                        if($w_rtn != 0 ){
                                $g_err_lv = 0;
                                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lst_lot_id'][$i], __LINE__);
                                return 4000;
                        }

                        if(count($w_po_no) > 1){
                                list($g_msg, $g_err_lv) = msg("err_Multiple_PO");
                                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lst_lot_id'][$i], __LINE__);
                                return 4000;
                        }

                        if($w_po_cnt == 0){
                                $w_base_po = $w_po_no[0];
				$w_po_cnt++;
                        }else{
                                if($w_base_po != $w_po_no[0]){
                                        list($g_msg, $g_err_lv) = msg("err_PO_notMatch");
                                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lst_lot_id'][$i] . " - " . $w_base_po . " - " . $w_po_no[0], __LINE__);
                                        return 4000;
                                }
                        }
                        #======================

			/*#---------------------------------------------
                                if ($w_base_po == ""){

                                        $w_rtn = cs_xgt_po_no($gw_scr['s_lst_lot_id'][$i], $r_dat);
                                        if ($w_rtn != 0){
                                                return 4000;
                                        }

					$w_base_po = $r_dat[0];

                                }else{
                                        $w_rtn = cs_xgt_po_no($gw_scr['s_lst_lot_id'][$i], $r_dat);
                                        if ($w_rtn != 0){
                                                return 4000;
                                        }

                                        if (count(array_unique($r_dat)) != 1 ) {
                                                list($g_msg, $g_err_lv) = msg("err_same_qty");
                                                $g_msg = xpt_err_msg($g_msg, $w_lot_bas['err_same_qty'], __LINE__);
                                                return 4000;
                                        }
                                }
                        #---------------------------------------------*/
		
			# SPACE CHECKING
			$w_rtn = chk_space(
				$gw_scr['s_usr_id'],
				$w_lot_bas['LOT_ID'],
				"Y",
				$w_lot_bas['PRD_CD'],
				$w_lot_bas['STP_CD'],
				$gw_scr['s_equ_cd'],
				$w_lot_bas['CHP_QTY'],
				"",
				"",
				$w_spc_err_cd,
				$w_spc_err_nm,
				$w_spc_err_msg
			);

			if($w_rtn != 0) return 4000;

			if($w_spc_err_cd != "xxxxx1"){
				list($g_msg, $g_err_lv) = msg("err_Err_Space");
				$g_msg = xpt_err_msg($g_msg, $w_spc_err_msg, __LINE__);
				return 4000;
			}	
		}
	}



	#------------------------------------------------------------------
	# 許可工程チェック
	#------------------------------------------------------------------
	# ステップ分類取得
	$w_rtn = xgt_stp_cls($w_lot_bas['STP_CD'], $w_stpcls2, $dmy);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, trim($w_lot_bas['STP_CD']), __LINE__);
		return 4000;
	}
		
	for ($i = 1; $i <= $gw_scr['s_h_inp_row']; $i++) {
		#------------------------------------------------------------------
		# Parent Child Controlチェック
		#------------------------------------------------------------------
		if (in_array(trim($w_stpcls2),
				unserialize(constant('E9_PARENT_CHILD_CONTROL')))) {
			$w_rtn = cs_xck_exst_child($w_lotinfo_list[$i]['lot_bas'],
					constant('AW_PARENT_CHILD_CONTROL'), constant('E9_F_TEST'),
					$w_exst_lot_bas, 1);
			if ($w_rtn != 0) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
			}

			$w_cnt_holdlot = 0;		
			for($b=1; $b<=count($w_exst_lot_bas); $b++){
				if($w_exst_lot_bas[$b]['LOT_ID'] != $w_lotinfo_list[$i]['lot_bas']['LOT_ID']){
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
	
	
	#入力されたロットIDで取得出来たロットの状態をチェックする。
	$w_plt_flg = 0;
	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lot_bas = $w_lotinfo['lot_bas'];
		if ($w_lot_bas['LOT_ST_DVS'] != "WT"
				|| $w_lot_bas['LOT_DVS_FIN'] != "00") {
			list($g_msg, $g_err_lv) = msg("err_Lot_01");
			$g_msg = xpt_err_msg($g_msg, $w_lot_bas['LOT_ID'], __LINE__);
			return 4000;
		}
		if($w_lot_bas['PLT_DVS_CD'] != "CBSEM00"){
			$w_plt_flg = 1;
		}
	}

	# Date Code validation 
	$w_dte_cd = array();
	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lot_bas = $w_lotinfo['lot_bas'];
		$w_dte_cd[$w_lot_bas['SECRET_NO']] =  $w_lot_bas['SECRET_NO']; 
	}

	#------------------------------------------------------------------
	# ロットＩＤが本体ロットである場合、
	# 既に梱包ロット作成したロットであるか、LOG_BIND_INFチェック
	#------------------------------------------------------------------
	$w_pckd	   = "";
	$w_pck_prd_nm = "";
	$w_pck_prd_cd = "";
	if($w_plt_flg == 0){
		$w_lot_bas = $w_lotinfo_list[1]['lot_bas'];

		$w_rtn = chk_packed($w_lot_bas['LOT_ID'], $w_pck_prd_cd);
		if($w_rtn != 0) return 4000;
		if($w_pck_prd_cd != ""){
			#$w_pckd = 1;
			$w_rtn = xgn_prd($w_pck_prd_cd, $w_pck_prd_nm, $dmy);
			if($w_rtn != 0){
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
			}
		}
	}

	#獲得したロット基本情報のstp_cdよりstp_cls_2を取得し、E9コードのチェックをする。
	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lot_bas = $w_lotinfo['lot_bas'];
		$w_rtn = xgt_stp_cls($w_lot_bas['STP_CD'], $w_stpcls2, $dmy);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $w_lot_bas['STP_CD'], __LINE__);
			return 4000;
		}
		if (!in_array(trim($w_stpcls2), unserialize(constant("E9_ALW")))) {
			list($g_msg, $g_err_lv) = msg("err_Disabled");
			$g_msg = xpt_err_msg($g_msg, trim($w_stpcls2), __LINE__);
			return 4000;
		}
	
		if(in_array(trim($w_stpcls2), unserialize(constant('E9_ALW')))){
			$w_rtn = chk_lot_sp(trim($w_lot_bas['LOT_ID']),$w_vrb);
			$w_bke_flg = 0;
			if(count($w_vrb) > 0) {
				for($i=0;$i<count($w_vrb);$i++) {
					$w_rtn = chk_lot_pc(trim($w_vrb[$i]), $w_lot_inf);
					if ($w_rtn != 0) {
						$g_err_lv = 0;
						$g_msg = xpt_err_msg($g_msg, trim($w_vrb[$i]), __LINE__);
						return 4000;
					}
					if($w_lot_inf == null) {
						$w_bke_flg = 1;
						break;
					}
				}
			}
			if($w_bke_flg == 0) {
				$w_rtn = chk_time_for_baking_packing($w_lot_bas);
				if($w_rtn != 0){
					return 4000;
				}
			}
		}
	}

	#複数ロット入力時のロット間の整合性チェック
	$w_chk_lot_data = array();
	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lot_bas = $w_lotinfo['lot_bas'];
		if (count($w_chk_lot_data) == 0) {
			$w_chk_lot_data = array(
					"STP_NO" => $w_lot_bas['STP_CD'],
					"MNG_FLG" => $w_lot_bas['MNG_FLG'],
					"RE_INS_FLG" => $w_lot_bas['RE_INS_FLG']
			);
		} else {
			if ($w_chk_lot_data
					!= array(
							"STP_NO" => $w_lot_bas['STP_CD'],
							"MNG_FLG" => $w_lot_bas['MNG_FLG'],
							"RE_INS_FLG" => $w_lot_bas['RE_INS_FLG'])
					) {
				list($g_msg, $g_err_lv) = msg("err_Lot_01");
				$g_msg = xpt_err_msg($g_msg, $w_lot_bas['LOT_ID'], __LINE__);
				return 4000;

			}
		}
	}

	#複数ロット入力時のロット間の整合性チェック
	$w_chk_lot_data = array();
	$w_fin_prd_cd = "";
	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
			$w_lot_bas = $w_lotinfo['lot_bas'];
		$w_rtn = get_fin_prd($w_lot_bas['RT_CD'],
											$w_lot_bas['PRC_CD'],
											$w_lot_bas['STP_CD'],
											$w_fin_prd);
		if($w_rtn != 0) return 4000;
		if (count($w_chk_lot_data) == 0) {
			$w_chk_lot_data = $w_fin_prd;
			$w_fin_prd_cd = $w_fin_prd[0]; #NEW
		} else {
			$w_inter = array_intersect($w_chk_lot_data, $w_fin_prd);
			if (count($w_inter) == 0) {
					list($g_msg, $g_err_lv) = msg("err_Lot_01");
					$g_msg = xpt_err_msg($g_msg, $w_lot_bas['LOT_ID'], __LINE__);
					return 4000;

			}
		}
	}
	   
		$w_chip_qty_all = array();

	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lot_bas = $w_lotinfo['lot_bas'];
		$w_rtn = xgn_prd($w_lot_bas['PRD_CD'], $w_prd_nm, $dmy);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $w_lot_bas['PRD_CD'], __LINE__);
			return 4000;
		}

		# LOT_INF_TBL上の拡散ロット番号取得
		$w_rtn = get_dif_lot_no($w_lot_bas['LOT_ID'], $w_dif_lot_no);
		if($w_rtn != 0) return 4000;

		$w_lotdisp['prd_nm'] = trim($w_prd_nm);
		$w_lotdisp['lot_id'] = trim($w_lot_bas['LOT_ID']);
		$w_lotdisp['lot_no_str'] = trim($w_lot_bas['LOT_NO']);
		$w_lotdisp['chp_qty'] = $w_lot_bas['CHP_QTY'];
		$w_last_lot_chp_qty = $w_lot_bas['CHP_QTY'];
		$w_lotdisp['secret_no'] = $w_lot_bas['SECRET_NO'];
		$w_lotdisp['upd_lev'] = $w_lot_bas['UPD_LEV'];
		$w_lotdisp['rt_cd'] = trim($w_lot_bas['RT_CD']);
		$w_lotdisp['prc_cd'] = trim($w_lot_bas['PRC_CD']);
		$w_lotdisp['stp_cd'] = trim($w_lot_bas['STP_CD']);
		$w_lotdisp['stp_no'] = trim($w_lot_bas['STP_NO']);
		$w_lotdisp['io_blc_cd'] = trim($w_lot_bas['IO_BLC_CD']);
		$w_lotdisp['plt_dvs_cd'] = trim($w_lot_bas['PLT_DVS_CD']);
		$w_lotdisp['prd_cd'] = trim($w_lot_bas['PRD_CD']);
		$w_lotdisp['dif_lot_no'] = $w_dif_lot_no;
		# 使用可能な装置の獲得関数
		$w_lotdisp['equ_cd'] = trim($w_equ_cd);
		$w_lotinfo_list[$w_idx]['disp'] = $w_lotdisp;

		# リング数量合計
		
		$w_chip_qty_all[] = $w_lot_bas['CHP_QTY']; #NEW
	}



	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lotdisp = $w_lotinfo['disp'];

		#装置材料チェック
		$w_rtn = cs_xck_equ_prt($w_lotdisp['equ_cd'], $w_lotdisp['stp_cd'],
				$w_lotdisp['prd_cd']);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
		#先頭ロットのみチェック
		break;
	}
	$w_sum_chp_qty = 0;
	$w_total_chip_qty = 0;
	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		#echo $w_lotdisp['chp_qty'];
		$w_lotdisp = $w_lotinfo['disp'];
		$w_sum_chp_qty += $w_lotdisp['chp_qty'];
	}
	$w_total_chip_qty = $w_sum_chp_qty; #NEW



########### NEW: Determine how many boxes to pack: 

	foreach($w_lotinfo_list as $w_idx => $w_lotinfo){
		for($x = 1; $x <= $w_box_qty_cd ; $x++){
			#echo " $w_idx:";
			$w_lotdisp = $w_lotinfo['disp'];
			$w_pack_lot_lst[$x]['chp_qty'][$w_idx]  = $w_lotdisp['chp_qty']; # QTY Per Lot
			$w_pack_lot_lst[$x]['chp_qty_ttl'] += $w_lotdisp['chp_qty'];
			$w_pack_lot_lst[$x]['prc_cd'] = $w_lotdisp['prc_cd'];
			$w_pack_lot_lst[$x]['stp_cd'] = $w_lotdisp['stp_cd'];
			$w_pack_lot_lst[$x]['prd_cd'] = $w_lotdisp['prd_cd'];
		}
	}
	



	#画面表示用設定
	$gw_scr['s_usr_nm'] = $w_usr_nm;
	$gw_scr['s_lbl_nm'] = $w_lbl_nm;
	$gw_scr['s_lbl_id'] = $w_lbl_id;
	$gw_scr['s_equ_cd'] = $w_equ_cd;
	$gw_scr['s_equ_nm'] = $w_equ_nm;
	$gw_scr['s_lbl_type'] = $w_lbl_type;
	$gw_scr['s_h_mag_row'] = count($w_lotinfo_list);
	$gw_scr['s_h_pack_row'] = count($w_pack_lot_lst); #Set # of rows for Packing
	$gw_scr['s_h_pack_maxcol'] = ($gw_scr['s_h_inp_row'] > DEFAULT_INPUT_ROW ? $gw_scr['s_h_inp_row']
			: DEFAULT_PACK_MAXCOL);
	$gw_scr['s_mag_prt_grp_b'] = $w_mag_prt_grp_b;
	$gw_scr['s_mag_prt_grp_a'] = $w_mag_prt_grp_a;

	$gw_scr['s_plt_flg'] = $w_plt_flg;

	#画面表示用設定(ロット一覧)
	$w_dte_cd = array();

	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lotdisp = $w_lotinfo['disp'];
		$w_rtn = get_datecd($w_lotdisp['lot_id'],$w_datecd);
		$gw_scr['s_lst_prd_nm'][$w_idx] = $w_lotdisp['prd_nm'];
		$gw_scr['s_lst_lot_no_str'][$w_idx] = $w_lotdisp['lot_no_str'];
		$gw_scr['s_lst_chp_qty'][$w_idx] = $w_lotdisp['chp_qty'];
 			$gw_scr['s_lst_dte_cd_str'][$w_idx] = $w_datecd;
		$gw_scr['s_lst_upd_lev'][$w_idx] = $w_lotdisp['upd_lev'];
		$gw_scr['s_lst_prc_cd'][$w_idx] = $w_lotdisp['prc_cd'];
		$gw_scr['s_lst_stp_cd'][$w_idx] = $w_lotdisp['stp_cd'];
		$gw_scr['s_lst_prd_cd'][$w_idx] = $w_lotdisp['prd_cd'];
		$gw_scr['s_lst_dif_lot_no'][$w_idx] = $w_lotdisp['dif_lot_no'];
		$w_dte_cd[] = $w_datecd;
		
	}
   	$w_dte_cn = array_unique($w_dte_cd,SORT_STRING);

	# Check if lots have varied date codes
	if(count($w_dte_cn) > 1){
		
		   #Allow only ONE box for packing/label printing if unique datecode count is more than 1
		   if($w_box_qty_cd != constant("BOX_QTY_ALW_DIFF_DATE_CODE")){
			list($g_msg, $g_err_lv) = msg("err_Lot_05");
			$g_msg = xpt_err_msg($g_msg,$w_box_qty_cd." > ". constant("BOX_QTY_ALW_DIFF_DATE_CODE"), __LINE__);
			return 4000;
		   }
	}

	# If Date code is the same for all the lots(Also applicable for lots with diff Date Codes), 
	#	check the total Chip Qty of the 1st up to the 2nd to the last lot.
	#	Sum should not exceed: STD Qty * number of boxes.

	# Get Standard Packing QTY using finished good Product Type
	$w_rtn = chk_pck_qty($w_fin_prd_cd,$w_std_pck_qty);
	if($w_rtn != 0) return 4000;
   
	$w_chip_qty_required= $w_std_pck_qty * $w_box_qty_cd;
	$w_tot_chip_qty_less1 = $w_total_chip_qty - $w_last_lot_chp_qty;

	if ($w_tot_chip_qty_less1 > $w_chip_qty_required){
		list($g_msg, $g_err_lv) = msg("err_Lot_06");
		$g_msg = xpt_err_msg($g_msg,$w_tot_chip_qty_less1." > ". $w_chip_qty_required, __LINE__);
		return 4000;
	}


	# Check if Maximum count for Date Code combination is set in "Product Information Master Registration"
	$w_rtn = chk_date_code_max($w_fin_prd_cd,$w_dt_cd_qty); #NEW
	if ($w_rtn != 0) {
		#if NOT Set, Allow only 1 Date Code
		$w_dte_cn = array_unique($w_dte_cd,SORT_STRING);
		if(count($w_dte_cn) > constant("DT_CDMAX")) {
			list($g_msg, $g_err_lv) = msg("err_Lot_04");
			$g_msg = xpt_err_msg($g_msg,count($w_dte_cn)." > ". constant("DT_CDMAX"), __LINE__);
			return 4000;
		}
	}
	   #################

	#先頭ロットのみチェック
	   
	#画面表示用設定(マガジンロットＩＤ)
	$w_row = 1;
	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lotdisp = $w_lotinfo['disp'];
		$gw_scr['s_mag_lot_id'][$w_row] = $w_lotdisp['lot_id'];
		$gw_scr['s_mag_lot_id_idx'][$w_row] = "$w_idx";
		$w_row++;
	}

	#画面表示用設定(Packing Lot ID)
	for ($i = 1; $i <= count($w_pack_lot_lst); $i++) {	#$w_pack_lot_lst --> # of boxes to PACK
		$w_pack_lot = $w_pack_lot_lst[$i];
		$gw_scr['s_pack_lot_id'][$i] = "";
		foreach ($w_pack_lot['chp_qty'] as $w_idx => $w_qty) {
			$gw_scr['s_pack_chp_qty'][$i][$w_idx] = $w_qty;
		}
		$gw_scr['s_pack_chp_qty_ttl'][$i] = $w_pack_lot['chp_qty_ttl'];
		$gw_scr['s_pack_prc_cd'][$i] = $w_pack_lot['prc_cd'];
		$gw_scr['s_pack_stp_cd'][$i] = $w_pack_lot['stp_cd'];
		$gw_scr['s_pack_prd_cd'][$i] = $w_pack_lot['prd_cd'];
	}
   
	#画面表示用設定(材料管理用)
	foreach ($w_lotinfo_list as $w_idx => $w_lotinfo) {
		$w_lotdisp = $w_lotinfo['disp'];
		$gw_scr['s_lot_id'] = $w_lotdisp['lot_id'];
		$gw_scr['s_rt_cd'] = $w_lotdisp['rt_cd'];
		$gw_scr['s_prc_cd'] = $w_lotdisp['prc_cd'];
		$gw_scr['s_stp_cd'] = $w_lotdisp['stp_cd'];
		$gw_scr['s_stp_no'] = $w_lotdisp['stp_no'];
		$gw_scr['s_io_blc_cd'] = $w_lotdisp['io_blc_cd'];
		$gw_scr['s_prd_cd'] = $w_lotdisp['prd_cd'];
		$gw_scr['s_equ_cd'] = $w_lotdisp['equ_cd'];
		$gw_scr['s_sum_chp_qty'] = $w_sum_chp_qty;
		$gw_scr['s_plt_dvs_cd'] = $w_lotdisp['plt_dvs_cd'];
		break;
	}


	if(count($w_warn) > 0){
		$g_err_lv = 3;
		$g_msg = implode("<br>", $w_warn);
	}

	# 梱包品種が取得できれば表示
	$gw_scr['s_pckd']	   = $w_pckd;
	$gw_scr['s_pck_prd_cd'] = $w_pck_prd_cd;
	$gw_scr['s_pck_prd_nm'] = $w_pck_prd_nm;

	//new code
	$w_rtn = get_sealing_condition($w_fin_prd_cd, $w_sealing_control);
	$gw_scr['s_ppcs_val'] = 0;
	if($w_sealing_control == "1") {
		$gw_scr['edit_enable'] = true;	
		$gw_scr['s_is_packing'] = true;
	}else{
		$gw_scr['edit_enable'] = false;
		$gw_scr['s_is_packing'] = false;
	}
#	echo " Control : ". $w_sealing_control;


	#set PCS control
        $w_rtn = refresh_pcs_ctrl_ui();
}

function main_md1_redisp() {
	global $gw_scr;
	$w_inp_row = trim($gw_scr['s_inp_row']);
	if ($w_inp_row == "") {
		$w_inp_row = $gw_scr['s_h_inp_row'];
	}
	if (!check_num($w_inp_row)) {
		$w_inp_row = $gw_scr['s_h_inp_row'];
	}
	if ($w_inp_row < 1 || $w_inp_row > constant("DEFAULT_MAX_INPLOT")) {
		$w_inp_row = $gw_scr['s_h_inp_row'];
	}


	$gw_scr['s_inp_row'] = $w_inp_row;
	$gw_scr['s_h_inp_row'] = $w_inp_row;
	$gw_scr['s_h_pack_maxcol'] = $w_inp_row;
}
#======================================================================
# モード2
#======================================================================
function main_md2() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch ($gw_scr['s_act']) {
	case "CHECK":
		$gw_scr['s_mag_col'] = $gw_scr['s_h_mag_col'];
		$w_rtn = main_md2_check();
		if ($w_rtn == 0) {
			scr_mode_chg(3);
		}
		break;
	case "ERASE":
		$gw_scr['s_mag_col'] = $gw_scr['s_h_mag_col'];
		set_init(3);
		break;
	case "RETURN":
		set_init(2);
		scr_mode_chg(1);

		$gw_scr['s_ppcs_val'] = 0;
		break;
	case "REDISP":
		main_md2_redisp();
		break;
	}

	return 0;
}

#======================================================================
# モード2
#======================================================================
function main_md3() {
		global $gw_scr;
		global $g_msg;
		global $g_err_lv;

		switch ($gw_scr['s_act']) {
		case "CHECK":
				$gw_scr['s_mag_col'] = $gw_scr['s_h_mag_col'];
				$w_rtn = main_md3_check();
				if ($w_rtn == 0) {
						scr_mode_chg(4);
				}
				break;
		case "ERASE":
				$gw_scr['s_mag_col'] = $gw_scr['s_h_mag_col'];
				set_init(4);
				break;
		case "RETURN":
				set_init(3);
				scr_mode_chg(2);

				// new code
				if($gw_scr['s_is_packing'] == 1) {
				        $gw_scr['edit_enable'] = true;
				        $gw_scr['s_pack_pcs'] = $gw_scr['s_ppcs_val'];
				}
				break;

		case "REDISP":
				main_md2_redisp();
				break;
		}

		return 0;
}

#======================================================================
function main_md2_check() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_rtn = refresh_pcs_ctrl_ui();

	// new code
	if($gw_scr['s_is_packing'] == 1) {
		$gw_scr['s_ppcs_val'] = $gw_scr['s_pack_pcs'];
		$gw_scr['edit_enable'] = true;
	}

	#------------------------------------------------------------------
	# 入力チェック
	#------------------------------------------------------------------
	$w_rtn = check_input(2);
	if ($w_rtn != 0) {
		return 4000;
	}
	# 入力した梱包品種が正しいか
	$w_rtn = xgc_prd($gw_scr['s_pck_prd_nm'], $w_pck_prd_cd, $dmy);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_pck_prd_nm'], __LINE__);
		return 4000;
	}

	$w_rtn = chk_pck_prd($gw_scr['s_rt_cd'],
						$gw_scr['s_prc_cd'],
						$gw_scr['s_stp_cd'],
						$w_pck_prd_cd,
						$dmy);
	if($w_rtn != 0) return 4000;

	#------------------------------------------------------------------
	# 次ステップ取得
	#------------------------------------------------------------------
	$w_rtn = xgt_nio($gw_scr['s_prc_cd'], $gw_scr['s_io_blc_cd'], $gw_scr['s_stp_no'], $gw_scr['s_plt_dvs_cd'],
					$w_nxt_io_blc_cd, $w_nxt_stp_cd, $w_nxt_stp_no);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# 材料チェック
	#------------------------------------------------------------------
	$w_rtn = cs_xck_prt_ctrl($w_pck_prd_cd, $w_nxt_stp_cd,
					$w_prt_ctrl, $dmy, $dmy, $dmy, $dmy, $dmy, $dmy, $dmy);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}
		
	#Use the finished goods Product type for STD Packing Qty Maintenance
	$w_rtn = chk_pck_qty($w_pck_prd_cd,$w_pck_qty);  
	if($w_rtn != 0) return 4000;

	# NEW
	$gw_scr['s_pck_prd_qty'] = $w_pck_qty;
	$gw_scr['s_pck_tot_qty'] = $w_pck_qty * $gw_scr['s_box_qty_cd'];


	$w_lotinfo_all_list = array();
	for ($i = 1; $i <= $gw_scr['s_h_inp_row']; $i++) {
		if ($gw_scr['s_lst_lot_id'][$i] != "") {
			$w_rtn = xgt_lot($gw_scr['s_lst_lot_id'][$i], $w_lot_bas);
			if ($w_rtn != 0) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
				return 4000;
			}
			$w_lotinfo_all_list[$i]['chip_qty'] = $w_lot_bas['CHP_QTY'];  #Lot_bas info for non-blank LOTID key-in
		}
	}

	#------------------------------------------------------------------
	# 許可工程チェック

	# 入力したチップ数、リング数を合計
	$w_pack_chp_qty_ttl = array();

	$w_rtn = fill_boxes();
	   
	#check if total chip qty is sufficient for the total boxes based on std_qty
	if(($gw_scr['s_pck_prd_qty'] * $gw_scr['s_box_qty_cd']) > $gw_scr['s_sum_chp_qty'] ) {
			list($g_msg, $g_err_lv) = msg("err_InsuffQtyWarn");
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			#return 4000;
	}


	if ($g_msg == "") {
		list($g_msg, $g_err_lv) = msg("guid_Confirm");
		$g_msg = xpt_err_msg($g_msg, "", "");
	}
	
	if($gw_scr['s_is_packing'] == 1){
		//new code
        	if($gw_scr['s_pack_pcs'] == 0){
			$gw_scr['edit_enable'] = true;
			$gw_scr['s_pack_pcs'] = $gw_scr['s_ppcs_val'];
			list($g_msg, $g_err_lv) = msg("err_PCS_Blank");
        	        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
	                return 4000;
	        }else if($gw_scr['s_pack_pcs'] == 2) {
			$gw_scr['edit_enable'] = true;
			$gw_scr['s_pack_pcs'] = $gw_scr['s_ppcs_val'];
			list($g_msg, $g_err_lv) = msg("err_PCS_NG");
        	        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                	return 4000;
	        }else{
        		$gw_scr['s_ppcs_val'] = $gw_scr['s_pack_pcs'];
	        	$gw_scr['edit_enable'] = false;
		}
	}

	# Check PCS Control
        $w_rtn = check_pcs_status();
        if($w_rtn != 0){
                return 4000;
        }

	$gw_scr['s_pck_prd_cd'] = trim($w_pck_prd_cd);

	$gw_scr['s_nxt_stp_cd'] = trim($w_nxt_stp_cd);
	$gw_scr['s_prt_ctrl'] = $w_prt_ctrl;
	return 0;
}

#======================================================================
# Loop the box
#======================================================================
function fill_boxes(){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	
	#fill in the values
	$w_total_boxes = $gw_scr['s_box_qty_cd'];
	$w_std_qty = $gw_scr['s_pck_prd_qty'];
	$w_lot_count = 0;
	
	#add in clear contents here
	#use the maximum columns
	$w_max_cols = constant('DEFAULT_MAX_INPLOT');
	for($w_box_cnt = 1; $w_box_cnt<= $w_total_boxes; $w_box_cnt++){
		for($i=1;$i<=$w_max_cols; $i++){
			$w_rtn = fill_post($w_box_cnt, $i, "");
		}
	}
	
	#markers
	$w_lot_start = 1;
	$w_curr_lot = 1;
	$w_curr_box_sum = 0;
	
	#$w_dummy lot values
	#fill this in
	for($i=1;$i<=count($gw_scr['s_lst_chp_qty']);$i++){
		if($gw_scr['s_lst_chp_qty'][$i] != ""){
			$w_lot_count++;
			$w_arr_lot_vals[$i] = $gw_scr['s_lst_chp_qty'][$i];
		}
	}
	
	for($w_box_cnt = 1; $w_box_cnt<= $w_total_boxes; $w_box_cnt++){
		$w_lot_start = $w_curr_lot;
		$w_curr_box_sum = 0;
		
		for($w_curr_lot = $w_lot_start; $w_curr_lot <= $w_lot_count; $w_curr_lot++){
			$w_curr_lot_val = $w_arr_lot_vals[$w_curr_lot];
			#echo "BOX:{$w_box_cnt} LOT:{$w_curr_lot} = {$w_curr_lot_val} <br>";
			$w_rem_box_qty = $w_std_qty - $w_curr_box_sum;
			
			#if remaining box qty is > standard qty
			if($w_curr_lot_val > $w_rem_box_qty){
				$w_rtn = fill_post($w_box_cnt, $w_curr_lot, $w_rem_box_qty);
				$w_arr_lot_vals[$w_curr_lot] -= $w_rem_box_qty;
				$w_curr_box_sum = $w_curr_box_sum + $w_rem_box_qty;
				#next box
				break;
			
			}elseif($w_curr_lot_val == $w_rem_box_qty){
				$w_rtn = fill_post($w_box_cnt, $w_curr_lot, $w_rem_box_qty);
				$w_arr_lot_vals[$w_curr_lot] -= $w_rem_box_qty;
				$w_curr_box_sum = $w_curr_box_sum + $w_rem_box_qty;
				#move to next lot
				$w_curr_lot++;
				#move to next box
				break;
			
			}elseif($w_curr_lot_val < $w_rem_box_qty){
				$w_rtn = fill_post($w_box_cnt, $w_curr_lot, $w_curr_lot_val);
				$w_curr_box_sum = $w_curr_box_sum + $w_curr_lot_val;
				$w_arr_lot_vals[$w_curr_lot] -= $w_curr_lot_val;
				
			}
			
		}
		$gw_scr['s_pack_chp_qty_ttl'][$w_box_cnt] = $w_curr_box_sum;
	}
	
	#add in remainder qty
	for($i=1;$i<=count($w_arr_lot_vals);$i++){
		$gw_scr['s_rem_chp_qty_ttl'][$i] = $w_arr_lot_vals[$i];
		$gw_scr['s_lst_rmn_qty'][$i] = $w_arr_lot_vals[$i];
	}
	
	return 0;
}


#======================================================================
# Fill the lot qty
#======================================================================
function fill_post($w_boxnum, $w_lotnum, $w_val){
	global $gw_scr;
	$gw_scr['s_pack_chp_qty'][$w_boxnum][$w_lotnum] = $w_val;
	return 0;
}





#======================================================================
# MAIN MD3 Check
#======================================================================
function main_md3_check() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_rtn = refresh_pcs_ctrl_ui();
	#------------------------------------------------------------------
	# 入力チェック
	#------------------------------------------------------------------
	$w_rtn = check_input(2);
	if ($w_rtn != 0) {
			return 4000;
	}
	# 入力した梱包品種が正しいか
	$w_rtn = xgc_prd($gw_scr['s_pck_prd_nm'], $w_pck_prd_cd, $dmy);
	if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $gw_scr['s_pck_prd_nm'], __LINE__);
			return 4000;
	}

	$w_rtn = chk_pck_prd($gw_scr['s_rt_cd'],
											$gw_scr['s_prc_cd'],
											$gw_scr['s_stp_cd'],
											$w_pck_prd_cd,
											$dmy);
	if($w_rtn != 0) return 4000;

	#------------------------------------------------------------------
	# 次ステップ取得
	#------------------------------------------------------------------
	$w_rtn = xgt_nio($gw_scr['s_prc_cd'], $gw_scr['s_io_blc_cd'], $gw_scr['s_stp_no'], $gw_scr['s_plt_dvs_cd'],
									$w_nxt_io_blc_cd, $w_nxt_stp_cd, $w_nxt_stp_no);
	if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
	}
	#------------------------------------------------------------------
	# 材料チェック
	#------------------------------------------------------------------
	$w_rtn = cs_xck_prt_ctrl($w_pck_prd_cd, $w_nxt_stp_cd,
									$w_prt_ctrl, $dmy, $dmy, $dmy, $dmy, $dmy, $dmy, $dmy);
	if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
	}

	$w_rtn = chk_pck_qty($w_pck_prd_cd,$w_pck_qty);  
	if($w_rtn != 0) return 4000;

	if($gw_scr['s_pck_prd_qty'] == ""){
	list($g_msg, $g_err_lv) = msg("err_Nec_Input");
	$w_tg = get_tg(itm("PckTypQty"), htmlspecialchars($gw_scr['s_pck_prd_qty']));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
	}

	if(!check_num($gw_scr['s_pck_prd_qty'])){
	list($g_msg, $g_err_lv) = msg("err_Inp_Char");
	$w_tg = get_tg(itm("PckTypQty"), htmlspecialchars($gw_scr['s_pck_prd_qty']));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
	}

	if($gw_scr['s_pck_prd_qty'] > $w_pck_qty) {
			list($g_msg, $g_err_lv) = msg("err_Pck_Input");
			$w_tg = get_tg(itm("PckTypQty"),
							   htmlspecialchars($gw_scr['s_pck_prd_qty']));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
	}

	#check if total chip qty is sufficient for the total boxes based on std_qty
	if(($gw_scr['s_pck_prd_qty'] * $gw_scr['s_box_qty_cd']) > $gw_scr['s_sum_chp_qty'] ) {
			list($g_msg, $g_err_lv) = msg("err_InsuffQty");
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
	}
	
	
	#If loose qty, then only supervisor can do.
##	if($gw_scr['s_pck_prd_qty'] < $w_pck_qty) {
##		$w_rtn = chk_usr_gr($gw_scr['s_usr_id'],$w_usr_grp);
##		if($w_rtn != 0) return 4000;
##               if (!array_intersect($w_usr_grp,unserialize(constant('DG_SUP')))) {
##					list($g_msg, $g_err_lv) = msg("err_Pck_Sup");
##					$w_tg = get_tg(itm("PckTypQty"),
##								   htmlspecialchars($gw_scr['s_pck_prd_qty']));
##					$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
##					return 4000;
##		}
##	}

	$w_pck_qty = $gw_scr['s_pck_prd_qty'];
	$w_rtn = fill_boxes();

	/*
	# 入力したチップ数、リング数を合計
	$w_pack_chp_qty_ttl = array();
	for($i=1; $i<=$gw_scr['s_h_pack_row']; $i++){
			$w_pack_chp_qty_ttl[$i] = 0;
			for($j=1; $j<=$gw_scr['s_h_pack_maxcol']; $j++){
					if($gw_scr['s_pack_chp_qty'][$i][$j] <= $w_pck_qty){
						if(($w_pack_chp_qty_ttl[$i] + $gw_scr['s_pack_chp_qty'][$i][$j]) <= $w_pck_qty) {
								$w_pack_chp_qty_ttl[$i] += $gw_scr['s_pack_chp_qty'][$i][$j];
						} else {
								$w_pack_chp_qty_ttl[$i] = $w_pck_qty;
						}
					} else {
						$gw_scr['s_pack_chp_qty'][$i][$j] = $w_pck_qty;
						$w_pack_chp_qty_ttl[$i] = $w_pck_qty;
					}
			}
	}
	# 残数計算
	for($i=1; $i<=$gw_scr['s_h_pack_row']; $i++){
			$w_rem_chp_qty_ttl[$i] = 0;
			for($j=1; $j<=$gw_scr['s_h_pack_maxcol']; $j++){
					if($gw_scr['s_lst_lot_id'][$j] == "") continue;
					### 現在の在庫数よりも多い入力はエラー
					if($gw_scr['s_lst_chp_qty'][$j] < $gw_scr['s_pack_chp_qty'][$i][$j]){
							list($g_msg, $g_err_lv) = msg("err_Ovr_StkQty");
							$w_tg = get_tg($gw_scr['s_lst_lot_id'][$i], $gw_scr['s_lst_chp_qty'][$j], $gw_scr['s_pack_chp_qty'][$i][$j]);
							$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
							return 4000;
					}

					#------------------------------------------------------------------
					# 鍛誼鍛蔵誕鈎
					#------------------------------------------------------------------
					$w_rtn = check_equ_succession_in($gw_scr['s_lst_lot_id'][$j], $gw_scr['s_equ_cd']);
					if ($w_rtn !=  0) {
							return $w_rtn;
					}


					$w_rem_chp_qty[$i][$j] = $gw_scr['s_lst_chp_qty'][$j] - $gw_scr['s_pack_chp_qty'][$i][$j];
					if($w_pck_qty >= $gw_scr['s_lst_chp_qty'][$j]) {
							$gw_scr['s_lst_rmn_qty'][$j] =  0;
							$gw_scr['s_pack_chp_qty'][$i][$j] = $gw_scr['s_lst_chp_qty'][$j];
							$w_pck_qty = $w_pck_qty - $gw_scr['s_lst_chp_qty'][$j];
					}else{
							$gw_scr['s_lst_rmn_qty'][$j] = $gw_scr['s_lst_chp_qty'][$j] - $w_pck_qty;
							$gw_scr['s_pack_chp_qty'][$i][$j] = $w_pck_qty;
							$w_pck_qty = 0;
					}
					if($w_pck_qty == 0 && ( $gw_scr['s_lst_rmn_qty'][$j] == $gw_scr['s_lst_chp_qty'][$j])){
							$gw_scr['s_pack_chp_qty'][$i][$j] = 0;
					}
					$w_rem_chp_qty_ttl[$i] += $w_rem_chp_qty[$i][$j];
			}
	}
	$gw_scr['s_pack_chp_qty_ttl']  = $w_pack_chp_qty_ttl;
	$gw_scr['s_rem_chp_qty'] = $w_rem_chp_qty;
	$gw_scr['s_rem_chp_qty_ttl'] = $w_rem_chp_qty_ttl;
	
	*/
	if ($g_msg == "") {
			list($g_msg, $g_err_lv) = msg("guid_Execute");
			$g_msg = xpt_err_msg($g_msg, "", "");
	}

	
	if(($gw_scr['s_pck_prd_qty'] * $gw_scr['s_box_qty_cd']) <> array_sum($gw_scr['s_pack_chp_qty_ttl'])) {
			list($g_msg, $g_err_lv) = msg("err_Pck_Eq");
			$w_tg = get_tg(itm("PckTypQty"),
							   htmlspecialchars($gw_scr['s_pck_prd_qty']));
			$g_msg = xpt_err_msg($g_msg, $w_tg . "-" . array_sum($gw_scr['s_pack_chp_qty_ttl']), __LINE__);
			return 4000;
	}

	$w_rtn = refresh_pcs_ctrl_ui();
	$w_rtn = update_all_pcs_status("1");	

	$gw_scr['s_pck_prd_cd'] = trim($w_pck_prd_cd);

	$gw_scr['s_nxt_stp_cd'] = trim($w_nxt_stp_cd);
	$gw_scr['s_prt_ctrl'] = $w_prt_ctrl;
	return 0;
}

function update_all_pcs_status($status){
        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        $w_pcs_count =  $gw_scr['s_h_pcs_row'];
        for($i =1 ; $i <= $w_pcs_count; $i++){
                $gw_scr['s_pcs_param_list'][$i] = $status;
        }

        return 0;
}

function check_pcs_status(){

        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        $w_pcs_count =  $gw_scr['s_h_pcs_row'];
        for($i =1 ; $i <= $w_pcs_count; $i++){
                if($gw_scr['s_pcs_param_list'][$i] != "1"){
                        list($g_msg, $g_err_lv) = msg("err_PCS");
                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_pcs_param_list_nm'][$i], __LINE__);
                        return 4000;
                }
        }

        return 0;

}
function refresh_pcs_ctrl_ui(){

        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        $w_pcs_cd = constant("P0_PCS_TRACK_IN");

        $w_rtn = cs_xgt_pcs_ctrl($w_pcs_cd, $gw_scr['s_stp_cd'], $gw_scr['s_prd_cd'], $w_pcs_data);
        $w_pcs_count = count($w_pcs_data);

        if($w_pcs_count > 0){
                $gw_scr['s_h_pcs_row'] = $w_pcs_count;
        }else{
                $gw_scr['s_h_pcs_row'] = -1;
        }

        for($i=1; $i <= $w_pcs_count; $i++){
                $gw_scr['s_pcs_param_list_nm'][$i] = $w_pcs_data[$i-1]['PAR_TXT'];
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
# check baking
#==================================================================
function chk_time_for_baking_packing($w_lot_bas){

		global $g_msg;
		global $g_err_lv;
		global $g_cpu_dts;
		$w_bake_time = "";
		$w_bake_count = "";
		### Get Baking Time / Baking Count
		$w_ctg_cd = constant("CT_BAKING_TIME_2");
		$w_rtn = cs_xck_baking__GetBakeTime($w_lot_bas['LOT_ID'],
											constant("CE_DVSCD"),
											$w_ctg_cd,
											$w_bake_time,
											$w_bake_count,
											"LOT_INF_TBL");
		if($w_rtn != 0){
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
		}

		if($w_bake_time == ""){
				### Get Baking Time / Baking Count
				$w_ctg_cd = constant("CT_BAKING_TIME_1");
				$w_rtn = cs_xck_baking__GetBakeTime($w_lot_bas['LOT_ID'],
													constant("CE_DVSCD"),
													$w_ctg_cd,
													$w_bake_time,
													$w_bake_count,
													"LOT_INF_TBL");
				if($w_rtn != 0){
						$g_err_lv = 0;
						$g_msg = xpt_err_msg($g_msg, "", __LINE__);
						return 4000;
				}
				
				/** If there is no baking time, just skip the checking **/
				/*
				if($w_bake_time == ""){
						list($g_msg, $g_err_lv) = msg("err_Get_BakeDat");
						$g_msg = xpt_err_msg($g_msg, trim($w_lot_bas['LOT_ID']), __LINE__);
						return 4000;
				}
				*/
		 }
	/*
		$w_ctg_cd = constant("CT_BAKING_TIME_3");
		$w_rtn = cs_xck_baking__GetBakeTime($w_lot_bas['LOT_ID'],
											constant("CE_DVSCD"),
											$w_ctg_cd,
											$w_bake_time,
											$w_bake_count,
											"LOT_INF_TBL");
		if($w_rtn != 0){
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
		}
	*/	
		/** If there is no baking time, just skip the checking **/
	
		if($w_bake_time != ""){
		
			### Check Baking
			$w_rtn = cs_xck_baking($w_lot_bas['STP_CD'],
									$w_lot_bas['PRD_CD'],
									constant("CE_DVSCD"),
									$w_ctg_cd,
									$w_bake_time,
									$w_bake_count);
			 	
			if($w_rtn != 0){
					$g_err_lv = 0;
					$g_msg = xpt_err_msg($g_msg, trim($w_lot_bas['LOT_ID']), __LINE__);
					return 4000;
			}
		}
	
		return 0;
}


#======================================================================
function chk_pck_prd($w_rt_cd, $w_prc_cd, $w_stp_cd, $w_pck_prd_cd, &$r_rt_cd)
{
	global $g_msg;
	global $g_err_lv;

	$w_sql = <<<_SQL
SELECT DISTINCT
	POM3.RT_CD,
	POM3.SEQ_NO_RT,
	POM3.M_RT_FLG_SCH,
	POM3.PRD_CD AS CD,
	PRD.PRD_NM AS NM
FROM
	PRD_ORG_MST POM1,
	PRD_ORG_MST POM2,
	PRD_ORG_MST POM3,
	PRD_MST	 PRD
WHERE
	POM1.RT_CD = '{$w_rt_cd}'
	AND POM1.PRC_CD = '{$w_prc_cd}'
	AND POM1.STP_CD = '{$w_stp_cd}'
	AND POM1.DEL_FLG = '0'
	AND POM2.PRC_CD = POM1.PRC_CD
	AND POM2.STP_CD = POM1.STP_CD
	AND POM2.PRD_CD = POM1.PRD_CD
	AND POM2.DEL_FLG = '0'
	AND POM3.RT_CD = POM2.RT_CD
	AND POM3.IO_FLG IN ('1','0')
	AND POM3.SEQ_NO_RT = POM2.SEQ_NO_RT + 1
	AND POM3.PRD_CD = '{$w_pck_prd_cd}'
	AND POM3.DEL_FLG = '0'
	AND PRD.PRD_CD = POM3.PRD_CD
	AND PRD.DEL_FLG = '0'
ORDER BY
	POM3.M_RT_FLG_SCH DESC,
	POM3.RT_CD DESC,
	POM3.SEQ_NO_RT
_SQL;
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, $w_pck_prd_cd, __LINE__);
		return 4000;
	}

	$cnt = 0;
	$strcnt = 0;
	$duprtcd  = array();
	$errprccd = array();
	while($w_row = db_fetch_row($w_stmt)){
		$cnt++;

		### 重複はスキップ
		$rtcd = trim($w_row['RT_CD']);
		if($duprtcd[$rtcd] == "1"){
			continue;
		}
		$duprtcd[$rtcd] = "1";

		#------------------------------------------------------------------
		# 新規振出可能かチェック
		#------------------------------------------------------------------
		$w_rtn = chk_strrtcd($w_row['RT_CD'], $w_row['SEQ_NO_RT'], $w_prccd, $w_flg);
		if($w_rtn != 0){
			return 4000;
		}
		if($w_flg == 0){
			$errprccd[] = $w_prccd;
			continue; ### 振出不可は飛ばす
		}
		$strcnt++;

		$r_rt_cd = trim($w_row['RT_CD']);
		break;
	}
	db_res_free($w_stmt);

	if($cnt == 0){
		list($g_msg, $g_err_lv) = msg("err_Get_PckRt");
		$g_msg = xpt_err_msg($g_msg, $w_pck_prd_cd, __LINE__);
		return 4000;
	}
	if($strcnt == 0){
		list($g_msg, $g_err_lv) = msg("err_NewStrPoint");
		$g_msg = xpt_err_msg($g_msg, $errprccd[0], __LINE__);
		return 4000;
	}

	return 0;
}


#======================================================================
function get_fin_prd($w_rt_cd, $w_prc_cd, $w_stp_cd, &$r_prd_cd)
{
		global $g_msg;
		global $g_err_lv;

		$w_sql = <<<_SQL
SELECT DISTINCT
		POM3.RT_CD,
		POM3.SEQ_NO_RT,
		POM3.M_RT_FLG_SCH,
		POM3.PRD_CD AS CD,
		PRD.PRD_NM AS NM
FROM
		PRD_ORG_MST POM1,
		PRD_ORG_MST POM2,
		PRD_ORG_MST POM3,
		PRD_MST	 PRD
WHERE
		POM1.RT_CD = '{$w_rt_cd}'
		AND POM1.PRC_CD = '{$w_prc_cd}'
		AND POM1.STP_CD = '{$w_stp_cd}'
		AND POM1.DEL_FLG = '0'
		AND POM2.PRC_CD = POM1.PRC_CD
		AND POM2.STP_CD = POM1.STP_CD
		AND POM2.PRD_CD = POM1.PRD_CD
		AND POM2.DEL_FLG = '0'
		AND POM3.RT_CD = POM2.RT_CD
		AND POM3.IO_FLG IN ('1','0')
		AND POM3.SEQ_NO_RT = POM2.SEQ_NO_RT + 1
		AND POM3.DEL_FLG = '0'
		AND PRD.PRD_CD = POM3.PRD_CD
		AND PRD.DEL_FLG = '0'
ORDER BY
		POM3.M_RT_FLG_SCH DESC,
		POM3.RT_CD DESC,
		POM3.SEQ_NO_RT
_SQL;
		$w_stmt = db_res_set($w_sql);
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
				list($g_msg, $g_err_lv) = msg("err_Sel");
				$g_msg = xpt_err_msg($g_msg, $w_pck_prd_cd, __LINE__);
				return 4000;
		}

		$cnt = 0;
		$strcnt = 0;
		$duprtcd  = array();
		$errprccd = array();
	$r_prd_cd = array();
		while($w_row = db_fetch_row($w_stmt)){
				$cnt++;

				### 重複はスキップ
				$rtcd = trim($w_row['RT_CD']);
				if($duprtcd[$rtcd] == "1"){
						continue;
				}
				$duprtcd[$rtcd] = "1";

				#------------------------------------------------------------------
				# 新規振出可能かチェック
				#------------------------------------------------------------------
				$w_rtn = chk_strrtcd($w_row['RT_CD'], $w_row['SEQ_NO_RT'], $w_prccd, $w_flg);
				if($w_rtn != 0){
						return 4000;
				}
				if($w_flg == 0){
						$errprccd[] = $w_prccd;
						continue; ### 振出不可は飛ばす
				}
				$strcnt++;
				$r_prd_cd[] = trim($w_row['CD']);
		}
		db_res_free($w_stmt);
		if($cnt == 0){
				list($g_msg, $g_err_lv) = msg("err_Get_PckRt");
				$g_msg = xpt_err_msg($g_msg, $w_pck_prd_cd, __LINE__);
				return 4000;
		}
		if($strcnt == 0){
				list($g_msg, $g_err_lv) = msg("err_NewStrPoint");
				$g_msg = xpt_err_msg($g_msg, $errprccd[0], __LINE__);
				return 4000;
		}

		return 0;
}

#======================================================================
function chk_pck_qty($w_prd_cd,&$r_pck_qty)
{
		global $g_msg;
		global $g_err_lv;
		$w_dat_cd = AW_PKGCHIP;
		$w_sql = <<<_SQL
SELECT NUM_DAT FROM
PRD_INF_MST
WHERE
DEL_FLG = '0' AND
PRD_CD = '$w_prd_cd' AND
DAT_CD = '$w_dat_cd'
_SQL;
	// echo $w_sql;
		$w_stmt = db_res_set($w_sql);
		#echo $w_sql;
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
				list($g_msg, $g_err_lv) = msg("err_Sel");
				$g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
				return 4000;
		}

		$w_row = db_fetch_row($w_stmt);
		db_res_free($w_stmt);

		if(!$w_row){
				list($g_msg, $g_err_lv) = msg("err_Get_PckQty");
				$g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
				return 4000;
		}

		$r_pck_qty = trim($w_row['NUM_DAT']);

		return 0;
}


#======================================================================
function chk_date_code_max($w_fin_prd_cd,&$r_dt_cdmax) #NEW
{
		global $g_msg;
		global $g_err_lv;
		$w_dat_cd = AW_DT_CDMAX;
		$w_sql = <<<_SQL
SELECT NUM_DAT FROM
PRD_INF_MST
WHERE
DEL_FLG = '0' AND
PRD_CD = '$w_fin_prd_cd' AND
DAT_CD = '$w_dat_cd'
_SQL;
		$w_stmt = db_res_set($w_sql);
		#echo $w_sql;
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
				list($g_msg, $g_err_lv) = msg("err_Sel");
				$g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
				return 4000;
		}

		$w_row = db_fetch_row($w_stmt);
		db_res_free($w_stmt);

		if(!$w_row){
				#list($g_msg, $g_err_lv) = msg("err_Get_PckQty");
				#$g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
				return 4000;
		}

		$r_dt_cdmax = trim($w_row['NUM_DAT']);

		return 0;
}

function get_sealing_condition($w_fin_prd_cd,&$r_sealing_condition_ctrl) #NEW
{
                global $g_msg;
                global $g_err_lv;
		$w_aw_sealing_control = constant("AW_SEALING_CONTROL");

                $r_sealing_condition_ctrl = "";
                $w_sql = <<<_SQL
SELECT 
	*
FROM
	PRD_INF_MST
WHERE
	PRD_CD = '$w_fin_prd_cd' AND
	DEL_FLG = '0' AND
	DAT_CD = '$w_aw_sealing_control'
_SQL;
                $w_stmt = db_res_set($w_sql);
                #echo $w_sql;
                $w_rtn = db_do($w_stmt);
                if($w_rtn != 0){
                                list($g_msg, $g_err_lv) = msg("err_Sel");
                                $g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
                                return 4000;
                }

                $w_row = db_fetch_row($w_stmt);
                db_res_free($w_stmt);

                if($w_row){
			$r_sealing_condition_ctrl = trim($w_row['TXT_DAT']);						
                }

#                $r_dt_cdmax = trim($w_row['NUM_DAT']);

                return 0;
}


#======================================================================


function chk_lot_sp($w_lot_id,&$r_lot_sp)
{
		global $g_msg;
		global $g_err_lv;

	$w_stp_pck = constant('ST_PACKING');
	$w_stp_pre = constant('ST_PREPACK');
	
		$w_sql = <<<_SQL
SELECT DISTINCT LOT_ID
FROM
	 LOT_LOG 
WHERE
LOT_ID IN(SELECT LOT_ID_T FROM LOT_LOG where LOT_ID = '{$w_lot_id}' AND DEL_FLG ='0'   AND STP_CD = '{$w_stp_pre}') AND
STP_CD_T = '{$w_stp_pck}'

_SQL;
		$w_stmt = db_res_set($w_sql);
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
				list($g_msg, $g_err_lv) = msg("err_Sel");
				$g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
				return 4000;
		}
	$r_lot_sp = array();
	$i = 0;
		while($w_row = db_fetch_row($w_stmt)){
				$r_lot_sp[$i] = $w_row['LOT_ID'];
		$i++;
		}

		db_res_free($w_stmt);

		return 0;
}



function chk_lot_sp_stp($w_lot_id,$w_e9,&$r_lot_sp)
{
		global $g_msg;
		global $g_err_lv;
		$w_sql = <<<_SQL
SELECT 
	distinct LL.lot_id_t
FROM 
	LOT_LOG LL
	INNER JOIN STP_MST SM on SM.STP_CD = LL.STP_CD and SM.DEL_FLG = '0'
WHERE 
	LL.LOT_ID='{$w_lot_id}' 
	AND LL.DEL_FLG = '0'
	AND SM.STP_CLS_2 = '{$w_e9}'
	AND LL.VERB = 'IOSP'
_SQL;
		$w_stmt = db_res_set($w_sql);
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
				list($g_msg, $g_err_lv) = msg("err_Sel");
				$g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
				return 4000;
		}
	$r_lot_sp = array();
	$w_cnt = 0;
		while($w_row = db_fetch_row($w_stmt)){
		$r_lot_sp[$i] = $w_row;
	}
		db_res_free($w_stmt);

		#$r_lot_sp = $w_row;

		return 0;
}



#======================================================================
function chk_lot_pc($w_lot_id,&$r_lot_sp)
{
		global $g_msg;
		global $g_err_lv;

	$w_stp_cls = constant('E9_PACKING');
		$w_sql = <<<_SQL
SELECT
		lot_id
FROM
		LOT_LOG LL
		INNER JOIN STP_MST SM on SM.STP_CD = LL.STP_CD and SM.DEL_FLG = '0'
WHERE
		LL.LOT_ID='{$w_lot_id}'
		AND LL.DEL_FLG = '0'
		AND SM.STP_CLS_2 = '{$w_stp_cls}'
		AND LL.VERB = 'IOOT'
	AND LL.DEL_FLG = '0'
	AND LL.CHP_QTY_T = '0'
_SQL;
		$w_stmt = db_res_set($w_sql);
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
				list($g_msg, $g_err_lv) = msg("err_Sel");
				$g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
				return 4000;
		}

		$w_row = db_fetch_row($w_stmt);
		db_res_free($w_stmt);

		$r_lot_sp = $w_row;

		return 0;
}

#======================================================================
function chk_usr_gr($w_usr_id,&$r_usr_gr)
{
		global $g_msg;
		global $g_err_lv;

		$w_sql = <<<_SQL
SELECT 
	* 
FROM
	USR_GRP_MST 
WHERE 
	USR_ID = '$w_usr_id' AND
	DEL_FLG = '0'
_SQL;
		$w_stmt = db_res_set($w_sql);
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
				list($g_msg, $g_err_lv) = msg("err_Sel");
				$g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
				return 4000;
		}

        	$r_usr_gr = array();
                while($w_row = db_fetch_row($w_stmt)){
                                ### 重複はスキップ
                                $r_usr_gr[] = trim($w_row['USR_GRP_CD']);
                }

		db_res_free($w_stmt);

		if(!$r_usr_gr){
				list($g_msg, $g_err_lv) = msg("err_Pck_Sup");
				$g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
				return 4000;
		}

		return 0;
}

#======================================================================
function main_md2_redisp() {
	global $gw_scr;
	$w_disp_row = trim($gw_scr['s_mag_col']);
	if ($w_disp_row == "") {
		$w_disp_row = $gw_scr['s_h_mag_col'];
	}
	if (!check_num($w_disp_row)) {
		$w_disp_row = $gw_scr['s_h_mag_col'];
	}
	if ($w_disp_row < 1 || $w_disp_row > 10) {
		$w_disp_row = $gw_scr['s_h_mag_col'];
	}
	$gw_scr['s_mag_col'] = $w_disp_row;
	$gw_scr['s_h_mag_col'] = $w_disp_row;

}
#======================================================================
# モード3
#======================================================================
function main_md4() {
	global $gw_scr;

#	 $w_rtn = refresh_pcs_ctrl_ui();
	$w_rtn = refresh_pcs_ctrl_ui();
        $w_rtn = update_all_pcs_status("1");
	switch ($gw_scr['s_act']) {
	case "EXECUTE":
		$w_rtn = main_md4_exe();
		if ($w_rtn == 0) {
			scr_mode_chg(5);
		}
		break;
	case "RETURN":
		set_init(5);
		scr_mode_chg(3);
		break;
	}

	return 0;
}
#======================================================================
function main_md4_exe() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;


	#-- トランザクションの開始
	db_begin();
	$w_rtn = main_md4_data_exe();
	if ($w_rtn != 0) {
		db_rollback();
		return $w_rtn;
	}
	db_commit();

	#Print the Packing LOTID 
	for ($i = 1; $i <= count($gw_scr['s_pack_lot_id']); $i++) {
		$w_lot_id = $gw_scr['s_pack_lot_id'][$i];
				#echo "Printing $w_lot_id";
		$w_lp_cd = $gw_scr['s_lbl_cd'];
		$w_rtn = cs_xpt_pcllsi_label($w_lot_id, $w_lp_cd,1);
		if ($w_rtn != 0) {
			$gw_scr['s_prnt_msg'] = $g_msg;
			$gw_scr['s_prnt_lv'] = $g_err_lv;
		}
	}
#	db_rollback();

	list($g_msg, $g_err_lv) = msg("msg_Update");
	return 0;

}

#==================================================================
function get_datecd($w_lot_id,&$r_datecd)
{
		global $g_msg;
		global $g_err_lv;

		$w_datecd = constant("CT_DATECD");
		$w_ctgdvs = constant("CE_DVSCD");

		$w_sql = <<<SQL
SELECT	
	DISTINCT 
		CTG_DAT_TXT
FROM
		LOT_INF_TBL
WHERE
		DEL_FLG = '0'
		AND CTG_CD = '{$w_datecd}'
		AND CTG_DVS_CD = '{$w_ctgdvs}'
	AND LOT_ID = '{$w_lot_id}'
		AND DEL_FLG = '0'
SQL;
		$w_stmt = db_res_set($w_sql);
		#echo $w_sql;
		$w_rtn = db_do($w_stmt);
		if($w_rtn != 0){
				list($g_msg, $g_err_lv) = msg("err_Sel");
				$g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
				return 4000;
		}
		$w_cnt = 0;
		$w_arr = array();
		while($w_row = db_fetch_row($w_stmt)){
				if(trim($w_row['CTG_DAT_TXT']) != "") {
						$w_arr[$w_cnt]= trim($w_row['CTG_DAT_TXT']);
						$w_cnt++;
				}
		}
		$r_datecd = $w_arr[0];

		if((count($w_arr) >= 1) && (count($w_arr) < 3)) {
				$r_datecd = implode(" ", $w_arr);
		} elseif(count($w_arr) >= 3){
				$r_datecd = implode(" ", $w_arr);
		} else {
				$r_datecd = '';
		}

		db_res_free($w_stmt);

		return 0;
}

#======================================================================
function main_md4_data_exe() {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;


	#------------------------------------------------------------------
	# テーブルロック
	#------------------------------------------------------------------
	$w_rtn = db_lock("LOT_NUM_TBL");
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}


	$w_cur_mother_lot_id = "";
#########################################################################################
#########################################################################################
	for($c=1;$c <= $gw_scr['s_box_qty_cd']; $c++) {
		$w_no_lots = 0;
		$w_no_lotid = array();
		$w_no_updlv = array();
		$w_no_qty = array();
		for($d=1; $d <= count($gw_scr['s_pack_chp_qty'][$c]);$d++) {
			if($gw_scr['s_pack_chp_qty'][$c][$d] > 0) {
				$w_no_lots++;
				$w_no_lotid[$w_no_lots] = $gw_scr['s_lst_lot_id'][$d];
				$w_no_updlv[$w_no_lots] = $gw_scr['s_lst_upd_lev'][$d];
				$w_no_qty[$w_no_lots] = $gw_scr['s_pack_chp_qty'][$c][$d];
				$w_no_rem_qty[$w_no_lots] = $gw_scr['s_lst_rmn_qty'][$d]; //remaining qty	
			} 
		}

		#var_dump($w_no_lots);echo "<br><br><br>";
		if(count($w_no_lotid)>=1){
			$w_cur_mother_lot_id = $w_no_lotid[1];
		}

		$w_equ_cd = $gw_scr['s_equ_cd'];
		$w_lotinfo_list = array();
		for ($i = 1; $i <= $w_no_lots; $i++) {
			$w_lot_id = $w_no_lotid[$i];
			$w_upd_lev = $w_no_updlv[$i];
			$w_rem_qty = $w_no_rem_qty[$i];

			#------------------------------------------------------------------------------------
                        # Packing VI control
                        # System to remove packing vi machine form LOT_INF_TBL if the lot remain qty is zero
                        #------------------------------------------------------------------------------------
                        if ( !empty( $w_lot_id ) && $w_rem_qty == 0 ) {

				$w_rtn = get_pack_vi_equ( $w_lot_id, $w_dat );
				if($w_rtn != 0) return 4000;

				if( count( $w_dat ) > 0 ) {
					$w_rtn = rm_pack_vi_equ( $w_lot_id );
					if($w_rtn != 0) return 4000;
				}
                        }

			### 更新後のリング数量
			if ($w_lot_id != "" && $w_no_qty[$i] > 0) {

				# ロット基本取得
				$w_rtn = xgt_lot($w_lot_id, $w_lot_bas);
				if ($w_rtn != 0) {
					$g_err_lv = 0;
					$g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
					return 4000;
				}

				# チップ数が梱包ロットのチップ数と異なる場合は分割
				$w_bind_lot_no_str = "";
				if($gw_scr['s_pckd'] != "1"
				&& $w_lot_bas['CHP_QTY'] > $w_no_qty[$i] && $w_no_qty[$i] > 0
				){
					$w_bind_lot_no_str = $w_lot_bas['LOT_NO_STR']; 	# LOG_BIND登録用
				   	$w_sp_lot_no_str   = $w_lot_bas['LOT_NO_STR'];
					$w_ex_lot_no_str   = $w_lot_bas['LOT_NO_STR'];

					### 分割準備
					$w_spcnt = 1;
					$w_arr_sl_qty[1] = $w_lot_bas['SL_QTY'];
					$w_arr_chp_qty[1] = $w_no_qty[$i];
					$w_arr_lf_qty[1] = 0;
					$w_arr_lot_no[1] = $w_sp_lot_no_str;
					$w_arr_sec_no[1] = $w_lot_bas['SECRET_NO'];
					### IOSP
					$w_rtn = main_verb_iosp($gw_scr['s_usr_id'],
										$w_spcnt,
										$w_arr_sl_qty,
										$w_arr_chp_qty,
										$w_arr_lf_qty,
										$w_arr_lot_no,
										$w_arr_sec_no,
										$w_lot_bas,
										$w_new_lot_id,
										$w_new_upd_lev);
					if($w_rtn != 0) return 4000;

					### 元ロットのロット基本退避
					$w_ex_lot_bas = $w_lot_bas;
					### 元ロットのLOT_NO_STR更新
					$w_upd = array
					(
						"LOT_NO"		=> $w_ex_lot_no_str,
						"LOT_NO_STR"	=> $w_ex_lot_no_str
					);
					$w_whr = "LOT_ID = '" . $w_ex_lot_bas['LOT_ID'] . "' ";
					$w_rtn = db_update_one("LOT_BAS_TBL", $w_upd, $w_whr);
					if($w_rtn != 0){
						list($g_msg, $g_err_lv) = msg("err_Upd");
						$g_msg = xpt_err_msg($g_msg, "LOT_BAS_TBL", __LINE__);
						return 4000;
					}

					### 分割新規ロットのロット基本取得
					$w_lot_bas = array();
					$w_rtn = xgt_lot($w_new_lot_id[1], $w_lot_bas);
					if($w_rtn != 0){
						$g_err_lv = 0;
						$g_msg = xpt_err_msg($g_msg, "", __LINE__);
						return 4000;
					}
					### 分割新規ロットの LOT_NO_STR 更新(次Verbで自動更新)
					$w_lot_bas['LOT_NO_STR'] = $w_sp_lot_no_str;
	
					### LOT_INF_TBL引継ぎ
					$w_rtn = inhrt_lot_inf_tbl_for_iosp($gw_scr['s_usr_id'],
													$w_ex_lot_bas['LOT_ID'],
													$w_lot_bas['LOT_ID']);
					if($w_rtn != 0) return 4000;

				}

				# IOIN
				$w_rtn = main_verb_ioin($gw_scr['s_usr_id'], $gw_scr['s_equ_cd'], $w_lot_bas);
				if($w_rtn != 0) return 4000;

				# IOOT
				$w_rtn = main_verb_ioot($gw_scr['s_usr_id'], array(), array(), array(),
								array(), array(), $w_lot_bas['SL_QTY'], $w_lot_bas['CHP_QTY'],
								$w_lot_bas['LF_QTY'], null, $w_lot_bas);
				if($w_rtn != 0) return 4000;

				# IOMV
				$w_rtn = main_verb_iomv($gw_scr['s_usr_id'], "", $w_lot_bas);
				if($w_rtn != 0) return 4000;

				$w_rtn = chk_pck_prd($gw_scr['s_rt_cd'],
									  $gw_scr['s_prc_cd'],
									  $gw_scr['s_stp_cd'],
									  $gw_scr['s_pck_prd_cd'],
									  $w_new_rt_cd);
				if($w_rtn != 0) return 4000;

				# IOPC
				if(trim($w_lot_bas['PRD_CD']) != $gw_scr['s_pck_prd_cd'] || trim($gw_scr['s_rt_cd']) != trim($w_new_rt_cd)){
					$w_rtn = main_verb_iopc($gw_scr['s_usr_id'],
										$w_new_rt_cd,
										$gw_scr['s_pck_prd_cd'],
										$w_lot_bas['RNK_PTN'],
										$w_lot_bas);
					if($w_rtn != 0) return 4000;
				}
				$w_lotinfo_list[$i] = $w_lot_bas['LOT_ID'];

			}
		}

		#------------------------------------------------------------------
		# 1秒加算
		#------------------------------------------------------------------
		sleep(1);
		xpt_1sec_dts();
		#var_dump($w_lotinfo_list);echo "<br><br>";

                if (count($w_lotinfo_list) >= 2) {
                          $w_rtn = main_verb_iomg_for_packing($gw_scr['s_usr_id'],
                                            $w_lotinfo_list, $w_no_qty, $w_ring_qty, $w_lot_bas_mg);
                          if ($w_rtn != 0) {
                                   $g_err_lv = 0;
                                   $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                                   return 4000;
                          }
                          $w_packinfo_list[$w_idx]['lotid'] = $w_lot_bas_mg['LOT_ID'];
                 }


                 $w_rtn = xgt_lot($w_lotinfo_list[1], $w_lot_bas);
                 if ($w_rtn != 0) {
                           $g_err_lv = 0;
                           $g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
                           return 4000;
                 }
		
                $w_rtn = get_cairn_flg($w_lot_bas['DVSN_CD_PRC'], $w_lot_bas['RDG_CD_PRC'], $w_flg);

                if($w_flg != 0){
			list($g_msg, $g_err_lv) = msg("err_cairn_running");
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
                }

		#------------------------------------------------------------------
		# SPACEチェック
		#------------------------------------------------------------------
		$w_rtn = chk_space($gw_scr['s_usr_id'],
							$w_lot_bas['LOT_ID'],
							"N",
							$w_lot_bas['PRD_CD'],
							$w_lot_bas['STP_CD'],
							$w_equ_cd,
							$w_lot_bas['CHP_QTY'],
							"",
							$gw_scr['s_prg_id'],
							$w_spc_err_cd,
							$w_spc_err_nm,
							$w_spc_err_msg);
				if($w_rtn != 0) return 4000;
	
		   	 	### エラーコードが xxxxx1 でない場合はエラー
				if($w_spc_err_cd != "xxxxx1"){
					list($g_msg, $g_err_lv) = msg("err_Err_Space");
					$g_msg = xpt_err_msg($g_msg, $w_spc_err_msg, __LINE__);
					return 4000;
				}

			#IOIN
			$w_rtn = main_verb_ioin($gw_scr['s_usr_id'], $gw_scr['s_equ_cd'], $w_lot_bas);
			if ($w_rtn != 0) {
				return 4000;
			}


                        # IOOT
                        $w_rtn = main_verb_ioot($gw_scr['s_usr_id'], array(), array(), array(),
                                                                array(), array(), $w_lot_bas['SL_QTY'], $w_lot_bas['CHP_QTY'],
                                                                $w_lot_bas['LF_QTY'], null, $w_lot_bas);
                        if($w_rtn != 0) return 4000;

			$w_lot_bas['CRTR'] = "1";
			$w_rtn = update_ctrl_lot_bas_tbl($w_lot_bas);
			if ($w_rtn != 0) {
				return 4000;
			}
		$gw_scr['s_pack_lot_id'][$c] = $w_lot_bas['LOT_ID'];		

		$w_po_req = false;
                $w_rtn = cs_chk_po_req($w_lot_bas['PRC_CD'], $w_po_req ) ;
                if($w_rtn != 0){
                        list($g_msg, $g_err_lv) = msg("err_Sel");
			$g_msg = xpt_err_msg($g_msg, "cs_chk_po_req", __LINE__);
                        return 4000;
                }
                // Check the Panasonic or not
                if ($w_po_req) {
                        // Add mother Lot
                        $w_rtn = ins_lot_inf_tbl_for_mother($gw_scr['s_usr_id'],$w_cur_mother_lot_id,$w_lot_bas['LOT_ID']);
                        if($w_rtn != 0 ){
                                return 4000;
                        }
                }
#	}
########################################################################################################################
########################################################################################################################

		#------------------------------------------------------------------
		# 材料管理対象 材料消費（ＭＴＣＳ）
		#------------------------------------------------------------------
		# TODO 要確認(PRT_WIP_LOG-MTCSのCHP_QTYが消費量計算時の扱い数ではなく、分割後扱い数で登録される)
		#var_dump($w_lot_bas);
		if ($gw_scr['s_prt_ctrl'] == "1") {
			$w_rtn = main_exe_usemat($w_lot_bas['LOT_ID'], $w_lot_bas['STP_CD'],
					$w_lot_bas['PRD_CD']);
			if ($w_rtn != 0)
				return 4000;
		}
	}
	$w_rtn = refresh_pcs_ctrl_ui();
        $w_rtn = update_all_pcs_status("1");

	return 0;
}

/*
 * To get the current packing vi lot running on the machine
 */
function get_pack_vi_equ( $w_lot_id, &$r_dat )
{
        global $g_msg;
        global $g_err_lv;

        $r_dat = array();

        $w_ctgcd  = constant("CT_PACKING_VI");

        $w_sql = "
                SELECT
                        *
                FROM
                        LOT_INF_TBL
                WHERE
                        DEL_FLG = '0'
                        AND LOT_ID = '{$w_lot_id}'
                        AND CTG_CD = '{$w_ctgcd}'
        ";

        $w_stmt = db_res_set( $w_sql );
        $w_rtn = db_do( $w_stmt );
        if ( $w_rtn != 0 ){
                list( $g_msg, $g_err_lv ) = msg( "err_Sel" );
                $g_msg = xpt_err_msg( $g_msg, "LOT_INF_TBL", __LINE__ );
                return 4000;
        }

        if ( $w_row = db_fetch_row( $w_stmt )) {
                $r_dat = array_map( "trim", $w_row );
        }

        return 0;
}

/*
 * To remove packing vi machine from lot information table 
 * so that the others lot can proceed with that machine
 */
function rm_pack_vi_equ( $w_lot_id )
{
        global $g_msg;
        global $g_err_lv;

	$w_ctg_packvi = constant( "CT_PACKING_VI" );

	$w_upd = array(
		"DEL_FLG" => "1"
	);

	$w_whr =  "
		LOT_ID = '{$w_lot_id}' AND 
		CTG_CD = '{$w_ctg_packvi}' AND 
		DEL_FLG = '0'
	";

        $w_rtn = db_update_one( "LOT_INF_TBL", $w_upd, $w_whr );
        if ( $w_rtn != 0 ) {
                list( $g_msg, $g_err_lv ) = msg( "err_Upd" );
                $g_msg = xpt_err_msg( $g_msg, "LOT_INF_TBL", __LINE__ );
                return 4000;
        }

        return 0;
}

function ins_lot_inf_tbl_for_mother($w_usr_id, $w_lot_id, $w_new_lot_id) {
        global $g_msg;
        global $g_err_lv;
        global $g_cpu_dts;
        global $g_low_dts;

        $w_ce_dvscd = constant("CE_DVSCD");
        $w_ct_mother_lot = constant("CT_MOTHER");
        $w_verb = "IOIN";

        $w_ins = array(
                     "DEL_FLG" => "0",
                     "LOT_ID" => $w_new_lot_id,
                     "CTG_DVS_CD" => $w_ce_dvscd,
                     "CTG_CD" => $w_ct_mother_lot,
                     "SL_ID" => " ",
                     "CTG_DAT_TXT" => $w_lot_id,
                     "CTG_DAT_VAL" => "",
                     "CRT_VERB" => $w_verb,
                     "CRT_DTS" => $g_cpu_dts,
                     "USR_ID_CRT" => $w_usr_id,
                     "UPD_VERB" => " ",
                     "UPD_DTS" => $g_low_dts,
                     "USR_ID_UPD" => " ",
                     "UPD_LEV" => "1"
        );
        $w_rtn = db_insert("LOT_INF_TBL", $w_ins);
        if ($w_rtn != 0) {
                list($g_msg, $g_err_lv) = msg("err_Ins");
                $g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
                return 4000;
        }

        return 0;
}

#======================================================================
# 使用材料登録
#======================================================================
function main_exe_usemat($w_lot_id, $w_stp_cd, $w_prd_cd) {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_mcnt = get_session_value('s_max_rows', constant("PGM_MTMNG"));
	$w_prt_cd = get_session_value('s_list_prt_cd', constant("PGM_MTMNG"));
	$w_mt_lot_id = get_session_value('s_list_mt_lot_id', constant("PGM_MTMNG"));
	$w_use_qty = get_session_value('s_list_use_qty', constant("PGM_MTMNG"));

	$w_mtcs_flg = 0;
	for ($m = 1; $m <= $w_mcnt; $m++) {
		# パーツコードが無い行は飛ばす
		if ($w_prt_cd[$m] == "")
			continue;
		# 使用数の入力の無い行は飛ばす
		if ($w_use_qty[$m] == "")
			continue;

		#---------------------------
		# ショップコード取得
		#---------------------------
		$w_rtn = xgt_stp($w_stp_cd, $w_shp_cd);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return $w_rtn;
		}
		#echo "{$w_prt_cd[$m]} -- $w_mt_lot_id[$m] -- <br>";
		#return 4000;
		#------------------------------------------------------------------
		# 材料在庫情報取得
		#------------------------------------------------------------------
		$w_rtn = xgt_prt_wip($w_prt_cd[$m], $w_shp_cd, "", $w_mt_lot_id[$m],
				$w_prt_wip);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}

		$w_rtn = main_verb_mtcs($w_lot_id, $w_prt_cd[$m], $w_stp_cd, "",
				$w_prd_cd, $w_use_qty[$m], $w_prt_wip);
		if ($w_rtn != 0) {
			return 4000;
		}

		$w_mtcs_flg = 1;
	} # end for

	# 材料消費入力が無ければエラー
	if ($w_mtcs_flg == 0) {
		list($g_msg, $g_err_lv) = msg("err_Inp_Mtcs");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}
#======================================================================
# モード4
#======================================================================
function main_md5() {
	global $gw_scr;

#	$w_rtn = refresh_pcs_ctrl_ui();
#        $w_rtn = update_all_pcs_status("1");

	switch ($gw_scr['s_act']) {
	case "RETURN":
		set_init(2);
		set_init(1);
		scr_mode_chg(1);
		break;
	}

	return 0;
}

#==================================================================
# Ｖｅｒｂ処理 統合
#==================================================================
function main_verb_iomg_for_packing($w_usr_id, $w_lot_id_mg, $w_chp_qty_mg,
		$w_ring_qty_mg, &$r_lot_bas_mg) {

	$w_arr_mg_lot = array();

	for ($i = 1; $i <= count($w_lot_id_mg); $i++) {
		$w_rtn = xgt_lot($w_lot_id_mg[$i], $w_lot_bas);
		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
			return 4000;
		}
		$w_lot_no = $w_lot_bas['LOT_NO'];
		$w_arr_mg_lot['lot_id'][$i] = $w_lot_bas['LOT_ID'];
		$w_arr_mg_lot['lot_no'][$i] = $w_lot_no;
		$w_arr_mg_lot['lot_no_str'][$i] = $w_lot_bas['LOT_NO_STR'];
		$w_arr_mg_lot['lot_st_dvs'][$i] = $w_lot_bas['LOT_ST_DVS'];
		$w_arr_mg_lot['chp_qty'][$i] = $w_chp_qty_mg[$i];
		$w_arr_mg_lot['lf_qty'][$i] = 0;
		$w_arr_mg_lot['secret_no'][$i] = $w_lot_bas['SECRET_NO'];
		$w_arr_mg_lot['sl_qty'][$i] = $w_lot_bas['SL_QTY'];
		$w_arr_mg_lot['cmt'][$i] = "";
		$w_arr_mg_lot['upd_lev'][$i] = $w_lot_bas['UPD_LEV'];
		$w_arr_mg_lot['lot_typ_cd'][$i] = trim($w_lot_bas['LOT_TYP_CD']);
		if ($i == 1) {
			$r_lot_bas_mg = $w_lot_bas;
		}
	}

	$w_rtn = main_verb_iomg($w_usr_id, $w_arr_mg_lot, $r_lot_bas_mg);
	if ($w_rtn != 0) {
		return $w_rtn;
	}

	// need to update 
	if(trim($r_lot_bas_mg['LOT_TYP_CD']) != constant("CC_RTRN")){
           if(in_array(constant("CC_RTRN"), $w_arr_mg_lot['lot_typ_cd'])){
                        $w_rtn = upd_lot_bas_tbl_lottyp($r_lot_bas_mg['LOT_ID'], constant("CC_RTRN"));
                        if($w_rtn != 0){
                                return $w_rtn;
                        }
                        $r_lot_bas_mg['LOT_TYP_CD'] = constant("CC_RTRN");
                }
        }


	#LOT_INF_TBL(リング数量合算)
	$w_lot_id = $w_lot_id_mg[1];
	for ($i = 2; $i <= count($w_lot_id_mg); $i++) {
		$w_new_lot_id = $w_lot_id_mg[$i];
		$w_rtn = inhrt_lot_inf_tbl_for_iomg($w_usr_id, $w_new_lot_id, $w_lot_id);
		if ($w_rtn != 0) {
			return $w_rtn;
		}
	}
	return 0;
}

function upd_lot_bas_tbl_lottyp($w_lot_id, $w_lot_typ)
{
        $g_msg;
        $g_err_lv;

        $w_where = "LOT_ID = '" . $w_lot_id . "' ";

        $w_upd = array
                (
                        "LOT_TYP_CD"    => $w_lot_typ
                );

        $w_rtn = db_update_one("LOT_BAS_TBL", $w_upd, $w_where);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = msg("err_Upd");
                $g_msg = xpt_err_msg($g_msg, "LOT_BAS_TBL", __LINE__);
                return $w_rtn;
        }

        return 0;
}


#==================================================================
# Ｖｅｒｂ処理 統合
#==================================================================
function main_verb_iomg($w_usr_id, $w_arr_mg_lot, &$w_lot_bas) {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# 統合後の各数量を計算
	#------------------------------------------------------------------
	$w_sum_sl_qty = array_sum($w_arr_mg_lot['sl_qty']);
	$w_sum_chp_qty = array_sum($w_arr_mg_lot['chp_qty']);
	$w_sum_lf_qty = array_sum($w_arr_mg_lot['lf_qty']);

	$w_array_cnt = count($w_arr_mg_lot['lot_id']);

	#------------------------------------------------------------------
	# ロット状態の確認
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
	# ＩＯＭＧ
	# 戻り値：$w_lot_bas
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
# Ｖｅｒｂ処理 分割
#==================================================================
function main_verb_iosp_for_packing($w_usr_id, $w_chp_qty_sp, $w_ring_qty_sp,
		&$w_lot_bas, &$r_lot_id_list) {
	if (count($w_chp_qty_sp) < 2) {
		return 4000;
	}
	for ($i = 2; $i <= count($w_chp_qty_sp); $i++) {
		$w_arr_chp_qty[$i - 1] = $w_chp_qty_sp[$i];
		$w_arr_sl_qty[$i - 1] = 0;
		$w_arr_lf_qty[$i - 1] = 0;
		$w_arr_lot_no[$i - 1] = $w_lot_bas['LOT_NO'];
		$w_arr_secret_no[$i - 1] = $w_lot_bas['SECRET_NO'];
	}
	$w_array_cnt = count($w_chp_qty_sp) - 1;
	$w_rtn = main_verb_iosp($w_usr_id, $w_array_cnt, $w_arr_sl_qty,
			$w_arr_chp_qty, $w_arr_lf_qty, $w_arr_lot_no, $w_arr_secret_no,
			$w_lot_bas, $w_new_lot_id, $w_new_upd_lev);
	if ($w_rtn != 0) {
		return $w_rtn;
	}

	#LOT_INF_TBL引継ぎ
	for ($i = 1; $i <= count($w_new_lot_id); $i++) {
		$w_rtn = inhrt_lot_inf_tbl_for_iosp($w_usr_id, $w_lot_bas['LOT_ID'],
				$w_new_lot_id[$i]);
		if ($w_rtn != 0) {
			return $w_rtn;
		}
	}
	$w_idx = 1;
	$r_lot_id_list = array();
	$r_lot_id_list[$w_idx++] = $w_lot_bas['LOT_ID'];
	for ($i = 1; $i <= count($w_new_lot_id); $i++) {
		$r_lot_id_list[$w_idx++] = $w_new_lot_id[$i];
				// echo " main_verb_iosp_for_packing:";
				#echo $w_new_lot_id[$i];
	}

	return $w_rtn;
}
#==================================================================
# Ｖｅｒｂ処理 分割
# 引数：$w_sl_qty		I	分割スライス面積
#		$w_chp_qty		I	分割チップ数
#		$w_lf_qty		I	分割スライス数
#		$w_lot_bas		I/O	ロット基本情報
#		$r_new_lot_id	O	新規ロットＩＤ
#		$r_new_upd_lev	O	新規更新レベル
#
# Ｖｅｒｂ処理後のロット基本情報が戻ることに注意
#==================================================================
function main_verb_iosp($w_usr_id, $w_array_cnt, $w_arr_sl_qty, $w_arr_chp_qty,
		$w_arr_lf_qty, $w_arr_lot_no, $w_arr_secret_no, &$w_lot_bas,
		&$r_new_lot_id, &$r_new_upd_lev) {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_rtn = iosp_st_check($w_lot_bas['LOT_ST_DVS']);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}

	$w_cmt = "";
	#	$w_cmt = $gw_scr['s_cmt'];

	for ($i = 1; $i <= $w_array_cnt; $i++) {
		$w_sp_sl_qty[$i] = $w_arr_sl_qty[$i];
		$w_sp_chp_qty[$i] = $w_arr_chp_qty[$i];
		$w_sp_lf_qty[$i] = $w_arr_lf_qty[$i];
		$w_sp_lot_no[$i] = $w_arr_lot_no[$i];
		$w_sp_secret_no[$i] = $w_arr_secret_no[$i];
		$w_sp_cmt[$i] = $w_cmt;
	}

	$w_lot_bas_b4_split = $w_lot_bas;
	$w_rtn = iosp($w_usr_id, $w_lot_bas['LOT_ID'], $w_lot_bas['UPD_LEV'],
			$w_cmt, $w_lot_bas['LOT_NO'], $w_array_cnt, $w_sp_sl_qty,
			$w_sp_chp_qty, $w_sp_lf_qty, $w_sp_lot_no, $w_sp_secret_no,
			$w_sp_cmt, $w_new_lot_id, $w_new_del_flg, $w_new_upd_lev,
			$w_lot_bas);

	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, '', __LINE__);
		return $w_rtn;
	}

	

	$r_new_lot_id = $w_new_lot_id;
	$r_new_upd_lev = $w_new_upd_lev;

	/* Inherit Section */
        # Ensure PO CTG_CD is inherited to child.
        $w_rtn = cs_xgt_inhrt_po_data($gw_scr['s_usr_id'],$w_lot_bas_b4_split, $w_new_lot_id);
        if ($w_rtn != 0) {
                $g_err_lv = 0;
                $g_msg  = xpt_err_msg($g_msg, $w_rtn, __LINE__);
                return 4000;
        }



	return 0;
}
#==================================================================
# 品種変更 ＩＯＰＣ
#==================================================================
function main_verb_iopc($w_usr_id,
						$w_new_rt_cd,
						$w_nxt_prd_cd,
						$w_rank,
						&$w_lot_bas){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_rtn = iopc_st_check($w_lot_bas['LOT_ST_DVS']);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	$w_rtn = iopc(
				$w_lot_bas['LOT_ID'],
				$w_usr_id,
				$w_lot_bas['UPD_LEV'],
				$w_nxt_prd_cd,
				$w_new_rt_cd,
				$w_rank,
				"",
				$w_lot_bas);

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}		

	return 0;
}
#==================================================================
# 材料使用開始
#==================================================================
function main_verb_mtin($w_usr_id, $w_lot_id, $w_mt_lot_id) {
	global $g_msg;
	global $g_err_lv;

	$w_shpcd  = constant("SH_BLCKCS");
	#------------------------------------------------------------------
	# 材料在庫取得ＳＱＬ
	#------------------------------------------------------------------
	$w_sql = "";
	$w_sql .= " SELECT * FROM PRT_WIP_TBL";
	$w_sql .= " WHERE MT_LOT_ID = '{$w_mt_lot_id}' ";
	$w_sql .= " AND SHP_CD = '{$w_shpcd}'";

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if ($w_rtn != 0) {
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRT_WIP_TBL", __LINE__);
		return 4000;
	}
	$w_prtwip = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	#------------------------------------------------------------------
	# 材料状態チェック
	#------------------------------------------------------------------
	$w_rtn = mtin_st_check($w_prtwip);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# MTIN 材料使用開始
	#------------------------------------------------------------------
	$w_rtn = mtin($w_lot_id, $w_mt_lot_id, " ", $w_usr_id,
			$w_prtwip['UPD_LEV'], "", $w_prtwip);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#=========================================================================
# Ｖｅｒｂ処理 材料消費 
# 引数：$w_arr_mg_lot   I   統合される全ロット（配列）
#	   $w_lot_bas	  I/O ロット基本情報
#
# Ｖｅｒｂ処理後のロット基本情報が戻ることに注意
#=========================================================================
function main_verb_mtcs($w_lot_id, $w_prt_cd, $w_stp_cd, $w_equ_cd, $w_prd_cd,
		$w_qty, &$r_prt_wip) {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------
	# 材料状態チェック
	#------------------------------------------------------
	$w_rtn = mtcs_st_check($r_prt_wip['PRT_ST_DVS']);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------
	# ショップで扱えるパーツであるかチェック
	#------------------------------------------------------
	$w_rtn = mtcs_shp_check($w_prt_cd, $w_stp_cd, $w_equ_cd, $w_prd_cd);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------
	# ＭＴＣＳ
	#------------------------------------------------------
	$w_rtn = mtcs($w_lot_id, $w_qty, $gw_scr['s_usr_id'],
			$r_prt_wip['UPD_LEV'], " ", $w_cmt, $r_prt_wip);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

function update_ctrl_lot_bas_tbl($w_lot_bas) {
	global $g_msg;
	global $g_err_lv;
	$w_upd = array(
			"CRTR" => $w_lot_bas['CRTR']
	);
	$w_whr = "LOT_ID = '" . $w_lot_bas['LOT_ID'] . "'";
	$w_rtn = db_update_one("LOT_BAS_TBL", $w_upd, $w_whr);
	if ($w_rtn != 0) {
		list($g_msg, $g_err_lv) = msg("err_Upd");
		$g_msg = xpt_err_msg($g_msg, "LOT_BAS_TBL", __LINE__);
		return 4000;
	}
	return 0;

}
#==================================================================
# LOT_INF_TBL引継ぎ
#==================================================================
function inhrt_lot_inf_tbl_for_iomg($w_usr_id, $w_lot_id, $w_lot_id_mg) {
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;
	$w_verb = "IOMG";

	#------------------------------------------------------------------
	# 引継ぎ元のデータ取得()
	#------------------------------------------------------------------
	$w_sql = "";
	$w_sql .= " SELECT * FROM LOT_INF_TBL";
	$w_sql .= " WHERE";
	$w_sql .= " LOT_ID = '{$w_lot_id}'";
	$w_sql .= " AND DEL_FLG = '0'";

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
	# 新規ロットへ引継ぎ
	#------------------------------------------------------------------
	for ($i = 1; $i <= $cnt; $i++) {

		$w_ins = array(
				"DEL_FLG" => "0",
				"LOT_ID" => $w_lot_id_mg,
				"CTG_DVS_CD" => $w_dat[$i]['CTG_DVS_CD'],
				"CTG_CD" => $w_dat[$i]['CTG_CD'],
				"SL_ID" => $w_dat[$i]['SL_ID'],
				"CTG_DAT_TXT" => $w_dat[$i]['CTG_DAT_TXT'],
				"CTG_DAT_VAL" => $w_dat[$i]['CTG_DAT_VAL'],
				"CRT_VERB" => $w_verb,
				"CRT_DTS" => $g_cpu_dts,
				"USR_ID_CRT" => $w_usr_id,
				"UPD_VERB" => " ",
				"UPD_DTS" => $g_low_dts,
				"USR_ID_UPD" => " ",
				"UPD_LEV" => "1"
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
# LOT_INF_TBL引継ぎ
#==================================================================
function inhrt_lot_inf_tbl_for_iosp($w_usr_id, $w_lot_id, $w_new_lot_id) {
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;
	$w_verb = "IOSP";
	#------------------------------------------------------------------
	# 引継ぎ元のデータ取得
	#------------------------------------------------------------------
	$w_sql = "";
	$w_sql .= " SELECT * FROM LOT_INF_TBL";
	$w_sql .= " WHERE";
	$w_sql .= " LOT_ID = '{$w_lot_id}'";
	$w_sql .= " AND DEL_FLG = '0'";

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

	$w_ctg_ng = array( constant( "CT_PACKING_VI" ));

	#------------------------------------------------------------------
	# 新規ロットへ引継ぎ
	#------------------------------------------------------------------
	for ($i = 1; $i <= $cnt; $i++) {
		# NG CT Code will not be inherited
		if ( in_array( $w_dat[$i]['CTG_CD'], $w_ctg_ng )) {
			continue;
		}
		
		$w_ins = array(
				"DEL_FLG" => "0",
				"LOT_ID" => $w_new_lot_id,
				"CTG_DVS_CD" => $w_dat[$i]['CTG_DVS_CD'],
				"CTG_CD" => $w_dat[$i]['CTG_CD'],
				"SL_ID" => $w_dat[$i]['SL_ID'],
				"CTG_DAT_TXT" => $w_dat[$i]['CTG_DAT_TXT'],
				"CTG_DAT_VAL" => $w_dat[$i]['CTG_DAT_VAL'],
				"CRT_VERB" => $w_verb,
				"CRT_DTS" => $g_cpu_dts,
				"USR_ID_CRT" => $w_usr_id,
				"UPD_VERB" => " ",
				"UPD_DTS" => $g_low_dts,
				"USR_ID_UPD" => " ",
				"UPD_LEV" => "1"
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
# LOT_INF_TBL上の拡散ロット番号を取得
#==================================================================
function get_dif_lot_no($w_lot_id, &$r_lot_no)
{
	global $g_msg;
	global $g_err_lv;

	$w_ctgdvs = constant("CE_SLINF");
	$w_ctgcd  = constant("CT_DATECD");

	$w_sql = <<<_SQL
SELECT
	CTG_DAT_TXT
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

	$w_row = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	$r_lot_no = trim($w_row['CTG_DAT_TXT']);

	return 0;
}


#==================================================================
# Get the Cairn Status
#==================================================================
function get_cairn_flg($w_dvsn_cd, $w_rdg_cd, &$r_flg)
{
        global $g_msg;
        global $g_err_lv;

        $r_fdg 		= "";
	$w_dvsn_cd 	= trim($w_dvsn_cd);
	$w_rdg_cd 	= trim($w_rdg_cd);

        $w_sql = <<<_SQL
SELECT
        FLG
FROM
        PM_ACC_CHK_TBL
WHERE
	DVSN_CD = '$w_dvsn_cd' AND
	RDG_CD = '$w_rdg_cd'
_SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "PM_ACC_CHK_TBL", __LINE__);
                return 4000;
        }

        $w_row = db_fetch_row($w_stmt);
        db_res_free($w_stmt);

        $r_flg = trim($w_row['FLG']);

        return 0;
}

#==================================================================
# LOG_BIND_INF検索
#==================================================================
function chk_packed($w_lot_id, &$r_pck_prd_cd)
{
	global $g_msg;
	global $g_err_lv;

	$r_pck_prd_cd = "";

	$w_ctgdvs  = constant("CE_SLINF");
	$w_ctgcd   = constant("CT_DATECD");
	$w_binddvs = constant("DVS_LAMINATE");

	$w_sql = <<<_SQL
SELECT
	LBT2.PRD_CD
FROM
	LOT_BAS_TBL LBT,
	LOT_INF_TBL LIT,
	LOG_BIND_INF BND,
	LOT_BAS_TBL LBT2
WHERE
	LBT.LOT_ID = '{$w_lot_id}'
	AND LIT.LOT_ID = LBT.LOT_ID
	AND LIT.CTG_DVS_CD = '{$w_ctgdvs}'
	AND LIT.CTG_CD = '{$w_ctgcd}'
	AND LIT.DEL_FLG = '0'
	AND BND.BIND_ID = LIT.CTG_DAT_TXT
	AND BND.BIND_DVS = '{$w_binddvs}'
	AND BND.BIND_TXT_1 = LBT.LOT_ID
	AND BND.DEL_FLG = '0'
	AND LBT2.LOT_ID = BND.BIND_TXT_2
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
		$r_pck_prd_cd = trim($w_row['PRD_CD']);
	}

	return 0;
}

#==================================================================
# [VERB] IOIN(着手)
#==================================================================
function main_verb_ioin($w_usr_id, $w_equ_cd, &$w_lot_bas) {
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# ロット状態のチェック
	#------------------------------------------------------------------
	$w_rtn = ioin_st_check($w_lot_bas);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}

	#------------------------------------------------------------------
	# 装置構成マスタのチェック
	#------------------------------------------------------------------
	$w_rtn = ioin_equ_check($w_equ_cd, $w_lot_bas);
	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}

	#------------------------------------------------------------------
	# ＩＯＩＮ
	# 戻り値：$w_lot_bas
	#------------------------------------------------------------------
	$w_rtn = ioin($w_lot_bas['LOT_ID'], # ロットＩＤ
			$w_usr_id, # ユーザＩＤ
			$w_lot_bas['UPD_LEV'], # 更新レベル
			$w_equ_cd, # 装置コード
#			"", # コメント
			trim(strtoupper($gw_scr['s_pck_rmks'])),
			$w_lot_bas); # 戻り値：ロット基本情報

	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# SPACEチェック
#==================================================================
function chk_space($w_usr_id,
								$w_lot_id,
								$w_simm,
								$w_prd_cd,
								$w_stp_cd,
								$w_equ_cd,
								$w_chp_qty,
								$w_sub_qty,
								$w_prg_id,
								&$r_spc_err_cd, &$r_spc_err_nm, &$r_spc_err_msg)
{
		global $gw_scr;
		global $g_msg;
		global $g_err_lv;
		global $g_cpu_dts;

		#------------------------------------------------------------------
		# 材料画面で入力した情報取得
		#------------------------------------------------------------------
		$w_mcnt			= get_session_value('s_mat_cnt', constant("PGM_MTMNG"));
		$w_list_prt_cd	 = get_session_value('s_list_prt_cd', constant("PGM_MTMNG"));
		$w_list_prt_grp_cd = get_session_value('s_list_prt_grp_cd', constant("PGM_MTMNG"));
		$w_list_prt_alt	= get_session_value('s_list_glb_prt_cd', constant("PGM_MTMNG"));
		$w_list_mt_lot_id  = get_session_value('s_list_mt_lot_id', constant("PGM_MTMNG"));
		$w_list_unt_cd	 = get_session_value('s_list_unt_cd', constant("PGM_MTMNG"));

		### 材料の入力が無ければエラー
		$w_mat_inp_flg = 0;
		for($i=1; $i<=$w_mcnt; $i++){
				if($w_list_mt_lot_id[$i] != ""){
						$w_mat_inp_flg = 1;
						break;
				}
		}
#		if($gw_scr['s_prt_ctrl'] == "1"
 #	   && ($w_mcnt == "" || $w_mat_inp_flg == 0)
  #	  ){
   #			 list($g_msg, $g_err_lv) = msg("err_Inp_MatMng");
	#			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	 #		   return 4000;
	  #  }

		#------------------------------------------------------------------
		# PRT_QTY 取得用ＳＱＬ
		#------------------------------------------------------------------
		$w_tmpsql = <<<_SQL
SELECT
	'1' AS DVS,
	POM.PRT_QTY,
	INV.PRT_QTY AS PRT_RAT
FROM
	PRT_ORG_MST POM,
	PRT_INV_MST INV
WHERE
	POM.PRD_CD = '{$w_prd_cd}'
	AND POM.STP_CD = '{$w_stp_cd}'
	AND POM.PRT_EXEC_DVS = 'STEQ'
	AND POM.EQU_CD = '{$w_equ_cd}'
	AND POM.PRT_GRP_CD = '%1\$s'
	AND POM.PRT_CD = '%2\%s'
	AND POM.TRC_EXC_FLG = '0'
	AND POM.DEL_FLG = '0'
	AND INV.PRD_CD = POM.PRD_CD
	AND INV.STP_CD = POM.STP_CD
	AND INV.PRT_EXEC_DVS = POM.PRT_EXEC_DVS
	AND INV.EQU_CD = POM.EQU_CD
	AND INV.PRT_GRP_CD = POM.PRT_GRP_CD
	AND INV.PRT_DVS = 'RT'
	AND INV.PRT_CD = POM.PRT_CD
	AND INV.DEL_FLG = '0'

UNION ALL

SELECT
	'2' AS DVS,
	POM.PRT_QTY,
	INV.PRT_QTY AS PRT_RAT
FROM
	PRT_ORG_MST POM,
	PRT_INV_MST INV
WHERE
	POM.PRD_CD = '{$w_prd_cd}'
	AND POM.STP_CD = '{$w_stp_cd}'
	AND POM.PRT_EXEC_DVS = 'ST'
	AND POM.EQU_CD = ' '
	AND POM.PRT_GRP_CD = '%1\$s'
	AND POM.PRT_CD = '%2\%s'
	AND POM.TRC_EXC_FLG = '0'
	AND POM.DEL_FLG = '0'
	AND INV.PRD_CD = POM.PRD_CD
	AND INV.STP_CD = POM.STP_CD
	AND INV.PRT_EXEC_DVS = POM.PRT_EXEC_DVS
	AND INV.EQU_CD = POM.EQU_CD
	AND INV.PRT_GRP_CD = POM.PRT_GRP_CD
	AND INV.PRT_DVS = 'RT'
	AND INV.PRT_CD = POM.PRT_CD
	AND INV.DEL_FLG = '0'

UNION ALL

SELECT
		'3' AS DVS,
		POM.PRT_QTY,
		BAS.PRT_RAT
FROM
		PRT_ORG_MST POM,
		PRT_BAS_MST BAS
WHERE
		POM.PRD_CD = '{$w_prd_cd}'
		AND POM.STP_CD = '{$w_stp_cd}'
		AND POM.PRT_EXEC_DVS = 'STEQ'
		AND POM.EQU_CD = '{$w_equ_cd}'
		AND POM.PRT_GRP_CD = '%1\$s'
		AND POM.PRT_CD = '%2\$s'
		AND POM.TRC_EXC_FLG = '0'
		AND POM.DEL_FLG = '0'
		AND BAS.PRT_CD = POM.PRT_CD
		AND BAS.DEL_FLG = '0'

UNION ALL

SELECT
		'4' AS DVS,
		POM.PRT_QTY,
		BAS.PRT_RAT
FROM
		PRT_ORG_MST POM,
		PRT_BAS_MST BAS
WHERE
		POM.PRD_CD = '{$w_prd_cd}'
		AND POM.STP_CD = '{$w_stp_cd}'
		AND POM.PRT_EXEC_DVS = 'ST'
		AND POM.EQU_CD = ' '
		AND POM.PRT_GRP_CD = '%1\$s'
		AND POM.PRT_CD = '%2\$s'
		AND POM.TRC_EXC_FLG = '0'
		AND POM.DEL_FLG = '0'
		AND BAS.PRT_CD = POM.PRT_CD
		AND BAS.DEL_FLG = '0'

ORDER BY
		DVS
_SQL;

		$w_arr_mat = array();
		for($i=1; $i<=$w_mcnt; $i++){
				#------------------------------------------------------------------
				# 論理消費数量を自動計算
				#------------------------------------------------------------------
				$w_sql = sprintf($w_tmpsql, $w_list_prt_grp_cd[$i], $w_list_prt_cd[$i]);
				$w_stmt = db_res_set($w_sql);
				$w_rtn = db_do($w_stmt);
				if($w_rtn != 0){
						list($g_msg, $g_err_lv) = msg("err_Sel");
						$g_msg = xpt_err_msg($g_msg, "", __LINE__);
						return 4000;
				}
				# はじめの１件を取得
				$w_row = db_fetch_row($w_stmt);
				db_res_free($w_stmt);

				$w_prt_qty = $w_row['PRT_QTY'];
				$w_prt_rat = $w_row['PRT_RAT'];

				### 論理消費数量
				$w_use_qty = $w_chp_qty * ($w_prt_qty / 1000) / ($w_prt_rat / 100);

				#------------------------------------------------------------------
				# 単位コードにより四捨五入
				# すべて第七位四捨五入に統一
				#------------------------------------------------------------------
#			   if($w_list_unt_cd[$i] == "UNSEM001"){
#					   $w_use_qty = round($w_use_qty);
#			   } else {
						$w_use_qty = ceil_7($w_use_qty);
#			   }

				$w_tmp = array
				(
						$w_list_prt_alt[$i]	 => $w_list_mt_lot_id[$i] . "_" . $w_use_qty
				);

				$w_arr_mat = $w_arr_mat + $w_tmp;
		}

		#------------------------------------------------------------------
		# SPACEチェック
		#------------------------------------------------------------------
		$w_rtn = cs_xck_trk_snd_lot($w_lot_id,
																$w_simm,
																$w_equ_cd,
																$w_prg_id,
																$w_usr_id,
																$g_cpu_dts,
																$w_chp_qty,
																$w_sub_qty,
																$w_arr_mat,
																$r_spc_err_cd,
																$r_spc_err_nm,
																$r_spc_err_msg);
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
function main_verb_ioot($w_usr_id, $w_ctg_dvs_cd, $w_ctg_cd, $w_ctg_qty,
		$w_ctg_dat_txt, $w_ctg_slid, $w_sl_qty_ok, $w_chp_qty_ok, $w_lf_qty_ok,
		$w_cmt, &$w_lot_bas) {
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# 最終ＩＯブロック確認 xck_lio
	# 戻り値：	$w_lot_st_dvs	ロット状態区分
	#			$w_io_blc_cs	ＩＯブロックコード
	#			$w_stp_cd		ステップコード
	#			$w_stp_no		ステップ番号
	#------------------------------------------------------------------
	$w_rtn = xck_lio($w_lot_bas['PRC_CD'], $w_lot_bas['IO_BLC_CD'],
			$w_lot_bas['PLT_DVS_CD'], $w_lot_st_dvs, # 戻り値：ロット状態区分
			$w_io_blc_cd, # 戻り値：ＩＯブロックコード
			$w_stp_cd, # 戻り値：ステップコード
			$w_stp_no); # 戻り値：ステップ番号

	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, '', __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# カテゴリ設定
	#------------------------------------------------------------------
	$w_ctg_flg = 0;
	if (is_array($w_ctg_cd)) {
		for ($i = 1; $i <= count($w_ctg_cd); $i++) {
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

	if ($w_ctg_flg == 0) {
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
	$w_rtn = ioot($w_lot_bas['LOT_ID'], # ロットＩＤ
			$w_usr_id, # ユーザＩＤ
			$w_lot_bas['UPD_LEV'], # 更新レベル
			$w_cmt, # コメント
			$w_lot_st_dvs, # ロット状態区分
			$w_sl_qty_ok, # 良品SL_QTY
			$w_chp_qty_ok, # 良品チップ数
			$w_lf_qty_ok, # 良品スライス数
			$w_lot_bas['SECRET_NO'], # 密番
			$w_arr_cnt, # カテゴリ数
			$w_arr_ctg_dvs_cd, # カテゴリ区分コード
			$w_arr_ctg_cd, # カテゴリコード
			$w_arr_equ_cd, # カテゴリ装置コード
			$w_arr_sl_id, # カテゴリスライスＩＤ
			$w_arr_qty, # カテゴリ数量
			$w_arr_txt, # カテゴリ収集データ
			$w_lot_bas); # 戻り値：ロット基本情報

	if ($w_rtn != 0) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, '', __LINE__);
		return 4000;
	}

	return 0;
}
#==================================================================
# 次工程渡し ＩＯＭＶ／ＰＲＰＴ-ＰＲＰＣ-ＰＲＧＴ
#==================================================================
function main_verb_iomv($w_usr_id, $w_cmt, &$w_lot_bas) {
	global $g_msg;
	global $g_err_lv;

	# ロット状態区分による振り分け
	switch ($w_lot_bas['LOT_ST_DVS']) {
	case "OW":
	#------------------------------------------------------------------
	# 次ＩＯブロックの獲得 xgt_nio
	#------------------------------------------------------------------
		$w_rtn = xgt_nio($w_lot_bas['PRC_CD'], $w_lot_bas['IO_BLC_CD'],
				$w_lot_bas['STP_NO'], $w_lot_bas['PLT_DVS_CD'], $w_io_blc_cd,
				# 戻り値：次ＩＯブロックコード
				$w_stp_cd, # 戻り値：次ステップコード
				$w_stp_no); # 戻り値：次ステップ番号

		$w_nxt_verb = "IOMV";

		break;
	case "EW":
	#------------------------------------------------------------------
	# 次プロセスの獲得 xgt_npr
	#------------------------------------------------------------------
		$w_rtn = xgt_npr($w_lot_bas['RT_CD'], $w_lot_bas['PRC_CD'],
				$w_lot_bas['PLT_DVS_CD'], $w_prc_cd, # 戻り値：次プロセスコード
				$w_io_blc_cd, # 戻り値：次ＩＯブロックコード
				$w_stp_cd, # 戻り値：次ステップコード
				$w_stp_no); # 戻り値：次ステップ番号

		if ($w_rtn == 0) {
			$w_nxt_verb = "PRPT";
		} else {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return 4000;
		}

		break;
	}

	switch ($w_nxt_verb) {
	case "IOMV":
	#------------------------------------------------------------------
	# ＩＯＭＶ
	# 戻り値：$w_lot_bas
	#------------------------------------------------------------------
		$w_rtn = iomv($w_lot_bas['LOT_ID'], $w_usr_id, $w_lot_bas['UPD_LEV'],
				$w_cmt, $w_io_blc_cd, $w_stp_cd, $w_stp_no, $w_lot_bas);

		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return $w_rtn;
		}

		break;
	case "PRPT":
	#------------------------------------------------------------------
	# ＰＲＰＴ
	# 戻り値：$w_lot_bas
	#------------------------------------------------------------------
		$w_rtn = prpt($w_lot_bas['LOT_ID'], $w_usr_id, $w_lot_bas['UPD_LEV'],
				$w_cmt, $w_lot_bas);

		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return $w_rtn;
		}

		#------------------------------------------------------------------
		# ＰＲＰＣ
		# 戻り値：$w_lot_bas
		#------------------------------------------------------------------
		$w_rtn = prpc($w_lot_bas['LOT_ID'], $w_usr_id, $w_lot_bas['UPD_LEV'],
				$w_cmt, $w_prc_cd, $w_io_blc_cd, $w_stp_cd, $w_stp_no,
				$w_lot_bas);

		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return $w_rtn;
		}

		#------------------------------------------------------------------
		# ＰＲＧＴ
		# 戻り値：$w_lot_bas
		#------------------------------------------------------------------
		$w_rtn = prgt($w_lot_bas['LOT_ID'], $w_usr_id, $w_lot_bas['UPD_LEV'],
				$w_cmt, $w_lot_bas);

		if ($w_rtn != 0) {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return $w_rtn;
		}

		break;

	} # end switch

	return 0;
}
#======================================================================
# LOG_BIND登録
#======================================================================
function ins_log_bind($w_usr_id, $w_ex_lot_bas, $w_lot_bas,
						$w_bind_lot_no_str, $w_sp_lot_no_str, $w_ex_lot_no_str)
{
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;
	global $g_PrgCD;

	# LOG_BIND_INF登録
	$w_ins = array
	(
		"DEL_FLG"		=> "0",
		"BIND_ID"		=> $w_bind_lot_no_str,
		"BIND_DVS"		=> constant("DVS_LAMINATE"),
		"BIND_TXT_1"	=> trim($w_ex_lot_bas['LOT_ID']),
		"BIND_TXT_G_1"	=> $w_ex_lot_no_str,
		"BIND_TXT_2"	=> trim($w_lot_bas['LOT_ID']),
		"BIND_TXT_G_2"	=> $w_sp_lot_no_str,
		"BIND_TXT_3"	=> " ",
		"BIND_TXT_G_3"	=> " ",
		"FORM_ID"		=> $g_PrgCD,
		"BIND_QTY_1"	=> $w_ex_lot_bas['CHP_QTY'],
		"BIND_QTY_2"	=> $w_lot_bas['CHP_QTY'],
		"BIND_QTY_3"	=> 0,
		"CRT_DTS"		=> $g_cpu_dts,
		"USR_ID_CRT"	=> $w_usr_id,
		"UPD_DTS"		=> $g_low_dts,
		"USR_ID_UPD"	=> " ",
		"UPD_LEV"		=> 1
	);
	$w_rtn = db_insert("LOG_BIND_INF", $w_ins);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Ins");
		$g_msg = xpt_err_msg($g_msg, "LOG_BIND_INF", __LINE__);
		return 4000;
	}

	# LOG_BIND_TBL登録
	$w_ins = array
	(
		"DEL_FLG"		=> "0",
		"BIND_ID"		=> $w_bind_lot_no_str,
		"LINE_ID"		=> "",
		"LOT_ID"		=> "",
		"VERB"			=> "IOOT",
		"STP_CD"		=> $w_lot_bas['STP_CD'],
		"LOG_CRT_DTS"	=> $g_cpu_dts,
		"CRT_DTS"		=> $g_cpu_dts,
		"USR_ID_CRT"	=> $w_usr_id,
		"UPD_DTS"		=> $g_low_dts,
		"USR_ID_UPD"	=> " ",
		"UPD_LEV"		=> 1,
	);

	### 元ロット
	$w_ins['LINE_ID'] = 1;
	$w_ins['LOT_ID']  = $w_ex_lot_bas['LOT_ID'];
	$w_rtn = db_insert("LOG_BIND_TBL", $w_ins);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "LOG_BIND_TBL", __LINE__);
		return 4000;
	}

	### 分割新規ロット
	$w_ins['LINE_ID'] = 2;
	$w_ins['LOT_ID']  = $w_lot_bas['LOT_ID'];
	$w_rtn = db_insert("LOG_BIND_TBL", $w_ins);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "LOG_BIND_TBL", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# Check Route for E9
#==================================================================
function chk_rt_e9($w_rtcd, $w_e9, &$r_flg)
{
	global $g_msg;
	global $g_err_lv;

	$r_prccd = "";
	$r_flg   = 0;


	$w_sql = <<<SQL
SELECT
	COUNT(*) as COUNTA
FROM
	PRD_ORG_MST POM
		INNER JOIN STP_MST SM
		ON POM.STP_CD = SM.STP_CD
		AND POM.DEL_FLG = '0'
		AND SM.DEL_FLG = '0'
WHERE
	POM.DEL_FLG = '0'
	AND POM.RT_CD = '{$w_rtcd}'
	AND POM.IO_FLG = '1'
	AND SM.STP_CLS_2 = '{$w_e9}'
	AND SM.DEL_FLG = '0'
	
SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
		return 4000;
	}
	if($w_row = db_fetch_row($w_stmt)){
		$r_flg_buff = $w_row['COUNTA'];
		
		if($r_flg_buff > 1 ){
			$r_flg   = 1; 
		}
	}
	db_res_free($w_stmt);

	return 0;
}

#==================================================================
# 新規振出可能かチェック
#==================================================================
function chk_strrtcd($w_rtcd, $w_seqnort, &$r_prccd, &$r_flg)
{
	global $g_msg;
	global $g_err_lv;

	$r_prccd = "";
	$r_flg   = 1;
	$w_aucd  = constant("AU_STR_PT_OK");

	$w_whr_seqnort = "";
	if($w_seqnort != ""){
		$w_whr_seqnort = "AND POM.SEQ_NO_RT >= '$w_seqnort'";
	}

	$w_sql = <<<SQL
SELECT
	POM.PRC_CD
FROM
	PRD_ORG_MST POM,
	PRC_MST PRC
WHERE
	POM.DEL_FLG = '0'
	AND POM.RT_CD = '{$w_rtcd}'
	AND POM.IO_FLG = '1'
	{$w_whr_seqnort}
	AND PRC.PRC_CD = POM.PRC_CD
	AND PRC.DEL_FLG = '0'
	AND PRC.ST_DVS_CD <> '{$w_aucd}'
SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
		return 4000;
	}
	if($w_row = db_fetch_row($w_stmt)){
		$r_prccd = trim($w_row['PRC_CD']);
		$r_flg   = 0; # 振出不可
	}
	db_res_free($w_stmt);

	return 0;
}

#======================================================================
# 処理開始
#======================================================================
$w_rtn = xdb_op_conndb();
if ($w_rtn != 0) {
	$g_err_lv = 0;
	$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	return;
}
#==================================================================
# セッション
#==================================================================
if ($gw_scr['s_rtn_flg']) {
	get_session_convert();
}
# セッション内のモードを取得
get_session_mode();
#---------------------------------------------------------
# 認証(要Scr記述 タイムアウト、他画面からの呼び出しに対応)
$refe_flg = 1;
require_once(getenv("GPRISM_HOME") . "/renzheng.php");
$bak_s_renzheng_t = $gw_scr['s_renzheng_t']; # 一時退避
$bak_s_renzheng = $gw_scr['s_renzheng']; # 一時退避
#---------------------------------------------------------
switch ($g_mode) {
case 1:
	main_md1();
	break;
case 2:
	main_md2();
	break;
case 3:
	main_md3();
	break;
case 4:
	main_md4();
	break;
case 5: 
	main_md5();
	break;	
default:
	main_init();
	break;
}

#======================================================================
# 画面表示設定
#======================================================================
scr_setting();

$gw_scr['s_renzheng'] = $bak_s_renzheng; # 認証用
$gw_scr['s_renzheng_t'] = $bak_s_renzheng_t; # 認証用
$gw_scr['s_opt_pack_pcs'] = unserialize(constant("PACK_PCS"));
get_screen(1, null, 1);

#======================================================================
# DB接続解除
#======================================================================
xdb_op_closedb();

?>
