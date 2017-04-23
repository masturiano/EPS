<?
# ===============================================================================
# [DATE]  : 2005.03.25          [AUTHOR]  : DOS)Y.Kawakami
# [SYS_ID]: GPRISM              [SYSTEM]  : 非自動化標準ＣＩＭ
# [SUB_ID]:                     [SUBSYS]  :
# [PRC_ID]:                     [PROCESS] :
# [PGM_ID]: PS00S01001960.php   [PROGRAM] : プログラムマスタ選択画面定義
# [MDL_ID]:                     [MODULE]  :
# -------------------------------------------------------------------------------
# [COMMENT]
#
# -------------------------------------------------------------------------------
# [UPDATE_LOG]
#
# [UPDATE_PERSON]       [UPDATE]    [COMMENT]
# ====================  ==========  =============================================
# DOS)M.Kawamoto        2005/05/12  全角文字の「頁」が直接書き込んでいるのを[Lang/ja]から取得
# -------------------------------------------------------------------------------
#---------------------------------------------------
# グローバル設定
#---------------------------------------------------
global $gw_scr;
global $g_msg;
global $g_err_lv;
global $g_PrgCD;

unset($gw_scr['s_row_num_r']);
for($i=1;$i<=PAGE_MAXROW;$i++){
#	$gw_scr['s_row_num'][$i] = $i;
	$gw_scr['s_row_num_r'][$i] = $i;
}

$GROUP[] = array(
    "cel"			=> "col",
    "matrix"		=> "4,5",


    "s_dvsn_cd"     => array(
        "matrix"        => "1,1",
        "title"         => PS00S01001960_item("DivisionCode"),
        "type"          => "select",
        "name"          => "s_dvsn_cd",
        "value"         => $gw_scr['s_dvsn_cd'],
        "option"        => $gw_scr['s_dvsn_cd_opt'],
        "size"          => 20,
        "maxlength"     => 13,
        "dt_colspan"    => 3,
        "disabled"      => array(
            1 => "",
            2 => "",

            3 => "",

            4 => "",



        ),
    ),


    "s_rdg_cd"      => array(
        "matrix"        => "1,2",
        "title"         => PS00S01001960_item("Ridge"),
        "type"          => "text",
        "name"          => "s_rdg_cd",
        "value"         => $gw_scr['s_rdg_cd'],
        "size"          => 20,
        "maxlength"     => 7,
        "dt_colspan"    => 3,
        "readonly"      => array(
            1 => "",
            2 => "true",
            ),
        "itm_cls"       => array(
            1 => "",
            2 => "dis_text",
            ),
        "ulist"         => array
        (
            "prgno"         => "PS00S06000560",
            "row"           => 20,
            "width"         => 600,
            "height"        => 800,
            "arg"   => array
            (
                "s_dvsn_cd"     => "s_dvsn_cd",
                "s_hour_cd"     => "s_hour_cd",
                "s_usrId"       => "usrId"
                ),
            "rtn"   => array
            (
                "s_rdg_cd",
                "s_rdg_nm",
							/*	"s_dvsn_cd",
                            "s_hour_cd",*/
                            "s_dvsn_cd_opt",
                            "s_hour_cd_opt"  

                            ),
            ),
        ),

    "s_rdg_nm"              => array(
        "matrix"        => "3,2",
        "type"          => "text",
        "name"          => "s_rdg_nm",
        "value"         => $gw_scr['s_rdg_nm'],
        "size"          => 30,
        "readonly"      => "true",
        "itm_cls"       => "dis_text",
        ),

    "s_hour_cd"     => array(
        "matrix"                => "1,4",
        "title"         => PS00S01001960_item("HourCode"),
        "type"          => "select",
        "name"          => "s_hour_cd",
        "value"         => $gw_scr['s_hour_cd'],
        "option"                => $gw_scr['s_hour_cd_opt'],
        "size"          => 35,
        "maxlength"             => 13,
        "dt_colspan"          => 3,
        "a_cap"               => PS00S01001960_item("Required"),
        ),

    "s_sub"			=> array(
       "matrix"	=> "1,5",
       "type"		=> "submit",
       "name"		=> "s_sub",
       "value"		=> button_name('Reference'),
       "class"		=> "noborder",
       "onclick"	=> "jgt_page_action('SEARCH')",
       "disabled"	=> array(1=>"", "", "true", "", "true")
       ),

    "s_hidden"		=> array(
       "matrix"	=> "2,4",
       "type"		=> "hidden",
       "name"		=> array(
			"s_act_mode",				# PSSEM00101130 用モード
			"s_send_pgm_id",			# PSSEM00101130 用編集 PGM_ID
			"s_list_pgm_id",
			"s_upd_lev",				# 更新レベル
			"s_maxpage",				# 最大ページ総数
			"s_send_cd",				# 戻し用（コード）
			"s_send_nm",				# 戻し用（名称）
			"s_send_row",				# 戻し用（行番号）
			"s_diff_pgm_id",			# 差分チェック用（プログラムID）
			"s_diff_name",				# 差分チェック用（名称）
			"s_diff_pgm_kbn",			# 差分チェック用（プログラム区分）
			"s_diff_sub_sys_kbn",		# 差分チェック用（サブシステム区分）
			"s_rtn_row",				# 戻し先行番号
			"s_renzheng",				# 認証用フラグ
			"s_renzheng_t",

           "s_dvsn_cd_2",
           "s_hour_cd_2",
           "s_dvsn_cd_opt",
           "s_hour_cd_opt",
           "s_list_pgm_id_cp"
           ),
       "value"		=> array(
           $gw_scr['s_act_mode'],
           $gw_scr['s_send_pgm_id'],
           $gw_scr['s_list_pgm_id'],
           $gw_scr['s_upd_lev'],
           $gw_scr['s_maxpage'],
           "",
           "",
           "",
           $gw_scr['s_diff_pgm_id'],
           $gw_scr['s_diff_name'],
           $gw_scr['s_diff_pgm_kbn'],
           $gw_scr['s_diff_sub_sys_kbn'],
           $gw_scr['s_rtn_row'],
           $gw_scr['s_renzheng'],
           $gw_scr['s_renzheng_t'],
           $gw_scr['s_dvsn_cd_2'],
           $gw_scr['s_hour_cd_2'],
           $gw_scr['s_dvsn_cd_opt'],
           $gw_scr['s_hour_cd_opt'],
           $gw_scr['s_list_pgm_id_cp'],
           ),
       "class"		=> "nodisp"
       )
    );


    if($gw_scr['s_prev_page'] == ""){
       $w_arr_url = array(
           "javascript:jgt_return",
           "PSSEM00101130.php",
           "s_send_pgm_id",
           $gw_scr['s_list_pgm_id'],
           "s_act_mode",
           2
           );
   } else {
       $w_arr_url = array(
           "javascript:jgt_return",
           $gw_scr['s_prev_page'],
           "s_send_cd",
           $gw_scr['s_list_pgm_id'],
           "s_send_nm",
           $gw_scr['s_list_name']
           );
   }


   $w_group3_row = MAXROW + 1;

if ( !empty( $g_msg )) {
    $GROUP[] = array(
        "cel"           => "col",
        "matrix"        => "1,1",
        "s_message"     => array(
            "matrix"    => "1,1",
            "type"      => "msg",
            "value"     => $g_msg,
            "lev"       => $g_err_lv
        )
    );
}

$GROUP[] = array(
    "cel"           => "col",
    "matrix"        => "9,1",
  "s_check" => array(
    "matrix" => "5,1",
    "type" => "button",
    "name" => "s_check",
    "value" => button_name("Check"),
    "class" => "noborder",
   "disabled" => array(
        1 => "",
        2 => "",
        3 => "true",
        4 => "true",
        ),
    /* "type"                  => array
                (
                        1 => "button",
                        2 => "none",
                        3 => "none",
                        4 => "none",
                ),
*/
    "onclick" => "jgt_page_action('CHECK', '', 1)"
    ),
  "s_execute" => array(
    "matrix" => "6,1",
    "type" => "button",
    "name" => "s_execute",
    "value" => button_name("Execute"),
    "class" => "noborder",
    "disabled" => array(
        1 => "true",
        2 => "true",
        3 => "",
        4 => "true",
        ),
    "onclick" => "jgt_page_action('EXECUTE', '', 1)"
    ),

  "s_back" => array(
    "matrix" => "7,1",
    "type" => "button",
    "name" => "s_back",
    "value" => PS00S01001960_item("Back"),
    "class" => "noborder",
    "disabled" => array(
        1 => "true",
        2 => "",
        3 => "",
        4 => "",
        ),

    "onclick" => "jgt_page_action('BACK', '', 1)"
    ),
);



$GROUP[] = array(
  array(
    "cel"           => "row",
    "matrix"        => "6,".$w_group3_row,


    "s_list_pgm_id"  => array(
        "matrix"    => "2,1",
        "width"     => 120,
        "type"      => "text",
        "title"     => PS00S01001960_item("Email"),
        "name"      => "s_list_pgm_id[]",
        "disabled" => array(
            1 => "",
            2 => "",
            3 => "",
            4 => "true",
            ),
        "itm_cls"               => array
        (
            1 => "",
            2 => "",
            3 => "dis_text",
            4 => "dis_text"
            ),


        "value"     => $gw_scr['s_list_pgm_id'],
        ),

    "s_list_upd_lev"=> array(
        "matrix"    => "6,1",
        "type"      => "hidden",
        "title"     => "&nbsp;",
        "name"      => "s_list_upd_lev[]",
        "value"     => $gw_scr['s_list_pgm_id'],
        "class"     => "noborder",
        ),
    ),


  array(
    "cel"           => "row",
    "matrix"        => "6,".$w_group3_row,


    "s_list_pgm_id_2"  => array(
        "matrix"    => "2,1",
        "startrow"      => (MAXROW + 1),
        "width"     => 120,
        "type"      => "text",
        "title"     => PS00S01001960_item("Email"),
        "name"      => "s_list_pgm_id[]",
        "disabled" => array(
            1 => "",
            2 => "",
            3 => "",
            4 => "true",
            ),
        "itm_cls"               => array
        (
            1 => "",
            2 => "",
            3 => "dis_text",
            4 => "dis_text"
            ),

        "value"     => $gw_scr['s_list_pgm_id'],
        ),

    "s_list_upd_lev_2"=> array(
        "matrix"    => "6,1",
        "startrow"      => (MAXROW + 1),
        "type"      => "hidden",
        "title"     => "&nbsp;",
        "name"      => "s_list_upd_lev[]",
        "value"     => $gw_scr['s_list_pgm_id'],
        "class"     => "noborder",
        ),
    ),

  array(
    "cel"           => "row",
    "matrix"        => "6,".$w_group3_row,

    "s_list_pgm_id_3"  => array(
        "matrix"    => "2,1",
        "startrow"      => ((MAXROW * 2)  + 1),
        "width"     => 120,
        "type"      => "text",
        "title"     => PS00S01001960_item("Email"),
        "name"      => "s_list_pgm_id[]",
        "disabled" => array(
            1 => "",
            2 => "",
            3 => "",
            4 => "true",
            ),

        "itm_cls"               => array
        (
            1 => "",
            2 => "",
            3 => "dis_text",
            4 => "dis_text"
            ),


        "value"     => $gw_scr['s_list_pgm_id'],
        ),

    "s_list_upd_lev_3"=> array(
        "matrix"    => "6,1",
        "startrow"      => ((MAXROW * 2) + 1),
        "type"      => "hidden",
        "title"     => "&nbsp;",
        "name"      => "s_list_upd_lev[]",
        "value"     => $gw_scr['s_list_pgm_id'],
        "class"     => "noborder",
        ),
    ),
  );


$g_js_o = array(
 "jgt_return.js",
 "jgt_confirm.js",
 "jgt_prev_page.js"
 );

$g_js_i = "
function jgt_radio_action(rtnpg,mode){

    var row_num;
    var s_send_pgm_id;

    len = document.forms[0].s_row_num.length;

    for (var i=0; i<len; i++) {
        if (document.forms[0].s_row_num[i].checked) {
            row_num = document.forms[0].s_row_num[i].value;
            break;
        }
    }

    s_send_pgm_id = document.forms[0].elements['s_list_pgm_id[' + row_num + ']'].value;
    document.forms[0].s_send_pgm_id.value = s_send_pgm_id;
    document.forms[0].s_act_mode.value = mode;

    // フォーム送信
    document.forms[0].action = rtnpg;
    document.forms[0].submit();
}
";

$g_def = array(
     1=>"s_inp_cd",
     $gw_scr['s_crsl_md2'],
     "s_nm_fll",
 );

$g_session = array(
    "s_pgm_id",
    "s_name",
    "s_pgm_kbn",
    "s_sub_sys_kbn",
    "s_hidden",
    "s_row_num",
    "s_list_pgm_id",
    "s_list_name",
    "s_list_pgm_kbn",
    "s_list_sub_sys_kbn",
    "s_list_upd_lev",
    "s_list_hidden",
    "s_sel_page",
    "cap_maxpage",
    "s_renzheng",
    "s_renzheng_t",
 );

 ?>
