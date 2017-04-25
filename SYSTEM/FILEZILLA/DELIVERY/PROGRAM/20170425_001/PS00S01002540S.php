<?php
# ========================================================================================
# [DATE]  : 2012.05.23				[AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM				[SYSTEM]  : CCD
# [SUB_ID]:					[SUBSYS]  : 
# [PRC_ID]:					[PROCESS] : 
# [PGM_ID]: PS00S01002540S.php			[PROGRAM] : DELIVERY (CCD)
# [MDL_ID]:					[MODULE]  : 
# ----------------------------------------------------------------------------------------
# [COMMENT]
# 
# ----------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]	[UPDATE]		[COMMENT]
# ====================  ======================  ==========================================
# DOS)H.Otsuka		I140411-0000003		返品ロット対応(UTAC対応)
# DOS)Mydel             2017.04.24              For CCD Department
# ----------------------------------------------------------------------------------------
global $gw_scr;
global $g_msg;
global $g_err_lv;
global $g_PrgCD;
global $g_mode;
#======================================================================
# ユーザＩＤ〜ロット票出力先
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "3,5",

	"s_usr_id"		=> array
	(
		"matrix"		=> "1,1",
		"title"			=> itm("UsrId"),
		"type"			=> "text",
		"name"			=> "s_usr_id",
		"value"			=> $gw_scr['s_usr_id'],
		"size"			=> 20,
		"maxlength"		=> 13,
		"readonly"		=> array
		(
			1 => "",
			2 => "true",
			3 => "true",
		),
		"itm_cls"		=> array
		(
			1 => "",
			2 => "dis_text",
			3 => "dis_text",
		),
	),

	"s_usr_nm"		=> array
	(
		"matrix"		=> "3,1",
		"type"			=> "disp",
		"name"			=> "s_usr_nm",
		"value"			=> $gw_scr['s_usr_nm'],
		"nowrap"		=> "true",
		"width"			=> 300,
	),

	"s_lp_cd"		=> array
	(
		"matrix"		=> "1,2",
		"title"			=> itm("LpCd"),
		"type"			=> "text",
		"name"			=> "s_lp_cd",
		"value"			=> $gw_scr['s_lp_cd'],
		"size"			=> 30,
		"maxlength"		=> 20,
		"hidden"		=> array
		(
			"s_lp_tag"		=> "LP"
		),
		"readonly"		=> array
		(
			1 => "",
			2 => "true",
			3 => "true",
		),
		"itm_cls"		=> array
		(
			1 => "",
			2 => "dis_text",
			3 => "dis_text",
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
				"s_info"	=> "s_dvsn_cd"
			),
			"rtn"   => array
			(
				"s_lp_nm",
				"s_lp_cd"				
			),
		),
	),

	"s_lp_nm"		=> array
	(
		"matrix"		=> "3,2",
		"type"			=> "text",
		"name"			=> "s_lp_nm",
		"value"			=> $gw_scr['s_lp_nm'],
		"size"			=> 50,
		"readonly"		=> "true",
		"itm_cls"		=> "dis_text",
	),

        "s_prt_no"              => array
        (
                "matrix"                => "1,3",
                "title"                 => itm("PrintNo"),
                "type"                  => "text",
                "name"                  => "s_prt_no",
                "value"                 => $gw_scr['s_prt_no'],
                "size"                  => 3,
                "maxlength"             => 3,
                "readonly"              => array
                (
                        1 => "",
                        2 => "true",
                        3 => "true",
                ),
                "itm_cls"               => array
                (
                        1 => "",
                        2 => "dis_text",
                        3 => "dis_text",
                ),
        ),

        "s_prt_nm"               => array
        (
                "matrix"                => "3,3",
                "type"                  => "text",
                "name"                  => "s_prt_nm",
                "value"                 => itm("PrintNote"),
                "size"                  => 50,
                "readonly"              => "true",
                "itm_cls"               => "dis_text",
        ),

	"s_hdn"		=> array
	(
		"matrix"		=> "1,4",
		"type"			=> "hidden",
		"name"			=> array
		(
			"s_dvsn_cd",
			"s_tag_lp",
			"s_retlot_flg",
			
		),
		"value"			=> array
		(
			$gw_scr['s_dvsn_cd'],
			"LP",
			$gw_scr['s_retlot_flg'],
		),
		"class"			=> "noborder",
	),
);

#======================================================================
# ボタン
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
			2 => "none",
			3 => "none",
		),
		"name"			=> "s_chk",
		"value"			=> button_name("Check"),
		"onclick"		=> "jgt_page_action('CHECK', '', '1')",
	),

	"s_exe"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "none",
			2 => "button",
			3 => "none",
		),
		"name"			=> "s_exe",
		"value"			=> button_name("Execute"),
		"onclick"		=> "jgt_page_action('EXECUTE', '', '1')",
	),

	"s_ers"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "button",
			2 => "none",
			3 => "none",
		),
		"name"			=> "s_ers",
		"value"			=> button_name("Erase"),
		"onclick"		=> "jgt_page_action('ERASE', '', '1')",
	),

	"s_back"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "none",
			2 => "button",
			3 => "button",
		),
		"name"			=> "s_back",
		"value"			=> button_name("Return"),
		"onclick"		=> "jgt_page_action('BACK', '', '1')",
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
# 合計チップ
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "2,1",

	"s_total"		=> array
	(
		"matrix"		=> "1,1",
		"title"			=> itm("Total"),
		"type"			=> "disp",
		"name"			=> "s_total",
		"value"			=> $gw_scr['s_total'],
		"width"			=> 120,
		"align"			=> "right",
		"nowrap"		=> "true",
	),
);

#======================================================================
# ロットＩＤ
#======================================================================
function crt_line(&$x, &$y, $i)
{
	global $gw_scr;
	$x = 0;
	$w_tmp = array
	(
		"rownum_".$i		=> array
		(
			"matrix"		=> ++$x.",".++$y,
			"type"			=> "disp",
			"value"			=> $i,
			"nowrap"		=> "true",
			"class"			=> "thcell",
		),

		"s_list_lot_id_".$i		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "text",
			"name"			=> "s_list_lot_id[$i]",
			"value"			=> $gw_scr['s_list_lot_id'][$i],
			"size"			=> 30,
			"maxlength"		=> 23,
			"readonly"		=> array
			(
				1 => "",
				2 => "true",
				3 => "true",
			),
			"itm_cls"		=> array
			(
				1 => "",
				2 => "dis_text",
				3 => "dis_text",
			),
		),

		"s_list_prd_nm_".$i		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "disp",
			"name"			=> "s_list_prd_nm[$i]",
			"value"			=> $gw_scr['s_list_prd_nm'][$i],
			"nowrap"		=> "true",
		),

		"s_list_chp_qty_".$i		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "disp",
			"name"			=> "s_list_chp_qty[$i]",
			"value"			=> $gw_scr['s_list_chp_qty'][$i],
			"align"			=> "right",
			"nowrap"		=> "true",
		),

		"s_list_hdn_".$i		=> array
		(
			"matrix"		=> ++$x.",".$y,
			"type"			=> "hidden",
			"name"			=> array
			(
				"s_list_hdn_lot_id[$i]",
				"s_list_upd_lev[$i]",
				"s_list_blk_cs_id[$i]",
			),
			"value"			=> array
			(
				$gw_scr['s_list_hdn_lot_id'][$i],
				$gw_scr['s_list_upd_lev'][$i],
				$gw_scr['s_list_blk_cs_id'][$i],
			),
			"class"			=> "noborder",
		),
	);
	return $w_tmp;
}
$x = 0;
$y = 0;
$w_head = array
(
	"ttldmy"		=> array
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

	"ttl_prdnm"		=> array
	(
		"matrix"		=> ++$x.",".$y,
		"type"			=> "disp",
		"value"			=> itm("PrdNm"),
		"class"			=> "thcell",
		"width"			=> 250,
		"nowrap"		=> "true",
	),

	"ttl_chpqty"		=> array
	(
		"matrix"		=> ++$x.",".$y,
		"type"			=> "disp",
		"value"			=> itm("ChpQty"),
		"class"			=> "thcell",
		"width"			=> 100,
		"nowrap"		=> "true",
	),
);

$crnty = $y;
$w_dat = array();
for($i=0; $i<constant("INI_BLC"); $i++){
	$y = $crnty;
	$w_dat[$i] = array();
	$w_wrk     = array();
	for($j=1; $j<=constant("INI_ROW"); $j++){
		$r = $j + (constant("INI_ROW") * $i);
		$w_tmp = crt_line($x, $y, $r);
		$w_wrk = $w_wrk + $w_tmp;
	}

	### 最終行にトータル行追加
	$w_ttl = array();
	if($i == (constant("INI_BLC") - 1)){
		$w_ttl = array
		(
			"s_dtl_total"		=> array
			(
				"matrix"		=> "1,".++$y,
				"type"			=> "disp",
				"title"			=> itm("Total"),
				"name"			=> "s_dtl_total",
				"value"			=> $gw_scr['s_dtl_total'],
				"align"			=> "right",
				"ttl_colspan"	=> ($x - 2),
			)
		);
	}

	$w_ini = array
	(
		"cel"		=> "col",
		"matrix"	=> $x.",".$y,
	);
	$w_dat[$i] = $w_ini + $w_head + $w_wrk + $w_ttl;
}
$GROUP[] = $w_dat;


#======================================================================
# メッセージ
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "1,2",

	# メッセージ領域
	"s_message"	=> array
	(
		"matrix"		=> "1,1",
		"type"			=> "msg",
		"value"			=> $g_msg,
		"lev"			=> $g_err_lv
	),

	"s_prnt_msg"	=> array
	(
		"matrix"		=> "1,2",
		"type"			=> "msg",
		"value"			=> $gw_scr['s_prnt_msg'],
		"lev"			=> $gw_scr['s_prnt_lv'],
	),
);

#======================================================================
# ボタン
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
			2 => "none",
			3 => "none",
		),
		"name"			=> "s_chk",
		"value"			=> button_name("Check"),
		"onclick"		=> "jgt_page_action('CHECK', '', '1')",
	),

	"s_exe"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "none",
			2 => "button",
			3 => "none",
		),
		"name"			=> "s_exe",
		"value"			=> button_name("Execute"),
		"onclick"		=> "jgt_page_action('EXECUTE', '', '1')",
	),

	"s_ers"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "button",
			2 => "none",
			3 => "none",
		),
		"name"			=> "s_ers",
		"value"			=> button_name("Erase"),
		"onclick"		=> "jgt_page_action('ERASE', '', '1')",
	),

	"s_back"		=> array
	(
		"matrix"		=> ++$x.",1",
		"type"			=> array
		(
			1 => "none",
			2 => "button",
			3 => "button",
		),
		"name"			=> "s_back",
		"value"			=> button_name("Return"),
		"onclick"		=> "jgt_page_action('BACK', '', '1')",
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
# 内部JavaScript
#======================================================================
$g_js_i = "";
$g_js_i = str_replace("\n", "", $g_js_i);
$g_js_i = str_replace("\t", "", $g_js_i);
#======================================================================
# 外部JavaScript読込
#======================================================================
$g_js_o = array();
#======================================================================
# 初期カーソル位置
#======================================================================
$g_def = array
(
	1 => "s_usr_id",
);
?>
