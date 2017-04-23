<?php
# ===============================================================================
# [DATE]  : 2004.06.18          [AUTHOR]  : DOS)Fujita
# [SYS_ID]: GPRISM              [SYSTEM]  : ��ư��ɸ��ãɣ�
# [SUB_ID]: pm                  [SUBSYS]  : �����������֥����ƥ�
# [PRC_ID]:                     [PROCESS] :
# [PGM_ID]: PS00S01002490.php   [PROGRAM] : �������Ϥ���������(��Ω) 
# [MDL_ID]:                     [MODULE]  :
# -------------------------------------------------------------------------------
# [COMMENT] �ѿ����
# $g_ : ���̥����Х�,$gw_ : ñ�Υ����Х�,$s_ : �����꡼��,����¾ : ������
# -------------------------------------------------------------------------------
# [UPDATE_LOG]
#
# [UPDATE_PERSON]       [UPDATE]    [COMMENT]
# ====================  ==========  ============================================
# DOS)K.Yamamoto        2004.10.20  PF�б��Ǥ˽���
# DOS)K.Yamamoto        2004.11.19  �����Х��ѿ�������
# ZXS)T.Yamasaki        2004.12.06  ����ե�����ѥ��ѹ���$g_func_dir . "/xpt_screen.php" > $g_Gfunc_dir . "/xpt_screen.php"
# DOS)K.Yamamoto		2005.03.16	���ץ�����¸�ߤ��ʤ����Υ��顼�����ɲ�
# DOS)Y.Kawakami		2005.08.30	ǧ�ڡ������ॢ�����ȹ���
# ZXS)K.Maeda           2006.02.27	PSCID�Ѥ�PSSEM01000050�򥳥ԡ����ƺ���
# DOS)N.Nishida			2006.04.21	���Ƕ�ʬ,���Ǽ�Ģ�ֹ�κ��
# dos)doi				I120413-0068395	ID��PS00C01000121����ܿ�(��󥰿��ڤӥС�����ɽ����ǽ�ɲ�)
# -------------------------------------------------------------------------------

if ($REQUEST_METHOD == "GET") {
	$gw_scr = cnv_formstr($_GET);
} else {
	$gw_scr = cnv_formstr($_POST);
}

//�ܻ���
$g_lang_path	= $gw_scr['g_lang_path'];
$g_PrgCD	= $gw_scr['g_PrgCD'];
$g_CharSet	= $gw_scr['g_CharSet'];
$g_usrId     	= $gw_scr['usrId'];

//�ƥ�����
if(!isset($g_lang_path)){
	$g_lang_path = "/Lang/ja";
	$g_CharSet   = "EUC-JP";
	$g_PrgCD     = "PS00S01002490";
}

$g_Version = "2.0";

require_once (getenv("GPRISM_HOME") . "/DirList_pf.php");		# �ѥ��ꥹ��
require_once (getenv("GPRISM_HOME") . "/Func/Check.php");	# ���Ϸ������å��ؿ���
require_once ($g_func_dir . "/global.php");					# �����ѿ�
#require_once ($g_func_dir . "/xglob_pm.php");				# �����ѿ�(��������)
require_once ($g_func_dir . "/db_op.php");					# �ģ����
require_once ($g_func_dir . "/xdb_op.php");					# �ģ£ɥ��ͥ��ȴؿ�
require_once ($g_func_dir . "/cs_xgn_man.php");				# �桼��̾�Τγ����ؿ�
require_once ($g_func_dir . "/xgn_cd.php");					# ̾�Τγ����ؿ�
require_once ($g_func_dir . "/xgn_prd.php");				# �ץ������̾�γ����ؿ�
require_once ($g_func_dir . "/xgn_pkg.php");				# �ѥå�����̾�γ����ؿ�
require_once ($g_func_dir . "/xgt_lot.php");				# ��åȴ��ܾ���γ����ؿ�
require_once ($g_func_dir . "/xgt_nio.php");				# ���ɣϥ֥�å���������δؿ�
require_once ($g_func_dir . "/xgt_npr.php");				# ���ץ�����������δؿ�
require_once ($g_func_dir . "/xpt_err_msg.php");			# ���顼��å����������ؿ�
require_once ($g_func_dir . "/xpt_msg_html.php");			# ��å��������ϴؿ�
require_once ($g_func_dir . "/xgt_bd_dvs.php");				# ���Ƕ�ʬ�γ����ؿ�
require_once ($g_func_dir . "/iomv.php");					# verb IOMV
require_once ($g_func_dir . "/ioin.php");
require_once ($g_func_dir . "/ioot.php");
require_once ($g_func_dir . "/xck_lio.php");
require_once ($g_func_dir . "/prpt.php");					# verb PRPT
require_once ($g_func_dir . "/prpc.php");					# verb PRPC
require_once ($g_func_dir . "/prgt.php");					# verb PRGT
require_once($g_func_dir . "/xgt_stp_cls.php");                        			# for stp_cls_2

require_once ($g_func_dir . "/cs_xexc_hold_rsv.php");
require_once ($g_func_dir . "/xpt_1sec_dts.php"); #sleep
require_once ($g_lang_dir . "/buttonM.php");				# �ܥ���̾��
require_once ($g_lang_dir . "/PS00S01002490M.php");			# ��å�����
#require_once ($g_scr_dir  . "/PS00S01002490S.php");			# �����꡼�����

//require_once ($g_func_dir . "/xpt_cnv_formstr.php");		# �Хå����������Ѵ��ؿ�
require_once ($g_Gfunc_dir . "/xpt_screen.php");

// ===========================================
// ����
// ===========================================
// ɽ��ʸ��������
define("ENCDISP", "EUC-JP");
// �ǡ����١���ʸ��������
define("ENCDB", "EUC-JP");

#------------------------------------------------------------------
# ���ƥ����ʬ
#------------------------------------------------------------------
define("CE_LTINF",		"CE00S02");				# Lot Information
define("CT_TTLBAR",		"CT00S0000011");		# Total Bar qty
define("CT_TTLRING",		"CT00S0000012");		# Total Ring qty

# NG Steps
define("E9_NG_STEPS", serialize(array(
	"E911S680",  #PACKING BGA
	"E949S250", #PACKING HLD
	"E949S140", #PACKING LD
	"E931S035", #PACKING IPD
	"E931S072", #PACKING MAT
	"E931S123", #PACKING QFN
	"E931S157", #PACKING SOB
	"E941S031", #PACKING TR
	"E931S233", #PACKING QFP
)));

define("ALLOWED_E9",		"E921S021");	

// ===========================================
// ���֥롼�������
// ===========================================
function cnv_formstr($array)
{
	foreach($array as $k => $v) {
		if (is_array($v)) {
			foreach ($v as $kk => $vv) {
				if (get_magic_quotes_gpc()) {
					$vv = stripslashes($vv);
				}
//			$vv = htmlspecialchars($vv);
				$array[$k][$kk] = $vv;
			}
		} else {
			# ��magic_quotes_gpc = On�פΤȤ��ϥ��������ײ��
			if (get_magic_quotes_gpc()) {
				$v = stripslashes($v);
			}
//		$v = htmlspecialchars($v);
			$array[$k] = $v;
		}
	}

	return $array;
}

// =================================================
// �������������
// =================================================
function next_prc(&$r_lot_bas_next, &$r_lot_bas)
{
	global $g_msg;
	global $g_err_lv;
#	global $g_lot_bas;			# ��åȴ��ܾ���ǡ���(Ϣ������)
	
	// �����Х������
#	global $g_prc_cd;           # [PR]�ץ���������
#	global $g_io_blc_cd;        # [IO]�ɣϥ֥�å�������
#	global $g_stp_cd;			# [ST]���ƥåץ�����
#	global $g_stp_no;			# ���ƥå��ֹ�
	
	// ��åȾ���
	if($r_lot_bas[LOT_ST_DVS] == "OW") {
		#==========================================================
		# ���ɣϥ֥�å��������(xgt_nio)
		# ����͡�w_io_blc_cd, w_stp_cd, w_stp_no
		#==========================================================
		$rtn = xgt_nio($r_lot_bas[PRC_CD], $r_lot_bas[IO_BLC_CD], $r_lot_bas[STP_NO], $r_lot_bas[PLT_DVS_CD],
						$w_io_blc_cd, $w_stp_cd, $w_stp_no);
	} elseif($r_lot_bas[LOT_ST_DVS] == "EW") {
		#==========================================================
		# ���ץ����������(xgt_npr)
		# $w_prc_cd, $w_io_blc_cd, $w_stp_cd, $w_stp_no
		#==========================================================
		$rtn = xgt_npr($r_lot_bas[RT_CD], $r_lot_bas[PRC_CD], $r_lot_bas[PLT_DVS_CD],
						$w_prc_cd, $w_io_blc_cd, $w_stp_cd, $w_stp_no);

		# ���ץ�����¸�ߤ��ʤ���票�顼 update DOS)K.Yamamoto 2005.03.16
		if($rtn == -1){
			list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Nxt_Prc");
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return 4000;
		}
	} else {
        list($g_msg, $g_err_lv) = PS00S01002490_msg("err_LotState");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}
	if ($rtn) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $rtn;
	}
	
	// ����ͤ򻲾Ȱ����˳�Ǽ
	$r_lot_bas_next[PRC_CD]		= $w_prc_cd;
	$r_lot_bas_next[IO_BLC_CD]	= $w_io_blc_cd;
	$r_lot_bas_next[STP_NO]		= $w_stp_no;
	$r_lot_bas_next[STP_CD]		= $w_stp_cd;
	
	// ����
	return 0;
}

// =================================================
// -----�������å�����
// =================================================
// function check_input($w_mode){
// 	global $gw_scr;
// 	global $g_msg;
// 	global $g_err_lv;
// 	global $g_cpu_dts;

// 	switch ($w_mode) {
// 		#------------------------------------------------------------------
// 		# Validation for Mode 1
// 		#------------------------------------------------------------------
// 		case 1:
// 			#------------------------------------------------------------------
// 			# Assign the values
// 			#------------------------------------------------------------------
// 			$gw_scr['s_usr_id'] = strtoupper(trim($gw_scr['s_usr_id']));
// 			$gw_scr['s_lot_id'] = strtoupper(trim($gw_scr['s_lot_id']));
// 			#------------------------------------------------------------------
// 			# Display error
// 			#------------------------------------------------------------------
// 			# For empty values
// 			list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Nec_Input");
// 			if($gw_scr['s_usr_id'] == ""){
// 				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("UsrID"), __LINE__);
// 				return 4000;
// 			}
// 			if($gw_scr['s_lot_id'] == ""){
// 				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("LotID"), __LINE__);
// 				return 4000;
// 			}
// 			# For illegal characters
// 			list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Inp_Char");
// 			if(!check_eisu($gw_scr['s_usr_id'])){
// 				$w_tg = get_tg(PS00S01002490_item("UsrID"), $gw_scr['s_usr_id']);
// 				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
// 				return 4000;
// 			}
// 			if(!check_eisu($gw_scr['s_lot_id'])){
// 				$w_tg = get_tg(PS00S01002490_item("LotID"), $gw_scr['s_lot_id']);
// 				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
// 				return 4000;
// 			}
// 		break;
// 		#------------------------------------------------------------------
// 		# Validation for Mode 2
// 		#------------------------------------------------------------------
// 		case 2:
// 			#------------------------------------------------------------------
// 			# Assign the values
// 			#------------------------------------------------------------------
// 			$gw_scr['s_equip_id'] = strtoupper(trim($gw_scr['s_equip_id']));
// 			$gw_scr['s_good_qty'] = strtoupper(trim($gw_scr['s_good_qty']));
// 			$gw_scr['s_rej_qty'] = strtoupper(trim($gw_scr['s_rej_qty']));
// 			#------------------------------------------------------------------
// 			# Display error
// 			#------------------------------------------------------------------
// 			# For empty values
// 			list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Nec_Input");
// 			if($gw_scr['s_equip_id'] == ""){
// 				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("EquipmentCode"), __LINE__);
// 				return 4000;
// 			}
// 			if($gw_scr['s_good_qty'] == ""){
// 				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("GoodQuantity"), __LINE__);
// 				return 4000;
// 			}
// 			if($gw_scr['s_rej_qty'] == ""){
// 				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("RejectQuantity"), __LINE__);
// 				return 4000;
// 			}
// 			# For illegal characters
// 			list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Inp_Char");
// 			if(!check_eisu($gw_scr['s_equip_id'])){
// 				$w_tg = get_tg(PS00S01002490_item("EquipmentCode"), $gw_scr['s_equip_id']);
// 				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
// 				return 4000;
// 			}
// 			if(!check_eisu($gw_scr['s_good_qty'])){
// 				$w_tg = get_tg(PS00S01002490_item("GoodQuantity"), $gw_scr['s_good_qty']);
// 				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
// 				return 4000;
// 			}
// 			if(!check_eisu($gw_scr['s_rej_qty'])){
// 				$w_tg = get_tg(PS00S01002490_item("RejectQuantity"), $gw_scr['s_rej_qty']);
// 				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
// 				return 4000;
// 			}
// 		break;
// 	}

// 	$g_msg    = "";
// 	$g_err_lv = "";

// 	return 0;
// }
function check_input($w_mode){
	// ���ϥѥ�᡼���μ�����global����
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch ($w_mode) {
		#------------------------------------------------------------------
		# Validation for Mode 1
		#------------------------------------------------------------------
		case 1:

			// ���ϥ����ɤ���ʸ���Ѵ�
			$gw_scr['s_usr_id'] = strtoupper(trim($gw_scr['s_usr_id']));
			$gw_scr['s_lot_id'] = strtoupper(trim($gw_scr['s_lot_id']));
			
			# ��¬���顼
			if($g_msg) return 4000;


			#check if the lot id is from plating

		        #E9 code for the Tie bar step
		        $E9_array=array(
		        'E931S056',
		        'E931S142',
		        'E931S018'
		        );

			$rtn = xgt_lot($gw_scr['s_lot_id'], $w_lot_bas);
			if ($rtn) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
				return $rtn;
			}


			$w_rtn = get_next_process(trim($w_lot_bas['RT_CD']),
				trim($w_lot_bas['PRD_CD']),
				trim($w_lot_bas['STP_CD']),
				$w_next_dat);
			if($w_rtn != 0){
				return 4000;
			}

			$w_rtn = xgt_stp_cls($w_next_dat['STP_CD'], $w_stpcls2, $dmy);
			if($w_rtn != 0){
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, $w_stp_cd, __LINE__);
				return 4000;
			}

		        #$w_rtn=chk_E9_code($gw_scr['s_lot_id'],$r_Enine_chk);
		        #check the step is plating or not

		        if(in_array($w_stpcls2, $E9_array))
		        {
				list ($g_msg, $g_err_lv) = PS00S01002490_msg("err_LotStepPlating");
			        $g_msg= xpt_err_msg($g_msg,"", __LINE__);
		        	return 4000;
			}

			# �����å���Ⱦ�ѱѿ�
			if(!check_eisu($gw_scr['s_usr_id'])) {
		        list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Alphabet");
				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("UsrID"), __LINE__);
				return 4000;
			}

			# �����å�����§ʸ��
			if(!check_err_lot($gw_scr['s_lot_id'])) {
		        list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Irregular");
				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("LotID"), __LINE__);
				return 4000;
			}
			
			#==========================================================
			# �����å����Ұ�̾(cs_xgn_man)	����͡�s_usr_nm
			#==========================================================
			$rtn = cs_xgn_man($gw_scr['s_usr_id'], $gw_scr['s_usr_nm']);
			if ($rtn) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_usr_id'], __LINE__);
				return $rtn;
			}

			#==========================================================	
			# ��������åȴ��ܾ���(xgt_lot)	����͡�w_lot_bas
			#==========================================================
			/*
			$rtn = xgt_lot($gw_scr['s_lot_id'], $w_lot_bas);
			if ($rtn) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
				return $rtn;
			}
			*/

			$w_rtn = xgt_stp_cls($w_lot_bas['STP_CD'], $w_stpcls2, $dmy);
			if($w_rtn != 0){
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, trim($w_lot_bas['STP_CD']), __LINE__);
				return 4000;
			}
			
			#verify if current step is at packing, do not allow if lot is at packing.
			if(in_array($w_stpcls2,unserialize(constant("E9_NG_STEPS")))){
				list($g_msg, $g_err_lv) = PS00S01002490_msg("err_LotStepPacking");
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
			}

			//����� $w_lot_bas
			# $gw_scr['s_bd_no']		= $w_lot_bas[PKT_CD];		//�ѥ��åȥ����� [���Ǽ�Ģ�ֹ�]
			$gw_scr['s_rnk_ptn']	= $w_lot_bas[RNK_PTN];		//��󥯥åѥ�����
			//$gw_scr['s_lot_no_str']	= $w_lot_bas[LOT_NO_STR];	//�Ȼ���å��ֹ�
			$gw_scr['s_lot_no']		= $w_lot_bas[LOT_NO];		//��Ω��å��ֹ�
			$gw_scr['s_secret_no']	= $w_lot_bas[SECRET_NO];	//̩��
			$gw_scr['s_sl_qty']		= $w_lot_bas[SL_QTY];		//���饤�����
			$gw_scr['s_chp_qty']	= $w_lot_bas[CHP_QTY];		//���å׿�
			$gw_scr['s_lf_qty']		= $w_lot_bas[LF_QTY];		//�꡼�ɥե졼���
			$gw_scr['s_upd_lev']	= $w_lot_bas[UPD_LEV];		//������٥���ݻ���
			
			# �����å�����åȾ���
			if($w_lot_bas[LOT_ST_DVS] != "OW" && $w_lot_bas[LOT_ST_DVS] != "EW") {
		        list($g_msg, $g_err_lv) = PS00S01002490_msg("err_LotState");
				$g_msg = xpt_err_msg($g_msg, "", __LINE__);
				return 4000;
			}

			#==========================================================
			# �����å����ɣϥ֥�å�̾(xgn_cd)	����͡�s_io_blc_nm
			#==========================================================
				if ($rtn) {
					$g_err_lv = 0;
					$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
					return $rtn;
				}
			# $gw_scr['s_io_blc_nm'] = $g_nm;	//�����

			#==========================================================	
			# �����å����ѥå�����̾(xgn_pkg)
			# ����͡�s_pkg_cd, s_pkg_nm
			#==========================================================
			$rtn = xgn_pkg($w_lot_bas[PRD_CD],1, $gw_scr['s_pkg_cd'], $gw_scr['s_pkg_nm']);
			if ($rtn) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
				return $rtn;
			}
			# $gw_scr['s_pkg_nm'] = $g_nm;	//�����

			#==========================================================	
			# �����å����ʼ�̾(xgn_prd)	����͡�s_prd_nm,s_bnd_dvs
			#==========================================================
			$rtn = xgn_prd($w_lot_bas[PRD_CD], $gw_scr['s_prd_nm'], $gw_scr['s_bnd_dvs']);
			if ($rtn) {
				$g_err_lv = 0;
				$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
				return $rtn;
			}
			# $gw_scr['s_prd_nm'] = $g_nm;	//�����

			#	#==========================================================	
			#	# ���Ƕ�ʬ�μ���(xgt_bd_dvs)	����͡�s_bd_dvs
			#	#==========================================================
			#	$rtn = xgt_bd_dvs($w_lot_bas[PRD_CD], $gw_scr['s_bd_dvs']);
			#	if ($rtn) {
			#		$g_err_lv = 0;
			#		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
			#		return $rtn;
			#	}
			#	$gw_scr["s_bd_dvs"] = $g_bd_dvs;	//�����

			#==========================================================	
			# ����������������������ؿ���
			#==========================================================
			$rtn = next_prc($lot_bas_next, $w_lot_bas);
			if($rtn){
				return $rtn;
			}

			#==========================================================
			# �����å����ɣϥ֥�å�̾�ʼ�������(xgn_cd)
			# ����͡�s_io_blc_nm_next
			#==========================================================
			$w_rtn = get_stp_val($w_lot_bas['RT_CD'],$w_lot_bas['STP_CD'],$w_st_stp);
		        if($w_rtn != 0){
		                list($g_msg, $g_err_lv) = PS00S01002490_msg("err_sel_dvs");
		                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		                return 4000;
		        }

		        if($w_st_stp['val'] == 'S'){
		                $w_rtn = get_end_stp($w_lot_bas['RT_CD'],$w_ed_stp);
		                if($w_rtn != 0){
		                        list($g_msg, $g_err_lv) = PS00S01002490_msg("err_no_end_step");
		                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		                        return 4000;
		                }

		                $w_rtn = get_next_stp($w_lot_bas['RT_CD'], $w_ed_stp['seq_num'], $w_nxt_stp);
		                if($w_rtn != 0){
		                        list($g_msg, $g_err_lv) = PS00S01002490_msg("");
		                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
		                        return 4000;
		                }

		                $rtn = xgn_cd($w_nxt_stp['stp_cd'],1, $gw_scr['s_io_blc_nm_next']);
		                if ($rtn) {
		                        $g_err_lv = 0;
		                        $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
		                        return $rtn;
		                }

		        }else{
				$rtn = xgn_cd($lot_bas_next[IO_BLC_CD],1, $gw_scr['s_io_blc_nm_next']);
				if ($rtn) {
					$g_err_lv = 0;
					$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
					return $rtn;
				}
			}

			
			#	$gw_scr['s_io_blc_nm_next'] = $g_nm;	//�����
			
			#------------------------------------------------------------------
			# ��åȾ���ơ��֥�μ���(��󥰿�)
			#------------------------------------------------------------------
			$w_rtn = get_lotinf($gw_scr['s_lot_id'],
								constant("CE_LTINF"),
								constant("CT_TTLRING"),
								$w_ringinf);
			if($w_rtn != 0){
				return 4000;
			}
			if(count($w_ringinf) != 0){
				$gw_scr['s_ring_qty'] = $w_ringinf['CTG_DAT_VAL'];
			}
			#------------------------------------------------------------------
			# ��åȾ���ơ��֥�μ���(�С���)
			#------------------------------------------------------------------
			$w_rtn = get_lotinf($gw_scr['s_lot_id'],
								constant("CE_LTINF"),
								constant("CT_TTLBAR"),
								$w_barinf);
			if($w_rtn != 0){
				return 4000;
			}
			if(count($w_barinf) != 0){
				$gw_scr['s_bar_qty'] = $w_barinf['CTG_DAT_VAL'];
			}
		break;
		#------------------------------------------------------------------
		# Validation for Mode 2
		#------------------------------------------------------------------
		case 2:
			#------------------------------------------------------------------
			# Assign the values
			#------------------------------------------------------------------
			$gw_scr['s_equip_id'] = strtoupper(trim($gw_scr['s_equip_id']));
			$gw_scr['s_good_qty'] = strtoupper(trim($gw_scr['s_good_qty']));
			$gw_scr['s_rej_qty'] = strtoupper(trim($gw_scr['s_rej_qty']));
			#------------------------------------------------------------------
			# Display error
			#------------------------------------------------------------------
			# For empty values
			list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Nec_Input");
			if($gw_scr['s_equip_id'] == ""){
				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("EquipmentCode"), __LINE__);
				return 4000;
			}
			if($gw_scr['s_good_qty'] == ""){
				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("GoodQuantity"), __LINE__);
				return 4000;
			}
			if($gw_scr['s_rej_qty'] == ""){
				$g_msg = xpt_err_msg($g_msg, PS00S01002490_item("RejectQuantity"), __LINE__);
				return 4000;
			}
			# For illegal characters
			list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Inp_Char");
			if(!check_eisu($gw_scr['s_equip_id'])){
				$w_tg = get_tg(PS00S01002490_item("EquipmentCode"), $gw_scr['s_equip_id']);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			if(!check_eisu($gw_scr['s_good_qty'])){
				$w_tg = get_tg(PS00S01002490_item("GoodQuantity"), $gw_scr['s_good_qty']);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
			if(!check_eisu($gw_scr['s_rej_qty'])){
				$w_tg = get_tg(PS00S01002490_item("RejectQuantity"), $gw_scr['s_rej_qty']);
				$g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
				return 4000;
			}
		break;
	}
	$g_msg    = "";
	$g_err_lv = "";

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



// =================================================
// -----�����������å�����
// =================================================
function check_update()
{
	// ���ϥѥ�᡼���μ�����global����
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	
	# �����Х������
#	global $g_lot_bas;			# ��åȴ��ܾ���ǡ���(Ϣ������)
	
	# Verb�� �������
#	global $g_lot_id;			# [LT]��åȣɣ�
#	global $g_usr_id;			# [MA]�桼�����ɣ�
#	global $g_prc_cd;           # [PR]�ץ��������� #PRPC��
#	global $g_io_blc_cd;        # [IO]�ɣϥ֥�å�������
#	global $g_stp_cd;           # [ST]���ƥåץ�����
#	global $g_stp_no;           # ���ƥå��ֹ�
#	global $g_upd_lev;			# ������٥� 
#	global $g_cmt;     			# ������ 
	
	# ��¬���顼
	if($g_msg) return 4000;

	#==========================================================	
	# ��������åȴ��ܾ���(xgt_lot)	����͡�w_lot_bas
	#==========================================================
	$rtn = xgt_lot($gw_scr['s_lot_id'], $w_lot_bas);
	if ($rtn) {
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
		return $rtn;
	}
	//����� $w_lot_bas

	#==========================================================
	# ����������������������ؿ���
	#==========================================================
	$rtn = next_prc($lot_bas_next, $w_lot_bas);
	if($rtn){
		return $rtn;
	}
	
	# Verb�� ��åȴ��ܾ��󥻥å�
#	$g_usr_id		= $gw_scr['s_usr_id'];
#	$g_lot_id		= $gw_scr['s_lot_id'];
#	$g_prc_cd		= $lot_bas_next[PRC_CD];
#	$g_io_blc_cd	= $lot_bas_next[IO_BLC_CD];
#	$g_stp_cd		= $lot_bas_next[STP_CD];
#	$g_stp_no		= $lot_bas_next[STP_NO];
#	$g_upd_lev		= $gw_scr['s_upd_lev'];		//MODE1-2�ݻ�������٥�
#	$g_cmt			= NULL;
	
	# �ȥ�󥶥������γ���
	db_begin();
	
	#Sql Debug��
	global $st_sql;

	$w_cmt = NULL;

	// ��åȾ���
	if($w_lot_bas[LOT_ST_DVS] == "OW") {
		#==========================================================
		# �֣���ʣɣϣͣ֡ˤε�ư	����͡�w_lot_bas
		#==========================================================
	
		if($gw_scr['s_upd_lev']!=$w_lot_bas['UPD_LEV']){
			$g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, "err_upd_lev", __LINE__);
                        return 4000;
		}

		$w_rtn = extra_iomv($w_lot_bas['RT_CD'],$w_lot_bas['STP_CD'], $w_lot_bas);
                if($w_rtn != 0){
                        $g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
                }
		$gw_scr['s_upd_lev']=$w_lot_bas['UPD_LEV'];	

		$rtn = next_prc($lot_bas_next, $w_lot_bas);
        	if($rtn){
                	return $rtn;
        	}
	
		$rtn = iomv($gw_scr['s_lot_id'], $gw_scr['s_usr_id'], $gw_scr['s_upd_lev'], $w_cmt,
						$lot_bas_next['IO_BLC_CD'], $lot_bas_next['STP_CD'], $lot_bas_next['STP_NO'],
						$w_lot_bas);
		if ($rtn) {
			db_rollback();			# ����Хå�
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return $rtn;
		}
		
	} elseif($w_lot_bas[LOT_ST_DVS] == "EW") {
		#==========================================================
		# �֣���ʣУңУԡˤε�ư	����͡�w_lot_bas
		#==========================================================
		$rtn = prpt($gw_scr['s_lot_id'], $gw_scr['s_usr_id'], $gw_scr['s_upd_lev'], $w_cmt,
						$w_lot_bas);
		if ($rtn) {
			db_rollback();			# ����Хå�
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return $rtn;
		}
		
		# ������٥� ����
#		$g_upd_lev = $g_lot_bas[UPD_LEV];

		#==========================================================		
		# �֣���ʣУңУáˤε�ư	����͡�w_lot_bas
		#==========================================================
		$rtn = prpc($gw_scr['s_lot_id'], $gw_scr['s_usr_id'], $w_lot_bas['UPD_LEV'], $w_cmt,
						$lot_bas_next['PRC_CD'], $lot_bas_next['IO_BLC_CD'], $lot_bas_next['STP_CD'],
						$lot_bas_next['STP_NO'], $w_lot_bas);
		if ($rtn) {
			db_rollback();			# ����Хå�
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return $rtn;
		}
		
		# ������٥� ����
#		$g_upd_lev = $g_lot_bas[UPD_LEV];

		#==========================================================		
		# �֣���ʣУңǣԡˤε�ư	����͡�w_lot_bas
		#==========================================================
		$rtn = prgt($gw_scr['s_lot_id'], $gw_scr['s_usr_id'], $w_lot_bas['UPD_LEV'], $w_cmt,
						$w_lot_bas);
		if ($rtn) {
			db_rollback();			# ����Хå�
			$g_err_lv = 0;
			$g_msg = xpt_err_msg($g_msg, "", __LINE__);
			return $rtn;
		}
		
	} else {
		db_rollback();			# ����Хå�
        list($g_msg, $g_err_lv) = PS00S01002490_msg("err_LotState");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}
	
	#-*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*-
	# STD-810-2011-03-25-002
	# ͽ��ۡ����
	#-*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*--*-
	$w_rtn = cs_xexc_hold_rsv($gw_scr['s_usr_id'],
								$w_lot_bas,
								$w_hold_exc_flg,
								$w_set_day,
								$w_rsn,
								$w_tel);
	if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	### �ۡ���ɻ��Υ�å�����
	if($w_hold_exc_flg == 1){
		list($w_hdmsg, $w_hdlv) = PS00S01002490_msg("End_Rsv_Hold");
		$w_hdmsg .= "<br>";
		$w_hdmsg .= PS00S01002490_item("RsvHoldInfo");
		$w_hdmsg = sprintf($w_hdmsg, $w_rsn, $w_tel, $w_set_day);

		$g_err_lv = $w_hdlv;
		$g_msg    = $w_hdmsg;
	}

	# �ȥ�󥶥������ν�λ
	db_commit();
	
	// ����
	return 0;
}

function extra_iomv($w_rt_cd, $w_curr_stp, &$w_lot_bas){	
        global $g_mst;
        global $g_err_lv;
        global $gw_scr;

        $w_usr_id = $gw_scr['s_usr_id'];

        $w_rtn = get_stp_val($w_rt_cd,$w_curr_stp,$w_st_stp);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = PS00S01002490_msg("err_sel_dvs");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        if($w_st_stp['val'] == 'S'){
                $w_rtn = get_end_stp($w_rt_cd,$w_ed_stp);
                if($w_rtn != 0){
                        list($g_msg, $g_err_lv) = PS00S01002490_msg("err_no_end_step");
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
                }

                $w_rtn  = get_stp_cds($w_rt_cd, $w_st_stp['seq_num'], $w_ed_stp['seq_num'], $w_stp_cds);
                if($w_rtn != 0){
                        list($g_msg, $g_err_lv) = PS00S01002490_msg("err_sel_stps");
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
                }

                foreach($w_stp_cds as $w_stp_cd){
                        $w_rtn = main_verb_iomv($w_usr_id, "", $w_lot_bas);
                        if($w_rtn != 0){
                                list($g_msg, $g_err_lv) = PS00S01001280_msg("err_iomv");
                                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                                return 4000;
                        }

                        sleep(1);
                        xpt_1sec_dts();

                        $w_rtn  = get_equ_cd($w_lot_bas['STP_CD'],$w_equ_cd);
                        if($w_rtn != 0){
                                list($g_msg, $g_err_lv) = PS00S01001280_msg("err_sel_equ_cd");
								 $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                                return 4000;
                        }

                        $w_rtn = main_ioin_verb($w_usr_id, $w_equ_cd, " ", $w_lot_bas);
                        if($w_rtn != 0){
                                list($g_msg, $g_err_lv) = PS00S01001280_msg("err_ioin");
                                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                                return 4000;
                        }


                        $w_ctg_dvs_cd = array();
                        $w_ctg_cd = array();
                        $w_ctg_qty = array();
                        $w_ctg_dat_txt = array();
                        $w_ctg_slid = array();
                        $w_sl_qty_ok = $w_lot_bas['SL_QTY'];
                        $w_chp_qty_ok = $w_lot_bas['CHP_QTY'];
                        $w_lf_qty_ok = $w_lot_bas['LF_QTY'];

                        $w_rtn = main_verb_ioot($w_usr_id, $w_ctg_dvs_cd, $w_ctg_cd,$w_ctg_qty, $w_ctg_dat_txt,
                                                $w_ctg_slid, $w_sl_qty_ok,$w_chp_qty_ok, $w_lf_qty_ok, " ", $w_lot_bas);
                        if($w_rtn != 0){
                                list($g_msg, $g_err_lv) = PS00S01001280_msg("err_ioot");
                                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                                return 4000;
                        }
                }
        }

        return 0;
}

function get_equ_cd($w_stp_cd, &$r_equ_cd)
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
                list($g_msg, $g_err_lv) = msg("err_no_equ_cd");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        $r_equ_cd = trim($w_row['EQU_CD']);

        return 0;
}

function get_stp_val($w_rt_cd,$w_stp_cd, &$r_dat){
        global $g_msg;
        global $g_err_lv;

        #------------------------------------------------------------------
        # ���㡼���������
        #------------------------------------------------------------------
        $w_sql = <<<SQL
select SEQ_NO_RT,flw.SRIS_DVS_1 from prd_org_mst org LEFT OUTER JOIN prc_flw_mst flw ON
(org.prc_cd = flw.prc_cd and org.stp_cd=flw.stp_cd and flw.del_flg = 0 ) where org.rt_cd='{$w_rt_cd}'
and org.del_flg = '0' and org.stp_cd='{$w_stp_cd}' order by SEQ_NO_RT
SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        $r_seq_num = 0;
        while($w_row = db_fetch_row($w_stmt)){
                $r_dat['seq_num'] = trim($w_row['SEQ_NO_RT']);
                $r_dat['val'] = trim($w_row['SRIS_DVS_1']);
        }

        db_res_free($w_stmt);

        return 0;
}

function get_end_stp($w_rt_cd, &$r_dat)
{
        global $g_msg;
        global $g_err_lv;
        #------------------------------------------------------------------
        # ���㡼���������
        #------------------------------------------------------------------
        $w_sql = <<<SQL
select SEQ_NO_RT,org.stp_cd from prd_org_mst org LEFT OUTER JOIN prc_flw_mst flw ON
(org.prc_cd = flw.prc_cd and org.stp_cd=flw.stp_cd and flw.del_flg = 0 )
where org.rt_cd='{$w_rt_cd}' and org.del_flg = '0' and flw.SRIS_DVS_1 = 'E' order by SEQ_NO_RT
SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        $r_seq_num = 0;
        if($w_row = db_fetch_row($w_stmt)){
                $r_dat['seq_num'] = trim($w_row['SEQ_NO_RT']);
                $r_dat['stp_cd'] = trim($w_row['STP_CD']);
        }

        db_res_free($w_stmt);

        return 0;
}

function get_next_stp($w_rt_cd, $w_end_seq, &$r_dat)
{
        global $g_msg;
        global $g_err_lv;
        #------------------------------------------------------------------
        # ���㡼���������
        #------------------------------------------------------------------
        $w_sql = <<<SQL
select SEQ_NO_RT,org.stp_cd from prd_org_mst org LEFT OUTER JOIN prc_flw_mst flw ON
(org.prc_cd = flw.prc_cd and org.stp_cd=flw.stp_cd and flw.del_flg = 0 )
where org.rt_cd='{$w_rt_cd}' and org.del_flg = '0'  
and seq_no_rt > {$w_end_seq} order by SEQ_NO_RT
SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        $r_seq_num = 0;
        $w_row = db_fetch_row($w_stmt);
        $r_dat['seq_num'] = trim($w_row['SEQ_NO_RT']);
        $r_dat['stp_cd'] = trim($w_row['STP_CD']);
        
        db_res_free($w_stmt);

        return 0;
}


function get_stp_cds($w_rt_cd, $w_st_seq, $w_ed_seq, &$r_stp_cds)
{
        global $g_msg;
        global $g_err_lv;

        #------------------------------------------------------------------
        # ���㡼���������
        #------------------------------------------------------------------
        $w_sql = <<<SQL
select org.stp_cd from prd_org_mst org LEFT OUTER JOIN prc_flw_mst flw ON (org.prc_cd = flw.prc_cd and
org.stp_cd=flw.stp_cd and flw.del_flg = 0 ) where org.rt_cd='{$w_rt_cd}' and org.del_flg = '0' and seq_no_rt > '{$w_st_seq}'
and seq_no_rt <= '{$w_ed_seq}' order by SEQ_NO_RT
SQL;

        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Sel");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        $r_stp_cds = array();
        while($w_row = db_fetch_row($w_stmt)){
				$r_stp_cds[] = trim($w_row['STP_CD']);
        }

        db_res_free($w_stmt);

        return 0;
}

function main_ioin_verb($w_usr_id, $w_equ_cd, $w_cmt, &$w_lot_bas)
{
        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        #------------------------------------------------------------------
        # check Lot state
        #------------------------------------------------------------------
        $w_rtn = ioin_st_check($w_lot_bas);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }
        if($w_equ_cd == ""){
                #------------------------------------------------------------------
                # get usable equ_cd
                #------------------------------------------------------------------
                $w_rtn = xgt_use_equ($w_lot_bas, $w_equ_cd);
                if($w_rtn != 0){
                        $g_err_lv = 0;
                        $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                        return 4000;
                }
        }

        #------------------------------------------------------------------
        # check equ_cd
        #------------------------------------------------------------------
        $w_rtn = ioin_equ_check($w_equ_cd, $w_lot_bas);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # IOIN
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

function main_verb_ioot($w_usr_id, $w_ctg_dvs_cd, $w_ctg_cd,
                                                $w_ctg_qty, $w_ctg_dat_txt, $w_ctg_slid, $w_sl_qty_ok,
                                                $w_chp_qty_ok, $w_lf_qty_ok, $w_cmt, &$w_lot_bas)
{
        global $g_msg;
        global $g_err_lv;

        #------------------------------------------------------------------
        # check Lot state
        #------------------------------------------------------------------
        $w_rtn = ioot_st_check($w_lot_bas['LOT_ST_DVS']);
        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # check last io block
        # return:       $w_lot_st_dvs   lot state division
        #                       $w_io_blc_cd    io block code
        #                       $w_stp_cd               step code
        #                       $w_stp_no               step no
        #------------------------------------------------------------------
        $w_rtn = xck_lio(
                                        $w_lot_bas['PRC_CD'],
                                        $w_lot_bas['IO_BLC_CD'],
                                        $w_lot_bas['PLT_DVS_CD'],
                                        $w_lot_st_dvs,
                                        $w_io_blc_cd,
                                        $w_stp_cd,
                                        $w_stp_no);

        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, '', __LINE__);
                return 4000;
        }

        #------------------------------------------------------------------
        # set registration details into CTG_LOG
        #------------------------------------------------------------------
        $w_ctg_flg = 0;
        if(is_array($w_ctg_cd)){
                for($i=1; $i<=count($w_ctg_cd); $i++){
                        $w_arr_cnt = $i;
                        $w_arr_ctg_dvs_cd[$i] = $w_ctg_dvs_cd[$i];
                        $w_arr_ctg_cd[$i]        = $w_ctg_cd[$i];
                        $w_arr_txt[$i]          = $w_ctg_dat_txt[$i];
                        $w_arr_equ_cd[$i]        = $w_lot_bas['EQU_CD'];
                        $w_arr_sl_id[$i]          = $w_ctg_slid[$i];
                        $w_arr_qty[$i]          = $w_ctg_qty[$i];
                }
                $w_ctg_flg = 1;
        }

        if($w_ctg_flg == 0){
                $w_arr_cnt = 0;
                $w_arr_ctg_dvs_cd = '';
                $w_arr_ctg_cd    = '';
                $w_arr_equ_cd    = '';
                $w_arr_sl_id      = '';
                $w_arr_qty              = '';
                $w_arr_txt              = '';
        }

        #------------------------------------------------------------------
        # IOOT
        #------------------------------------------------------------------
        $w_rtn = ioot(
                                $w_lot_bas['LOT_ID'],                   # Lot ID
                                $w_usr_id,                                              # User ID
                                $w_lot_bas['UPD_LEV'],                  # Update lev
                                $w_cmt,                                                 # Comment
                                $w_lot_st_dvs,                                  # Lot state division
                                $w_sl_qty_ok,                                   # pass slice qty
                                $w_chp_qty_ok,                                  # pass chip qty
                                $w_lf_qty_ok,                                   # pass lf qty
                                $w_lot_bas['SECRET_NO'],                # date code
                                $w_arr_cnt,                                             # category count
                                $w_arr_ctg_dvs_cd,                              # (array)category division code
                                $w_arr_ctg_cd,                                  # (array)category code
                                $w_arr_equ_cd,                                  # (array)equ_cd for ctg_log
                                $w_arr_sl_id,                                   # (array)slice ID for ctg_log
                                $w_arr_qty,                                             # category qty
                                $w_arr_txt,                                             # category text
                                $w_lot_bas);                                    # [return]lot_bas_tbl

        if($w_rtn != 0){
                $g_err_lv = 0;
                $g_msg = xpt_err_msg($g_msg, '', __LINE__);
                return 4000;
        }

        return 0;
}

function main_verb_iomv($w_usr_id, $w_cmt, &$w_lot_bas)
{
        global $g_msg;
        global $g_err_lv;

        switch($w_lot_bas['LOT_ST_DVS']){
        case "OW":
                #------------------------------------------------------------------
                # get next io block
                #------------------------------------------------------------------
                $w_rtn = xgt_nio(
                                                $w_lot_bas['PRC_CD'],
                                                $w_lot_bas['IO_BLC_CD'],
                                                $w_lot_bas['STP_NO'],
                                                $w_lot_bas['PLT_DVS_CD'],
                                                $w_io_blc_cd,                           # [return]next io block code
                                                $w_stp_cd,                                      # [return]next step code
                                                $w_stp_no);                                     # [return]next step no


                $w_nxt_verb = "IOMV";

                break;
        case "EW":
                #------------------------------------------------------------------
                # get next process
                #------------------------------------------------------------------
                $w_rtn = xgt_npr(
                                                $w_lot_bas['RT_CD'],
                                                $w_lot_bas['PRC_CD'],
                                                $w_lot_bas['PLT_DVS_CD'],
                                                $w_prc_cd,                                      # [return]next process code
                                                $w_io_blc_cd,                           # [return]next io block code
                                                $w_stp_cd,                                      # [return]next step code
                                                $w_stp_no);                                     # [return]next step no

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
                # IOMV
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
                # PRPT
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
                # PRPC
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
                # PRGT
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
# ��åȾ���ơ��֥�μ���
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
		list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "LOT_INF_TBL", __LINE__);
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

function main_init()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	# Mode 1 changes	
	scr_mode_chg(1);

	return 0;
}

#==================================================================
# MODE 1
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
		case "CHECK";
			$w_rtn = check_input(1);
			if($w_rtn != 0){
				return $w_rtn;
			}
			else{
				$rtn = xgt_lot($gw_scr['s_lot_id'], $w_lot_bas);
			        if ($rtn) {
			                $g_err_lv = 0;
			                $g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
			                return $rtn;
			        }

				$w_rtn = get_next_process(trim($w_lot_bas['RT_CD']),
					trim($w_lot_bas['PRD_CD']),
					trim($w_lot_bas['STP_CD']),
					$w_next_dat
				);
				if($w_rtn != 0){
					return 4000;
				}

				// valid step code ST21S0000021 result to valid class
			        //$w_rtn = xgt_stp_cls('ST21S0000021', $w_stpcls2, $dmy);
			        $w_rtn = xgt_stp_cls($w_lot_bas['STP_CD'], $w_stpcls2, $dmy);
			        if($w_rtn != 0){
			                $g_err_lv = 0;
			                $g_msg = xpt_err_msg($g_msg, $w_stp_cd, __LINE__);
			                return 4000;
			        }

			        //echo "STEP CODE: ". $w_lot_bas['STP_CD'] . "<br/>";
			        //echo "STEP CLASS: ". $w_stpcls2;

			        if($w_stpcls2 != ALLOWED_E9){
			        	list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Allow_e9");
					$g_msg = xpt_err_msg($g_msg, $w_stpcls2, __LINE__);
					return 4000;
			        }

			        # Display Step Name
			        $w_rtn = getStepName($w_stpcls2,$result);
				if($w_rtn != 0){
					$g_err_lv = 0;
					$g_msg = xpt_err_msg($g_msg, "", __LINE__);
					return 4000;
				}
				$result_count = count($result);
				if($result_count > 0){
					$gw_scr['s_io_blc_nm'] = $result['STP_NM_FLL'];					
				}
				
			        # Display Diffusion Lot No
			        $gw_scr['s_lot_no_str'] = $w_lot_bas[LOT_NO_STR];

			        # Display Type Name
			        $rtn = xgn_prd($w_lot_bas[PRD_CD], $gw_scr['s_prd_nm'], $gw_scr['s_bnd_dvs']);
				if ($rtn) {
					$g_err_lv = 0;
					$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
					return $rtn;
				}

				# Display Package Name
				$rtn = xgn_pkg($w_lot_bas[PRD_CD],1, $gw_scr['s_pkg_cd'], $gw_scr['s_pkg_nm']);
				if ($rtn) {
					$g_err_lv = 0;
					$g_msg = xpt_err_msg($g_msg, $gw_scr['s_lot_id'], __LINE__);
					return $rtn;
				}

				# Change to mode 2
				scr_mode_chg(2);
			}
			
		break;
	}

	return 0;
}

function getStepName($step_class,&$result){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	$w_sql = <<<_SQL
		select
			stp_nm_fll 
		from 
			stp_mst 
		where 
			stp_cls_2 = '{$step_class}'
_SQL;
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "STP_MST", __LINE__);
		return 4000;
	}
	$result = array();
	if ($w_row = db_fetch_row($w_stmt)) {
		$result_row = array(
			"STP_NM_FLL" => $w_row['STP_NM_FLL'],
		);
	}
	$result = $result_row;
	db_res_free($w_stmt);

	return 0;
}
#==================================================================
# MODE 2
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
		case "CHECK";
			$w_rtn = check_input(2);
			if($w_rtn != 0){
				return $w_rtn;
			}
			else{
				# For row data
				$w_rtn = getPicturePerusal($gw_scr['s_lot_id'],$result);
				if($w_rtn != 0){
					$g_err_lv = 0;
					$g_msg = xpt_err_msg($g_msg, "", __LINE__);
					return 4000;
				}
				$result_count = count($result);
				if($result_count > 0){
					$s_equip_id = strtoupper(trim($gw_scr['s_equip_id']));
					$s_good_qty =  strtoupper(trim($gw_scr['s_good_qty']));
					$s_rej_qty =  strtoupper(trim($gw_scr['s_rej_qty']));
					$s_input_qty = ($s_good_qty + $s_rej_qty);
					
					$equ_cd = strtoupper(trim($result['EQU_CD']));
					$good_qty = strtoupper(trim($result['GOOD_QTY']));
					$input_qty = strtoupper(trim($result['INPUT_QTY']));
					$reject_qty = strtoupper(trim($result['REJECT_QTY']));
					if($s_good_qty != $good_qty || $s_rej_qty != $reject_qty || $s_input_qty != $input_qty){
						list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Not_Match");
						$g_msg = xpt_err_msg($g_msg, $s_equip_id, __LINE__);
						return 4000;
					}
					else{
						list($g_msg, $g_err_lv) = PS00S01002490_msg("comp_CHECK");
					}
					scr_mode_chg(3);
				}
			}
		break;
		case "BACK";
			main_init();
			set_init(2);
			scr_mode_chg(1);
		break;
	}

	return 0;
}

#==================================================================
# MODE 3
#==================================================================
function main_md3()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){
		case "EXECUTE";
			check_update();
			list($g_msg, $g_err_lv) = PS00S01002490_msg("comp_UPDATE");
			scr_mode_chg(4);
		break;
		case "BACK";
			main_init();
			scr_mode_chg(2);
		break;
	}
	return 0;
}
#==================================================================
# MODE 4
#==================================================================
function main_md4()
{
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	switch($gw_scr['s_act']){
		case "BACK";
			main_init();
			set_init(1);
			scr_mode_chg(1);
		break;
	}
	return 0;
}

function getPicturePerusal($lot_id,&$result){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	// LT31S145190036
	$w_sql = <<<_SQL
		select 
			ll.equ_cd, 
			ll.chp_qty input_qty, 
			ll.chp_qty_t good_qty,
			(ll.chp_qty - ll.chp_qty_t) reject_qty,
			ll.lot_id lot_id,
			sm.stp_cls_2 step_class
		from 
			lot_log ll
		join 
			stp_mst sm on sm.del_flg = 0
			and sm.stp_cls_2 = 'E921S021' 
			and sm.stp_cd = ll.stp_cd
		where 
			ll.del_flg=0
			and ll.lot_id='{$lot_id}'
			and ll.verb='IOOT'									
_SQL;
	$w_stmt = db_res_set($w_sql);
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = PS00S01002490_msg("err_Sel");
		$g_msg = xpt_err_msg($g_msg, "PAR_MST", __LINE__);
		return 4000;
	}
	$result = array();
	if ($w_row = db_fetch_row($w_stmt)) {
		$result_row = array(
			"EQU_CD" => $w_row['EQU_CD'],
			"INPUT_QTY" => $w_row['INPUT_QTY'],
			"GOOD_QTY" => $w_row['GOOD_QTY'],
			"REJECT_QTY" => $w_row['REJECT_QTY'],
		);
	}
	$result = $result_row;
	db_res_free($w_stmt);

	return 0;
}
#==================================================================
# SET INITIAL DISPLAY
#==================================================================
function set_init($w_mode)
{
	global $gw_scr;
	# Clear
	if($w_mode == 1){
		# Clear user and lot
		$gw_scr['s_usr_id'] = "";
		$gw_scr['s_usr_nm'] = "";
		$gw_scr['s_lot_id'] = "";
		# Clear filtering
		$gw_scr['s_equip_id'] = "";
		$gw_scr['s_good_qty'] = "";
		$gw_scr['s_rej_qty'] = "";
		# Display Data
		$gw_scr['s_io_blc_nm'] = "";
		$gw_scr['s_pkg_nm'] = "";
		$gw_scr['s_prd_nm'] = "";
		$gw_scr['s_lot_no_str'] = "";
		
	}
	if($w_mode == 2){
		# Clear filtering
		$gw_scr['s_equip_id'] = "";
		$gw_scr['s_good_qty'] = "";
		$gw_scr['s_rej_qty'] = "";
		# Display Data
		$gw_scr['s_io_blc_nm'] = "";
		$gw_scr['s_pkg_nm'] = "";
		$gw_scr['s_prd_nm'] = "";
		$gw_scr['s_lot_no_str'] = "";
		
	}
	return 0;
}
#==================================================================
# ���顼�����о�ʸ��������
#==================================================================
function get_tg()
{
	$w_arr = func_get_args();
	return implode("/", $w_arr);
}

// =================================================
// -----���ȥåײ���
// =================================================
function main_input_fuc($clr_flg = false)
{

	global $g_mode;
	global $gw_scr;

	if($clr_flg == true){
		$gw_scr = array();
	}
	
	# MODE1����ɽ��
	scr_mode_chg(0);

	return;
	
}

// =================================================
// -----�������å�����
// =================================================
function main_read_fuc()
{
	global $g_msg;
	global $g_err_lv;
	
	//���ϥ����å�
	if (check_input()) {
		# MODE1���顼����ɽ��
	} else {
		# MODE2����ɽ��
		if(!$g_msg){
			list($g_msg, $g_err_lv) = PS00S01002490_msg("comp_CHECK");
		}
		scr_mode_chg(1);
	}
}

// =================================================
// -----����λ����
// =================================================
function main_update_fuc()
{
	global $g_msg;
	global $g_err_lv;
	
	// ���ϥ����å�
	if (check_update()) {
		# MODE2���顼����ɽ��
	} else {
		# MODE3����ɽ��
		if(!$g_msg){
			list($g_msg, $g_err_lv) = PS00S01002490_msg("comp_UPDATE");
		}
		scr_mode_chg(2);
	}
	
}

// =================================================
// ���̾�������
// =================================================
function scr_initialize()
{
    global $gw_scr;

    # ���̽����
    foreach($gw_scr as $key => $val){
        if($key == "s_usr_id" || $key == "s_lot_id"){
            continue;
        }
        $gw_scr[$key] = "";
    }


}



// =================================================
// ����������
// =================================================
// DB��³
$gw_rtn = xdb_op_conndb();
if($gw_rtn){
	$g_err_lv = 0;
	$g_msg = xpt_err_msg($g_msg, "", __LINE__);
}

#---------------------------------------------------------
# ǧ��(��Scr���� �����ॢ���ȡ�¾���̤���θƤӽФ����б�)
$refe_flg=1;
require_once (getenv("GPRISM_HOME") . "/renzheng.php");
$bak_s_renzheng_t = $gw_scr['s_renzheng_t'];    # �������
$bak_s_renzheng = $gw_scr['s_renzheng'];        # �������
#---------------------------------------------------------

// -- �ڡ������Ƥ�ɽ��
// if(!isset($g_mode)){
//     main_input_fuc(true);
// } else {
//     switch($g_mode){
//     case 0:
//         if($gw_scr['s_act'] == "erase"){
//             main_input_fuc(true);
//         } else {
//             main_read_fuc();
//             if($g_err_lv == 0){
//                 scr_initialize();
//             }
//         }
//         break;
//     case 1:
//         if($gw_scr['s_act'] == "erase"){
//             main_read_fuc();
//         } elseif($gw_scr['s_act'] == "return"){
//             scr_initialize();
//             scr_mode_chg(0);
//         } else {
//             main_update_fuc();
//         }
//         break;

//     default:
//         main_input_fuc(true);
//         break;
//     }
// }

$w_func = "main_md" . $g_mode;
if(function_exists($w_func)){
	$w_func();
} else {
	main_init();
}

$gw_scr['s_renzheng'] = $bak_s_renzheng;        # ǧ����
$gw_scr['s_renzheng_t'] = $bak_s_renzheng_t;    # ǧ����

get_screen();

// DB����
if(!$gw_rtn) xdb_op_closedb();
?>
