<?php
# ===============================================================================
# [DATE]  : 2004.10.20          [AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM              [SYSTEM]  : 非自動化標準ＣＩＭ
# [SUB_ID]:                     [SUBSYS]  :
# [PRC_ID]:                     [PROCESS] :
# [PGM_ID]: PS00S01000400S.php  [PROGRAM] : 次工程渡し
# [MDL_ID]:                     [MODULE]  :
# -------------------------------------------------------------------------------
# [COMMENT]
#
# -------------------------------------------------------------------------------
# [UPDATE_LOG]
#
# [UPDATE_PERSON]       [UPDATE]    [COMMENT]
# ====================  ==========  ============================================
# DOS)Y.Kawakami		2005.08.30	認証・タイムアウト組込み
# DOS)K.Yamamoto		2005.09.29	ボタンタイプ submit -> button に変更
# ZXS)K.Maeda           2006.02.27	PSCID用にPSSEM01000050をコピーして作成
# DOS)N.Nishida			2006.04.21	保税区分,保税手帳番号の削除
# dos)doi				I120413-0068395	ID版PS00C01000121から移植(リング数及びバー数の表示機能追加)
# -------------------------------------------------------------------------------

#---------------------------------------------------
# グローバル設定
#---------------------------------------------------
global $gw_scr;
global $g_msg;
global $g_err_lv;
global $g_PrgCD;
#---------------------------------------------------
# グループ設定
# 画面をグループに分け、それぞれのプロパティを
# 配列によって設定する。
#---------------------------------------------------
$GROUP[0] = array(
				"cel"		=> "col",
				"matrix"	=> "3,2",

				"s_usr_id"	=> array(
								"matrix"		=> "1,1",
								"title"			=> PS00S01000400_item("UsrID"),
								"type"			=> "text",
								"name"			=> "s_usr_id",
								"size"			=> 19,
								"maxlength"		=> 13,
								"readonly"		=> array("false","true","true","true"),
								"itm_cls"		=> array("","dis_text","dis_text","dis_text"),
								"value"			=> $gw_scr['s_usr_id']
								),

				"s_usr_nm"	=> array(
								"matrix"		=> "3,1",
								"type"			=> "disp",
								"name"			=> "s_usr_nm",
								"width"			=> "200",
								"value"			=> $gw_scr['s_usr_nm']
								),

				"s_lot_id"	=> array(
								"matrix"		=> "1,2",
								"title"			=> PS00S01000400_item("LotID"),
								"type"			=> "text",
								"name"			=> "s_lot_id",
								"size"			=> 20,
								"maxlength"		=> 15,
								"readonly"		=> array("false","true","true","true"),
								"itm_cls"		=> array("","dis_text","dis_text","dis_text"),
								"value"			=> $gw_scr['s_lot_id'],
								),

				"s_hidden"	=> array(
								"matrix"		=> "3,2",
								"type"			=> "hidden",
								"name"			=> array(
													"s_upd_lev"
													),
								"value"			=> array(
													$gw_scr['s_upd_lev']
													),
								"class"			=> "noborder"
								)
													
				);


$GROUP[1] = array(
				"cel"		=> "col",
#				"matrix"	=> "3,12",
				"matrix"	=> "2,13",

				"s_io_blc_nm"	=> array(
									"matrix"		=> "1,1",
									"title"			=> PS00S01000400_item("IoBlockName"),
									"type"			=> "disp",
									"name"			=> "s_io_blc_nm",
									"value"			=> $gw_scr['s_io_blc_nm'],
									),

				"s_prd_nm"		=> array(
									"matrix"		=> "1,2",
									"title"			=> PS00S01000400_item("KindName"),
									"type"			=> "disp",
									"name"			=> "s_prd_nm",
									"value"			=> $gw_scr['s_prd_nm']
									),

#				"s_bd_dvs"      => array(
#									"matrix"        => "3,2",
#									"type"          => "disp",
#									"name"          => "s_bd_dvs",
#									"value"         => $gw_scr['s_bd_dvs'],
#									"class"         => "noborder"
#									),
#
#
#				"s_bd_no"		=> array(
#									"matrix"		=> "1,3",
#									"title"			=> PS00S01000400_item("BondedNo"),
#									"type"			=> "disp",
#									"name"			=> "s_bd_no",
#									"value"			=> $gw_scr['s_bd_no']
#									),

				"s_rnk_ptn"		=> array(
#									"matrix"		=> "1,4",
									"matrix"		=> "1,3",
									"title"			=> PS00S01000400_item("AimRank"),
									"type"			=> "disp",
									"name"			=> "s_rnk_ptn",
									"value"			=> $gw_scr['s_rnk_ptn']
									),

				"s_lot_no_str"	=> array(
#									"matrix"		=> "1,5",
									"matrix"		=> "1,4",
									"title"			=> PS00S01000400_item("DiffFactoryNo"),
									"type"			=> "disp",
									"name"			=> "s_lot_no_str",
									"value"			=> $gw_scr['s_lot_no_str']
									),

				"s_lot_no"		=> array(
#									"matrix"		=> "1,6",
									"matrix"		=> "1,5",
									"title"			=> PS00S01000400_item("AssFactoryNo"),
									"type"			=> "disp",
									"name"			=> "s_lot_no",
									"value"			=> $gw_scr['s_lot_no']
									),

				"s_secret_no"	=> array(
#									"matrix"		=> "1,7",
									"matrix"		=> "1,6",
									"title"			=> PS00S01000400_item("SecretNo"),
									"type"			=> "disp",
									"name"			=> "s_secret_no",
									"value"			=> $gw_scr['s_secret_no']
									),

				"s_pkg_nm"		=> array(
#									"matrix"		=> "1,8",
									"matrix"		=> "1,7",
									"title"			=> PS00S01000400_item("PackageName"),
									"type"			=> "disp",
									"name"			=> "s_pkg_nm",
									"value"			=> $gw_scr['s_pkg_nm']
									),

				"s_sl_qty"		=> array(
#									"matrix"		=> "1,9",
									"matrix"		=> "1,8",
									"title"			=> PS00S01000400_item("SlQty"),
									"type"			=> "disp",
									"name"			=> "s_sl_qty",
									"value"			=> $gw_scr['s_sl_qty']
									),

				"s_chp_qty"		=> array(
#									"matrix"		=> "1,10",
									"matrix"		=> "1,9",
									"title"			=> PS00S01000400_item("ChipQty"),
									"type"			=> "disp",
									"name"			=> "s_chp_qty",
									"value"			=> $gw_scr['s_chp_qty']
									),

				"s_lf_qty"		=> array(
#									"matrix"		=> "1,11",
									"matrix"		=> "1,10",
									"title"			=> PS00S01000400_item("LFQty"),
									"type"			=> "disp",
									"name"			=> "s_lf_qty",
									"value"			=> $gw_scr['s_lf_qty']
									),

				"s_ring_qty"		=> array(
									"matrix"		=> "1,11",
									"title"			=> PS00S01000400_item("RingQty"),
									"type"			=> "disp",
									"name"			=> "s_ring_qty",
									"value"			=> $gw_scr['s_ring_qty']
									),
				"s_bar_qty"		=> array(
									"matrix"		=> "1,12",
									"title"			=> PS00S01000400_item("BarQty"),
									"type"			=> "disp",
									"name"			=> "s_bar_qty",
									"value"			=> $gw_scr['s_bar_qty']
									),

				"s_io_blc_nm_next"	=> array(
#									"matrix"		=> "1,12",
									"matrix"		=> "1,13",
									"title"			=> PS00S01000400_item("NextIoBlockName"),	
									"type"			=> "disp",
									"name"			=> "s_io_blc_nm_next",
									"value"			=> $gw_scr['s_io_blc_nm_next']
									),

				);



$GROUP[2] = array(
				"cel"		=> "col",
				"matrix"	=> "1,1",

				"s_message"	=> array(
								"matrix"	=> "1,1",
								"type"		=> "msg",
								"value"		=> $g_msg,
								"lev"		=> $g_err_lv
								)
				);


$GROUP[3] = array(
                "cel"       => "col",
                "matrix"    => "5,1",
                "class"     => "noborder",

                "s_chk"     => array(
                                "matrix"    => "1,1",
                                "type"      => array("button","none","none"),
                                "name"      => "s_chk",
                                "value"     => button_name("Check"),
                                "onclick"   => "jgt_btn_flg(" . $g_PrgCD . ")"
                                ),

                "s_sub"     => array(
                                "matrix"    => "2,1",
                                "type"      => array("none","button","none"),
                                "name"      => "s_sub",
                                "value"     => button_name("Execute"),
                                "onclick"   => "jgt_btn_flg(" . $g_PrgCD . ")"
                                ),

                "s_erase"   => array(
                                "matrix"    => "3,1",
                                "type"      => array("button","none","none"),
                                "name"      => "s_erase",
                                "value"     => button_name("Erase"),
                                "onclick"   => "jgt_action('erase')"
                                ),
                "s_rtn"     => array(
                                "matrix"    => "4,1",
                                "type"      => array("none","button","button"),
                                "name"      => "s_rtn",
                                "value"     => button_name("Return"),
                                "onclick"   => "jgt_action('return')"
                                ),
                );

$GROUP[4] = array(
				"cel"           => "col",
				"matrix"        => "1,1",
				"s_hidden"      => array(
									"matrix"    => "1,1",
									"type"      => "hidden",
									"name"      => array(
														"s_renzheng",
														"s_renzheng_db",
														"s_renzheng_t",
													),
									"value"     => array(
														$gw_scr['s_renzheng'],
														$gw_scr['s_renzheng_db'],
														$gw_scr['s_renzheng_t'],
													),
									"class"     => "noborder"
								),
			);


$g_def = array(
			"s_usr_id",
			"s_sub",
			"s_rtn"
			);

?>
