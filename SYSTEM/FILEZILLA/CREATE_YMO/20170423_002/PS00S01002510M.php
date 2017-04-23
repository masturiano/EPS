<?php
# ======================================================================================
# [DATE]  : 2013.02.27		[AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM		[SYSTEM]  : CCD
# [SUB_ID]:			[SUBSYS]  : 
# [PRC_ID]:			[PROCESS] : 
# [PGM_ID]: PS00S01002510M.php	[PROGRAM] : Create YMO Lot
# [MDL_ID]:			[MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
# 
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]	[UPDATE]		[COMMENT]
# ====================	====================	========================================
# DOS)MYDEL		2017.04.23		FOR CCD DEPARTMENT WITH ADDITIONAL E9 UNALLOWED
# --------------------------------------------------------------------------------------

function PS00S01002510_item($label)
{
	$item = array
	(
		"HeaderTitle"	=> "Create YMO Lot",
		"ScreenTitle"	=> "*** Create YMO Lot ***",

		"UsrId"		=> "User ID",
		"PrdNm"		=> "Type Name",
		"StpCd"		=> "Step Code",
		"PltNo"		=> "Plate No",
		"LblPrinter"	=> "Label Printer",

		"InpLotCnt"	=> "Number of Lots",
		"LotId"		=> "Lot ID",
		"DispRow"	=> "Number of Lines",
		"Pkg"		=> "Package",
		"DateCode"	=> "Date Code",
		"ChpQty"	=> "Chip Qty",
		"Total"		=> "Total",

		"1to10"		=> "* 1 - 10",
		"MgznId"	=> "Magazine ID",

		"Line"		=> "Line",
	 );

	return $item[$label];
}

function PS00S01002510_msg($label)
{
	$msg = array
	(
		# input check error
		"err_Nec_Input"			=> array("msg" => "Required input.",									"lv" => 0),
		"err_Datecode"			=> array("msg" => "Please enter the datecode for all the Lot ids.",					"lv" => 0),
		"err_Inp_Char"			=> array("msg" => "Contains invalid characters.",							"lv" => 0),
		"err_Inp_Tag"			=> array("msg" => "This input tag form ID is not correct.",						"lv" => 0),
		"err_Inp_Dup"			=> array("msg" => "Duplicate data.",									"lv" => 0),
		"err_Inp_Over"			=> array("msg" => "Outside input range.",								"lv" => 0),
		"err_Inp_LotID"			=> array("msg" => "Please input Lot ID.",								"lv" => 0),
		"err_PoNo_lot"			=> array("msg" => "Two unique PO detected in one Lot.",							"lv" => 0),
		"err_PoNo"			=> array("msg" => "Cannot merge lots with different PO Numbers.",                      			"lv" => 0),
		"err_SNI_PO_notMatch"		=> array("msg" => "Only lots with the same SNI PO Number are allowed to combine together.",		"lv" => 0),
                "err_Multiple_SNI_PO"		=> array("msg" => "Lot have multiple SNI PO. Please inform Supervisor.",                            	"lv" => 0),		

		# SQL error
		"err_Sel"			=> array("msg" => "Failed to retrieve.",								"lv" => 0),
		"err_Upd"			=> array("msg" => "Failed to update.",									"lv" => 0),
		"err_Ins"			=> array("msg" => "Failed to insert.",									"lv" => 0),

		# common error
		"err_Disabled"			=> array("msg" => "This Lot cannot be processed on this menu.",						"lv" => 0),
		"err_Dup"			=> array("msg" => "Duplicate data.",									"lv" => 0),
		"err_lot_type_cd"       	=> array("msg" => "Cannot input both normal lot and returned lot.",					"lv" => 0),
		"err_LotCond"			=> array("msg" => "These Lot condition do not match.",							"lv" => 0),
		"err_Dis_PrdCd"			=> array("msg" => "The Type of specified Lot does not match.",						"lv" => 0),
		"err_Unexpected"		=> array("msg" => "An unexpected error occurred.",							"lv" => 0),
		"err_Mis_Log"			=> array("msg" => "Could not be found the Log.",							"lv" => 0),
		"err_Get_Sec"			=> array("msg" => "Cannot acquire the Date Code Information.",						"lv" => 0),
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

		# end message
		"end_NewLot"			=> array("msg" => "New Lot created.",									"lv" => 1),
		"end_Print"			=> array("msg" => "Printed label.",									"lv" => 1),

		"err_Po_Pol_merge"  		=> array("msg" => "SNI PO Number or PO Line Number are not same.",       				"lv" => 0)
	);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}
?>
