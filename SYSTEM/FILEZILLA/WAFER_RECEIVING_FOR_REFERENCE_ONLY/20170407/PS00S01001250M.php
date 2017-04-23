<?php
# ======================================================================================
# [DATE]  : 2014.02.10          		[AUTHOR]  : MIS) Paul
# [SYS_ID]: GPRISM						[SYSTEM]  : CIM
# [SUB_ID]:								[SUBSYS]  : 
# [PRC_ID]:								[PROCESS] : 
# [PGM_ID]: PS00S01001250M.php			[PROGRAM] : Wafer Receive(BGA)
# [MDL_ID]:								[MODULE]  : 
# --------------------------------------------------------------------------------------
# [COMMENT]
# 
# --------------------------------------------------------------------------------------
# [UPDATE_LOG]
# 
# [UPDATE_PERSON]		[UPDATE]			[COMMENT]
# ====================	==================	============================================
# ®
# --------------------------------------------------------------------------------------

function PS00S01001250_item($label)
{
	$item = array
		(
			"HeaderTitle"			=> "Wafer Receive",
			"ScreenTitle"			=> "*** Wafer Receive ***",
			"UsrId"					=> "User ID",
			"ChpNm"					=> "Wafer Name",
			"AssPrdNm"				=> "Sub Finish Good Name",
			"Rnk"					=> "Rank",
			"MtLotID"				=> "Material Lot ID",
			"SlInf"					=> "Slice Information",
			"SlQty"					=> "Slice Qty",
			"SapLotID"				=> "SAP Lot Number",
			"LotClsCd"				=> "Lot Dividing Code",
			"LotDecCd"				=> "Lot Identification Code",
			"MfgDte"				=> "Manufacturing date",
			"ExpDte"				=> "Expired Date",
			"LotPaperOutputName"	=> "Printer Code",
			"DiffLotNo"				=> "Diffusion Lot No",
			"SlInf"					=> "Slice Information",
			"SlQty"					=> "Slice Qty",
			"ChpQty"				=> "Chip Qty",
			"RngQty"				=> "Ring Qty",
			"BarQty"				=> "Bar Qty",
			"RcvLot"				=> "Received Lot",
			"ExpDte"				=> "Expired Date",
			"MfgDte"				=> "Manufacturing Date",
			"RsvHoldInfo"			=> "Reservation Reason: %s, Contact: %s, Release Date set %s days after from today.",
			"LotRmks"				=> "Comment"
		 );

	return $item[$label];
}

function PS00S01001250_msg($label)
{
	$msg = array
	(
		# ÆþÎÏ¥¨¥é¡¼
		"err_Nec_Input"			=> array("msg" => "Required input.",										"lv" => 0),
		"err_Inp_Char"			=> array("msg" => "Contains invalid characters.",								"lv" => 0),
		"err_Inp_Tag"			=> array("msg" => "This input tag form ID is not correct.",							"lv" => 0),
		"err_Inp_Dup"			=> array("msg" => "Duplicate data.",										"lv" => 0),
		"err_Inp_1to999"		=> array("msg" => "Please enter a number from 1 to 999.",							"lv" => 0),
		"err_NoInp"			=> array("msg" => "Unable to be input.",									"lv" => 0),
	
		# £Ó£Ñ£Ì¥¨¥é¡¼
		"err_Sel"			=> array("msg" => "Failed to retrieve.",									"lv" => 0),
		"err_Upd"			=> array("msg" => "Failed to update.",										"lv" => 0),
		"err_Ins"			=> array("msg" => "Failed to insert.",										"lv" => 0),

		# ÄÌ¾ï¥¨¥é¡¼
		"err_Disabled"			=> array("msg" => "This Product cannot be processed on this menu. Please check routing master.",		"lv" => 0),
		"err_SameDiffLotNo"		=> array("msg" => "Can receive only the same Diffusion Lot.",							"lv" => 0),
		"err_PrdSmpl"			=> array("msg" => "Cannot be specified sample for formal product.",						"lv" => 0),
                "err_SapUse"                    => array("msg" => "SAP Lot No already used.",									"lv" => 0),		
		"err_NotQASmpl"			=> array("msg" => "Only QA member can set sample lot.",								"lv" => 0),
		"err_DifLotClsDec"		=> array("msg" => "Lot Dividing Code and Lot Identification Code cannot be matched.",				"lv" => 0),
		"err_NoRtInfo"			=> array("msg" => "Cannot be acquired Product Route.",								"lv" => 0),
		"err_NoFinInfo"                 => array("msg" => "Cannot be acquired FG Name.",                                                          	"lv" => 0),
		"err_NoStpInfo"			=> array("msg" => "Cannot be acquired appropriate Step Code.",							"lv" => 0),
		"err_NoSapData"			=> array("msg" => "Cannot be acquired SAP Lot Number.",								"lv" => 0),
		"err_DataFile"			=> array("msg" => "Wafer information is not acquired. Please inform to MIS.",					"lv" => 0),
		"err_PrdDec"			=> array("msg" => "Inch master is not set into product registration . Please confirm master registration.",	"lv" => 0),
		"err_PTest"			=> array("msg" => "There are some inconsistencies in the P-Test Program Information. Please inform to MIS.",	"lv" => 0),
		"err_NextProcessInfo"		=> array("msg" => "Failed to acquire Next Step Information.","lv" => 0),
                "err_NewStrPoint"               => array("msg" => "This process is not available as starting new Lot.",                                         "lv" => 0),
	
		# ¥¬¥¤¥É
		"guid_Execute"			=> array("msg" => "Check has been completed. Please press Execute.",						"lv" => 2),

		# ´°Î»¥á¥Ã¥»¡¼¥¸
		"end_Execute"			=> array("msg" => "Update completed successfully.",								"lv" => 1),
		"end_Print"			=> array("msg" => "New Lot created.",										"lv" => 1),

		# ·Ù¹ð
		"End_Rsv_Hold"			=> array("msg" => "This Lot has been executed Hold Reservation processing.",					"lv" => 3),
		"alrt_SampleLot"		=> array("msg" => "This lot becomes out of accounting.",							"lv" => 3),
	);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}
?>

