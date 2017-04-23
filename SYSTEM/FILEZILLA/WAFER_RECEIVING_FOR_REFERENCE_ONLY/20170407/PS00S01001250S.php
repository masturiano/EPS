<?php
# ======================================================================================
# [DATE]  : 2014.02.10          		[AUTHOR]  : MIS) Paul
# [SYS_ID]: GPRISM						[SYSTEM]  : ÁÈÎ©É¸½àCIM
# [SUB_ID]:								[SUBSYS]  : 
# [PRC_ID]:								[PROCESS] : 
# [PGM_ID]: PS00S01001250S.php			[PROGRAM] : Wafer Receive(LDHU)
# [MDL_ID]:								[MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
# 
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]		[UPDATE]			[COMMENT]
# ====================	==================	============================================
# 
# --------------------------------------------------------------------------------------

global $gw_scr;
global $g_msg;
global $g_err_lv;
global $g_PrgCD;

#======================================================================
# ¥æ¡¼¥¶£É£Ä¡Á¥í¥Ã¥ÈÉ¼½ÐÎÏÀè
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "3,17",
	"class"		=> array
	(
		1 => "",
		2 => "",
		3 => "",
	),

	# ¥æ¡¼¥¶£É£Ä
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
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "",
			2 => "dis_text",
			3 => "dis_text",
			4 => "dis_text"
		),
	),

        "s_usr_nm"              => array
        (
                "matrix"                => "3,1",
                "type"                  => "disp",
                "name"                  => "s_usr_nm",
                "value"                 => $gw_scr['s_usr_nm'],
                "width"                 => 200,
        ),

        # ¢¿¢ªª¡?«¿?ªª?ª²¡¿¡¿
        "s_sap_lot_id"   => array
        (
                "matrix"                => "1,2",
                "title"                 => itm("SapLotID"),
                "type"                  => "text",
                "name"                  => "s_sap_lot_id",
                "value"                 => $gw_scr['s_sap_lot_id'],
                "size"                  => 30,
                "maxlength"             => 30,
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
        ),


	# ¥Á¥Ã¥×ÉÊÌ¾
	"s_prd_nm"		=> array
	(
		"matrix"		=> "1,3",
		"title"			=> itm("ChpNm"),
		"type"			=> "text",
		"name"			=> "s_prd_nm",
		"value"			=> $gw_scr['s_prd_nm'],
		"size"			=> 40,
		"maxlength"		=> 30,
		"readonly"		=> array
		(
			1 => "true",
			2 => "true",
			3 => "true",
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => "dis_text",
			3 => "dis_text",
			4 => "dis_text",
		),
		"list"		=> array
		(
			"xpt_prd_in_dvsn_prccls4",
			"50",
			$g_PrgCD,
			"s_prd_cd",
			"s_prd_nm",
			"s_dvsn_cd",
			constant("D6_BGA_INF"),
		),
	),
	"s_prd_cd"		=> array
	(
		"matrix"		=> "3,3",
		"type"			=> "text",
		"name"			=> "s_prd_cd",
		"value"			=> $gw_scr['s_prd_cd'],
		"size"			=> 30,
		"readonly"		=> "true",
		"itm_cls"		=> "dis_text",
	),

	# ÁÈÎ©´°À®ÉÊÌ¾
	"s_prd_nm_fin"	=> array
	(
		"matrix"		=> "1,4",
		"title"			=> itm("AssPrdNm"),
		"type"			=> "text",
		"name"			=> "s_prd_nm_fin",
		"value"			=> $gw_scr['s_prd_nm_fin'],
		"size"			=> 40,
		"maxlength"		=> 30,
		"readonly"		=> array
		(
			1 => "true",
			2 => ($gw_scr['s_fin_flg']==1)?"true":"false",
			3 => "true",
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => ($gw_scr['s_fin_flg']==1)?"dis_text":"",
			3 => "dis_text",
			4 => "dis_text"
		),
		"ulist"         => array
                (
                        "prgno"         => "PS00S06000520",
                        "row"           => 10,
                        "width"         => 800,
                        "height"        => 600,
                        "arg"           => array
                        (
                                "s_wf_cd"     => "s_prd_cd"
                        ),
                        "rtn"   => array
                        (
                                "s_prd_cd_fin",
                                "s_prd_nm_fin",
                        ),
                ),

	),
	"s_prd_cd_fin"	=> array
	(
		"matrix"		=> "3,4",
		"type"			=> "text",
		"name"			=> "s_prd_cd_fin",
		"value"			=> $gw_scr['s_prd_cd_fin'],
		"size"			=> 30,
		"readonly"		=> "true",
		"itm_cls"		=> "dis_text",
	),

	# ¥é¥ó¥¯
	"s_rnk"	=> array
	(
		"matrix"		=> "1,5",
		"title"			=> itm("Rnk"),
		"type"			=> "text",
		"name"			=> "s_rnk",
		"value"			=> $gw_scr['s_rnk'],
		"size"			=> 20,
		"maxlength"		=> 10,
		"readonly"		=> array
		(
			1 => "true",
			2 => "true",
			3 => "true",
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => "dis_text",
			3 => "dis_text",
			4 => "dis_text",
		),
		"list"		=> array
		(
			"xpt_pr_grd_mst",
			"20",
			$g_PrgCD,
			"s_rnk",
			constant("GR_IT_AIMS"),
			"s_prd_nm",
		),
	),


        # ?ª²¢ä?«¿?ªª?ª²¡ò¡ò
        "s_dif_lot_no"  => array
        (
                "matrix"                => "1,6",
                "title"                 => itm("DiffLotNo"),
                "type"                  => "text",
                "name"                  => "s_dif_lot_no",
                "value"                 => $gw_scr['s_dif_lot_no'],
                "size"                  => 40,
                "maxlength"             => 30,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "true",
                        3 => "true",
			4 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "dis_text",
                        3 => "dis_text",
			4 => "dis_text"
                ),
        ),

	# ºàÎÁ¥í¥Ã¥È£É£Ä
	"s_mt_lot_id"	=> array
	(
		"matrix"		=> "1,7",
		"title"			=> itm("MtLotID"),
		"type"			=> "text",
		"name"			=> "s_mt_lot_id",
		"value"			=> $gw_scr['s_mt_lot_id'],
		"size"			=> 30,
		"maxlength"		=> 30,
		"readonly"		=> array
		(
			1 => "true",
			2 => "true",
			3 => "true",
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => "dis_text",
			3 => "dis_text",
			4 => "dis_text"
		),
	),


        # ¥ê¥ó¥°¿ôÎÌ
        "s_sl_inf"     => array
        (
                "matrix"                => "1,8",
                "title"                 => itm("SlInf"),
                "type"                  => "text",
                "name"                  => "s_sl_inf",
                "value"                 => $gw_scr['s_sl_inf'],
                "size"                  => 30,
                "maxlength"             => 30,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "true",
                        3 => "true",
			4 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "dis_text",
                        3 => "dis_text",
			4 => "dis_text"
                ),
        ),

        # ¥ê¥ó¥°¿ôÎÌ
        "s_sl_qty"     => array
        (
                "matrix"                => "1,9",
                "title"                 => itm("SlQty"),
                "type"                  => "text",
                "name"                  => "s_sl_qty",
                "value"                 => $gw_scr['s_sl_qty'],
                "size"                  => 12,
                "maxlength"             => 12,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "true",
                        3 => "true",
			4 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "dis_text",
                        3 => "dis_text",
			4 => "dis_text"
                ),
        ),

	# ¥Á¥Ã¥×¿ôÎÌ
	"s_chp_qty"	=> array
	(
		"matrix"		=> "1,10",
		"title"			=> itm("ChpQty"),
		"type"			=> "text",
		"name"			=> "s_chp_qty",
		"value"			=> $gw_scr['s_chp_qty'],
		"size"			=> 12,
		"maxlength"		=> 10,
		"readonly"		=> array
		(
			1 => "true",
			2 => "true",
			3 => "true",
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => "dis_text",
			3 => "dis_text",
			4 => "dis_text"
		),
	),

        # ¥Á¥Ã¥×¿ôÎÌ
        "s_exp_dte"     => array
        (
                "matrix"                => "1,11",
                "title"                 => itm("ExpDte"),
                "type"                  => "text",
                "name"                  => "s_exp_dte",
                "value"                 => $gw_scr['s_exp_dte'],
                "size"                  => 30,
                "maxlength"             => 30,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "true",
                        3 => "true",
			4 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "dis_text",
                        3 => "dis_text",
			4 => "dis_text"
                ),
        ),

        # ¥Á¥Ã¥×¿ôÎÌ
        "s_mfg_dte"     => array
        (
                "matrix"                => "1,12",
                "title"                 => itm("MfgDte"),
                "type"                  => "text",
                "name"                  => "s_mfg_dte",
                "value"                 => $gw_scr['s_mfg_dte'],
                "size"                  => 30,
                "maxlength"             => 30,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "true",
                        3 => "true",
			4 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "dis_text",
                        3 => "dis_text",
			4 => "dis_text"
                ),
        ),


	# ¥í¥Ã¥È¶èÊ¬¥³¡¼¥É
	"s_lot_typ_cd"	=> array
	(
		"matrix"		=> "1,13",
		"title"			=> itm("LotClsCd"),
		"type"			=> "text",
		"name"			=> "s_lot_typ_cd",
		"value"			=> $gw_scr['s_lot_typ_cd'],
		"size"			=> 20,
		"maxlength"		=> 7,
		"readonly"		=> array
		(
			1 => "true",
			2 => "",
			3 => "true",
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => "",
			3 => "dis_text",
			4 => "dis_text"
		),
		"list"		=> array
		(
			"xpt_nm_mst",
			"20",
			$g_PrgCD,
			"s_lot_typ_cd",
			"s_lot_typ_nm",
			constant("TG_CC"),
		),
	),
	"s_lot_typ_nm"	=> array
	(
		"matrix"		=> "3,13",
		"type"			=> "text",
		"name"			=> "s_lot_typ_nm",
		"value"			=> $gw_scr['s_lot_typ_nm'],
		"size"			=> 40,
		"readonly"		=> "true",
		"itm_cls"		=> "dis_text",
	),

	# ¥í¥Ã¥È¼±ÊÌ¥³¡¼¥É
	"s_lot_dsc_cd"	=> array
	(
		"matrix"		=> "1,14",
		"title"			=> itm("LotDecCd"),
		"type"			=> "text",
		"name"			=> "s_lot_dsc_cd",
		"value"			=> $gw_scr['s_lot_dsc_cd'],
		"size"			=> 20,
		"maxlength"		=> 7,
		"readonly"		=> array
		(
			1 => "true",
			2 => "",
			3 => "true",
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => "",
			3 => "dis_text",
			4 => "dis_text"
		),
		"list"		=> array
		(
			"xpt_nm_mst",
			"20",
			$g_PrgCD,
			"s_lot_dsc_cd",
			"s_lot_dsc_nm",
			constant("TG_CD"),
		),
	),
	"s_lot_dsc_nm"	=> array
	(
		"matrix"		=> "3,14",
		"type"			=> "text",
		"name"			=> "s_lot_dsc_nm",
		"value"			=> $gw_scr['s_lot_dsc_nm'],
		"size"			=> 40,
		"readonly"		=> "true",
		"itm_cls"		=> "dis_text",
	),

	# ¥í¥Ã¥ÈÉ¼½ÐÎÏÀè
	"s_lp_cd"	=> array
	(
		"matrix"		=> "1,15",
		"title"			=> itm("LotPaperOutputName"),
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
			1 => "true",
			2 => "",
			3 => "true",
			4 => "true"
		),
		"itm_cls"		=> array
		(
			1 => "dis_text",
			2 => "",
			3 => "dis_text",
			4 => "dis_text"
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
	"s_lp_nm"	=> array
	(
		"matrix"		=> "3,15",
		"type"			=> "text",
		"name"			=> "s_lp_nm",
		"value"			=> $gw_scr['s_lp_nm'],
		"size"			=> 50,
		"readonly"		=> "true",
		"itm_cls"		=> "dis_text",
	),

        # ¥í¥Ã¥ÈÉ¼½ÐÎÏÀè
        "s_lot_rmks"       => array
        (
                "matrix"                => "1,16",
                "title"                 => itm("LotRmks"),
                "type"                  => "text",
                "name"                  => "s_lot_rmks",
                "value"                 => $gw_scr['s_lot_rmks'],
                "size"                  => 50,
                "maxlength"             => 250,
                "readonly"              => array
                (
                        1 => "true",
                        2 => "",
                        3 => "true",
                        4 => "true"
                ),
                "itm_cls"               => array
                (
                        1 => "dis_text",
                        2 => "",
                        3 => "dis_text",
                        4 => "dis_text"
                ),
        ),

	# ±£¤·¹àÌÜ
	"s_hidden" => array
	(
		"matrix"		=> "1,17",
		"class"			=> "noborder",
		"type"			=> "hidden",
		"name"			=> array
		(
			"s_dvsn_cd",
			"s_rt_cd_cairn",
			"s_prc_cd_cairn",
			"s_prd_cd_cairn",
			"s_io_blc_cd_cairn",
			"s_stp_cd_cairn",
			"s_stp_no_cairn",
			"s_mng_flg",
			"s_new_lot_id",
			"s_iohd_flg",
			"s_fin_flg",	
			"s_rt_cd_mcp",
			"s_prc_cd_mcp",
			"s_prd_cd_mcp",
			"s_io_blc_cd_mcp",
			"s_stp_cd_mcp",
			"s_stp_no_mcp",
		),
		"value"			=> array
		(
			$gw_scr['s_dvsn_cd'],
			$gw_scr['s_rt_cd_cairn'],
			$gw_scr['s_prc_cd_cairn'],
			$gw_scr['s_prd_cd_cairn'],
			$gw_scr['s_io_blc_cd_cairn'],
			$gw_scr['s_stp_cd_cairn'],
			$gw_scr['s_stp_no_cairn'],
			$gw_scr['s_mng_flg'],
			$gw_scr['s_new_lot_id'],
			$gw_scr['s_iohd_flg'],
			$gw_scr['s_fin_flg'],
			$gw_scr['s_rt_cd_mcp'],
			$gw_scr['s_prc_cd_mcp'],
			$gw_scr['s_prd_cd_mcp'],
			$gw_scr['s_io_blc_cd_mcp'],
			$gw_scr['s_stp_cd_mcp'],
			$gw_scr['s_stp_no_mcp']
		),
		"dt_colspan"	=> 3,
	),
);

#======================================================================
# ¥á¥Ã¥»¡¼¥¸
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "1,2",
	"class"		=> array
	(
		1 => "",
		2 => "",
		3 => "",
	),

	# ¥á¥Ã¥»¡¼¥¸ÎÎ°è
	"s_message"	=> array
		(
			"matrix"	=> "1,1",
			"type"		=> "msg",
			"value"		=> $g_msg,
			"lev"		=> $g_err_lv
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
# Ç§¾Ú
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
# ¥Ü¥¿¥ó
#======================================================================
$GROUP[] = array
(
	"cel"		=> "col",
	"matrix"	=> "5,1",
	"class"		=> "noborder",

        # Search button 
        's_ref' => array
                (
                        'matrix'  => '1,1',
                        'type'    => array(1=>'button', 'none'),
                        'name'    => 's_ref',
                        'value'   => button_name('Reference'),
                        'onclick' => "jgt_page_action('REF')"
                ),

	"s_chk"		=> array
	(
		"matrix"		=> "2,1",
		"type"			=> array
		(
			1 => "none",
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
		"matrix"		=> "3,1",
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
		"matrix"		=> "4,1",
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
		"matrix"		=> "5,1",
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

#==================================================================
# ¥»¥Ã¥·¥ç¥ó
#==================================================================
$g_session = array();
for($i=0; $i<count($GROUP); $i++){
	foreach($GROUP[$i] as $key => $arr){
		if(is_numeric($key)){
			foreach($arr as $kk => $vv){
				search_scr_id($kk, $vv);
			}
		} else {
			search_scr_id($key, $arr);
		}
	}
	reset($GROUP[$i]);
}
reset($GROUP);

function search_scr_id($s_key, $s_arr){
	global $g_session;
	if(preg_match("/^s_/", $s_key)){					# s_ ¤Ç»Ï¤Þ¤ë¥­¡¼¤ò¼èÆÀ
		if(is_array($s_arr['type'])){
			if(in_array("button", $s_arr['type'])		# type=button
			|| in_array("none", $s_arr['type'])			# type=none
			|| !isset($s_arr['name'])					# name¤¬Ì¤ÀßÄê
			){											# ¤Î¹àÌÜ¤Ï
				return;									# Èô¤Ð¤¹
			}
		} else {
			if($s_arr['type'] == "button"
			|| $s_arr['type'] == "none"
			|| !isset($s_arr['name'])
			){
				return;
			}
		}
		$g_session[] = $s_key;
	}
}

#======================================================================
# ³°ÉôJavaScriptÆÉ¹þ
#======================================================================
$g_js_o = array
(
	"jgt_return",
);

#======================================================================
# ½é´ü¥«¡¼¥½¥ë°ÌÃÖ
#======================================================================
$g_def = array
(
	1 => "s_usr_id",
);
?>

