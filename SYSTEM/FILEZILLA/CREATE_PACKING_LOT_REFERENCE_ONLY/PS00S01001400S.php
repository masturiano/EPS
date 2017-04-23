<?php
# ======================================================================================
# [DATE]  : 2012.05.22          		[AUTHOR]  : MIS)L.Acera
# [SYS_ID]: GPRISM              		[SYSTEM]  : Gprism
# [SUB_ID]:                     		[SUBSYS]  :
# [PRC_ID]:                     		[PROCESS] :
# [PGM_ID]: PS00S01000610S.php  		[PROGRAM] : CreatePackingLot(BGA)
# [MDL_ID]:                     		[MODULE]  :
# --------------------------------------------------------------------------------------
# [COMMENT]
#
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
#
# [UPDATE_PERSON]		[UPDATE]			[COMMENT]
# ====================	=================	============================================
# --------------------------------------------------------------------------------------

global $gw_scr;
global $g_msg;
global $g_err_lv;
global $g_PrgCD;

#======================================================================
# 
#======================================================================
$GROUP[] = array(
		"cel" => "col",
		"matrix" => "4,5",
		"s_usr_id" => array(
				"matrix" => "1, 1",
				"title" => itm("UsrId"),
				"type" => "text",
				"name" => "s_usr_id",
				"size" => 20,
				"maxlength" => 13,
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
						"dis_text",
				),
				"value" => $gw_scr['s_usr_id'],
#				"dt_colspan"	=> 2,
		),
		"s_usr_nm" => array(
				"matrix" => "4, 1",
				"type" => "disp",
				"name" => "s_usr_nm",
				"value" => $gw_scr['s_usr_nm']
		),

        	# 装置コード
        	"s_equ_cd"      => array
        	(
                	"matrix"                => "1,2",
                	"title"                 => itm("EquCd"),
                	"type"                  => "text",
                	"name"                  => "s_equ_cd",
                	"value"                 => $gw_scr['s_equ_cd'],
                	"size"                  => 20,
                	"maxlength"             => 12,
                	"readonly"              => array
                	(
                                                1 => false,
                                                true,
                                                true,
                                                true,
                                                true
                	),
                	"itm_cls"               => array
                	(
                                                1 => "",
                                                "dis_text",
                                                "dis_text",
                                                "dis_text",
                                                "dis_text",
                	),
#			"dt_colspan"    => 2,
        	),
        	"s_equ_nm"      => array
        	(
                	"matrix"                => "4,2",
                	"type"                  => "disp",
                	"name"                  => "s_equ_nm",
                	"value"                 => $gw_scr['s_equ_nm'],
        	),

		"s_lbl_cd" => array(
				"matrix" => "1, 3",
				"title" => itm("PrinterCode"),
				"type" => "text",
				"name" => "s_lbl_cd",
				"size" => 30,
				"maxlength" => 20,
				"hidden"		=> array
				(
					"s_lp_tag"		=> "LP"
				),
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
				"value" => $gw_scr['s_lbl_cd'],
				"ulist"         => array
				(
					"prgno"         => PGMID_PRINT,
					"row"           => 10,
					"width"         => 500,
					"height"        => 400,
					"arg"           => array
					(
						"s_tag"     => "s_lp_tag",
						"s_info"    => "s_dvsn_cd"
					),
					"rtn"   => array
					(
						"s_lbl_nm",
						"s_lbl_cd"				
					),
				),				
		),
		"s_lbl_nm" => array(
				"matrix" => "4, 3",
				"type" => "text",
				"name" => "s_lbl_nm",
				"size" => 50,
				"readonly" => true,
				"itm_cls" => "dis_text",
				"value" => $gw_scr['s_lbl_nm']
		),
		"s_inp_row" => array(
				"matrix" => "1, 5",
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
				"value" => $gw_scr['s_inp_row'],
		),
		"s_redisp" => array(
				"matrix" => "3,5",
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
				"a_cap" => "Max:" . constant("DEFAULT_MAX_INPLOT"),
		),



		"s_box_qty_cd" => array(
				"matrix" => "1, 4",
				"title" => itm("BoxQty"),
				"type" => "text",
				"name" => "s_box_qty_cd",
				"size" => 3,
				"maxlength" => 2,
				"readonly" => array(
						true,
						true,
						true,
						true,
						true
				),
				"itm_cls" => array(
						"dis_text",
						"dis_text",
						"dis_text",
						"dis_text",
						"dis_text",
				),
				"value" => $gw_scr['s_box_qty_cd'],
		),



);

$GROUP[] = array(
		"cel" => "col",
		"matrix" => "1,1",
		"s_hdn" => array(
				"matrix" => "1,1",
				"type" => "hidden",
				"name" => array(
				"s_dvsn_cd",
						"s_lbl_id",
						"s_lbl_type",
						"s_h_inp_row",
						"s_h_mag_row",
						"s_h_mag_col",
						"s_h_pack_row",
						"s_h_pack_maxcol",
						"s_mag_prt_grp_b",
						"s_mag_prt_grp_a",
						"s_lot_id",
						"s_rt_cd",
						"s_prc_cd",
						"s_stp_cd",
						"s_stp_no",
						"s_io_blc_cd",
						"s_prd_cd",
#						"s_equ_cd",
						"s_sum_chp_qty",
						"s_prt_ctrl",
						"s_nxt_stp_cd",
						"s_send_lot_id",
						"s_send_prd_cd",
						"s_send_stp_cd",
						"s_send_equ_cd",
						"s_send_chp_qty",
						"s_send_rt_cd",
						"s_send_prc_cd",
						"s_pckd",
						"s_plt_dvs_cd",
						"s_plt_flg",
						"s_h_pcs_row",
				),
				"value" => array(
				$gw_scr['s_dvsn_cd'],
						$gw_scr['s_lbl_id'],
						$gw_scr['s_lbl_type'],
						$gw_scr['s_h_inp_row'],
						$gw_scr['s_h_mag_row'],
						$gw_scr['s_h_mag_col'],
						$gw_scr['s_h_pack_row'],
						$gw_scr['s_h_pack_maxcol'],
						$gw_scr['s_mag_prt_grp_b'],
						$gw_scr['s_mag_prt_grp_a'],
						$gw_scr['s_lot_id'],
						$gw_scr['s_rt_cd'],
						$gw_scr['s_prc_cd'],
						$gw_scr['s_stp_cd'],
						$gw_scr['s_stp_no'],
						$gw_scr['s_io_blc_cd'],
						$gw_scr['s_prd_cd'],
#						$gw_scr['s_equ_cd'],
						$gw_scr['s_sum_chp_qty'],
						$gw_scr['s_prt_ctrl'],
						$gw_scr['s_nxt_stp_cd'],
						"",
						"",
						"",
						"",
						"",
						"",
						"",
						$gw_scr['s_pckd'],
						$gw_scr['s_plt_dvs_cd'],
						$gw_scr['s_plt_flg'],
						$gw_scr['s_h_pcs_row'],
				),
				"class" => "noborder",
		),
);

#======================================================================
# ロット一覧
#======================================================================
function create_lot_list_body($w_row, $w_index) {
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
			"s_lst_lot_id_" . $w_index => array(
					"matrix" => "2," . $w_row,
					"type" => "text",
					"name" => "s_lst_lot_id[$w_index]",
					"value" => $gw_scr['s_lst_lot_id'][$w_index],
					"size" => 20,
					"maxlength" => 15,
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
			),
			"s_lst_prd_nm_" . $w_index => array(
					"matrix" => "3," . $w_row,
					"type" => "disp",
					"name" => "s_lst_prd_nm[$w_index]",
					"value" => $gw_scr['s_lst_prd_nm'][$w_index]
			),
			"s_lst_lot_no_str_" . $w_index => array(
					"matrix" => "4," . $w_row,
					"type" => "disp",
					"name" => "s_lst_lot_no_str[$w_index]",
					"value" => $gw_scr['s_lst_lot_no_str'][$w_index]
			),
                        "s_lst_dte_cd_str_" . $w_index => array(
                                        "matrix" => "5," . $w_row,
                                        "type" => "disp",
                                        "name" => "s_lst_dte_cd_str[$w_index]",
                                        "value" => $gw_scr['s_lst_dte_cd_str'][$w_index]
                        ),
			"s_lst_chp_qty_" . $w_index => array(
					"matrix" => "6," . $w_row,
					"type" => "disp",
					"align" => "right",
					"num" => "true",
					"name" => "s_lst_chp_qty[$w_index]",
					"value" => $gw_scr['s_lst_chp_qty'][$w_index]
			),
			"s_lst_rmn_qty_" . $w_index => array(
					"matrix" => "7," . $w_row,
					"type" => "disp",
					"align" => "right",
					"num" => "true",
					"name" => "s_lst_rmn_qty[$w_index]",
					"value" => $gw_scr['s_lst_rmn_qty'][$w_index]
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
# ロット一覧
#======================================================================
function create_lot_list_total($w_row) {
        global $gw_scr;

        $w_tmp = array(
                        "s_lst_row_no_s"  => array(
                                        "matrix" => "1," . $w_row,
                                        "type" => "hidden",
                                        "width" => 24,
                                        "nowrap" => "true",
					"name" => "row1",
					"value" => "",
					"class" => "noborder",
                        ),
                        "s_lst_lot_id_s"  => array(
                                        "matrix" => "2," . $w_row ,
                                        "type" => "hidden",
                                        "size" => 20,
                                        "name" => "row2",
                                        "value" => "",
                                        "maxlength" => 15,
					"class" => "noborder",


                        ),
                        "s_lst_prd_nm_s"  => array(
                                        "matrix" => "3," . $w_row,
                                        "type" => "hidden",
                                        "name" => "row3",
                                        "value" => "",
					"class" => "noborder",
                        ),
                        "s_lst_lot_no_str_s"  => array(
                                        "matrix" => "4," . $w_row,
                                        "type" => "hidden",
                                        "name" => "row4",
                                        "value" => "",
					"class" => "noborder",
                        ),
                        "s_lst_dte_cd_str_s"  => array(
                                        "matrix" => "5," . $w_row,
                                        "type" => "disp",
					"name" => "row5",
                                        "value" => "Total", 
                                        "class" => "thcell",
                                        "nowrap" => "true",

                        ),
                        "s_lst_chp_qty_s" => array(
                                        "matrix" => "6," . $w_row,
                                        "type" => "disp",
                                        "align" => "right",
                                        "num" => "true",
                                        "name" => "s_sum_chp_qty",
                                        "value" => $gw_scr['s_sum_chp_qty']
                        ),
                        "s_lst_rmn_qty_s"  => array(
                                        "matrix" => "7," . $w_row,
                                        "type" => "hidden",
                                        "align" => "right",
                                        "num" => "true",
                                        "name" => "row6",
                                        "value" => "",
					"class" => "noborder",
                        ),
        );

        return $w_tmp;
}

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
				"value" => itm("LotId"),
				"class" => "thcell",
				"nowrap" => "true",
		),
		"s_lot_header_3" => array(
				"matrix" => "3," . $w_row,
				"type" => "disp",
				"value" => itm("PrdNm"),
				"class" => "thcell",
				"nowrap" => "true",
		),
		"s_lot_header_4" => array(
				"matrix" => "4," . $w_row,
				"type" => "disp",
				"value" => itm("LotNoStr"),
				"class" => "thcell",
				"nowrap" => "true",
		),
                "s_lot_header_5" => array(
                                "matrix" => "5," . $w_row,
                                "type" => "disp",
                                "value" => itm("DteCdStr"),
                                "class" => "thcell",
                                "nowrap" => "true",
                ),
		"s_lot_header_6" => array(
				"matrix" => "6," . $w_row,
				"type" => "disp",
				"value" => itm("OrgQty"),
				"class" => "thcell",
				"nowrap" => "true",
		),
		"s_lot_header_7" => array(
				"matrix" => "7," . $w_row,
				"type" => "disp",
				"value" => itm("RmnQty"),
				"class" => "thcell",
				"nowrap" => "true",
		),
);
$w_inp_row = $gw_scr['s_h_inp_row'];
$w_list_body = array();
for ($i = 1; $i <= $w_inp_row; $i++) {
	$w_tmp = create_lot_list_body($i + 1, $i);
	$w_list_body = $w_list_body + $w_tmp;
}
$w_tmp = create_lot_list_total($i + 1);	
$w_list_body = $w_list_body + $w_tmp;	
$w_inp_col = 8;
$w_list_ini = array(
		"cel" => "col",
		"matrix" => $w_inp_col . "," . ($w_inp_row + 2),
);

$GROUP[] = $w_list_ini + $w_list_header + $w_list_body;

#======================================================================
# Packing Type Name 
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "3,1",
	"s_pck_prd_nm"		=> array
	(
		"matrix"		=> "1,1",
		"title"			=> itm("PckTypNm"),
		"type"			=> "text",
		"name"			=> "s_pck_prd_nm",
		"value"			=> $gw_scr['s_pck_prd_nm'],
		"size"			=> 40,
		"maxlength"		=> 40,
		"readonly"		=> array
		(
			1 => "true",
			2 => ($gw_scr['s_pckd'] == "1")?("true"):(""),
			3 => "true",
			4 => "true",
			5 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => ($gw_scr['s_pckd'] == "1")?("dis_text"):(""),
			3 => "dis_text",
			4 => "dis_text",
			5 => "dis_text"
		),
		"ulist"		=> array
		(
			"prgno"		=> "PS00S06000170",
			"row"		=> 20,
			"width"		=> 600,
			"height"	=> 800,
			"arg"		=> array
			(
				"s_send_rt_cd"	=> "s_rt_cd",
				"s_send_prc_cd"	=> "s_prc_cd",
				"s_send_stp_cd"	=> "s_stp_cd"
			),
			"rtn"		=> array
			(
				"s_pck_prd_cd",
				"s_pck_prd_nm"
			),
		),
	),

	"s_pck_hdn"	=> array
	(
		"matrix"		=> "3,1",
		"type"			=> "hidden",
		"name"			=> array
		(
			"s_pck_prd_cd",
		),
		"value"			=> array
		(
			$gw_scr['s_pck_prd_cd'],
		),
		"class"			=> "noborder",
	),
);


#======================================================================
# Packing Comments
#======================================================================
$GROUP[] = array
(
        "cel"           => "col",
        "matrix"        => "3,1",
        "s_pck_rmks"          => array
        (
                "matrix"                => "1,1",
                "title"                 => itm("PckRmks"),
                "type"                  => "text",
                "name"                  => "s_pck_rmks",
                "value"                 => $gw_scr['s_pck_rmks'],
                "size"                  => 50,
                "maxlength"             => 250,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "false",
                        3 => "true",
                        4 => "true",
                        5 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "true",
                        3 => "dis_text",
                        4 => "dis_text",
                        5 => "dis_text"
                ),
        )
);


#======================================================================
# Packing Lot Qty
#======================================================================
$GROUP[] = array
(
        "cel"           => "col",
        "matrix"        => "3,1",
        "s_pck_prd_qty"          => array
        (
                "matrix"                => "1,1",
                "title"                 => itm("PckTypQty"),
                "type"                  => "text",
                "name"                  => "s_pck_prd_qty",
                "value"                 => $gw_scr['s_pck_prd_qty'],
                "size"                  => 15,
                "maxlength"             => 15,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "true",
                        3 => "false",
                        4 => "true",
			5 => "true",
			6 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "dis_text",
                        3 => "true",
                        4 => "dis_text",
			5 => "dis_text"	,
			6 => "dis_text"
                ),
        )
);

#======================================================================
# Packing Lot ID
#======================================================================
function create_pack_list_header($w_row, $w_pack_maxcol) {
	$w_col = 0;

	if ($w_row == 1) {
                $w_tmp = array(
                                "s_pack_header_11" => array(
                                                "matrix" => (++$w_col) . "," . $w_row,
                                                "type" => "disp",
                                                "value" => "",
                                                "class" => "noborder",
                                ),
                                "s_pack_header_12" => array(
                                                "matrix" => (++$w_col) . "," . $w_row,
                                                "type" => "disp",
                                                "value" => "",
                                                "class" => "noborder",
                                ),
                                "s_pack_header_13" => array(
                                                "matrix" => (++$w_col) . "," . $w_row,
                                                "type" => "disp",
                                                "value" => itm("ChpQty"),
                                                "class" => "thcell",
                                                "dt_colspan" => ($w_pack_maxcol + 1),
                                ),
                );
	
	} else {
	$w_tmp = array(
			"s_pack_header_".$w_row."1" => array(
					"matrix" => (++$w_col) . "," . $w_row,
					"type" => "disp",
					"value" => "",
					"class" => "noborder",
			),
			"s_pack_header_".$w_row."2" => array(
					"matrix" => (++$w_col) . "," . $w_row,
					"type" => "disp",
					"value" => (itm("PckIDLotId")),
					"class" => "thcell",
			),
	);

	for ($i = 1; $i <= $w_pack_maxcol; $i++) {
		$w_dat = array(
				"s_pack_header_".$w_row."3_" . $i => array(
						"matrix" => (++$w_col) . "," . $w_row,
						"type" => "disp",
						"value" => "&nbsp;($i)&nbsp;",
						"width" => 20,
						"class" => "thcell",
				),
		);
		$w_tmp = $w_tmp + $w_dat;
	}
	$w_dat = array(
			"s_pack_header_".$w_row."4" => array(
					"matrix" => (++$w_col) . "," . $w_row,
					"type" => "disp",
					"value" => itm("TtlQty"),
					"class" => "thcell",
			),
	);
	$w_tmp = $w_tmp + $w_dat;
	}
	return $w_tmp;
}
function create_pack_list_body($w_itm, $w_row, $w_index, $w_pack_maxcol) {
	global $gw_scr;
	$w_col = 0;
	$w_tmp = array(
			"s_".$w_itm."_row_no" . $w_index => array(
					"matrix" => (++$w_col) . "," . $w_row,
					"type" => "disp",
					"value" => $w_index,
					"class" => "thcell",
					"width"	=> 24,
					"nowrap" => "true",
			),
			"s_".$w_itm."_lot_id_" . $w_index => array(
					"matrix" => (++$w_col) . "," . $w_row,
					"type" => "disp",
					"name" => "s_".$w_itm."_lot_id[$w_index]",
					"value" => $gw_scr['s_'.$w_itm.'_lot_id'][$w_index],
			),
	);

	for ($i = 1; $i <= $w_pack_maxcol; $i++) {
		$w_dat = array(
				"s_".$w_itm."_chp_qty_" . $w_index . "_" . $i => array(
						"matrix" => (++$w_col) . "," . $w_row,
						"type"   => array("hidden","hidden","hidden","disp","disp","disp"),
						"name"   => "s_".$w_itm."_chp_qty[$w_index][$i]",
						"value"  => $gw_scr['s_'.$w_itm.'_chp_qty'][$w_index][$i],
						"width"	 => 20,
						"readonly"	=> array
						(
							0 => "true",
							1 => "true",
							2 => "true",
							3 => "true",
							4 => "true",
							5 => "true"
						),
						"itm_cls"		=> array
						(
							0 => "dis_text",
							1 => "dis_text",
							2 => "dis_text",
							3 => "dis_text",
							4 => "dis_text",
							5 => "dis_text"
						),
						"align" => "right",
						"num" => "true",
				),
		);
		$w_tmp = $w_tmp + $w_dat;
	}
	$w_ttl = array(
			"s_".$w_itm."_chp_qty_ttl_" . $w_index => array(
					"matrix" => (++$w_col) . "," . $w_row,
#					"type" => "disp",
					"type" => array("hidden","hidden","hidden","disp","disp","disp"),
					"name" => "s_".$w_itm."_chp_qty_ttl[$w_index]",
					"value" => $gw_scr['s_'.$w_itm.'_chp_qty_ttl'][$w_index],
					"align" => "right",
					"num" => "true",
			),
	);
	$w_tmp = $w_tmp + $w_ttl;

	$w_space = array(
			"s_".$w_itm."_space_" . $w_index => array(
					"matrix" => (++$w_col) . "," . $w_row,
					"type" => "disp",
					"value" => "",
					"class" => "noborder",
			),
	);
	$w_tmp = $w_tmp + $w_space;

	$w_hdn_names = array(
			"s_".$w_itm."_prc_cd[$w_index]",
			"s_".$w_itm."_stp_cd[$w_index]",
			"s_".$w_itm."_prd_cd[$w_index]",
	);
	$w_hdn_values = array(
			$gw_scr['s_'.$w_itm.'_prc_cd'][$w_index],
			$gw_scr['s_'.$w_itm.'_stp_cd'][$w_index],
			$gw_scr['s_'.$w_itm.'_prd_cd'][$w_index],
	);

	$w_hdn = array(
			"s_hdn_".$w_itm."_" . $w_index => array(
					"matrix" => (++$w_col) . "," . $w_row,
					"type" => "hidden",
					"name" => $w_hdn_names,
					"value" => $w_hdn_values,
					"class" => "noborder",
			),
	);
	$w_tmp = $w_tmp + $w_hdn;

	return $w_tmp;
}
$w_row = 0;
$w_pack_maxcol = $gw_scr['s_h_pack_maxcol'];
$w_pack_maxrow = $gw_scr['s_h_pack_row'];
$w_pack_head = array();
$w_pack_head = $w_pack_head + create_pack_list_header(++$w_row, $w_pack_maxcol);
$w_pack_head = $w_pack_head + create_pack_list_header(++$w_row, $w_pack_maxcol);
$w_pack_dat = array();
for ($i = 1; $i <= $w_pack_maxrow; $i++) {
	$w_tmp = create_pack_list_body("pack", ++$w_row, $i, $w_pack_maxcol);
	$w_pack_dat = $w_pack_dat + $w_tmp;
}

$w_max_col = 2 + (($w_pack_maxcol + 1) * 2) + 1/*space*/ + 1/*blackcase*/ + 1/*hidden*/;

$w_tmp = array
(
	"dmyspan"	=> array
	(
		"matrix"		=> "1,".++$w_row,
		"type"			=> "disp",
		"value"			=> " ",
		"height"		=> 10,
		"dt_colspan"	=> $w_max_col,
		"class"			=> "noborder",
	)
);
$w_pack_dat = $w_pack_dat + $w_tmp;

$w_max_row = $w_row;

$w_pack_ini = array(
		"cel" => "col",
		"matrix" => $w_max_col . "," . ($w_max_row),
);
$GROUP[] = $w_pack_ini + $w_pack_head + $w_pack_dat;

$GROUP[] = array
(
        "cel"           => "col",
        "matrix"        => "1,1",
        "class"         => array
        (
                1 => "",
                2 => "",
                3 => "",
                4 => "",
		5 => "",
		6 => ""
        ),
        "s_pack_pcs"           => array
        (
                "matrix"                => "1,1",
                "title"                 => itm("PPCS"),
                "type"                  => array(
                        1       =>      "select",
                        2       =>      "select",
                        3       =>      "select",
                        4       =>      "select",
			5	=> 	"select",
			6 	=>	"select"
                ),
                "name"                  => "s_pack_pcs",
                "value"                 => $gw_scr['s_ppcs_val'],
                "size"                  => 30,
                "maxlength"             => 13,
                "option"                => $gw_scr['s_opt_pack_pcs'],
                "onchange"              => "jgt_toggle_enable(this, {$gw_scr['edit_enable']})",
                "disabled"              => array
                (
                        1 => "true",
                        2 => $gw_scr['edit_enable']? "false":"true",
                        3 => "true",
                        4 => "true",
			5 => "true",
			6 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => $gw_scr['edit_enable']? "":"dis_text",
                        3 => "dis_text",
                        4 => "dis_text",
			5 => "dis_text",
			6 => "dis_text"
                ),
        )
);


###################
# Start PCS Contrl
###################
$w_pcs_row = $gw_scr['s_h_pcs_row'];
function crt_pcs_control($x, $y, $r)
{
        global $gw_scr;

        $w_obj = array
        (

                "s_pcs_param_list_" .$r          => array
                (
                        "matrix"                => $x. ",". $y,
                        "title"                 => $gw_scr['s_pcs_param_list_nm'][$r],
                        "type"                  => array(
                                1       =>      "select",
                                2       =>      "select",
                                3       =>      "select",
                                4       =>      "select",
				5	=> 	"select"
                        ),
                        "name"                  => "s_pcs_param_list[$r]",
                        "value"                 => $gw_scr['s_pcs_param_list'][$r],
                        "size"                  => 30,
                        "maxlength"             => 13,
                        "option"                => array
                        (
                                "0" => "",
                                "1" => "OK",
                                "2" => "NG"
                        ),
                        "disabled"              => array
                        (
                                1 => "",
                                2 => "",
                                3 => "true",
                                4 => "true",
				5 => "true"
                        ),
                        "itm_cls"               => array
                        (
                                1 => "",
                                2 => "",
                                3 => "dis_text",
                                4 => "dis_text",
				5 => "dis_text"
                        ),
                ),
        );
        return $w_obj;
};

$w_dat2 = array();
for($i=1; $i<=$w_pcs_row; $i++){
        $x = 1;
        $y = $i + 1;
        $w_tmp2 = crt_pcs_control($x, $y, $i);
        $w_dat2 = $w_dat2 + $w_tmp2;
}

$w_head2 = array
(
        "cel"           => "col",
        "matrix"        => "1,". ($w_pcs_row+1),
        "s_ttl_pcs_id" => array
        (
                "matrix"                => "1,1",
                "type"                  => "disp",
                "value"                 => itm("PCS_Items"),
                "class"                 => "thcell",
                "nowrap"                => "true",
                "dt_colspan"            => 2,
        ),

);
if(count($w_pcs_row) > 0){
        $GROUP[] = array
        (
                $w_head2 + $w_dat2
        );
}
##################
# End PCS Control
##################

#======================================================================
# メッセージ
#======================================================================
$GROUP[] = array(
		"cel" => "col",
		"matrix" => "1,2",
		"s_mes2" => array(
				"matrix" => "1,1",
				"type" => "msg",
				"value" => $g_msg,
				"lev" => $g_err_lv
		),
		"s_message" => array(
				"matrix" => "1,2",
				"type" => ($gw_scr['s_prnt_msg'] != "" ? "msg" : "none"),
				"value" => $gw_scr['s_prnt_msg'],
				"lev" => $gw_scr['s_prnt_lv']
		)
);

#======================================================================
# button
#======================================================================
$GROUP[] = array(
		"cel" => "col",
		"matrix" => "5, 1",
		"class" => "noborder",
		"s_chk" => array(
				"matrix" => "1, 1",
				"type" => array(
						1 => "button",
						"button",
						"button",
						"none",
						"none"
				),
				"name" => "s_chk",
				"value" => button_name("Check"),
				"onclick" => "jgt_page_action('CHECK')"
		),
		"s_sub" => array(
				"matrix" => "2, 1",
				"type" => array(
						1 => "none",
						"none",
						"none",
						"button",
						"none"
				),
				"name" => "s_sub",
				"value" => button_name("Execute"),
				"onclick" => "jgt_page_action('EXECUTE')"
		),
		"s_erase" => array(
				"matrix" => "3, 1",
				"type" => array(
						1 => "button",
						"button",
						"button",
						"none",
						"none"
				),
				"name" => "s_erase",
				"value" => button_name("Erase"),
				"onclick" => "jgt_page_action('ERASE')"
                ),
                "s_rtn" => array(
                                "matrix" => "4, 1",
                                "type" => array(
                                                1 => "none",
                                                "button",
                                                "button",
                                                "button",
						"button"	
                                ),
                                "name" => "s_rtn",
                                "value" => button_name("Return"),
                                "onclick" => "jgt_page_action('RETURN')"
                ),
                "s_mat" => array(
                                "matrix" => "5, 1",
                                "type" => array(
                                                1 => "none",
                                                2 => "none",
						3 => "none",
                                                4 => ($gw_scr['s_prt_ctrl'] == "1") ? ("button")
                                                                : ("none"),
                                ),
                                "name" => "s_mat",
                                "value" => button_name("MaterialManage"),
                                "width" => 150,
                                "align" => "right",
                                "onclick" => "jgt_return('" . constant("PGM_MTMNG") . "',"
                                                . "'s_send_lot_id'," . "'" . $gw_scr['s_lot_id'] . "',"
                                                . "'s_send_prd_cd'," . "'" . $gw_scr['s_pck_prd_cd'] . "',"
                                                . "'s_send_stp_cd'," . "'" . $gw_scr['s_nxt_stp_cd'] . "',"
#                                                . "'s_send_equ_cd'," . "'" . $gw_scr['s_equ_cd']
                                               . "'s_send_equ_cd'," . "'" . $gw_scr['s_equ_cd'] . "',"
                                                . "'s_send_chp_qty'," . "'" . $gw_scr['s_pck_prd_qty']
                                                . "')"
                ),
);

#======================================================================
# 認証
#======================================================================
$GROUP[] = array(
                "cel" => "col",
                "matrix" => "1,1",
                "class" => "noborder",
                "s_hidden_ren" => array(
                                "matrix" => "1,1",
                                "type" => "hidden",
                                "name" => array(
                                                "s_renzheng",
                                                "s_renzheng_db",
                                                "s_renzheng_t",
						"s_ppcs_val",
						"s_is_packing"
                                ),
                                "value" => array(
                                                $gw_scr['s_renzheng'],
                                                $gw_scr['s_renzheng_db'],
                                                $gw_scr['s_renzheng_t'],
						$gw_scr['s_ppcs_val'],
						$gw_scr['s_is_packing']
                                ),
                ),
);

#==================================================================
# セッション
#==================================================================
$g_session = array();
for ($i = 0; $i < count($GROUP); $i++) {
	foreach ($GROUP[$i] as $key => $arr) {
		if (is_numeric($key)) {
			foreach ($arr as $kk => $vv) {
				search_scr_id($kk, $vv);
			}
		} else {
			search_scr_id($key, $arr);
		}
	}
	reset($GROUP[$i]);
}
reset($GROUP);

function search_scr_id($s_key, $s_arr) {
	global $g_session;
	if (preg_match("/^s_/", $s_key)) { # s_ で始まるキーを取得
		if (is_array($s_arr['type'])) {
			if (in_array("button", $s_arr['type']) # type=button
 || in_array("none", $s_arr['type']) # type=none
 || !isset($s_arr['name']) # nameが未設定
			) { # の項目は
				return; # 飛ばす
			}
		} else {
			if ($s_arr['type'] == "button" || $s_arr['type'] == "none"
					|| !isset($s_arr['name'])) {
				return;
			}
		}
		$g_session[] = $s_key;
	}
}
#======================================================================
# 外部JavaScript
#======================================================================
$g_js_o = array(
		"jgt_return.js",
);
#======================================================================
# 初期カーソル位置
#======================================================================
$g_def = array(
		1 => "s_usr_id",
);

?>
