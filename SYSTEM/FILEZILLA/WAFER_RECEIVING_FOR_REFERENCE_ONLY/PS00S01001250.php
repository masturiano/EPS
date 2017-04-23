<?php
# ======================================================================================
# [DATE]  : 2014.02.10          		[AUTHOR]  : MIS) Paul
# [SYS_ID]: GPRISM						[SYSTEM]  : Gprism CIM
# [SUB_ID]:								[SUBSYS]  : 
# [PRC_ID]:								[PROCESS] : 
# [PGM_ID]: PS00S01001250.php			[PROGRAM] : Wafer Receive(LSI)
# [MDL_ID]:					[MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
#
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]	[UPDATE]		[COMMENT]
# ====================	==================	============================================
# MIS)L.Acera		2012-05-28		Copy from PS00S01000160
# --------------------------------------------------------------------------------------
#******************************************************************
#
# Program Version
#
#******************************************************************
$g_Version = "2.0";
$g_PrgCD = "PS00S01001250";
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
# ��������
#------------------------------------------------------------------
require_once($g_func_dir . "/cs_xexc_hold_rsv.php");				# ͽ��ۡ���ɸ������¹Դؿ�
#------------------------------------------------------------------
# �����꡼��
#------------------------------------------------------------------
require_once ($g_lang_dir . "/buttonM.php");					# �ܥ���̾��
require_once ($g_lang_dir . "/PS00S01001250M.php");				# ��å�����
require_once ($g_Gfunc_dir . "/xpt_screen.php");				# �ץ����ե졼��ƤӽФ�
require_once ($g_func_dir . "/cs_xgt_po_data.php");
require_once ($g_func_dir . "/xgt_to220_cd_cnt.php");
require_once ($g_func_dir . "/cs_xgt_secno.php");
require_once ($g_func_dir . "/xgt_cd_cnt.php");
#******************************************************************
#
# ������
#
#******************************************************************
#------------------------------------------------------------------
# ɽ����
#------------------------------------------------------------------
#------------------------------------------------------------------
# �����
#------------------------------------------------------------------
define("INI_CCCD",					"CCSEM01");		# ��åȶ�ʬ������
define("INI_CDCD",					"CDSEM01");		# ��åȼ��̥�����
#------------------------------------------------------------------
# ���ƥ����ʬ
#------------------------------------------------------------------
define("CE_LTINF",					"CE00S02");		# Lot Information
#------------------------------------------------------------------
# ���ƥ��ꥳ����
#------------------------------------------------------------------
define("CT_SLCINF",                                     "CT00S0000098");        # Slice information
define("CT_EXPDTE",                                     "CT00S0000099");        # Expired Date
define("CT_MFGDTE",                                     "CT00S0000100");        # Manufacturing Date
#------------------------------------------------------------------
# �������
#------------------------------------------------------------------
define("TG_MA",					"MA");				# User Tag
define("TG_CC",					"CC");				# ��åȶ�ʬ������
define("TG_CD",					"CD");				# ��åȼ��̥�����
define("TG_LP",					"LP");				# ��å�ɼ������
#------------------------------------------------------------------		
# ����¾���
#------------------------------------------------------------------
define("GR_IT_AIMS",			"GRSEM01");				# �������
define("DG_QA_MEMBER",			"DG00S020");				# QA Member
define("D6_BGA_INF",			"D6SEM003");				# Please change to actual BGA D6
define("AM_PARTNER",                    "AM00S0001");
define("AU_STR_PT_OK",                  "AUSEM01");
define("PGM_LBL",			"PS00S03000090");
define("DT_BLANK",			"0001-01-01 00:00:00");
#------------------------------------------------------------------
# ����
#------------------------------------------------------------------
define("E9_WAFER_RCV_BGA",		"E911S020");				# WAFER_RECEIVE_BGA (E911S020)
# ���Ĺ���
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
# ����ǡ��������Ѵ�
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
#==================================================================
# �������
#==================================================================
function main_init()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	# ɽ�����ܽ��������
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

	$gw_scr['s_lot_typ_cd'] = constant("INI_CCCD");
	$gw_scr['s_lot_dsc_cd'] = constant("INI_CDCD");

	#------------------------------------------------------------------
	# ��åȶ�ʬ̾�μ���
	#------------------------------------------------------------------
	$w_rtn = xgn_cd($gw_scr['s_lot_typ_cd'], 1, $w_lot_typ_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_typ_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ��åȼ���̾�μ���
	#------------------------------------------------------------------
	$w_rtn = xgn_cd($gw_scr['s_lot_dsc_cd'], 1, $w_lot_dsc_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_dsc_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ü���Ρ��ɤ���ץ�󥿾���γ���
	#------------------------------------------------------------------
	# ��å�ɼ������
	$w_rtn = xgt_lp2(2, $w_lp_cd, $w_lp_nm, $w_lp_id, $w_lp_type);
	if($w_rtn != 0){
		$g_err_lv = 3;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	}
	$gw_scr['s_lot_typ_nm'] = trim($w_lot_typ_nm);
	$gw_scr['s_lot_dsc_nm'] = trim($w_lot_dsc_nm);
	$gw_scr['s_lp_cd']      = trim($w_lp_cd);
	$gw_scr['s_lp_nm']      = trim($w_lp_nm);

	# �⡼�ɣ��ˤ���
	scr_mode_chg(1);

	return 0;
}

###################################################################
#==================================================================
# �⡼�ɣ�
#==================================================================
function main_md1()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){
	case "ERASE":
		main_init();
		break;
        case "REF":
                main_md1_chk();
                break;
	}

	return 0;
}
#==================================================================
# �⡼�ɣ�
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
        case "ERASE":
		set_init(3);
                scr_mode_chg(2);
                break;
	case "BACK":
		set_init(2);
                scr_mode_chg(1);
		break;

        }

        return 0;
}

#==================================================================
# �⡼�ɣ� ��ǧ�ܥ��󲡲�����
#==================================================================
function main_md2_chk()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# ���ϥ����å�
	#------------------------------------------------------------------
	$w_rtn = check_input(1);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# �桼��̾�μ���
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
	# �ץ�����ȥ����ɼ���
	#------------------------------------------------------------------
	### ���å���̾
	$w_rtn = xgc_prd($w_sap_data['ITEM_CD'], $w_prd_cd, $w_bnd_dvs);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_prd_nm'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ��󥯥ѥ���������å�
	#------------------------------------------------------------------
	$w_rtn = xck_rnk($gw_scr['s_rnk'], $w_prd_cd, constant("GR_IT_AIMS"));
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_rnk'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ��åȶ�ʬ̾����
	#------------------------------------------------------------------
	$w_rtn = xgn_cd($gw_scr['s_lot_typ_cd'], 1, $w_lot_typ_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_typ_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ��åȼ���̾����
	#------------------------------------------------------------------
	$w_rtn = xgn_cd($gw_scr['s_lot_dsc_cd'], 1, $w_lot_dsc_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_dsc_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ��å�ɼ���������
	#------------------------------------------------------------------
	$w_rtn = xgt_lp2_cd($gw_scr['s_lp_cd'], $w_dmy, $w_dmy, $w_lp_nm);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lp_cd'], __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# �ץ�����Ⱦ���μ���
	#------------------------------------------------------------------
	$w_rtn = get_prdinf($w_prd_cd, "", $w_prd_inf);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# ��åȶ�ʬ�ѹ��Բĥ����å�
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
	# QA���С��������å�
	#------------------------------------------------------------------
	$w_rtn = chk_qa_member($gw_scr['s_usr_id'], $w_qa_flg);
	if($w_rtn != 0){
		return 4000;
	}
	### QA���С��ʳ��ξ��
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
	# ��åȶ�ʬ/���̥����å�
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
	# �����ե饰
	#------------------------------------------------------------------
	$w_mng_flg = 1;
	if($gw_scr['s_lot_typ_cd'] == constant("INI_CCCD") &&
	   $gw_scr['s_lot_dsc_cd'] == constant("INI_CDCD")){
		$w_mng_flg = 0;
	}

	#------------------------------------------------------------------
	# ��ϩ����μ���
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
	# ���Ĺ��������å�
	#------------------------------------------------------------------
	# ���ƥå�ʬ�����
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
	# Scr�ѿ���Ÿ��
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
# �⡼�ɣ� ��ǧ�ܥ��󲡲�����
#==================================================================
function main_md1_chk()
{
        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        #------------------------------------------------------------------
        # ���ϥ����å�
        #------------------------------------------------------------------
        $w_rtn = check_input(3);
        if($w_rtn != 0){
                return 4000;
        }

        #------------------------------------------------------------------
        # ü���Ρ��ɤ���ץ�󥿾���γ���
        #------------------------------------------------------------------
        # ��å�ɼ������
	$w_rtn = xgt_lp2(2, $w_lp_cd, $w_lp_nm, $w_lp_id, $w_lp_type);
	if($w_rtn != 0){
       		$g_err_lv = 0;
       		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	}
        $gw_scr['s_lp_cd']      = trim($w_lp_cd);
        $gw_scr['s_lp_nm']      = trim($w_lp_nm);


        #------------------------------------------------------------------
        # �桼��̾�μ���
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
        # �ץ�����ȥ����ɼ���
        #------------------------------------------------------------------
        ### ���å���̾
        $w_rtn = xgc_prd($w_sap_data['ITEM_CD'],$w_prd_cd, $w_bnd_dvs);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_prd_nm'], __LINE__);
                return 4000;
        }
        ### ��Ω������̾
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
        # ��󥯥ѥ���������å�
        #------------------------------------------------------------------
        $w_rtn = xck_rnk($gw_scr['s_rnk'], $w_prd_cd, constant("GR_IT_AIMS"));
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_rnk'], __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # ��åȶ�ʬ̾����
        #------------------------------------------------------------------
        $w_rtn = xgn_cd($gw_scr['s_lot_typ_cd'], 1, $w_lot_typ_nm);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_typ_cd'], __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # ��åȼ���̾����
        #------------------------------------------------------------------
        $w_rtn = xgn_cd($gw_scr['s_lot_dsc_cd'], 1, $w_lot_dsc_nm);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_dsc_cd'], __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # �ץ�����Ⱦ���μ���
        #------------------------------------------------------------------
        $w_rtn = get_prdinf($w_prd_cd, "", $w_prd_inf);
        if($w_rtn != 0){
                return 4000;
        }

        #------------------------------------------------------------------
        # ��åȶ�ʬ�ѹ��Բĥ����å�
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
        # QA���С��������å�
        #------------------------------------------------------------------
        $w_rtn = chk_qa_member($gw_scr['s_usr_id'], $w_qa_flg);
        if($w_rtn != 0){
                return 4000;
        }
        ### QA���С��ʳ��ξ��
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
        # ��åȶ�ʬ/���̥����å�
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
        # �����ե饰
        #------------------------------------------------------------------
        $w_mng_flg = 1;
        if($gw_scr['s_lot_typ_cd'] == constant("INI_CCCD") &&
           $gw_scr['s_lot_dsc_cd'] == constant("INI_CDCD")){
                $w_mng_flg = 0;
        }
        #------------------------------------------------------------------
        # ��ϩ����μ���
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
        # ���Ĺ��������å�
        #------------------------------------------------------------------
        # ���ƥå�ʬ�����
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
        # Scr�ѿ���Ÿ��
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
##### �⡼�ɣ�                                                #####
#####                                                         #####
###################################################################
#==================================================================
# �⡼�ɣ�
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
# �⡼�ɣ� �¹ԥܥ��󲡲�����
#==================================================================
function main_md3_exe()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# �ȥ�󥶥�����󳫻�
	#------------------------------------------------------------------
	db_begin();

	#------------------------------------------------------------------
	# �¹Խ���
	#------------------------------------------------------------------
	$w_rtn = main_exe();
	if($w_rtn != 0){
		db_rollback();
		return 4000;
	}

	#------------------------------------------------------------------
	# ���ߥå�
	#------------------------------------------------------------------
	db_commit();

	if($gw_scr['s_iohd_flg'] != "1"){
		#------------------------------------------------------------------
		# ��å�ɼ����
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
# �¹Խ���
#==================================================================
function main_exe()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;
	global $g_low_dts;

	#------------------------------------------------------------------
	# �����
	#------------------------------------------------------------------
	$gw_scr['s_iohd_flg']   = "";
	$gw_scr['s_new_lot_id'] = "";

	#------------------------------------------------------------------
	# LOT_NUM_TBL ��¾ɽ��å�
	#------------------------------------------------------------------
	$w_rtn = db_lock("LOT_NUM_TBL");
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ����
	#------------------------------------------------------------------
	$w_rnk_ptn = $gw_scr['s_rnk'];
	if ($w_rnk_ptn == "") {
		$w_rnk_ptn = ' ';
	}

	#------------------------------------------------------------------
	# PDCR�ѤΥǡ�������
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
	# ��å�����(�Уģã�)
	#------------------------------------------------------------------
	$w_rtn = pdcr($w_bas, $w_new_lot_id, $w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ¾�������(�ɣϣң�)
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
	# ��åȾ���ơ��֥����Ͽ
	#------------------------------------------------------------------
	### ��󥰿���(���)
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

        ### ��󥰿���(���)
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

        ### ��󥰿���(���)
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
	# ����������μ���
	#------------------------------------------------------------------
	$w_rtn = get_next_process(trim($w_lot_bas['RT_CD']),
							  trim($w_lot_bas['PRD_CD']),
							  trim($w_lot_bas['STP_CD']),
							  $w_next_dat);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# ���
	#------------------------------------------------------------------
	$w_rtn = main_ioin_verb($gw_scr['s_usr_id'], "", "", $w_lot_bas);
	if($w_rtn != 0){
		return 4000;
	}

	#------------------------------------------------------------------
	# ��λ
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
	# �������Ϥ�
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
	# ������������̾�ȼ����ƥåפ���̾���㤦���
	#------------------------------------------------------------------
	if(trim($w_lot_bas['PRD_CD']) != $w_next_dat['PRD_CD']){
		#------------------------------------------------------------------
		# �ʼ��ѹ�
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
	# ͽ��ۡ���ɸ���/�¹�
	#------------------------------------------------------------------
	$w_rtn = cs_xexc_hold_rsv($gw_scr['s_usr_id'], $w_lot_bas, $w_hold_exc_flg,
							  $w_set_day, $w_rsn, $w_tel);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}
	### �ۡ���ɻ��Υ�å�����
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
# �֣������ ���
#==================================================================
function main_ioin_verb($w_usr_id, $w_equ_cd, $w_cmt, &$w_lot_bas)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# ��åȾ��֤Υ����å�
	#------------------------------------------------------------------
	$w_rtn = ioin_st_check($w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}
	if($w_equ_cd == ""){
		#------------------------------------------------------------------
		# ���Ѳ�ǽ�����֤γ����ؿ�
		#------------------------------------------------------------------
		$w_rtn = xgt_use_equ($w_lot_bas, $w_equ_cd);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
	}

	#------------------------------------------------------------------
	# ���ֹ����ޥ����Υ����å�
	#------------------------------------------------------------------
	$w_rtn = ioin_equ_check($w_equ_cd, $w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# �ɣϣɣ�
	# ����͡�$w_lot_bas
	#------------------------------------------------------------------
	$w_rtn = ioin($w_lot_bas['LOT_ID'],
				  $w_usr_id,
				  $w_lot_bas['UPD_LEV'],
				  $w_equ_cd,
				  $w_cmt,
				  $w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# ��λ �ɣϣϣ�
#==================================================================
function main_verb_ioot($w_usr_id, $w_ctg_dvs_cd, $w_ctg_cd,
						$w_ctg_qty, $w_ctg_dat_txt, $w_ctg_slid, $w_sl_qty_ok,
						$w_chp_qty_ok, $w_lf_qty_ok, $w_cmt, &$w_lot_bas)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# ��åȾ��֤Υ����å�
	#------------------------------------------------------------------
	$w_rtn = ioot_st_check($w_lot_bas['LOT_ST_DVS']);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# �ǽ��ɣϥ֥�å���ǧ xck_lio
	# ����͡�	$w_lot_st_dvs	��åȾ��ֶ�ʬ
	#			$w_io_blc_cs	�ɣϥ֥�å�������
	#			$w_stp_cd		���ƥåץ�����
	#			$w_stp_no		���ƥå��ֹ�
	#------------------------------------------------------------------
	$w_rtn = xck_lio(
					$w_lot_bas['PRC_CD'],
					$w_lot_bas['IO_BLC_CD'],
					$w_lot_bas['PLT_DVS_CD'],
					$w_lot_st_dvs,				# ����͡���åȾ��ֶ�ʬ
					$w_io_blc_cd,				# ����͡��ɣϥ֥�å�������
					$w_stp_cd,					# ����͡����ƥåץ�����
					$w_stp_no);					# ����͡����ƥå��ֹ�

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, '', __LINE__);
		return 4000;
	}

	#------------------------------------------------------------------
	# ���ƥ�������
	#------------------------------------------------------------------
	$w_ctg_flg = 0;
	if(is_array($w_ctg_cd)){
		for($i=1; $i<=count($w_ctg_cd); $i++){
			$w_arr_cnt = $i;
			$w_arr_ctg_dvs_cd[$i] = $w_ctg_dvs_cd[$i];
			$w_arr_ctg_cd[$i]     = $w_ctg_cd[$i];
			$w_arr_txt[$i]        = $w_ctg_dat_txt[$i];
			$w_arr_equ_cd[$i]     = $w_lot_bas['EQU_CD'];
			$w_arr_sl_id[$i]      = $w_ctg_slid[$i];
			$w_arr_qty[$i]        = $w_ctg_qty[$i];
		}
		$w_ctg_flg = 1;
	}

	if($w_ctg_flg == 0){
		$w_arr_cnt = 0;
		$w_arr_ctg_dvs_cd = '';
		$w_arr_ctg_cd     = '';
		$w_arr_equ_cd     = '';
		$w_arr_sl_id      = '';
		$w_arr_qty        = '';
		$w_arr_txt        = '';
	}

	#------------------------------------------------------------------
	# �ɣϣϣ�
	# ����͡�$w_lot_bas
	#------------------------------------------------------------------
	$w_rtn = ioot(
				$w_lot_bas['LOT_ID'],			# ��åȣɣ�
				$w_usr_id,						# �桼���ɣ�
				$w_lot_bas['UPD_LEV'],			# ������٥�
				$w_cmt,							# ������
				$w_lot_st_dvs,					# ��åȾ��ֶ�ʬ
				$w_sl_qty_ok,					# ����SL_QTY
				$w_chp_qty_ok,					# ���ʥ��å׿�
				$w_lf_qty_ok,					# ���ʥ��饤����
				$w_lot_bas['SECRET_NO'],		# ̩��
				$w_arr_cnt,						# ���ƥ����
				$w_arr_ctg_dvs_cd,				# ���ƥ����ʬ������
				$w_arr_ctg_cd,					# ���ƥ��ꥳ����
				$w_arr_equ_cd,					# ���ƥ������֥�����
				$w_arr_sl_id,					# ���ƥ��ꥹ�饤���ɣ�
				$w_arr_qty,						# ���ƥ������
				$w_arr_txt,						# ���ƥ�������ǡ���
				$w_lot_bas);					# ����͡���åȴ��ܾ���

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, '', __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# �������Ϥ� �ɣϣͣ֡��УңУ�-�УңУ�-�Уңǣ�
#==================================================================
function main_verb_iomv($w_usr_id, $w_cmt, &$w_lot_bas)
{
	global $g_msg;
	global $g_err_lv;

	# ��åȾ��ֶ�ʬ�ˤ�뿶��ʬ��
	switch($w_lot_bas['LOT_ST_DVS']){
	case "OW":
		#------------------------------------------------------------------
		# ���ɣϥ֥�å��γ��� xgt_nio
		#------------------------------------------------------------------
		$w_rtn = xgt_nio(
						$w_lot_bas['PRC_CD'],
						$w_lot_bas['IO_BLC_CD'],
						$w_lot_bas['STP_NO'],
						$w_lot_bas['PLT_DVS_CD'],
						$w_io_blc_cd,				# ����͡����ɣϥ֥�å�������
						$w_stp_cd,					# ����͡������ƥåץ�����
						$w_stp_no);					# ����͡������ƥå��ֹ�


		$w_nxt_verb = "IOMV";

		break;
	case "EW":
		#------------------------------------------------------------------
		# ���ץ����γ��� xgt_npr
		#------------------------------------------------------------------
		$w_rtn = xgt_npr(
						$w_lot_bas['RT_CD'],
						$w_lot_bas['PRC_CD'],
						$w_lot_bas['PLT_DVS_CD'],
						$w_prc_cd,					# ����͡����ץ���������
						$w_io_blc_cd,				# ����͡����ɣϥ֥�å�������
						$w_stp_cd,					# ����͡������ƥåץ�����
						$w_stp_no);					# ����͡������ƥå��ֹ�

		if($w_rtn == 0){
			$w_nxt_verb = "PRPT";
		} else {
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return 4000;
		}

		break;
	}


	switch($w_nxt_verb){
	case "IOMV":
		#------------------------------------------------------------------
		# �ɣϣͣ�
		# ����͡�$w_lot_bas
		#------------------------------------------------------------------
		$w_rtn = iomv(
					$w_lot_bas['LOT_ID'],
					$w_usr_id,
					$w_lot_bas['UPD_LEV'],
					$w_cmt,
					$w_io_blc_cd,
					$w_stp_cd,
					$w_stp_no,
					$w_lot_bas);

		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return $w_rtn;
		}

		break;
	case "PRPT":
		#------------------------------------------------------------------
		# �УңУ�
		# ����͡�$w_lot_bas
		#------------------------------------------------------------------
		$w_rtn = prpt(
					$w_lot_bas['LOT_ID'],
					$w_usr_id,
					$w_lot_bas['UPD_LEV'],
					$w_cmt,
					$w_lot_bas);

		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return $w_rtn;
		}

		#------------------------------------------------------------------
		# �УңУ�
		# ����͡�$w_lot_bas
		#------------------------------------------------------------------
		$w_rtn = prpc(
					$w_lot_bas['LOT_ID'],
					$w_usr_id,
					$w_lot_bas['UPD_LEV'],
					$w_cmt,
					$w_prc_cd,
					$w_io_blc_cd,
					$w_stp_cd,
					$w_stp_no,
					$w_lot_bas);

		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return $w_rtn;
		}

		#------------------------------------------------------------------
		# �Уңǣ�
		# ����͡�$w_lot_bas
		#------------------------------------------------------------------
		$w_rtn = prgt(
					$w_lot_bas['LOT_ID'],
					$w_usr_id,
					$w_lot_bas['UPD_LEV'],
					$w_cmt,
					$w_lot_bas);

		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, '', __LINE__);
			return $w_rtn;
		}

		break;

	} # end switch

	return 0;
}

#==================================================================
# �ۡ���� �ɣϣȣ�
#==================================================================
function main_verb_iohd($w_usr_id, $w_hld_can_dts, $w_cus_cd, $w_cmt, &$w_lot_bas)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------
	# ��åȾ��֥����å�
	#------------------------------------------------------
	$w_rtn = iohd_st_check($w_lot_bas['LOT_ST_DVS']);
	if ($w_rtn) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	#------------------------------------------------------
	# �ɣϣȣ�
	#------------------------------------------------------
	$w_rtn = iohd(
				$w_lot_bas['LOT_ID'],
				$w_usr_id,
				$w_lot_bas['UPD_LEV'],
				$w_hld_can_dts,
				$w_cus_cd,
				$w_cmt,
				$w_lot_bas);

	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# �������Ϥ����� ��VERB����
#==================================================================
function set_nxt_verb($w_lot_bas, &$r_nxt_verb, &$r_nxt_ionm)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# ��åȾ��֤ˤ�ꡢ�����å���ʬ
	#------------------------------------------------------------------
	switch($w_lot_bas['LOT_ST_DVS']){
	case "OW":
		#------------------------------------------------------------------
		# ���ɣϥ֥�å�����
		#------------------------------------------------------------------
		$w_rtn = xgt_nio($w_lot_bas['PRC_CD'],
						$w_lot_bas['IO_BLC_CD'],
						$w_lot_bas['STP_NO'],
						$w_lot_bas['PLT_DVS_CD'],
						$w_nxt_io_blc_cd,
						$w_nxt_stp_cd,
						$w_nxt_stp_no);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
		#------------------------------------------------------------------
		# �ɣϥ֥�å�̾�μ���
		#------------------------------------------------------------------
		$w_rtn = xgn_cd($w_nxt_io_blc_cd, 1, $w_nxt_io_blc_nm);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
		$r_nxt_verb = 'IOMV';
		$r_nxt_ionm = trim($w_nxt_io_blc_nm);

		break;
	case "EW":
		#------------------------------------------------------------------
		# ���ץ�������
		#------------------------------------------------------------------
		$w_rtn = xgt_npr($w_lot_bas['RT_CD'],
						$w_lot_bas['PRC_CD'],
						$w_lot_bas['PLT_DVS_CD'],
						$w_nxt_prc_cd,
						$w_nxt_io_blc_cd,
						$w_nxt_stp_cd,
						$w_nxt_stp_no);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
		#------------------------------------------------------------------
		# �ɣϥ֥�å�̾�μ���
		#------------------------------------------------------------------
		$w_rtn = xgn_cd($w_nxt_io_blc_cd, 1, $w_nxt_io_blc_nm);
		if($w_rtn != 0){
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
		$r_nxt_verb = 'PRPT';
		$r_nxt_ionm = trim($w_nxt_io_blc_nm);

		break;
	}

	return 0;
}

#==================================================================
# �ʼ��ѹ� �ɣϣУ�
#==================================================================
function main_verb_iopc($w_usr_id, $w_nxt_prd_cd, $w_rank, $w_cmt, &$w_lot_bas)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_rtn = iopc_st_check($w_lot_bas['LOT_ST_DVS']);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

# Ʊ���롼�Ⱦ���ʼ��ѹ�����٤��ν�������
#		$w_rtn = iopc_prd_check($w_nxt_prd_cd, $w_lot_bas, $w_new_rt_cd);
#		if($w_rtn != 0){
#			$g_err_lv = 0;
#			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
#			return 4000;
#		}

	$w_rtn = iopc($w_lot_bas['LOT_ID'], $w_usr_id,
				  $w_lot_bas['UPD_LEV'], $w_nxt_prd_cd, $w_lot_bas['RT_CD'], $w_rank,
				  $w_cmt, $w_lot_bas);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	return 0;
}

#==================================================================
# ��åȾ���ơ��֥����Ͽ
#==================================================================
function ins_lot_inf_tbl($w_usr_id,
						 $w_lot_id,
						 $w_ctg_dvs_cd,
						 $w_ctg_cd,
						 $w_sl_id,
						 $w_ctg_dat_txt,
						 $w_ctg_dat_val)
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
		"CRT_VERB"		=> 'IORV',
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
# �ץ�����Ⱦ���μ���
#==================================================================
function get_prdinf($w_prd_cd, $w_prd_nm, &$r_dat)
{
	global $g_msg;
	global $g_err_lv;

	$r_dat = array();

	$w_whr_prd_cd = "";
	if($w_prd_cd != ""){
		$w_whr_prd_cd = "AND PRD_CD = '". $w_prd_cd ."'";
	}
	$w_whr_prd_nm = "";
	if($w_prd_nm != ""){
		$w_whr_prd_nm = "AND PRD_NM = '". $w_prd_nm ."'";
	}

	$w_sql = <<<_SQL
SELECT
	*
FROM
	PRD_MST
WHERE
	DEL_FLG = '0'
	{$w_whr_prd_cd}
	{$w_whr_prd_nm}
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRD_MST", __LINE__);
		return 4000;
	}

	if($w_row = db_fetch_row($w_stmt)){
		foreach($w_row as $key => $val){
			$w_row[$key] = trim($w_row[$key]);
		}
		$r_dat = $w_row;
	}
	db_res_free($w_stmt);

	return 0;
}

function get_prdinf2($w_prd_cd, $w_dat_cd, &$r_dat)
{
        global $g_msg;
        global $g_err_lv;

        $r_dat = array();

        $w_sql = <<<_SQL
SELECT
        *
FROM
        PRD_INF_MST
WHERE
        PRD_CD = '{$w_prd_cd}'
        AND DAT_CD = '{$w_dat_cd}'
        AND DEL_FLG = '0'
_SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "PRD_INF_MST", __LINE__);
                return 4000;
        }

        $r_dat = db_fetch_row($w_stmt);
        db_res_free($w_stmt);

        return 0;

}


#==================================================================
# QA���С��������å�
#==================================================================
function chk_qa_member($w_usr_id, &$r_qa_flg)
{
	global $g_msg;
	global $g_err_lv;

	$r_qa_flg  = 0;
	$w_usr_grp = constant("DG_QA_MEMBER");

	$w_sql = <<<_SQL
SELECT
	COUNT(*) AS CNT
FROM
	USR_GRP_MST
WHERE
	USR_ID = '{$w_usr_id}'
	AND USR_GRP_CD = '{$w_usr_grp}'
	AND DEL_FLG = '0'
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "USR_GRP_MST", __LINE__);
		return 4000;
	}

	if($w_row = db_fetch_row($w_stmt)){
		if($w_row['CNT'] > 0){
			$r_qa_flg = 1;
		}
	}
	db_res_free($w_stmt);

	return 0;
}

#==================================================================
# ��ϩ����μ���
#==================================================================
function get_rt_info($w_prd_cd_fin,
					 $w_prd_cd,
					 $w_prc_cls_4,
					 &$r_rt_cd,
					 &$r_prc_cd,
					 &$r_io_blc_cd,
					 &$r_stp_cd,
					 &$r_stp_no,
					 &$r_blc_cls_3)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# �����
	#------------------------------------------------------------------
	$r_rt_cd     = "";
	$r_prc_cd    = "";
	$r_io_blc_cd = "";
	$r_stp_cd    = "";
	$r_stp_no    = "";
	$r_blc_cls_3 = "";
	
	#------------------------------------------------------------------
	# PRD_ORG_MST����
	#------------------------------------------------------------------
	$w_sql = <<<_SQL
SELECT
	RT_CD,
	PRC_CD,
	M_RT_FLG_SCH
FROM
	PRD_ORG_MST
WHERE
	DEL_FLG = '0'
	AND PRD_CD_FIN = '{$w_prd_cd_fin}'
	AND PRD_CD = '{$w_prd_cd}'
	AND PRC_CLS_4 = '{$w_prc_cls_4}'
	AND NO_USE_FLG = '0'
ORDER BY
	M_RT_FLG_SCH DESC, RT_CD DESC, SEQ_NO_RT
_SQL;
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
		return 4000;
	}

        $cnt = 0;
        $strcnt = 0;
        $duprtcd  = array();
        $errprccd = array();
        while($w_row = db_fetch_row($w_stmt)){
                $cnt++;

                ### ��ʣ�ϥ����å�
                $rtcd = trim($w_row['RT_CD']);
                if($duprtcd[$rtcd] == "1"){
                        continue;
                }
                $duprtcd[$rtcd] = "1";

                #------------------------------------------------------------------
                # �������в�ǽ�������å�
                #------------------------------------------------------------------
                $w_rtn = chk_strrtcd($w_row['RT_CD'], "", $w_prccd, $w_flg);
                if($w_rtn != 0){
                        return 4000;
                }
                if($w_flg == 0){
                        $errprccd[] = $w_prccd;
                        continue; ### �����ԲĤ����Ф�
                }
                $strcnt++;

                ### �롼�ȡ��ץ�������
                $r_rt_cd  = trim($w_row['RT_CD']);
                $r_prc_cd = trim($w_row['PRC_CD']);
                break;
        }
        db_res_free($w_stmt);

        if($cnt == 0){
                list($g_msg, $g_err_lv) = msg("err_NoRtInfo");
                $g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
                return 4000;
        }
        if($strcnt == 0){
                list($g_msg, $g_err_lv) = msg("err_NewStrPoint");
                $g_msg = xpt_err_msg($g_msg, $errprccd[0], __LINE__);
                return 4000;
        }

	#------------------------------------------------------------------
	# PRC_FLW_MST����
	#------------------------------------------------------------------
	$w_sql = <<<_SQL
SELECT
	STP_SEQ_NO,
	IO_BLC_CD,
	STP_CD,
	STP_NO,
	BLC_CLS_3
FROM
	PRC_FLW_MST
WHERE
	PRC_CD = '{$r_prc_cd}'
	AND IO_FLG = '1'
	AND DEL_FLG = '0'
ORDER BY
	STP_SEQ_NO
_SQL;
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRC_FLW_MST", __LINE__);
		return 4000;
	}
	$w_row = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	### �����Ǥ��ʤ��ä����
	if(!$w_row){
		list($g_msg, $g_err_lv) = msg("err_NoStpInfo");
		$g_msg = xpt_err_msg($g_msg, $r_prc_cd, __LINE__);
		return 4000;
	}

	### ���ƥå׾��������
	$r_io_blc_cd = trim($w_row['IO_BLC_CD']);
	$r_stp_cd    = trim($w_row['STP_CD']);
	$r_stp_no    = trim($w_row['STP_NO']);
	$r_blc_cls_3 = trim($w_row['BLC_CLS_3']);
	
	return 0;
}

#==================================================================
# ��ϩ����μ���
#==================================================================
function get_rt_stp_lst($w_rt_cd, &$r_dat){

	global $g_msg;
	global $g_err_lv;

	$r_dat = array();
	
	$w_sql = <<<_SQL
SELECT 
	* 
FROM 
	PRD_ORG_MST
WHERE 
	DEL_FLG = '0'
ORDER BY 
	RT_CD
	, SEQ_NO_RT
	, SEQ_NO_PRC
	, STP_NO
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
		return 4000;
	}

	while($w_row = db_fetch_row($w_stmt)){
		$r_dat[] = trim($w_row['STP_CD']);
	}
	return 0;

}

#==================================================================
# ��ϩ����μ���
#==================================================================
function get_rt_info_lsi($w_prd_cd_fin,
					 $w_prd_cd,
					 $w_prc_cls_4,
					 &$r_rt_cd_cairn,
					 &$r_prc_cd_cairn,
					 &$r_io_blc_cd_cairn,
					 &$r_stp_cd_cairn,
					 &$r_stp_no_cairn,
					 &$r_blc_cls_3_cairn,
					 &$r_prd_cd_cairn,
					 &$r_rt_cd_mcp,
					 &$r_prc_cd_mcp,
					 &$r_io_blc_cd_mcp,
					 &$r_stp_cd_mcp,
					 &$r_stp_no_mcp,
					 &$r_blc_cls_3_mcp,
					 &$r_prd_cd_mcp)
{
	global $g_msg;
	global $g_err_lv;

	#------------------------------------------------------------------
	# �����
	#------------------------------------------------------------------
	$r_rt_cd_cairn     = "";
	$r_prc_cd_cairn    = "";
	$r_io_blc_cd_cairn = "";
	$r_stp_cd_cairn    = "";
	$r_stp_no_cairn    = "";
	$r_blc_cls_3_cairn = "";
	$r_prd_cd_cairn	= "";
	
	$r_rt_cd_mcp     = "";
	$r_prc_cd_mcp    = "";
	$r_io_blc_cd_mcp = "";
	$r_stp_cd_mcp    = "";
	$r_stp_no_mcp    = "";
	$r_blc_cls_3_mcp = "";
	$r_prd_cd_mcp	= "";
	
	#------------------------------------------------------------------
	# PRD_ORG_MST����
	#------------------------------------------------------------------
	$w_sql = <<<_SQL
SELECT
	RT_CD,
	PRC_CD,
	PRD_CD,
	M_RT_FLG_SCH,
	M_RT_FLG_AC
FROM
	PRD_ORG_MST
WHERE
	DEL_FLG = '0'
	AND PRD_CD_FIN = '{$w_prd_cd_fin}'
	
	AND PRC_CLS_4 = '{$w_prc_cls_4}'
	AND NO_USE_FLG = '0'
ORDER BY
	M_RT_FLG_SCH DESC, M_RT_FLG_AC DESC, RT_CD DESC, SEQ_NO_RT
_SQL;

#AND PRD_CD = '{$w_prd_cd}'
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
		return 4000;
	}

	$cnt = 0;
	$strcnt = 0;
	$duprtcd  = array();
	$errprccd = array();
	$w_ac_rt_cd = "";
	$w_ac_prc_cd = "";
	$w_single_flg = false;
	$w_found_flg = false;
	$w_mcp_stp_lst = array();
	$w_cairn_stp_lst = array();
	
	while($w_row = db_fetch_row($w_stmt)){
			$cnt++;

			### ��ʣ�ϥ����å�
			$rtcd = trim($w_row['RT_CD']);
			if($duprtcd[$rtcd] == "1"){
					continue;
			}
			$duprtcd[$rtcd] = "1";

			#------------------------------------------------------------------
			# �������в�ǽ�������å�
			#------------------------------------------------------------------
			$w_rtn = chk_strrtcd($w_row['RT_CD'], "", $w_prccd, $w_flg);
			if($w_rtn != 0){
					return 4000;
			}
			if($w_flg == 0){
					$errprccd[] = $w_prccd;
					continue; ### �����ԲĤ����Ф�
			}
			$strcnt++;

			### �롼�ȡ��ץ�������
			#$r_rt_cd  = trim($w_row['RT_CD']);
			#$r_prc_cd = trim($w_row['PRC_CD']);
			if($w_row['M_RT_FLG_SCH']  == 1 && $w_row['M_RT_FLG_AC']  == 1){
				$w_single_flg = true;
				$w_found_flg = true;
				$r_rt_cd_mcp = trim($w_row['RT_CD']);
				$r_prc_cd_mcp = trim($w_row['PRC_CD']);
				$r_prd_cd_mcp = trim($w_row['PRD_CD']);
				$r_rt_cd_cairn = trim($w_row['RT_CD']);
				$r_prc_cd_cairn = trim($w_row['PRC_CD']);
				$r_prd_cd_cairn = trim($w_row['PRD_CD']);
				
				break;
			}elseif($w_row['M_RT_FLG_SCH']  == 1 && $w_row['M_RT_FLG_AC']  == 0){
				$r_rt_cd_mcp = trim($w_row['RT_CD']);
				$r_prc_cd_mcp = trim($w_row['PRC_CD']);
				$r_prd_cd_mcp = trim($w_row['PRD_CD']);
			
				$w_rtn = get_rt_stp_lst($r_rt_cd_mcp, $w_mcp_stp_lst);
				if($w_rtn !=0){
					return 4000;
				}
				
				
			}elseif($w_row['M_RT_FLG_SCH']  == 0 && $w_row['M_RT_FLG_AC']  == 1){
				# i assume that mcp rt_cd is already defined
				$r_rt_cd_cairn = trim($w_row['RT_CD']);
				$r_prc_cd_cairn = trim($w_row['PRC_CD']);
				$r_prd_cd_cairn = trim($w_row['PRD_CD']);
			
				$w_rtn = get_rt_stp_lst($r_rt_cd_cairn, $w_cairn_stp_lst);
				if($w_rtn !=0){
					return 4000;
				}
				
				#compare two arrays
				if($w_cairn_stp_lst === $w_mcp_stp_lst){
					$w_found_flg = true;
					break;
				}
				
			}elseif($w_row['M_RT_FLG_SCH']  == 0 && $w_row['M_RT_FLG_AC']  == 0){
				# i assume that mcp rt_cd is already defined
				$r_rt_cd_cairn = trim($w_row['RT_CD']);
				$r_prc_cd_cairn = trim($w_row['PRC_CD']);
				$r_prd_cd_cairn = trim($w_row['PRD_CD']);
			
				$w_rtn = get_rt_stp_lst($r_rt_cd_cairn, $w_cairn_stp_lst);
				if($w_rtn !=0){
					return 4000;
				}
				
				#compare two arrays
				if($w_cairn_stp_lst === $w_mcp_stp_lst){
					$w_found_flg = true;
					break;
				}
				
			}
			
	}
	db_res_free($w_stmt);

	if($cnt == 0){
			list($g_msg, $g_err_lv) = msg("err_NoRtInfo");
			$g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
			return 4000;
	}
	if($strcnt == 0){
			list($g_msg, $g_err_lv) = msg("err_NewStrPoint");
			$g_msg = xpt_err_msg($g_msg, $errprccd[0], __LINE__);
			return 4000;
	}
	
	if(!$w_found_flg){
			list($g_msg, $g_err_lv) = msg("err_NoRtInfo");
			$g_msg = xpt_err_msg($g_msg, $w_prd_cd, __LINE__);
			return 4000;
	}

	#get the cairn first
	$w_rtn = get_prc_flw_info($r_prc_cd_cairn, $r_dat);
	if($w_rtn!=0){
		return 4000;
	}

	### ���ƥå׾��������
	$r_io_blc_cd_cairn = trim($r_dat['IO_BLC_CD']);
	$r_stp_cd_cairn    = trim($r_dat['STP_CD']);
	$r_stp_no_cairn    = trim($r_dat['STP_NO']);
	$r_blc_cls_3_cairn = trim($r_dat['BLC_CLS_3']);

	#get the mcp next 
	$w_rtn = get_prc_flw_info($r_prc_cd_mcp, $r_dat);
	if($w_rtn!=0){
		return 4000;
	}

	### ���ƥå׾��������
	$r_io_blc_cd_mcp = trim($r_dat['IO_BLC_CD']);
	$r_stp_cd_mcp    = trim($r_dat['STP_CD']);
	$r_stp_no_mcp    = trim($r_dat['STP_NO']);
	$r_blc_cls_3_mcp = trim($r_dat['BLC_CLS_3']);
		
	return 0;
}

#------------------------------------------------------------------
# PRC_FLW_MST����
#------------------------------------------------------------------
function get_prc_flw_info($w_prc_cd, &$r_dat){

	global $g_msg;
	global $g_err_lv;

	
	#------------------------------------------------------------------
	# PRC_FLW_MST����
	#------------------------------------------------------------------
	$w_sql = <<<_SQL
SELECT
	STP_SEQ_NO,
	IO_BLC_CD,
	STP_CD,
	STP_NO,
	BLC_CLS_3
FROM
	PRC_FLW_MST
WHERE
	PRC_CD = '{$w_prc_cd}'
	AND IO_FLG = '1'
	AND DEL_FLG = '0'
ORDER BY
	STP_SEQ_NO
_SQL;
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PRC_FLW_MST", __LINE__);
		return 4000;
	}
	$w_row = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	### �����Ǥ��ʤ��ä����
	if(!$w_row){
		list($g_msg, $g_err_lv) = msg("err_NoStpInfo");
		$g_msg = xpt_err_msg($g_msg, $w_prc_cd, __LINE__);
		return 4000;
	}
	
	$r_dat = $w_row;
	
	return 0;
	
}


#==================================================================
# Check if Process == AUSEM01
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
                $r_flg   = 0; # �����Բ�
        }
        db_res_free($w_stmt);

        return 0;
}


#==================================================================
# ���֥����ɤμ���
#==================================================================
function get_equcd($w_stp_cd, &$r_equ_cd)
{
	global $g_msg;
	global $g_err_lv;

	$r_equ_cd = "";

	$w_sql = <<<_SQL
SELECT
	MAX(EQU_CD) AS EQU_CD
FROM
	EQU_ORG_MST
WHERE
	DEL_FLG = '0'
	AND STP_CD = '{$w_stp_cd}'
_SQL;

	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "EQU_ORG_MST", __LINE__);
		return 4000;
	}
	$w_row = db_fetch_row($w_stmt);
	db_res_free($w_stmt);

	if(!$w_row){
		list($g_msg, $g_err_lv) = msg("err_NoEquCd");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	$r_equ_cd = trim($w_row['EQU_CD']);

	return 0;
}


#==================================================================
# ���֥����ɤμ���
#==================================================================
function get_finnm($w_prd_cd, &$r_fin_cd)
{
        global $g_msg;
        global $g_err_lv;

        $r_equ_cd = "";

        $w_sql = <<<_SQL
SELECT
    DISTINCT PM.PRD_CD AS PRD_CD, PM.PRD_NM AS PRD_NM
FROM
    PRD_MST PM, PRD_ORG_MST POM
WHERE
    POM.PRD_CD = '{$w_prd_cd}' AND
    PM.DEL_FLG ='0' AND
    POM.DEL_FLG='0' AND
    POM.NO_USE_FLG = '0' AND	
    PM.PRD_CD = POM.PRD_CD_FIN
ORDER BY PM.PRD_NM
_SQL;
        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "PRD_ORG_MST", __LINE__);
                return 4000;
        }

	$w_cnt = 0;
	$w_res = array();
 	while($w_row = db_fetch_row($w_stmt)){
		$w_res[] = array('PRD_CD' => $w_row['PRD_CD'], 'PRD_NM' => $w_row['PRD_NM']);
		$w_cnt++;
	}
        db_res_free($w_stmt);

        if($w_cnt == 0){
                list($g_msg, $g_err_lv) = msg("err_NoFinInfo");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        $r_fin_cd = $w_res;

        return 0;
}


#==================================================================
# ����������μ���
#==================================================================
function get_next_process($w_rt_cd, $w_prd_cd, $w_stp_cd, &$r_next_dat)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$r_next_dat = array();

	#------------------------------------------------------------------
	# �������������
	#------------------------------------------------------------------
	$w_sql = <<<_SQL
SELECT
	POM2.RT_CD AS RT_CD,
	POM2.PRD_CD_FIN AS PRD_CD_FIN,
	MIN(POM2.SEQ_NO_RT) AS SEQ_NO_RT
FROM
	PRD_ORG_MST POM1,
	PRD_ORG_MST POM2
WHERE
	POM1.RT_CD = '{$w_rt_cd}'
	AND POM1.PRD_CD = '{$w_prd_cd}'
	AND POM1.STP_CD = '{$w_stp_cd}'
	AND POM1.DEL_FLG = '0'
	AND POM1.RT_CD = POM2.RT_CD
	AND POM1.PRD_CD_FIN = POM2.PRD_CD_FIN
	AND POM2.DEL_FLG = '0'
	AND POM1.SEQ_NO_RT < POM2.SEQ_NO_RT
	AND POM2.IO_FLG = '1'
GROUP BY
	POM2.RT_CD, POM2.PRD_CD_FIN
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
		list($g_msg, $g_err_lv) = msg("err_NextProcessInfo");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	$w_sql = <<<_SQL
SELECT
	PRD_CD,
	PRC_CD,
	STP_NO,
	STP_CD
FROM
	PRD_ORG_MST
WHERE
	RT_CD = '{$w_row['RT_CD']}'
	AND PRD_CD_FIN = '{$w_row['PRD_CD_FIN']}'
	AND SEQ_NO_RT = '{$w_row['SEQ_NO_RT']}'
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
		list($g_msg, $g_err_lv) = msg("err_NextProcessInfo");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	### �ǡ�������
	$r_next_dat['PRD_CD'] = trim($w_row['PRD_CD']);
	$r_next_dat['PRC_CD'] = trim($w_row['PRC_CD']);
	$r_next_dat['STP_NO'] = trim($w_row['STP_NO']);
	$r_next_dat['STP_CD'] = trim($w_row['STP_CD']);

	return 0;
}

#==================================================================
# �����å�����
#==================================================================
function check_input($w_mode)
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	global $g_cpu_dts;

	switch ($w_mode) {
	#------------------------------------------------------------------
	# �⡼�ɣ�
	#------------------------------------------------------------------
	case 1:
		#------------------------------------------------------------------
		# �ȥ�����ʸ����
		#------------------------------------------------------------------
		$gw_scr['s_usr_id']     = strtoupper(trim($gw_scr['s_usr_id']));
		$gw_scr['s_prd_nm']     = strtoupper(trim($gw_scr['s_prd_nm']));
		$gw_scr['s_prd_nm_fin'] = strtoupper(trim($gw_scr['s_prd_nm_fin']));
		$gw_scr['s_rnk']        = strtoupper(trim($gw_scr['s_rnk']));
		$gw_scr['s_dif_lot_no'] = strtoupper(trim($gw_scr['s_dif_lot_no']));
		$gw_scr['s_mt_lot_id']  = strtoupper(trim($gw_scr['s_mt_lot_id']));
		$gw_scr['s_chp_qty']    = trim($gw_scr['s_chp_qty']);
		$gw_scr['s_rng_qty']    = trim($gw_scr['s_rng_qty']);
		$gw_scr['s_lot_typ_cd'] = strtoupper(trim($gw_scr['s_lot_typ_cd']));
		$gw_scr['s_lot_dsc_cd'] = strtoupper(trim($gw_scr['s_lot_dsc_cd']));
		$gw_scr['s_lp_cd']      = strtoupper(trim($gw_scr['s_lp_cd']));
		$gw_scr['s_lot_rmks']      = strtoupper(trim($gw_scr['s_lot_rmks']));
		#------------------------------------------------------------------
		# ɬ��
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Nec_Input");
		if($gw_scr['s_usr_id'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("UsrId"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_prd_nm'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("ChpNm"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_prd_nm_fin'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("AssPrdNm"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_mt_lot_id'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("MtLotID"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_chp_qty'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("ChpQty"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_lot_typ_cd'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("LotClsCd"), __LINE__);
			return 4000;
		}
		if($gw_scr['s_lot_dsc_cd'] == ""){
			$g_msg = xpt_err_msg($g_msg, itm("LotDecCd"), __LINE__);
			return 4000;
		}
		#if($gw_scr['s_lot_rmks'] == ""){
		#	$g_msg = xpt_err_msg($g_msg, itm("LotRmks"), __LINE__);
		#	return 4000;
		#}
		#------------------------------------------------------------------
		# ����
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Inp_Tag");
		$w_tg = substr($gw_scr['s_usr_id'], 0, 2);
		if($w_tg != constant("TG_MA")){
			$w_tg = get_tg(itm("UsrId"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		$w_tg = substr($gw_scr['s_lot_typ_cd'], 0, 2);
		if($w_tg != constant("TG_CC")){
			$w_tg = get_tg(itm("LotClsCd"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		$w_tg = substr($gw_scr['s_lot_dsc_cd'], 0, 2);
		if($w_tg != constant("TG_CD")){
			$w_tg = get_tg(itm("LotDecCd"));
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}

		#------------------------------------------------------------------
		# �ػ�ʸ��
		#------------------------------------------------------------------
		list($g_msg, $g_err_lv) = msg("err_Inp_Char");
		if(!check_eisu($gw_scr['s_usr_id'])){
			$w_tg = get_tg(itm("UsrId"), $gw_scr['s_usr_id']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_prdnm($gw_scr['s_prd_nm'])){
			$w_tg = get_tg(itm("ChpNm"), $gw_scr['s_prd_nm']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_prdnm($gw_scr['s_prd_nm_fin'])){
			$w_tg = get_tg(itm("AssPrdNm"), $gw_scr['s_prd_nm_fin']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_eisu($gw_scr['s_rnk'])){
			$w_tg = get_tg(itm("Rnk"), $gw_scr['s_rnk']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_err_code($gw_scr['s_dif_lot_no'])){
			$w_tg = get_tg(itm("DiffLotNo"), $gw_scr['s_dif_lot_no']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_err_cmt($gw_scr['s_mt_lot_id'])){
			$w_tg = get_tg(itm("MtLotID"), $gw_scr['s_mt_lot_id']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_num($gw_scr['s_chp_qty'])){
			$w_tg = get_tg(itm("ChpQty"), $gw_scr['s_chp_qty']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_err_code($gw_scr['s_lot_typ_cd'])){
			$w_tg = get_tg(itm("LotClsCd"), $gw_scr['s_lot_typ_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_err_code($gw_scr['s_lot_dsc_cd'])){
			$w_tg = get_tg(itm("LotDecCd"), $gw_scr['s_lot_dsc_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}
		if(!check_err_cmt($gw_scr['s_lp_cd'])){
			$w_tg = get_tg(itm("LotPaperOutputName"), $gw_scr['s_lp_cd']);
			$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
			return 4000;
		}

		break;

	#------------------------------------------------------------------
	# �⡼�ɣ�
	#------------------------------------------------------------------
	case 2:
		break;

        #------------------------------------------------------------------
        # �⡼�ɣ�
        #------------------------------------------------------------------
        case 3:
		db_begin();

		echo "<BR><BR><BR><BR>";

		echo "DateCode Testing<BR>========================================<BR><BR>";

		$w_dvsn = "ABSEM31";
		$w_rdg = "RD00SC0";
		$w_fg_prd = "PD31S0001697";
		$w_lot_no = "U";	
		$w_lot_no_str = "HP18439.00F";
		$w_usr_id = "MASEMC30059";
		$w_slice_no = "26";
		$g_cpu_dts = "2016-09-01 00:00:00";
		$w_to220 = "NO";

		$w_rtn = get_prdinf2($w_fg_prd, "AW00S0000060", $w_datecode_inf);
                if($w_rtn != 0) {
                        return 4000;
                }
                if(isset($w_datecode_inf)){
                        if(trim($w_datecode_inf['TXT_DAT']) == 'Y') {
                                $w_to220 = "YES";
                        }
		}

		$w_rtn = cs_xgt_secno($w_dvsn, 
					$w_rdg, 
					$w_fg_prd, 
					substr($w_lot_no, -1), 
					$w_lot_no_str, 
					$g_cpu_dts , 
					$w_usr_id,
					$w_slice_no,  
					$w_digit, 
					$w_secno); 
		

                print_datecodeinfo($w_fg_prd, $w_lot_no_str, $w_to220, $w_slice_no, $g_cpu_dts, $w_digit, $w_secno);

		if($w_rtn != 0) {
			echo "";
			echo "Error Message : ". $g_msg;
			echo "<BR><BR>";
			
		}

		$w_dvsn = "ABSEM31";
                $w_rdg = "RD00SC0";
                $w_fg_prd = "PD31S0000232";
                $w_lot_no = "U";
                $w_lot_no_str = "HP18439.00F";
                $w_usr_id = "MASEMC30059";
                $w_slice_no = "10";
                $g_cpu_dts = "2016-09-01 00:00:00";
                $w_to220 = "NO";

		$w_rtn = get_prdinf2($w_fg_prd, "AW00S0000060", $w_datecode_inf);
                if($w_rtn != 0) {
                        return 4000;
                }
                if(isset($w_datecode_inf)){
                        if(trim($w_datecode_inf['TXT_DAT']) == 'Y') {
                                $w_to220 = "YES";
                        }
                }


                $w_rtn = cs_xgt_secno($w_dvsn,
                                        $w_rdg,
                                        $w_fg_prd,
                                        substr($w_lot_no, -1),
                                        $w_lot_no_str,
                                        $g_cpu_dts ,
                                        $w_usr_id,
                                        $w_slice_no,
                                        $w_digit,
                                        $w_secno);

                print_datecodeinfo($w_fg_prd, $w_lot_no_str, $w_to220, $w_slice_no, $g_cpu_dts, $w_digit, $w_secno);

                if($w_rtn != 0) {
                        echo "";
                        echo "Error Message : ". $g_msg;
                        echo "<BR><BR>";

                }

		$w_dvsn = "ABSEM31";
                $w_rdg = "RD00SC0";
                $w_fg_prd = "PD31S0002966";
                $w_lot_no = "U";
                $w_lot_no_str = "HP18439.33";
                $w_usr_id = "MASEMC30059";
                $w_slice_no = "19";
                $g_cpu_dts = "2016-09-01 00:00:00";
                $w_to220 = "NO";

		$w_rtn = get_prdinf2($w_fg_prd, "AW00S0000060", $w_datecode_inf);
                if($w_rtn != 0) {
                        return 4000;
                }
                if(isset($w_datecode_inf)){
                        if(trim($w_datecode_inf['TXT_DAT']) == 'Y') {
                                $w_to220 = "YES";
                        }
                }

                $w_rtn = cs_xgt_secno($w_dvsn,
                                        $w_rdg,
                                        $w_fg_prd,
                                        substr($w_lot_no, -1),
                                        $w_lot_no_str,
                                        $g_cpu_dts ,
                                        $w_usr_id,
                                        $w_slice_no,
                                        $w_digit,
                                        $w_secno);

                print_datecodeinfo($w_fg_prd, $w_lot_no_str, $w_to220, $w_slice_no, $g_cpu_dts, $w_digit, $w_secno);

                if($w_rtn != 0) {
                        echo "";
                        echo "Error Message : ". $g_msg;
                        echo "<BR><BR>";

                }


		
	
#		$w_usr_id, $w_prd_cd, $w_diff, $w_date, &$r_cd_cnt
#		$w_rtn = xgt_to220_cd_cnt("MASEMC30059", "PD11S0000837", "", "20160820", $r_cd_cnt); 
#		echo "Return : " . $w_rtn ."<BR>";
#		echo "Cnt : ". $r_cd_cnt;	
#		if($w_rtn != 0){
#			echo "ERROR : " . $g_msg;
#			db_rollback();
#			return 4000;
#		}
#		db_commit();
		db_rollback();	

		echo "<BR><BR><BR><BR>";
		echo "<BR><BR><BR><BR>";
		$gw_scr['s_usr_id']     = strtoupper(trim($gw_scr['s_usr_id']));
                list($g_msg, $g_err_lv) = msg("err_Nec_Input");
                if($gw_scr['s_usr_id'] == ""){
                        $g_msg = xpt_err_msg($g_msg, itm("UsrId"), __LINE__);
                        return 4000;
                }
                list($g_msg, $g_err_lv) = msg("err_Inp_Tag");
                $w_tg = substr($gw_scr['s_usr_id'], 0, 2);
                if($w_tg != constant("TG_MA")){
                        $w_tg = get_tg(itm("UsrId"));
                        $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                        return 4000;
                }
                list($g_msg, $g_err_lv) = msg("err_Inp_Char");
                if(!check_eisu($gw_scr['s_usr_id'])){
                        $w_tg = get_tg(itm("UsrId"), $gw_scr['s_usr_id']);
                        $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                        return 4000;
                }

                $gw_scr['s_sap_lot_id']     = strtoupper(trim($gw_scr['s_sap_lot_id']));
                list($g_msg, $g_err_lv) = msg("err_Nec_Input");
                if($gw_scr['s_sap_lot_id'] == ""){
                        $g_msg = xpt_err_msg($g_msg, itm("SapLotID"), __LINE__);
                        return 4000;
                }

                break;
	}

	$g_msg    = "";
	$g_err_lv = "";

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
# ɽ�����ܽ��������
#==================================================================
function set_init($w_mode)
{
	global $gw_scr;

	### �⡼�ɣ�
	if($w_mode == 1){
		$gw_scr['s_usr_id']     = "";
		$gw_scr['s_prd_nm']     = "";
		$gw_scr['s_prd_nm_fin'] = "";
		$gw_scr['s_rnk']        = "";
		$gw_scr['s_dif_lot_no'] = "";
		$gw_scr['s_mt_lot_id']  = "";
		$gw_scr['s_chp_qty']    = "";
		$gw_scr['s_rng_qty']    = "";
		$gw_scr['s_sap_lot_id'] = "";	
		$gw_scr['s_sl_inf']     = "";
		$gw_scr['s_sl_qty']     = "";
		$gw_scr['s_chp_qty']    = "";
		$gw_scr['s_exp_dte']    = "";
		$gw_scr['s_mfg_dte']    = "";
		$gw_scr['s_lot_typ_cd'] = "";
		$gw_scr['s_lot_dsc_cd'] = "";
		$gw_scr['s_lp_cd']      = "";
		$gw_scr['s_lot_rmks']   = "";
	}

	### �⡼�ɣ�
	if($w_mode <= 2){
		$gw_scr['s_prd_cd']     = "";
		$gw_scr['s_prd_nm']     = "";
		$gw_scr['s_prd_nm_fin'] = "";
		$gw_scr['s_rnk']        = "";
		$gw_scr['s_dif_lot_no'] = "";            # DIF_LOT_NO
		$gw_scr['s_mt_lot_id']  = "";            # SAP_LOT_NO
		$gw_scr['s_prd_cd_fin'] = "";
		$gw_scr['s_sl_inf']     = "";
		$gw_scr['s_sl_qty']     = "";
		$gw_scr['s_chp_qty']    = "";
		$gw_scr['s_exp_dte']    = "";
		$gw_scr['s_mfg_dte']    = "";
		
		$gw_scr['s_rt_cd_cairn']      = "";
		$gw_scr['s_prc_cd_cairn']     = "";
		$gw_scr['s_prd_cd_cairn']     = "";
		$gw_scr['s_io_blc_cd_cairn']  = "";
		$gw_scr['s_stp_cd_cairn']     = "";
		$gw_scr['s_stp_no_cairn']     = "";
		
		$gw_scr['s_rt_cd_mcp']      = "";
		$gw_scr['s_prc_cd_mcp']     = "";
		$gw_scr['s_prd_cd_mcp']     = "";
		$gw_scr['s_io_blc_cd_mcp']  = "";
		$gw_scr['s_stp_cd_mcp']     = "";
		$gw_scr['s_stp_no_mcp']     = "";
		
		$gw_scr['s_mng_flg']    = "";
		$gw_scr['s_new_lot_id'] = "";
		$gw_scr['s_iohd_flg']   = "";
	}

        if($w_mode <= 3){
                $gw_scr['s_lp_cd']     = "";
		$gw_scr['s_lp_nm']     = "";
		$gw_scr['s_lot_rmks']  = "";

	}
	return 0;
}
#==================================================================
# ����ɽ��ľ������
#==================================================================
function scr_setting()
{
	global $gw_scr;
	global $g_mode;

	return;
}
#==================================================================
# �����������˿���ľ��
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
# ����򥷥ꥢ�饤��
#==================================================================
function userialize($w_arr)
{
	return str_replace("\"", "~", serialize($w_arr));
}
#==================================================================
# ���ꥢ�饤��������ʸ��������������
#==================================================================
function uunserialize($w_serial)
{
	return unserialize(str_replace("~", "\"", $w_serial));
}
#==================================================================
# SQL�Υ磻��ɥ����ɤ��ޤޤ�Ƥ�����硢����������ʸ��($)����Ϳ�����֤�
#==================================================================
function str_escape($str){
	return str_replace(array('%', '_', '*'), array('$%', '$_', ''), $str);
}
#==================================================================
# ���顼�����о�ʸ��������
#==================================================================
function get_tg()
{
	$w_arr = func_get_args();
	return implode("/", $w_arr);
}
#==================================================================
# Lang�ǡ��������ؿ���ά��
#==================================================================
function itm($var)
{
	return PS00S01001250_item($var);
}
function msg($var)
{
	return PS00S01001250_msg($var);
}
#******************************************************************
#******************************************************************
#******************************************************************
#******************************************************************
#******************************************************************
#
# MAIN��������
#
#******************************************************************
#==================================================================
# DB��³
#==================================================================
$w_rtn = xdb_op_conndb();
if ($w_rtn != 0) {
	$g_err_lv = 0;
	$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	return;
}
#==================================================================
# ���å����
#==================================================================
if($gw_scr['s_rtn_flg']){
	get_session_convert();
}
# ���å������Υ⡼�ɤ����
get_session_mode();
#==================================================================
# ǧ��
#==================================================================
# ��ǧ��(��Scr���� session�ޤ� ���å���������˵���)
$refe_flg=1;
require_once (getenv("GPRISM_HOME") . "/renzheng.php");
$bak_s_renzheng_t = $gw_scr['s_renzheng_t'];	# �������
$bak_s_renzheng   = $gw_scr['s_renzheng'];		# �������
#==================================================================
# �⡼�ɤ��Ȥν���
#==================================================================
# �ؿ�̾���

$w_func = "main_md" . $g_mode;
if(function_exists($w_func)){
	$w_func();
} else {
	main_init();
}

$gw_scr['s_renzheng']   = $bak_s_renzheng;		# ǧ����
$gw_scr['s_renzheng_t'] = $bak_s_renzheng_t;	# ǧ����

scr_setting();
get_screen(1, null, 1);

#==================================================================
# ������λ
#==================================================================
xdb_op_closedb();
?>

