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
# ®
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
		    2 => "",
		    3 => "",
			4 => "",
	    ),
	    "disabled" => array(
			1 => "",
			2 => "",
			3 => "",
			4 => "",
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
        "class" => "noborder",
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
        "disabled" => array(
	        1 => "",
	        2 => "",
	        3 => "",
	        4 => "",
		),
	),

    # Search Button
    "s_sub" => array(
       "matrix" => "2,3",
       "type" => "submit",
       "name" => "s_sub",
       "value" => button_name('Reference'),
       "class" => "noborder",
       "onclick" => "jgt_page_action('SEARCH')",
       "type" => array(
	        1 => "button",
	        2 => "button",
	        3 => "none",
	        4 => "none",
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
	        1 => "",
	        2 => "",
	        3 => "",
	        4 => "", // true or false
		),
		"disabled" => array(
	        1 => "",
	        2 => "",
	        3 => "",
	        4 => "",
		),
		"value" => $gw_scr['s_inp_row'],
	),

	# Reload button
	"s_redisp" => array(
		"matrix" => "2,4",
		"type" => "button",
		"name" => "s_redisp",
		"value" => button_name("ReDisp"),
		"class" => "noborder",
		"onclick" => "jgt_page_action('REDISP', '', 1)",
		"type" => array(
	        1 => "button",
	        2 => "button",
	        3 => "none",
	        4 => "none",
        ),
	),

    "s_hidden"    => array(
		"matrix" => "2,4",
		"type"   => "hidden",
		"name"   => array(
			"s_act_mode",       # PSSEM00101130 ÍÑ¥â¡¼¥É
			"s_send_pgm_id",      # PSSEM00101130 ÍÑÊÔ½¸ PGM_ID
			"s_list_pgm_id",
			"s_upd_lev",        # ¹¹¿·¥ì¥Ù¥ë
			"s_maxpage",        # ºÇÂç¥Ú¡¼¥¸Áí¿ô
			"s_send_cd",        # Ìá¤·ÍÑ¡Ê¥³¡¼¥É¡Ë
			"s_send_nm",        # Ìá¤·ÍÑ¡ÊÌ¾¾Î¡Ë
			"s_send_row",       # Ìá¤·ÍÑ¡Ê¹ÔÈÖ¹æ¡Ë
			"s_diff_pgm_id",      # º¹Ê¬¥Á¥§¥Ã¥¯ÍÑ¡Ê¥×¥í¥°¥é¥àID¡Ë
			"s_diff_name",        # º¹Ê¬¥Á¥§¥Ã¥¯ÍÑ¡ÊÌ¾¾Î¡Ë
			"s_diff_pgm_kbn",     # º¹Ê¬¥Á¥§¥Ã¥¯ÍÑ¡Ê¥×¥í¥°¥é¥à¶èÊ¬¡Ë
			"s_diff_sub_sys_kbn",   # º¹Ê¬¥Á¥§¥Ã¥¯ÍÑ¡Ê¥µ¥Ö¥·¥¹¥Æ¥à¶èÊ¬¡Ë
			"s_rtn_row",        # Ìá¤·Àè¹ÔÈÖ¹æ
			"s_renzheng",       # Ç§¾ÚÍÑ¥Õ¥é¥°
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
	        2 => "button",
	        3 => "none",
	        4 => "none",
        ),
	    "name" => "s_check",
	    "value" => button_name("Check"),
	    "class" => "noborder",
	    "onclick" => "jgt_page_action('CHECK','', 1)"
    ),

    "s_clear" => array(
	    "matrix" => "2,1",
	    "type" => array(
	        1 => "button",
	        2 => "button",
	        3 => "none",
	        4 => "none",
        ),
	    "name" => "s_clear",
	    "value" => itm("Clear"),
	    "class" => "noborder",
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
	    "onclick" => "jgt_page_action('EXECUTE','', 1)"
    ),

	# Back Button
	"s_back" => array(
	    "matrix" => "4,1",
	    "type" => array(
	        1 => "none",
	        2 => "button",
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
function create_list_body($w_row, $w_index) {
	global $gw_scr;

	$w_tmp = array(
			"s_lst_row_no" . $w_index => array(
					"matrix" => "1," . $w_row,
					"type" => "disp",
					"value" => $w_index,
					"class" => "thcell",
					"width" => 24,
					"nowrap" => "true",
			),
			"s_lst_eqp_id_" . $w_index => array(
					"matrix" => "2," . $w_row,
					"type" => "text",
					"name" => "s_lst_eqp_id_[$w_index]",
					"value" => $gw_scr['s_lst_eqp_id_'][$w_index],
					"size" => 20,
					"maxlength" => 15,
					"disabled" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "",
					),
					"readonly" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "",
					),
					"itm_cls" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "dis_text",
					),
			),
			"s_lst_tnm_id_" . $w_index => array(
					"matrix" => "3," . $w_row,
					"type" => "text",
					"name" => "s_lst_tnm_id_[$w_index]",
					"value" => $gw_scr['s_lst_tnm_id_'][$w_index],
					"size" => 20,
					"maxlength" => 15,
					"disabled" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "",
					),
					"readonly" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "",
					),
					"itm_cls" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "dis_text",
					),
			),
			"s_lst_prn_id_3" . $w_index => array(
					"matrix" => "4," . $w_row,
					"type" => "text",
					"name" => "s_lst_prn_id_3[$w_index]",
					"value" => $gw_scr['s_lst_prn_id_3'][$w_index],
					"size" => 20,
					"maxlength" => 15,
					"disabled" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "",
					),
					"readonly" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "",
					),
					"itm_cls" => array(
				        1 => "",
				        2 => "",
				        3 => "",
				        4 => "dis_text",
					),
			),
	);

	$w_hdn_names = array(
			"s_lst_upd_lev[$w_index]",
			"s_lst_prc_cd[$w_index]",
			"s_lst_stp_cd[$w_index]",
			"s_lst_prd_cd[$w_index]",
			"s_lst_dif_lot_no[$w_index]",
	);
	$w_hdn_values = array(
			$gw_scr['s_lst_upd_lev'][$w_index],
			$gw_scr['s_lst_prc_cd'][$w_index],
			$gw_scr['s_lst_stp_cd'][$w_index],
			$gw_scr['s_lst_prd_cd'][$w_index],
			$gw_scr['s_lst_dif_lot_no'][$w_index],
	);

	$w_hdn = array(
			"s_hdn_" . $w_index => array(
					"matrix" => "8," . $w_row,
					"type" => "hidden",
					"name" => $w_hdn_names,
					"value" => $w_hdn_values,
					"class" => "noborder",
			),
	);

	$w_tmp = $w_tmp + $w_hdn;

	return $w_tmp;
}
#======================================================================
# Display Header
#======================================================================
$w_row = 1;
$w_list_header = array(
		"s_lot_header_1" => array(
				"matrix" => "1," . $w_row,
				"type" => "disp",
				"value" => "",
				"class" => "noborder",
		),
		"s_lot_header_2" => array(
				"matrix" => "2," . $w_row,
				"type" => "disp",
				"value" => itm("EQP"),
				"class" => "thcell",
				"nowrap" => "true",
		),
		"s_lot_header_3" => array(
				"matrix" => "3," . $w_row,
				"type" => "disp",
				"value" => itm("T&M"),
				"class" => "thcell",
				"nowrap" => "true",
		),
		"s_lot_header_4" => array(
				"matrix" => "4," . $w_row,
				"type" => "disp",
				"value" => itm("PRN"),
				"class" => "thcell",
				"nowrap" => "true",
		),
);

//$w_inp_row = $gw_scr['s_h_inp_row'];
$w_inp_row = $gw_scr['s_inp_row'];
$w_list_body = array();
for ($i = 1; $i <= $w_inp_row; $i++) {
	$w_tmp = create_list_body($i + 1, $i);
	$w_list_body = $w_list_body + $w_tmp;
}
$w_list_body = $w_list_body + $w_tmp;	
$w_inp_col = 8;
$w_list_ini = array(
		"cel" => "col",
		"matrix" => $w_inp_col . "," . ($w_inp_row + 2),
);

$GROUP[] = $w_list_ini + $w_list_header + $w_list_body;
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

