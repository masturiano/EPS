<?
# ===============================================================================
# [DATE]  : 2005.03.25          [AUTHOR]  : DOS)Y.Kawakami
# [SYS_ID]: GPRISM              [SYSTEM]  : ��ư��ɸ��ãɣ�
# [SUB_ID]:                     [SUBSYS]  :
# [PRC_ID]:                     [PROCESS] :
# [PGM_ID]: PS00S01001960.php   [PROGRAM] : �ץ������ޥ���������Ͽ
# [MDL_ID]:                     [MODULE]  :
# -------------------------------------------------------------------------------
# [COMMENT]
#
# -------------------------------------------------------------------------------
# [UPDATE_LOG]
#
# [UPDATE_PERSON]       [UPDATE]            [COMMENT]
# ====================  ==================  =============================================
# ORDER BY�б�			2005/03/03			Oracle -> Informix�б� 		DOS)Y.Kawakami
# DOS)K.Yamamoto        ID-2007-04-04-002   ǧ�ڵ�ǽ�ե饰$refe_flg������꽤��
# -------------------------------------------------------------------------------
$g_Version = "2.0";
$g_PrgCD   = "PS00S01001960";

#===========================================
# ���ϥѥ�᡼���Ѵ�
#===========================================
if($REQUEST_METHOD == "GET"){
    $gw_scr = cnv_formstr($_GET);
} else {
    $gw_scr = cnv_formstr($_POST);
}
#===========================================
# �ܻ���
#===========================================
$g_lang_path    = $gw_scr['g_lang_path'];
$g_CharSet      = $gw_scr['g_CharSet'];
$g_usrId        = $gw_scr['usrId'];
$g_menuNo1      = $gw_scr['menuNo1'];
$g_menuNo2      = $gw_scr['menuNo2'];
$g_menuNo3      = $gw_scr['menuNo3'];
$g_menuNo4      = $gw_scr['menuNo4'];


if($g_lang_path == ""){
	# �ƥ�����
	$g_lang_path = "Lang/ja";
	$g_PrgCD = "PS00S01001960";
	$g_CharSet = "x-euc-jp";
}


#-----------------------------------------------

require_once (getenv("GPRISM_HOME") . "/DirList_pf.php");					# �ѥ��ꥹ��
require_once (getenv("GPRISM_HOME") . "/Func/Check.php");					# ���Ϸ������å��ؿ���

require_once ($g_Mfunc_dir . "/global.php");									# �����ѿ�
require_once ($g_Mfunc_dir . "/xglob_pm.php");								# �����ѿ�(��������)
require_once ($g_Mfunc_dir . "/db_op.php");									# �ģ����
require_once ($g_Mfunc_dir . "/xdb_op.php");									# �ģ���³��Ϣ
require_once ($g_Mfunc_dir . "/xpt_err_msg.php");							# ���顼��å����������ؿ�
require_once ($g_Mlang_dir . "/buttonM.php");								# �ܥ���̾��

require_once ($g_Mfunc_dir . "/xgt_cdnm.php");								# ������̾�μ����ؿ�
require_once ($g_Mfunc_dir . "/xgt_ctg.php");								# ���ƥ�������ؿ�
require_once ($g_Mfunc_dir . "/xgt_code.php");
require_once ($g_Mfunc_dir . "/xgt_prev_page.php");
require_once ($g_Mfunc_dir . "/xcnt_lev.php");								# ������٥륫����ȥ��å״ؿ�
require_once ($g_Mfunc_dir . "/xgt_dvsn.php");

require_once ($g_Gfunc_dir . "/xpt_screen.php");							# ����Ÿ���ؿ�
require_once ($g_lang_dir . "/PS00S01001960M.php");								# ����ե�����

#=============================================
# �������
#=============================================
# �����
define("MAXROW", 30);
define("PAGE_MAXROW", MAXROW);
define("PAGE_MAXCOL", 3);
# ����
define("TAG_TAG", "TG");
define("TAG_PREFIX", "TGSEM");

# ���
define("CNT_PGM_ID", 13);
define("CNT_NMFLL", 40);
define("CNT_PGMKBN", 2);
define("CNT_SSYSKBN", 2);

#===========================================
# ����������
#===========================================
xgt_prev_page($gw_scr['s_prev_page']);
if($gw_scr['s_prev_page'] == ""){
	$gw_scr['s_dsbl_prev'] = "true";
} else {
	$gw_scr['s_prev_page'] .= ".php";
}

#===========================================
# ����ǡ��������Ѵ�
#===========================================
function cnv_formstr($array)
{
    foreach($array as $k => $v){
        if(is_array($v)){
            foreach($v as $kk => $vv){
                if(get_magic_quotes_gpc()){
                    $vv = stripslashes($vv);
                }
#               $vv = htmlspecialchars($vv);
                $array[$k][$kk] = $vv;
            }
        } else {
            // ��magic_quotes_gpc = On�פΤȤ��ϥ��������ײ��
            if (get_magic_quotes_gpc()) {
                $v = stripslashes($v);
            }
#           $v = htmlspecialchars($v);
            $array[$k] = $v;
        }
    }

    return $array;
}


#=================================================
# �������
#=================================================
function PS00S01001960_init_disp($vals){

	global $gw_scr;
	global $g_msg;
	global $g_err_lv;


  $w_rtn = get_division_cd($w_dvsn_cd_opt);
        #print_r( $w_dvsn_cd_opt);
        if($w_rtn != 0){
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

	#echo "<pre>"; print_r($w_dvsn_cd_opt);echo "<pre>";  exit();

        $gw_scr['s_dvsn_cd_opt']        = $w_dvsn_cd_opt;
       #print_r( $gw_scr);
	$s_hour_cd = array(
	    "2HR" => "2HR",
	     "4HR" => "4HR",
	     "6HR" => "6HR",
	      "8HR" => "8HR",
		 "24HR" => "24HR"

	);


		 $gw_scr['s_hour_cd_opt']        = $s_hour_cd;
	#-------------------------------------------------
	# ɽ�����ܤν����
	#-------------------------------------------------
	$gw_scr['s_pgm_id'] = "";
	$gw_scr['s_name'] = "";
	$gw_scr['s_pgm_kbn'] = "";
	$gw_scr['s_sub_sys_kbn'] = "";

	#-------------------------------------------------
	# �ڡ���ʬ�ǡ�������
	#-------------------------------------------------
	$gw_scr['s_act'] = "SEARCH";
#	$gw_scr['s_inp_cd_sub'] = $gw_scr['s_inp_cd'];
#	 $gw_scr['s_dvsn_cd_opt']= $gw_scr['s_dvsn_cd'];
	$w_rtn = pgm_select($w_cnt, 'MDINIT');
	if($w_rtn != 0){
		return $w_rtn;
	}

#	list($g_msg, $g_err_lv) = PS00S01001960_msg("guid_Select");
#	$g_msg = xpt_err_msg($g_msg, "", "");


	# �⡼�ɤ򣱤ˤ���
	scr_mode_chg(1);

	return;
}

#=================================================
# �⡼�ɣ�����
#=================================================
function PS00S01001960_md1(){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;
	switch($gw_scr['s_act']){
	case "SEARCH":

		$w_rtn = input_common_check();
		if($w_rtn != 0){
			set_init_list('MD1');
			return $w_rtn;
		}


		 #scr_mode_chg(2);

		$w_rtn = PS00S01001960_md1_srch('SEARCH');
		if($w_rtn != 0){
			return $w_rtn;
		}
		break;



 case "CHECK":



foreach ($gw_scr['s_list_pgm_id']  as $val) {
if($val !='' ) {
 $old_form .= $val."***";
}
}
 $gw_scr['s_list_pgm_id_cp']=$old_form;


                #PS00S01001960_md2();
PS00S01001960_md2();

		$w_rtn = PS00S01001960_md1_srch('CHECK');
		if($w_rtn != 0){
			return $w_rtn;
		}




                break;
        # ▒ɲåܥ▒▒󲡲▒▒▒

 case "BACK":
	 PS00S01001960_init_disp("BACK");
        scr_mode_chg(1);
        break;


	case "DELETE":
		get_page_info();
		$w_rtn = PS00S01001960_md1_dlt();
		if($w_rtn != 0){
			return $w_rtn;
		}

		$w_rtn = pgm_select($w_cnt);
		if($w_rtn != 0){
			return $w_rtn;
		}

		list($g_msg, $g_err_lv) = PS00S01001960_msg("end_Delete");
		$g_msg = xpt_err_msg($g_msg, "", "");

		# �⡼�ɤ򣱤ˤ���
		scr_mode_chg(1);
		
		break;

	case "PUP":
	case "PDN":
	case "PULL":
		$w_rtn = pgm_select($w_cnt, 'MD1');
		if($w_rtn != 0){
			get_page_info();
			return $w_rtn;
		}

#		list($g_msg, $g_err_lv) = PS00S01001960_msg("guid_Select");
#		$g_msg = xpt_err_msg($g_msg, "", "");

		break;
	default:
		# �����̤���äƤ������
       /* $w_rtn = input_common_check();
        if($w_rtn != 0){
            set_init_list('MD1');
            return $w_rtn;
        }*/
        $w_rtn = PS00S01001960_md1_srch('CH');
        if($w_rtn != 0){
            return $w_rtn;
        }
        break;


		break;
	}

}

function PS00S01001960_md2(){
        global $gw_scr;
        global $g_msg;
        global $g_err_lv;
  #set_init_list();


 $w_rtn = PS00S01001960_md1_srch('CHECK');
                if($w_rtn != 0){
                        return $w_rtn;
                }

$temp_arr=array();
foreach ($gw_scr['s_list_pgm_id'] as $email) {
	/*if ($email!='' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {

		  list($g_msg, $g_err_lv) = PS00S01001960_msg("err_Email");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
}*/
	if(in_array(trim($email), $temp_arr)) {
		 $g_err_lv = 0;
		   list($g_msg, $g_err_lv) = PS00S01001960_msg("err_Email_exist");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
		}
	if ($email!='') {
	$temp_arr[]=$email;
}


}

$w_rtn = check_hrdata( $temp_arr, $r_response );
if ( $w_rtn != 0 ) {
	list($g_msg, $g_err_lv) = PS00S01001960_msg("err_chk_hr_data");
	$g_msg = xpt_err_msg($g_msg, "", __LINE__);
	return 4000;
}

if ( $r_response['status'] == "invalid" ) {
	$w_inv_ids = implode(",", $r_response['invalid_ids']);
	list($g_msg, $g_err_lv) = PS00S01001960_msg("err_chk_hr_data");
	$g_msg = xpt_err_msg($g_msg, $w_inv_ids, __LINE__);
	return 4000;
}

if($gw_scr['s_act']=="BACK"){
	PS00S01001960_md1();      
	# scr_mode_chg(1);	
}
else {
	scr_mode_chg(3);
}

	# PS00S01001960_md3();




	  
	  
	  }


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

function PS00S01001960_md3() {
        global $gw_scr;
 global $g_cpu_dts;
        global $g_low_dts;

        global $g_msg;
        global $g_err_lv;

if($gw_scr['s_act']=="BACK"){

	PS00S01001960_init_disp("BACK");
        #scr_mode_chg(0);
return 0;

}




$init_email=array();


$old_form = explode('***', $gw_scr['s_list_pgm_id_cp']);
$old_forms =array_filter ($old_form);





 $w_rtn = PS00S01001960_md1_srch('CHECK');
                if($w_rtn != 0){
                        return $w_rtn;
                }



 	db_begin();

	if($gw_scr['s_rdg_nm'] !='' && $gw_scr['s_hour_cd'] !='' ) {
        $par_id=$gw_scr['s_rdg_nm']."_".$gw_scr['s_hour_cd'];
	}



#get all the values init form the db




		  $w_where = " del_flg = '0' ";

        if($par_id!=''){
                $w_where .= "AND par_id = '".$par_id."' ";
        }


                $w_where .= "AND PAR_CLS_CD = 'P000S016' ";









 $w_select = "SELECT * "
                                . "FROM PAR_MST WHERE ";

        $w_sql = $w_select . $w_where;
        $w_stmt = db_res_set($w_sql);
     
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                db_res_free($w_stmt);
                list($g_msg, $g_err_lv) = PS00S01001960_msg("SYS_CLS");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return $w_rtn;
        }

      
        while($w_row = db_fetch_row($w_stmt)){
              $init_email[]=$w_row['PAR_TXT']; 
	}

# end of the init values









#update the emails

foreach($init_email as $evals) {

if(!in_array($evals, $gw_scr['s_list_pgm_id'])) {


  $w_table = 'PAR_MST';
                                $w_dat   = array
                                (
                                        'UPD_DTS'      => $g_cpu_dts,
                                        'USR_ID_UPD'   => $_GET['usrId'],
                                        'DEL_FLG'      => '1',
					 'UPD_LEV'              => '2'
                                );

                                $w_where = "PAR_TXT  = '" . trim($evals) . "' AND "
                                        . "PAR_CLS_CD= 'P000S016' AND "
                                        . "DEL_FLG = '0' AND "
                                        . "PAR_ID = '" . trim($par_id). "' ";

                                $w_rtn = db_update_large($w_table, $w_dat, $w_where);

                                if ($w_rtn != 0) {
                                        list($g_msg, $g_err_lv) = msg("err_Upd_SniRcvTbl");
                                        $g_msg = xpt_err_msg($g_msg, '', __LINE__);
                                        return $w_rtn;
                                }



}


}





	foreach ($gw_scr['s_list_pgm_id'] as $email) {

if($email!='') {

	
		  $w_where = " del_flg = '0' ";

        if($par_id!=''){
                $w_where .= "AND par_id = '".$par_id."' ";
        }


                $w_where .= "AND PAR_CLS_CD = 'P000S016' ";









    $w_where .=" and par_txt= '".$email."'";
 $w_select = "SELECT * "
                                . "FROM PAR_MST WHERE ";

        $w_sql = $w_select . $w_where;
        $w_stmt = db_res_set($w_sql);
     
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                db_res_free($w_stmt);
                list($g_msg, $g_err_lv) = PS00S01001960_msg("SYS_CLS");
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return $w_rtn;
        }

      
        while($w_row = db_fetch_row($w_stmt)){
              $res_email=$w_row['PAR_TXT']; 
	}


if($res_email =='') {

   $w_table = 'PAR_MST';

  $w_dat   = array
                                (
                                        'DEL_FLG'              => '0',
					 'PAR_CLS_CD'              => 'P000S016',

					'PAR_ID'  =>  $par_id,
					'DVSN_CD'  => $gw_scr['s_dvsn_cd'],
					'FCT_CD' => 'FCSEMS',
'RDG_CD' => $gw_scr['s_rdg_cd'],
'LST_NO' => '0',


					'PAR_TXT'    => $email,


'PAR_NUM' => '1',


					'CRT_DTS'              => $g_cpu_dts,
					'USR_ID_CRT'              => $_GET['usrId'],


'UPD_DTS' => '0001-01-01 00:00:00',
'USR_ID_UPD' => ' ',
					'UPD_LEV'              => '1'
                                );

    $w_rtn = db_insert($w_table, $w_dat);
                                if ($w_rtn != 0) {
                                        list($g_msg, $g_err_lv) = msg("err_Add_SniRcvTbl");
                                        $g_msg = xpt_err_msg($g_msg, '', __LINE__);
                                        return $w_rtn;
                                }


}




$w_where='';
$res_email='';
}	






/*

  if ($w_rtn != 0) {
                db_rollback(); #▒▒▒▒Хå▒
                return $w_rtn;
        }
  db_commit();
*/




}


  scr_mode_chg(4);

  if($w_rtn != 0){
                db_rollback();
                return 4000;
        }


if($w_rtn != '4000') {
 list($g_msg, $g_err_lv) = PS00S01001960_msg("end_Update");

db_commit();


}
        //exit();

        #------------------------------------------------------------------
        # ▒▒▒ߥå▒
        #------------------------------------------------------------------



}


function PS00S01001960_md4() {
	global $gw_scr;

	if($gw_scr['s_act']=="BACK"){
		$gw_scr['s_rdg_cd']=='';
		$gw_scr['s_dvsn_cd_opt']=='';
		PS00S01001960_init_disp("TEST");
		$gw_scr['s_list_pgm_id'] = "";
		scr_mode_chg(1);
	}
	else {
	        scr_mode_chg(1);
	}
}



#=================================================
# �⡼�ɣ��ʸ���������
#=================================================
function PS00S01001960_md1_srch($vals){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

#	$gw_scr['s_inp_cd_sub'] = $gw_scr['s_inp_cd'];
	$w_rtn = pgm_select($w_cnt, $vals);
	if($w_rtn != 0){
		return $w_rtn;
	}

	if($w_cnt == 0){
		list($g_msg, $g_err_lv) = PS00S01001960_msg("err_Fnd_Cd");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return 4000;
	}

	$gw_scr['s_sel_row'] = 1;

#	list($g_msg, $g_err_lv) = PS00S01001960_msg("guid_Select");
#	$g_msg = xpt_err_msg($g_msg, "", "");

	return 0;

}


#=================================================
# �⡼�ɣ��ʺ��������
#=================================================
#=================================================
# ���ϥ����å�
#=================================================
function input_common_check(){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;

	#-------------------------------------------------
	# ��ʸ���Ѵ� & �ȥ��
	#-------------------------------------------------
	$gw_scr['s_pgm_id'] = strtoupper(trim($gw_scr['s_pgm_id']));
	$gw_scr['s_name'] = trim($gw_scr['s_name']);
	$gw_scr['s_pgm_kbn'] = strtoupper(trim($gw_scr['s_pgm_kbn']));
	$gw_scr['s_sub_sys_kbn'] = strtoupper($gw_scr['s_sub_sys_kbn']);





	 if($gw_scr['s_dvsn_cd']==''){
                list($g_msg, $g_err_lv) = PS00S01001960_msg("err_Select_Div");
               # $w_tg = get_tg(PS00S01001960_item("Pgmid"), $gw_scr['s_pgm_id']);
                $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                return 4000;
        }


  if($gw_scr['s_rdg_cd']==''){
                list($g_msg, $g_err_lv) = PS00S01001960_msg("err_Select_Rid");
               # $w_tg = get_tg(PS00S01001960_item("Pgmid"), $gw_scr['s_pgm_id']);
                $g_msg = xpt_err_msg($g_msg, $w_tg, __LINE__);
                return 4000;
        }


	#-------------------------------------------------
	# �ػ�ʸ�������å�
	#-------------------------------------------------

	return 0;

}

#=================================================
# �������ܽ����
#=================================================
function set_init_list($vals){
	global $gw_scr;

	$gw_scr['s_rslt_cnt'] = "";
	$gw_scr['s_sel_page_option'] = "";
	$gw_scr['s_maxpage'] = "";
if( $vals != 'UPDATE' &&  $vals != 'CHECK' && $vals!="BACK" && $vals !='MDINIT')  {
        $gw_scr['s_list_pgm_id'] = "";
}

	$gw_scr['s_list_name'] = "";
	$gw_scr['s_list_pgm_kbn'] = "";
	$gw_scr['s_list_sub_sys_kbn'] = "";
	$gw_scr['s_list_upd_lev'] = "";



 $w_rtn = get_division_cd($w_dvsn_cd_opt);
        #print_r( $w_dvsn_cd_opt);
        if($w_rtn != 0){
                $g_msg = xpt_err_msg($g_msg, "", __LINE__);
                return 4000;
        }

        #echo "<pre>"; print_r($w_dvsn_cd_opt);echo "<pre>";  exit();

        $gw_scr['s_dvsn_cd_opt']        = $w_dvsn_cd_opt;
        #print_r( $gw_scr);*/
        $s_hour_cd = array(
            "2HR" => "2HR",
             "4HR" => "4HR",
             "6HR" => "6HR",
              "8HR" => "8HR",
                 "24HR" => "24HR"

        );
                 $gw_scr['s_hour_cd_opt']        = $s_hour_cd;


}


#=================================================
# �ץ������ޥ����θ���
# ����͡�$r_cnt	�����
#=================================================
function pgm_select(&$r_cnt , $vals){
	global $gw_scr;
	global $g_msg;
	global $g_err_lv;



	#-------------------------------------------------
	# �����
	#-------------------------------------------------
	set_init_list($vals);

/*	$w_rtn = input_common_check();
	if($w_rtn != 0){
		return $w_rtn;
	}
*/
	$w_where = "";
    // �ץ������ޥ����θ���
if( $vals != 'UPDATE' &&  $vals != 'CHECK' && $vals !='MDINIT')  {


#echo "<pre>";     print_r($gw_scr);   echo "</pre>";




if($gw_scr['s_rdg_nm'] !='' && $gw_scr['s_hour_cd'] !='') {
	$par_id=$gw_scr['s_rdg_nm']."_".$gw_scr['s_hour_cd'];

}

#echo $par_id;
#:w
#exit();

    $sql = "select pgm_id, pgm_nm_fll,
                   pgm_cls, sys_cls
              from pgm_mst ";

	$w_where = " del_flg = '0' ";

	if($par_id!=''){
		$w_where .= "AND par_id like '".$par_id."%' ";
	}

	
                $w_where .= "AND PAR_CLS_CD = 'P000S016' ";
        




    $w_where .=" and rownum<=100 ORDER BY crt_dts";

	#-------------------------------------------------
	# ������μ���
	#-------------------------------------------------
	$w_where_cnt = ereg_replace("ORDER","--ORDER",$w_where);		# ORDER BY �б� 2005/03/03
	$w_rtn = get_rslt_cnt($w_where_cnt, $w_rslt_cnt);
	if($w_rtn != 0){
		return $w_rtn;
	}
	$gw_scr['s_rslt_cnt'] = $w_rslt_cnt;

	# �ڡ����������
	$w_chg_flg = get_page_info();

	#-------------------------------------------------
	# �¥ǡ�������
	#-------------------------------------------------
	$w_select = "SELECT * "
				. "FROM PAR_MST WHERE ";

	$w_sql = $w_select . $w_where;

	# �ӣѣ̽���
	$w_stmt = db_res_set($w_sql);
	# �ӣѣ̼¹�
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		db_res_free($w_stmt);
		list($g_msg, $g_err_lv) = PS00S01001960_msg("SYS_CLS");
		$g_msg = xpt_err_msg($g_msg, "", __LINE__);
		return $w_rtn;
	}




	$cnt = 0;
	$i = 0;
	$w_end_pt = $gw_scr['s_sel_page'] * (PAGE_MAXROW * PAGE_MAXCOL);     # �ڡ����ǽ��ǡ����ݥ���
	$w_start_pt = $w_end_pt - (PAGE_MAXROW *  PAGE_MAXCOL) + 1;           # �ڡ������ϥǡ����ݥ���


$continue= $w_end_pt-36;

if($gw_scr['s_sel_page'] > 1) {
$gw_scr['s_list_pgm_id_startrow'] ='200';
#$i=$continue;

}

	while($w_row = db_fetch_row($w_stmt)){
		$cnt++;
		if($w_start_pt > $cnt){
			continue;
		}

		$i++;

		$gw_scr['s_list_pgm_id'][$i] = trim($w_row['PAR_TXT']);
		$gw_scr['s_list_name'][$i] = trim($w_row['PAR_ID']);
/*
		$gw_scr['s_list_pgm_kbn'][$i] = trim($w_row['PGM_CLS']);
		$gw_scr['s_list_sub_sys_kbn'][$i] = trim($w_row['SYS_CLS']);
		$gw_scr['s_list_upd_lev'][$i] = trim($w_row['UPD_LEV']);
*/
		if($w_end_pt == $cnt){
			break;
		}
	}

	db_res_free($w_stmt);
	$r_cnt = $cnt;
#	 $r_cnt = '200';

}
else {
 $r_cnt = '2';
}



	if($w_chg_flg != 0){
		list($g_msg, $g_err_lv) = PS00S01001960_msg("guid_ReSearch");
		$g_msg = xpt_err_msg($g_msg, "", "");
	}

	#-------------------------------------------------
	# ��������ѹ���
	#-------------------------------------------------
	$gw_scr['s_diff_pgm_id'] = $gw_scr['s_pgm_id'];
	$gw_scr['s_diff_name'] = $gw_scr['s_name'];
	$gw_scr['s_diff_pgm_kbn'] = $gw_scr['s_pgm_kbn'];
	$gw_scr['s_diff_sub_sys_kbn'] = $gw_scr['s_sub_sys_kbn'];


	return 0;


}

#=================================================
# ������
#=================================================
function get_page_info(){
	global $gw_scr;

	#-------------------------------------------------
	# ������郎�ѹ����줿��
	#-------------------------------------------------
	if($gw_scr['g_mode'] == "1"){
		$w_chg_flg = check_search_input();
	}

	#-------------------------------------------------
	# ���ٹԥ����å�
	#-------------------------------------------------
	if($gw_scr['s_row_num'] == ""){
		$gw_scr['s_row_num'] = 1;
	}

	#-------------------------------------------------
	# �ȡ�����ڡ���������
	#-------------------------------------------------
	$w_ttl_page = ceil($gw_scr['s_rslt_cnt'] / (PAGE_MAXROW * PAGE_MAXCOL));
	$gw_scr['s_maxpage'] = $w_ttl_page;

	for($i=1; $i<=$w_ttl_page; $i++){
		$gw_scr['s_sel_page_option'][$i] = $i;
	}

	#-------------------------------------------------
	# �����ȥڡ�������
	#-------------------------------------------------
	if($w_chg_flg == 0){
		get_page_no($gw_scr['s_sel_page']);
	} else {
		# ������郎�ѹ�����Ƥ������ϡ����ڡ����ܤ˥��å�
		$gw_scr['s_sel_page'] = 1;
	}

	#-------------------------------------------------
	# �ܥ���ɽ������ɽ������
	#-------------------------------------------------
	if($gw_scr['s_sel_page'] == 1){
		$gw_scr['s_pre_disabled'] = "true";
	}
	if($gw_scr['s_maxpage'] == 0 || $gw_scr['s_maxpage'] == $gw_scr['s_sel_page']){
		$gw_scr['s_nxt_disabled'] = "true";
	}

	return $w_chg_flg;

}

#=================================================
# ��������ѹ�̵ͭ�����å�
#=================================================
function check_search_input(){
	global $gw_scr;

	$w_flg = 0;

	if($gw_scr['s_act'] == "" || $gw_scr['s_act'] == "SEARCH"){
		return $w_flg;
	}

	if($gw_scr['s_pgm_id'] != $gw_scr['s_diff_pgm_id']){
		$w_flg = 1;
	}
	if($gw_scr['s_name'] != $gw_scr['s_diff_name']){
		$w_flg = 1;
	}
	if($gw_scr['s_pgm_kbn'] != $gw_scr['s_diff_pgm_kbn']){
		$w_flg = 1;
	}
	if($gw_scr['s_sub_sys_kbn'] != $gw_scr['s_diff_sub_sys_kbn']){
		$w_flg = 1;
	}


	return $w_flg;

}


#=================================================
# ������̿�����
#=================================================
function get_rslt_cnt($w_where, &$r_cnt) {
	global $g_msg;
	global $g_err_lv;

	$w_sql = "SELECT COUNT(*) CNT "
			. "FROM PAR_MST "
			. "WHERE "
			. $w_where;

	# �ӣѣ̤���Ω�򤹤�
	$w_stmt = db_res_set($w_sql);
	# �ӣѣ̼¹�
	$w_rtn = db_do($w_stmt);
	if($w_rtn != 0){
		list($g_msg, $g_err_lv) = PS00S01001960_msg("err_Sel_PgmMst");
		$g_msg = xpt_err_msg($g_msg, '', __LINE__);
		return $w_rtn;
	}

	$row = db_fetch_row($w_stmt);

	$r_cnt = (int)$row['CNT'];
# $r_cnt ="200";

	db_res_free($w_stmt);

	return 0;
}

#=================================================
# �����ȥڡ����ֹ����
#=================================================
function get_page_no(&$r_page){
	global $gw_scr;

	switch($gw_scr['s_act']){
	case "SEARCH":
		$r_page = 1;
		break;
	 case "CHECK":
                $r_page = 1;
                break;
	case "PUP":
		$r_page = $r_page - 1;
		break;
	case "PDN":
		$r_page = $r_page + 1;
		break;
	case "PULL":
	case "EDIT":
	case "INSERT":
		$r_page = $gw_scr['s_sel_page'];
		break;
	default:
		break;
	}
	return 0;
}

#=================================================
# ���顼�����о�ʸ��������
#=================================================
function get_tg($w_item, $w_dt){
	return $w_item . "/" . $w_dt;
}


function get_division_cd(&$r_dvsn_cd_opt) {

        # -- Initialize variables for query
        global $gw_scr;
        global $g_msg;
        global $g_err_lv;

        # -- SQL query for Division code and name
        $w_sql = "SELECT "
                        . "NM_FLL, "
                        . "CD "
                        . "FROM "
                                . "NM_MST "
                        . "WHERE "
                                . "TAG = 'AB' "
                                . "AND DEL_FLG = '0' "
                        . "ORDER BY CD ";

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





#=================================================
# ��������
#=================================================
$w_rtn = xdb_op_conndb();
if($w_rtn != 0){
	$g_msg = xpt_err_msg($g_msg, $g_err_lv, __LINE__);
}

if($gw_scr['s_rtn_flg']){
	get_session_convert();
}

# ���å������Υ⡼�ɤ�����ʽ�����ܻ��϶��ˤ����
get_session_mode();

# �㳰 **************************************************************
# �ץ������ޥ�����Ͽ����(PSSEM00101110)�����������Ȥ������ܤ��Ƥ������
# �������Ͽ����SCR_ID�ϡ�ǧ�ڤ�Ԥ�ʤ�
$arr_http_chk[0]='PSSEM00101110';

$http_ref=trim(getenv("HTTP_REFERER"));
for($ii=0;$ii<count($arr_http_chk);$ii++){
    if(ereg($arr_http_chk[$ii],$http_ref)){
		$refe_flg=1;		# ǧ�ڳ�ǧ��Ԥ鷺��������ǧ�ڼ���
		break;
	}
}
# *******************************************************************

#---------------------------------------------------------
# ��ǧ��(��Scr���� session�ޤ� ���å���������˵���)
# ����������ܻ�
$refe_flg = 1;
require_once (getenv("GPRISM_HOME") . "/renzheng.php");
#---------------------------------------------------------

switch($g_mode){
case 1:
	PS00S01001960_md1();
	break;

case 2:
        PS00S01001960_md2();
        break;

case 3:
        PS00S01001960_md3();
        break;
case 4:
        PS00S01001960_md4();
        break;


default:
	PS00S01001960_init_disp("TEST");
	scr_mode_chg(1);
	break;
}


get_screen();

#=================================================
# ������λ
#=================================================
xdb_op_closedb();
?>