<?php
# ======================================================================================
# [DATE]  : 2013.02.27			[AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM				[SYSTEM]  : CIM
# [SUB_ID]:						[SUBSYS]  : 
# [PRC_ID]:						[PROCESS] : 
# [PGM_ID]: PS00S01000990M.php	[PROGRAM] : Create YMO Lot
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

function PS00S01000990_item($label)
{
	$item = array
	(
		"HeaderTitle"		=> "Create Rework Lot",
		"ScreenTitle"		=> "*** Create Rework Lot ***",

		"UsrId"			=> "User ID",
		"PrdNm"			=> "Type Name",
		"StpCd"			=> "Step Code",
		"PrcCd"                 => "Process Code",
		"PltNo"			=> "Plate No",
		"LblPrinter"		=> "Label Printer",		

		"InpLotCnt"		=> "Number of Lots",
		"LotId"			=> "Lot ID",
		"DispRow"		=> "Number of Lines",
		"Pkg"			=> "Package",
		"DateCode"		=> "Date Code",
		"ChpQty"		=> "Chip Qty",
		"Total"			=> "Total",

		"Cmt"			=> "Comment",
		"ChipQty"                => "Chip Qty",
		"TypeNm"                => "Current Type Name",
		"1to10"			=> "* 1 - 10",
		"MgznId"		=> "Magazine ID",

		"Line"			=> "Line",
	 );

	return $item[$label];
}

function PS00S01000990_msg($label)
{
	$msg = array
	(
		# input check error
		"err_Nec_Input"			=> array("msg" => "Required input.",									"lv" => 0),
		"err_Inp_Char"			=> array("msg" => "Contains invalid characters.",							"lv" => 0),
		"err_Inp_Tag"			=> array("msg" => "This input tag form ID is not correct.",						"lv" => 0),
		"err_Inp_Dup"			=> array("msg" => "Duplicate data.",									"lv" => 0),
		"err_Inp_Over"			=> array("msg" => "Outside input range.",								"lv" => 0),
		"err_Inp_LotID"			=> array("msg" => "Please input Lot ID.",								"lv" => 0),

		# SQL error
		"err_Sel"			=> array("msg" => "Failed to retrieve.",								"lv" => 0),
		"err_Upd"			=> array("msg" => "Failed to update.",									"lv" => 0),
		"err_Ins"			=> array("msg" => "Failed to insert.",									"lv" => 0),

		# common error
		"err_Disabled"			=> array("msg" => "This Lot cannot be processed on this menu.",						"lv" => 0),
		"err_Dup"			=> array("msg" => "Duplicate data.",									"lv" => 0),
		"err_LotCond"			=> array("msg" => "These Lot condition do not match.",							"lv" => 0),
		"err_Dis_PrdCd"			=> array("msg" => "The Type of specified Lot does not match.",						"lv" => 0),
		"err_Unexpected"		=> array("msg" => "An unexpected error occurred.",							"lv" => 0),
		"err_Mis_Log"			=> array("msg" => "Cannot find the rework step.",							"lv" => 0),
		"err_Get_Sec"			=> array("msg" => "Cannot acquire the Date Code Information.",						"lv" => 0),
                "err_Dup_Sec"                   => array("msg" => "Cannot merge Lot ID with different Date Code Information.",                          "lv" => 0),
		"err_Get_RejQty"		=> array("msg" => "Cannot acquire the Reject Qty Information.",						"lv" => 0),
		"err_Ovr_RejQty"		=> array("msg" => "The specified Qty exceeds the original Qty which has been calculated as Reject Qty.","lv" => 0),
		"err_Get_prdNm"			=> array("msg" => "Cannot acquire the Type Name.",							"lv" => 0),
		"err_Get_pkgNm"			=> array("msg" => "Cannot acquire the Package.",							"lv" => 0),
		"err_Get_RtInf"			=> array("msg" => "Cannot be acquired Product Route.",							"lv" => 0),
		"err_Reg_JigOrg"		=> array("msg" => "Registration of Jig Organized Master is not correct.",				"lv" => 0),
		"err_Dif_PrtCd"			=> array("msg" => "Cannot use different Magazine.",							"lv" => 0),
		"err_Dsbl_Jig"			=> array("msg" => "This Magazine ID cannot use.",							"lv" => 0),
		"err_Dsbl_JigSt"		=> array("msg" => "%s is not in usable state.",								"lv" => 0),
		"err_Dsbl_Stp"			=> array("msg" => "Cannot create Lot in this step.",							"lv" => 0),
		"err_Get_Mgzn"			=> array("msg" => "Cannot be found this Magazine information.",						"lv" => 0),

		# guid
		"guid_Execute"			=> array("msg" => "Check has been completed. Please press Execute.",					"lv" => 2),
		"err_wrong_Stp" 	=> array("msg" => "Selected step is not valid. Select a step that is before your lot's current step.",					"lv" => 0),
		"err_wrong_process_code" 	=> array("msg" => "Selected Process Code is not valid for this step.",					"lv" => 0),
		"err_wrong_product_code"	=> array("msg" => "Selected Type Name is not valid for this step.",                                        "lv" => 0),
		# end message
		"end_NewLot"			=> array("msg" => "New Lot created.",									"lv" => 1),
		"end_Print"				=> array("msg" => "Printed label.",								"lv" => 1),
	);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}
?>
