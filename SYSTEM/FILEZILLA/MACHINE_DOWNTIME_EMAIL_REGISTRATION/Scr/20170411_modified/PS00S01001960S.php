<?php
# ======================================================================================
# [DATE]  : 2017.04.07              [AUTHOR]  : MIS) Mydel
# [SYS_ID]: GPRISM            [SYSTEM]  : CCD
# [SUB_ID]:               [SUBSYS]  : 
# [PRC_ID]:               [PROCESS] : 
# [PGM_ID]: PS00S01001960S.php      [PROGRAM] : Machine Downtime Email Registration
# [MDL_ID]:               [MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
# 
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]   [UPDATE]      [COMMENT]
# ====================  ==================  ============================================
# �
# --------------------------------------------------------------------------------------
global $gw_scr;
global $g_msg;
global $g_err_lv;
global $g_PrgCD;
#======================================================================
# Forms
#======================================================================
// unset($gw_scr['s_row_num_r']);
// for($i=1;$i<=PAGE_MAXROW;$i++){
// # $gw_scr['s_row_num'][$i] = $i;
//   $gw_scr['s_row_num_r'][$i] = $i;
// }

$GROUP[] = array(
	"cel" => "col",
	"matrix" => "4,5",

	# Division code drop down
	"s_dvsn_cd" => array(
		"matrix" => "1,1",
		"title" => itm("DivisionCode"),
		"type" => "select",
		"name" => "s_dvsn_cd",
		"value" => $gw_scr['s_dvsn_cd'],
		"option" => $gw_scr['s_dvsn_cd_opt'],
		"size" => 20,
		"maxlength" => 13,
		"dt_colspan" => 3,
		"disabled" => array(
			1 => "",
			2 => "",
			3 => "",
			4 => "",
		),
	),

	# Ridge Textbox
	"s_rdg_cd" => array(
	    "matrix" => "1,2",
	    "title" => itm("Ridge"),
	    "type" => "text",
	    "name" => "s_rdg_cd",
	    "value" => $gw_scr['s_rdg_cd'],
	    "size" => 20,
	    "maxlength" => 7,
	    "dt_colspan" => 3,
	    "readonly" => array(
	      1 => "",
	      2 => "true",
	    ),
	    "itm_cls" => array(
	      1 => "",
	      2 => "dis_text",
	    ),
	    "ulist" => array(
	      "prgno" => "PS00S06000560",
	      "row" => 20,
	      "width" => 600,
	      "height" => 800,
	        "arg" => array(
	            "s_dvsn_cd" => "s_dvsn_cd",
	            "s_hour_cd" => "s_hour_cd",
	            "s_usrId" => "usrId"
			),
	        "rtn" => array(
	            "s_rdg_cd",
	            "s_rdg_nm",
	            "s_dvsn_cd_opt",
	            "s_hour_cd_opt"  
			),
		),
	),

	# Ridge Display Outside
    "s_rdg_nm" => array(
        "matrix"  => "3,2",
        "type" => "text",
        "name" => "s_rdg_nm",
        "value" => $gw_scr['s_rdg_nm'],
        "size" => 30,
        "readonly" => "true",
        "itm_cls" => "dis_text",
	),

    # Time Range
    "s_hour_cd" => array(
        "matrix" => "1,3",
        "title" => itm("HourCode"),
        "type" => "select",
        "name" => "s_hour_cd",
        "value" => $gw_scr['s_hour_cd'],
        "option" => $gw_scr['s_hour_cd_opt'],
        "size" => 35,
        "maxlength" => 13,
        "dt_colspan" => 3,
        "a_cap" => itm("Required"),
	),

    # Search Button
    "s_sub" => array(
       "matrix" => "2,3",
       "type" => "submit",
       "name" => "s_sub",
       "value" => button_name('Reference'),
       "class" => "noborder",
       "onclick" => "jgt_page_action('SEARCH')",
       "disabled" => array(
	        1 => "",
	        2 => "",
	        3 => "",
	        4 => "",
		),
	),

    # Number of Rows
	"s_inp_row" => array(
		"matrix" => "1, 4",
		"title" => itm("InputRow"),
		"type" => "text",
		"name" => "s_inp_row",
		"size" => 3,
		"maxlength" => 2,
		"readonly" => array(
				1 => false,
				true,
				true,
				true,
				true
		),
		"itm_cls" => array(
				1 => "",
				"dis_text",
				"dis_text",
				"dis_text",
				"dis_text"
		),
		"value" => $gw_scr['s_h_inp_row'],
	),

	"s_redisp" => array(
		"matrix" => "2,4",
		"type" => "button",
		"name" => "s_redisp",
		"value" => button_name("ReDisp"),
		"class" => "noborder",
		"onclick" => "jgt_page_action('REDISP', '', 1)",
		"disabled" => array(
			1 => "false",
			"true",
			"true",
			"true"
		),
	),

    "s_hidden"    => array(
		"matrix" => "2,4",
		"type"   => "hidden",
		"name"   => array(
			"s_act_mode",       # PSSEM00101130 �ѥ⡼��
			"s_send_pgm_id",      # PSSEM00101130 ���Խ� PGM_ID
			"s_list_pgm_id",
			"s_upd_lev",        # ������٥�
			"s_maxpage",        # ����ڡ������
			"s_send_cd",        # �ᤷ�ѡʥ����ɡ�
			"s_send_nm",        # �ᤷ�ѡ�̾�Ρ�
			"s_send_row",       # �ᤷ�ѡʹ��ֹ��
			"s_diff_pgm_id",      # ��ʬ�����å��ѡʥץ����ID��
			"s_diff_name",        # ��ʬ�����å��ѡ�̾�Ρ�
			"s_diff_pgm_kbn",     # ��ʬ�����å��ѡʥץ�����ʬ��
			"s_diff_sub_sys_kbn",   # ��ʬ�����å��ѡʥ��֥����ƥ��ʬ��
			"s_rtn_row",        # �ᤷ����ֹ�
			"s_renzheng",       # ǧ���ѥե饰
			"s_renzheng_t",
			"s_dvsn_cd_2",
			"s_hour_cd_2",
			"s_dvsn_cd_opt",
			"s_hour_cd_opt",
			"s_list_pgm_id_cp"
		),
		"value"    => array(
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
		"class"    => "nodisp"
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
} 
else {
	$w_arr_url = array(
		"javascript:jgt_return",
		$gw_scr['s_prev_page'],
		"s_send_cd",
		$gw_scr['s_list_pgm_id'],
		"s_send_nm",
		$gw_scr['s_list_name']
	);
}

if ( !empty($g_msg)) {
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

#======================================================================
# Button Below
#======================================================================
$GROUP[] = array(
    "cel"           => "col",
    "matrix"        => "9,1",

    # Seach Button
	"s_check" => array(
	    "matrix" => "1,1",
	    "type" => array(
	        1 => "none",
	        2 => "none",
	        3 => "none",
	        4 => "none",
        ),
	    "name" => "s_check",
	    "value" => button_name("Check"),
	    "class" => "noborder",
	    "disabled" => array(
	        1 => "",
	        2 => "",
	        3 => "",
	        4 => "",
		),
	    "onclick" => "jgt_page_action('CHECK','', 1)"
    ),

    "s_clear" => array(
	    "matrix" => "2,1",
	    "type" => array(
	        1 => "button",
	        2 => "none",
	        3 => "none",
	        4 => "none",
        ),
	    "name" => "s_clear",
	    "value" => itm("Clear"),
	    "class" => "noborder",
	    "disabled" => array(
	        1 => "",
	        2 => "",
	        3 => "",
	        4 => "",
		),
	    "onclick" => "jgt_page_action('CLEAR','', 1)"
    ),

    # Execute Button
	"s_execute" => array(
	    "matrix" => "3,1",
	    "type" => array(
	        1 => "none",
	        2 => "none",
	        3 => "none",
	        4 => "none",
        ),
	    "name" => "s_execute",
	    "value" => button_name("Execute"),
	    "class" => "noborder",
	    "disabled" => array(
	        1 => "",
	        2 => "",
	        3 => "",
	        4 => "",
		),
	    "onclick" => "jgt_page_action('EXECUTE','', 1)"
    ),

	# Back Button
	"s_back" => array(
	    "matrix" => "4,1",
	    "type" => array(
	        1 => "none",
	        2 => "none",
	        3 => "none",
	        4 => "none",
        ),
	    "name" => "s_back",
	    "value" => itm("Back"),
	    "class" => "noborder",
	    "disabled" => array(
	        1 => "",
	        2 => "",
	        3 => "",
	        4 => "",
		),
	    "onclick" => "jgt_page_action('BACK', '', 1)"
    ),
);


#======================================================================
# Display Row
#======================================================================
$GROUP[] = array(
	# EQP
	array(
	    "cel"           => "row",
	    "matrix"        => "1,6",

		# Visible list for EQP
		"s_list_pgm_id"  => array(
		    "matrix"    => "1,1",
		    "width"     => 120,
		    "type"      => "text",
		    "name"      => "s_list_pgm_id",
		    "title"     => itm("EQP"),
		    "disabled" => array(
		        1 => "",
		        2 => "",
		        3 => "",
		        4 => "",
			),
		    "itm_cls" => array(
		        1 => "",
		        2 => "",
		        3 => "",
		        4 => "dis_text"
			),
		    "value"    => $gw_scr['s_list_pgm_id'],
		),
	),

	#T&M
    array(
	    "cel"           => "row",
	    "matrix"        => "5,5",

	    # Header for T&M
	    "s_list_tnm_header"  => array(
	        "matrix"    => "1,1",
	        "width"     => 120,
	        "type"      => "text",
	        "title"     => itm("T&M"),
	        "itm_cls" => array(
		        1 => "dis_text",
		        2 => "dis_text",
		        3 => "dis_text",
		        4 => "dis_text"
		    ),
		),
	)
);

for($i=1;$i<$gw_scr['s_h_inp_row'];$i++){
	echo $i;
	echo "</br>";
}

# Visible list for EQP
// "s_list_pgm_id"  => array(
//     "matrix"    => $w_row.",1",
//     "width"     => 120,
//     "type"      => "text",
//     "title"     => itm("Email"),
//     "name"      => "s_list_pgm_id",
//     "disabled" => array(
//         1 => "",
//         2 => "",
//         3 => "",
//         4 => "",
// 	),
//     "itm_cls" => array(
//         1 => "",
//         2 => "",
//         3 => "dis_text",
//         4 => "dis_text"
// 	),
//     "value"    => $gw_scr['s_list_pgm_id'],
// ),
#======================================================================



#======================================================================
# Javascript
#======================================================================
$g_js_o = array(
	"jgt_return.js",
	"jgt_confirm.js",
	"jgt_prev_page.js"
);

#======================================================================
# ?
#======================================================================
$g_def = array(
     1=>"s_inp_cd",
     $gw_scr['s_crsl_md2'],
     "s_nm_fll",
 );

#======================================================================
# Session
#======================================================================
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

