<?php
# ===================================================================================
# [DATE]  : 2012.05.23				[AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM				[SYSTEM]  : CCD
# [SUB_ID]:					[SUBSYS]  : 
# [PRC_ID]:					[PROCESS] :
# [PGM_ID]: PS00S01002540M.php			[PROGRAM] : DELIVERY (CCD)
# [MDL_ID]:					[MODULE]  : 
# -----------------------------------------------------------------------------------
# [COMMENT]
# 
# -----------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]	[UPDATE]		[COMMENT]
# ====================	======================	=====================================
# DOS)H.Otsuka		I140411-0000003		返品ロット対応(UTAC対応)
# DOS)Mydel 		2017.04.24		For CCD Department
# -----------------------------------------------------------------------------------

function PS00S01002540_item($label)
{
	$item = array
	(
		"ScreenTitle"		=> "*** Delivery(CCD) ***",

		"UsrId"			=> "User ID",
		"LpCd"			=> "Printer Code",
		"PrintNo"		=> "Number of Copies",	
		"PrintNote"		=> "*maximum 10 copies",
		"Total"			=> "Total",
		"LotId"			=> "Lot ID",
		"PrdNm"			=> "Type Name",
		"ChpQty"		=> "Chip Qty",
		"Line"			=> "Line",
	 );

	return $item[$label];
}

function PS00S01002540_msg($label)
{
	$msg = array
	(
		# 入力エラー
		"err_Nec_Input"			=> array("msg" => "Required input.",								"lv" => 0),
		"err_Inp_Alphabet"		=> array("msg" => "Please enter in alphanumeric.",						"lv" => 0),
		"err_Inp_Char"			=> array("msg" => "Contains invalid characters.",						"lv" => 0),
		"err_Inp_Num_One"		=> array("msg" => "Please specify number 1 or more.",						"lv" => 0),
		"err_Inp_Num"			=> array("msg" => "Please specify numeric value.",						"lv" => 0),
		"err_Inp_Par"                   => array("msg" => "Please specify numeric value 1-10.",                              		"lv" => 0),
                "err_Wcs_Chk"                   => array("msg" => "WCS not approved yet, please contact MO Office.",                            "lv" => 0),		
		"err_Inp_Tag"			=> array("msg" => "ID Tag format specified is not correct.",					"lv" => 0),

		# ＳＱＬエラー
		"err_Sel"			=> array("msg" => "Failed to retrieve.",							"lv" => 0),
		"err_Upd"			=> array("msg" => "Failed to update.",								"lv" => 0),
		"err_Ins"			=> array("msg" => "Failed to insert.",								"lv" => 0),

		# 通常エラー
		"err_Dup"			=> array("msg" => "Lot ID has been entered duplicate.",						"lv" => 0),
		"err_UpdLev"			=> array("msg" => "Other user has updated already.",						"lv" => 0),
		"err_Disabled"			=> array("msg" => "This Lot cannot be processed in this Step.",					"lv" => 0),
		"err_Plrl_Dvsn"                 => array("msg" => "Plural Division Code exist.",                                                "lv" => 0),
		"err_Rdg_Cd"                 	=> array("msg" => "All lots must have the same ridge code process.",    			"lv" => 0),  
		"err_None_BlkCs"		=> array("msg" => "Black Case ID has not been registered.",					"lv" => 0),
		"err_Not_Pck"			=> array("msg" => "This Lot doesn't been processed yet in packing step.",			"lv" => 0),
		"err_Get_BlkCase"		=> array("msg" => "Cannot be acquired Black Case Information.",					"lv" => 0),
		"err_Bind_Contain"		=> array("msg" => "'%s' Packing Lot has not been specified all.",				"lv" => 0),
		"err_ParentChildControl"	=> array("msg" => "The other child Lot has been HOLD before F-test.",				"lv" => 0),
		"err_Mix_NormRetLot"		=> array("msg" => "Cannot input both normal lot and returned lot.",				"lv" => 0), 
		"err_not_allowed_combine"       => array("msg" => "Not Allowed to delivery UTL products and Other products in same transfer note.","lv" => 0),
		# ガイド
		"guid_Check"			=> array("msg" => "Please enter required data, and press Check button.",			"lv" => 2),
		"guid_Execute"			=> array("msg" => "Check has been completed. Please press Execute.",				"lv" => 2),

		# 完了メッセージ
		"end_Update"			=> array("msg" => "Update process was completed successfully.",					"lv" => 1),
		"end_Print_TrnNote"		=> array("msg" => "Printed Transfer Note.",							"lv" => 1),
		 #for SNI project
                "err_Chk_Sni"             	=> array("msg" => "An error occured while checking for SNI product.",  				"lv" => 0),

                "err_Sni_pro"      		=> array("msg" => "Cannot use multiple products type for SNI product.",  			"lv" => 0),

                "err_Lot_Bas"      		=> array("msg" => "An error occured while getting the information from the lot_bas_tbl.",  	"lv" => 0),
		"err_PO_Not_Found"              => array("msg" => "SNI PO No can't find.",							"lv" => 0),
		"err_PO_Not_Same"		=> array("msg" => "SNI PO are not same. Can't delivery as one transfer note.", 			"lv" => 0),
		"err_Po_Pol_merge"  		=> array("msg" => "SNI PO Number are not same.",            					"lv" => 0),

		#F0-TEST Expiry Checking
		"err_F0_test_expired"		=> array("msg" => "F0 test is expired. Need to re-test.",					"lv" => 0),
		# PO/BO Control
		"err_mul_po_control"   	        => array("msg" => "1 lot cannot have multiple PO number.",					"lv" => 0),
		"err_dif_po_control"   	        => array("msg" => "The lots with different PO number cannot be delivered.",			"lv" => 0),
	);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}

?>
