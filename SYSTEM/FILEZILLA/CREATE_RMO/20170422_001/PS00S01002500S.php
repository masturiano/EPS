<?php
# ======================================================================================
# [DATE]  : 2013.02.27			[AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM				[SYSTEM]  : CIM
# [SUB_ID]:						[SUBSYS]  : 
# [PRC_ID]:						[PROCESS] : 
# [PGM_ID]: PS00S01000990S.php	[PROGRAM] : Create YMO Lot
# [MDL_ID]:						[MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
# 
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]		[UPDATE]			[COMMENT]
# ====================	==================	============================================
# --------------------------------------------------------------------------------------

global $gw_scr;
global $g_msg;
global $g_err_lv;
global $g_PrgCD;

#======================================================================
# UserID, LotID
#======================================================================
$GROUP[] = array
(
        "cel"           => "col",
        "matrix"        => "3,9",

        "s_usr_id"              => array
        (
                "matrix"                => "1,1",
                "title"                 => itm("UsrId"),
                "type"                  => "text",
                "name"                  => "s_usr_id",
                "value"                 => $gw_scr['s_usr_id'],
                "size"                  => 20,
                "maxlength"             => 13,
                "readonly"              => array
                (
                        1 => "",
                        2 => "true",
                        3 => "true",
                        4 => "true",
                ),
                "itm_cls"               => array
                (
                        1 => "",
                        2 => "dis_text",
                        3 => "dis_text",
                        4 => "dis_text",
                ),
        ),
        "s_usr_nm"              => array
        (
                "matrix"                => "3,1",
                "type"                  => "disp",
                "name"                  => "s_usr_nm",
                "value"                 => $gw_scr['s_usr_nm'],
                "width"                 => 200,
                "nowrap"                => "true",
        ),

        "s_lot_id"              => array
        (
                "matrix"                => "1,2",
                "title"                 => itm("LotId"),
                "type"                  => "text",
                "name"                  => "s_lot_id",
                "value"                 => $gw_scr['s_lot_id'],
                "size"                  => 30,
                "maxlength"             => 20,
                "readonly"              => array
                (
                        1 => "",
                        2 => "true",
                        3 => "true",
                        4 => "true",
                ),
                "itm_cls"               => array
                (
                        1 => "",
                        2 => "dis_text",
                        3 => "dis_text",
                        4 => "dis_text",
                ),
        ),

        "s_chp_qty"              => array
        (
                "matrix"                => "1,3",
                "title"                 => itm("ChipQty"),
                "type"                  => "text",
                "name"                  => "s_chp_qty",
                "value"                 => $gw_scr['s_chp_qty'],
                "size"                  => 30,
                "maxlength"             => 20,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "true",
                        3 => "true",
                        4 => "true",
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "dis_text",
                        3 => "dis_text",
                        4 => "dis_text",
                ),
        ),
        "s_typ_nm"              => array
        (
                "matrix"                => "1,4",
                "title"                 => itm("TypeNm"),
                "type"                  => "text",
                "name"                  => "s_typ_nm",
                "value"                 => $gw_scr['s_typ_nm'],
                "size"                  => 30,
                "maxlength"             => 20,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "true",
                        3 => "true",
                        4 => "true",
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "dis_text",
                        3 => "dis_text",
                        4 => "dis_text",
                ),
        ),


        "s_prd_nm"              => array
        (
                "matrix"                => "1,5",
                "title"                 => itm("PrdNm"),
                "type"                  => "text",
                "name"                  => "s_prd_nm",
                "value"                 => $gw_scr['s_prd_nm'],
                "size"                  => 40,
                "maxlength"             => 30,
                "readonly"              => array
                (
                        1 => "",
                        2 => "true",
                        3 => "true",
                        4 => "true",
                ),
                "itm_cls"               => array
                (
                        1 => "",
                        2 => "dis_text",
                        3 => "dis_text",
                        4 => "dis_text",
                ),
                "list"          => array
                (
                                     "xpt_prd_mst_in_dvsn",
                                     "50",
                                     $g_PrgCD,
                                     "s_prd_cd",
                                     "s_prd_nm",
                                     "s_dvsn_cd",
                ),
        ),

        "s_prd_cd"              => array
        (
                "matrix"                => "3,5",
                "type"                  => "text",
                "name"                  => "s_prd_cd",
                "value"                 => $gw_scr['s_prd_cd'],
                "size"                  => 30,
                "readonly"              => "true",
                "itm_cls"               => "dis_text",
        ),

        "s_process_cd"          => array
                        (
                "matrix"                => "1,6",
                "title"                 => PS00S01000990_item("PrcCd"),
                "type"                  => "text",
                "name"                  => "s_process_cd",
                "size"                  => 19,
                "maxlength"             => 12,
                "readonly"              => array
                                                (
                                                1 => "",
                                                2 => "true",
                                                3 => "true",
						4 => "true"
                                                ),
                "itm_cls"               => array
                                               (
                                                1 => "",
                                                2 => "dis_text",
                                                3 => "dis_text",
						4 => "dis_text"
                                               ),
                "value"                 => $gw_scr['s_process_cd'],
                "list"                  => array
                                               (
                                                "xpt_prc_mst",
                                                "20",
                                                $g_PrgCD,
                                                "s_process_cd",
                                                "s_process_nm",
                                                "s_prd_nm"
                                               )
        ),
        "s_process_nm"          => array
                        (
                "matrix"                => "3,6",
                "type"                  => "text",
                "name"                  => "s_process_nm",
                "size"                  => 40,
                "readonly"              => true,
                "itm_cls"               => "dis_text",
                "value"                 => $gw_scr['s_process_nm']
        ),

         "s_io_block_cd" => array
         (
                "matrix"                => "1,7",
                "title"                 => PS00S01000990_item("StpCd"),
                "type"                  => "text",
                "name"                  => "s_io_block_cd",
                "size"                  => 19,
                "maxlength"             => 12,
                "readonly"              => array
                                                (
                                                1 => "",
                                                2 => "true",
                                                3 => "true",
						4 => "true"
                                                ),
                "itm_cls"               => array
                                                (
                                                1 => "",
                                                2 => "dis_text",
                                                3 => "dis_text",
						4 => "dis_text"
                                                ),
                "value"                 => $gw_scr['s_io_block_cd'],
                "list"                  => array
                                                (
                                                "xpt_prc_flw_mst_io",
                                                "100",
                                                $g_PrgCD,
                                                "s_io_block_cd",
                                                "s_io_block_nm",
                                                "s_process_cd"
                                                )
                ),

        "s_io_block_nm"=> array
                  (
                "matrix"                => "3,7",
                "type"                  => "text",
                "name"                  => "s_io_block_nm",
                "size"                  => 40,
                "readonly"              => true,
                "itm_cls"               => "dis_text",
                "value"                 => $gw_scr['s_io_block_nm']
        ),

        "s_lbl_cd"                      => array
        (
                "matrix"                => "1,8",
                "title"                 => itm("LblPrinter"),
                "type"                  => "text",
                "name"                  => "s_lbl_cd",
                "value"                 => $gw_scr['s_lbl_cd'],
                "size"                  => 30,
                "maxlength"             => 20,
                "hidden"                => array
                (
                        "s_lp_tag"              => "LP"
                ),
                "readonly"              => array
                (
                        1 => "",
                        2 => "true",
                        3 => "true",
                        4 => "true",
                        5 => "true",
                        6 => "true",
                ),
                "itm_cls"               => array
                (
                        1 => "",
                        2 => "dis_text",
                        3 => "dis_text",
                        4 => "dis_text",
                        5 => "dis_text",
                        6 => "dis_text",
                ),
                "ulist"         => array
                (
                        "prgno"         => PGMID_PRINT,
                        "row"           => 10,
                        "width"         => 500,
                        "height"        => 400,
                        "arg"           => array
                        (
                                "s_tag"     => "s_lp_tag",
                                "s_info"        => "s_dvsn_cd"
                        ),
                        "rtn"   => array
                        (
                                "s_lbl_nm",
                                "s_lbl_cd"
                        ),
                ),
        ),

        "s_lbl_nm"              => array
        (
                "matrix"                => "3,8",
                "type"                  => "text",
                "name"                  => "s_lbl_nm",
                "value"                 => $gw_scr['s_lbl_nm'],
                "size"                  => 50,
                "maxlength"             => 40,
                "readonly"              => "true",
                "itm_cls"               => "dis_text",
        ),
       "s_hidden" => array
        (
                "matrix"                => "1,9",
                "class"                 => "noborder",
                "type"                  => "hidden",
                "name"                  => array
                (
                        "s_hdn_inp_cnt",
                        "s_mgzn_flg",
                        "s_hdn_mgzn_row",
                        "s_tag_lp",
                        "s_dvsn_cd",
                        "s_lst_prd",
                        "s_rtn_prd_cd",
                        "s_rtn_prd_nm",
                        "s_stp_cd",
			"s_typ_nm",
			"s_chp_qty",
                        "s_srlz_rtinf",
                ),
                "value"                 => array
                (
                        $gw_scr['s_hdn_inp_cnt'],
                        $gw_scr['s_mgzn_flg'],
                        $gw_scr['s_hdn_mgzn_row'],
                        "LP",
                        $gw_scr['s_dvsn_cd'],
                        "prd_in_dvsn",
                        "s_prd_cd",
                        "s_prd_nm",
                        $gw_scr['s_stp_cd'],
			$gw_scr['s_typ_nm'],
			$gw_scr['s_chp_qty'],
                        $gw_scr['s_srlz_rtinf'],
                ),
        ),
);
/*
#======================================================================
# Create Main/Sub Lot Field
#======================================================================
function crt_lotfld(&$x, &$y)
{
	global $gw_scr;
	global $g_mode;
	global $g_PrgCD;

	$w_dat = array();
	#------------------------------------------------------------------
	# create number of lines
	#------------------------------------------------------------------
	$x = 0;
	$y = 0;
	$w_tmp = array
	(
		"ttl_inpcnt"		=> array
		(
			"matrix"		=> ++$x.",".++$y,
			"type"			=> "disp",
			"value"			=> itm("InpLotCnt"),
			"class"			=> "thcell",
			"nowrap"		=> "true",
			"dt_colspan"	=> 2,
			"dmy"			=> ++$x,
			"dmy2"			=> ++$x,
		),
		"s_inp_cnt"		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "text",
			"name"			=> "s_inp_cnt",
			"value"			=> $gw_scr['s_inp_cnt'],
			"size"			=> 3,
			"maxlength"		=> 3,
			"readonly"		=> array
			(
				1 => "",
				2 => "true",
				3 => "true",
				4 => "true",
			),
			"itm_cls"		=> array
			(
				1 => "",
				2 => "dis_text",
				3 => "dis_text",
				4 => "dis_text",
			),
			"a_cap"			=> "<input type=\"button\" "
								. "name=\"s_rdsp_$w_dvs\" "
								. "value=\"".button_name("ReDisp")."\" "
								. "onclick=\"jgt_page_action('REDISP', '', '1')\" "
								. (($g_mode==1)?(""):("disabled")).">",
			"dt_colspan"	=> 5,
			"class"			=> "noborder",
		),
	);
	$w_dat = $w_dat + $w_tmp;

	#------------------------------------------------------------------
	# Titles of List
	#------------------------------------------------------------------
	$x = 0;
	$w_tmp = array
	(
		"ttl_dmy"		=> array
		(
			"matrix"		=> ++$x.",".++$y,
			"type"			=> "disp",
			"value"			=> " ",
			"class"			=> "thcell",
			"nowrap"		=> "true",
		),

		"ttl_lotid"		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "disp",
			"value"			=> itm("LotId"),
			"class"			=> "thcell",
			"nowrap"		=> "true",
		),

		"ttl_pkg"		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "disp",
			"value"			=> itm("Pkg"),
			"class"			=> "thcell",
			"nowrap"		=> "true",
		),

		"ttl_prdnm"		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "disp",
			"value"			=> itm("PrdNm"),
			"class"			=> "thcell",
			"nowrap"		=> "true",
		),

		"ttl_SecNo"		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "disp",
			"value"			=> itm("DateCode"),
			"class"			=> "thcell",
			"nowrap"		=> "true",
		),

		"ttl_PltNo"		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "disp",
			"value"			=> itm("PltNo"),
			"class"			=> "thcell",
			"nowrap"		=> "true",
		),

		"ttl_chpqty"		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "disp",
			"value"			=> itm("ChpQty"),
			"class"			=> "thcell",
			"nowrap"		=> "true",
		),
	);
	$w_dat = $w_dat + $w_tmp;

	#------------------------------------------------------------------
	# LotID
	#------------------------------------------------------------------
	for($i=1; $i<=$gw_scr['s_hdn_inp_cnt']; $i++){
		$x = 0;
		$w_tmp = array
		(
			"s_row_".$i		=> array
			(
				"matrix"		=> ++$x.",".++$y,
				"type"			=> "disp",
				"value"			=> $i,
				"width"			=> 20,
				"class"			=> "thcell",
				"nowrap"		=> "true",
			),

			"s_list_lot_id_".$i		=> array
			(
				"matrix"		=> ++$x.",".$y,
				"type"			=> "text",
				"name"			=> "s_list_lot_id[$i]",
				"value"			=> $gw_scr['s_list_lot_id'][$i],
				"size"			=> 20,
				"maxlength"		=> 15,
				"readonly"		=> array
				(
					1 => "",
					2 => "true",
					3 => "true",
					4 => "true",
				),
				"itm_cls"		=> array
				(
					1 => "",
					2 => "dis_text",
					3 => "dis_text",
					4 => "dis_text",
				),
			),

			"s_list_pkg_nm_".$i		=> array
			(
				"matrix"		=> ++$x.",".$y,
				"type"			=> "disp",
				"name"			=> "s_list_pkg_nm[$i]",
				"value"			=> $gw_scr['s_list_pkg_nm'][$i],
				"nowrap"		=> "true",
			),

			"s_list_prd_nm_".$i		=> array
			(
				"matrix"		=> ++$x.",".$y,
				"type"			=> "disp",
				"name"			=> "s_list_prd_nm[$i]",
				"value"			=> $gw_scr['s_list_prd_nm'][$i],
				"nowrap"		=> "true",
			),

			"s_list_sec_no_".$i		=> array
			(
				"matrix"		=> ++$x.",".$y,
				"type"			=> "disp",
				"name"			=> "s_list_sec_no[$i]",
				"value"			=> $gw_scr['s_list_sec_no'][$i],
				"nowrap"		=> "true",
			),

			"s_list_plt_no_".$i		=> array
			(
				"matrix"		=> ++$x.",".$y,
				"type"			=> "disp",
				"name"			=> "s_list_plt_no[$i]",
				"value"			=> $gw_scr['s_list_plt_no'][$i],
				"nowrap"		=> "true",
			),

			"s_list_chp_qty_".$i		=> array
			(
				"matrix"		=> ++$x.",".$y,
				"type"			=> "text",
				"name"			=> "s_list_chp_qty[$i]",
				"value"			=> $gw_scr['s_list_chp_qty'][$i],
				"size"			=> 10,
				"maxlength"		=> 10,
				"readonly"		=> array
				(
					1 => "true",
					2 => "true",
					3 => "true",
					4 => "true",
				),
				"itm_cls"		=> array
				(
					1 => "dis_text",
					2 => "dis_text",
					3 => "dis_text",
					4 => "dis_text",
				),
			),

			"s_list_".$w_dvs."_hdn_".$i		=> array
			(
				"matrix"		=> ++$x.",".$y,
				"type"			=> "hidden",
				"name"			=> array
				(
					"s_list_hdn_chp_qty[$i]",
					"s_list_prd_cd[$i]",
				),
				"value"			=> array
				(
					$gw_scr['s_list_hdn_chp_qty'][$i],
					$gw_scr['s_list_prd_cd'][$i],
				),
				"class"			=> "noborder",
			),
		);
		$w_dat = $w_dat + $w_tmp;
	}

	### total
	$w_tmp = array
	(
		"ttl_ttl"		=> array
		(
			"matrix"		=> "1,".++$y,
			"type"			=> "disp",
			"value"			=> itm("Total"),
			"class"			=> "thcell",
			"dt_colspan"	=> ($x - 2),
		),

		"s_ttl_qty"		=> array
		(
			"matrix"		=> ($x - 1).",".$y,
			"type"			=> "disp",
			"name"			=> "s_ttl_qty",
			"value"			=> $gw_scr['s_ttl_qty'],
			"nowrap"		=> "true",
		),
	);

	$w_dat = $w_dat + $w_tmp;

	return $w_dat;
}

$w_dat = crt_lotfld($x, $y);

$w_ini = array
(
	"cel"		=> "col",
	"matrix"	=> $x.",".$y,
);

$GROUP[] = $w_ini + $w_dat;
*/
#======================================================================
# Comment
#======================================================================
$GROUP[] = array
(
        "cel"           => "col",
        "matrix"        => "2,1",

        "s_cmt"         => array
        (
                "matrix"                => "1,1",
                "title"                 => itm("Cmt"),
                "type"                  => "text",
                "name"                  => "s_cmt",
                "value"                 => $gw_scr['s_cmt'],
                "size"                  => 100,
                "maxlength"             => 255,
                "readonly"              => array
                (
                        1 => "",
                        2 => "true",
                        3 => "true",
                        4 => "true",
                ),
                "itm_cls"               => array
                (
                        1 => "",
                        2 => "dis_text",
                        3 => "dis_text",
                        4 => "dis_text",
                ),
        ),
);

#======================================================================
# Message field
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "1,2",

	"s_message"	=> array
	(
		"matrix"	=> "1,1",
		"type"		=> "msg",
		"value"		=> $g_msg,
		"lev"		=> $g_err_lv
	),

	"s_prnt_msg"	=> array
	(
		"matrix"	=> "1,2",
		"type"		=> "msg",
		"value"		=> $gw_scr['s_prnt_msg'],
		"lev"		=> $gw_scr['s_prnt_lv'],
	),
);

#======================================================================
# Authentification
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "1,1",
	"class"		=> "noborder",

	"s_hidden_ren"	=> array
		(
			"matrix"	=> "1,1",
			"type"		=> "hidden",
			"name"		=> array
				(
					"s_renzheng",
					"s_renzheng_db",
					"s_renzheng_t",
				),
			"value"     => array
				(
					$gw_scr['s_renzheng'],
					$gw_scr['s_renzheng_db'],
					$gw_scr['s_renzheng_t'],
				),
		),
);

#======================================================================
# button
#======================================================================
$x = 0;
$w_dat = array
(
	"s_chk"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "button",
			2 => "button",
			3 => "none",
			4 => "none",
		),
		"name"			=> "s_chk",
		"value"			=> button_name("Check"),
		"onclick"		=> "jgt_page_action('CHECK', '', 1)",
	),

	"s_exe"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "none",
			2 => "none",
			3 => "button",
			4 => "none",
		),
		"name"			=> "s_exe",
		"value"			=> button_name("Execute"),
		"onclick"		=> "jgt_page_action('EXECUTE', '', 1)",
	),

	"s_ers"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "button",
			2 => "button",
			3 => "none",
			4 => "none",
		),
		"name"			=> "s_ers",
		"value"			=> button_name("Erase"),
		"onclick"		=> "jgt_page_action('ERASE', '', 1)",
	),

	"s_back"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "none",
			2 => "button",
			3 => "button",
			4 => "button",
		),
		"name"			=> "s_back",
		"value"			=> button_name("Return"),
		"onclick"		=> "jgt_page_action('BACK', '', 1)",
	),
);

$w_ini = array
(
	"cel"		=> "col",
	"matrix"	=> $x.",1",
	"class"		=> "noborder",
);
$GROUP[] = $w_ini + $w_dat;

#======================================================================
# JavaScript(internal)
#======================================================================
$g_js_i = "";
$g_js_i = str_replace("\n", "", $g_js_i);
$g_js_i = str_replace("\t", "", $g_js_i);


#======================================================================
# JavaScript(external)
#======================================================================
$g_js_o = array
(
	"jgt_return",
);

#======================================================================
# caret point by each mode
#======================================================================
$g_def = array
(
	1 => "s_usr_id",
	2 => "s_list_chp_qty[1]",
);
?>
