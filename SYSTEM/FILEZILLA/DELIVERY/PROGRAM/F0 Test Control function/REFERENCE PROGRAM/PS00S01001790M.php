<?php
# ======================================================================================
# [DATE]  : 2013.02.13			[AUTHOR]  : DOS)K.Yamamoto
# [SYS_ID]: GPRISM				[SYSTEM]  : CIM
# [SUB_ID]:						[SUBSYS]  : 
# [PRC_ID]:						[PROCESS] : 
# [PGM_ID]: PS00S01001790M.php	[PROGRAM] : Multi-Chip Track-Out(BGA)
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

function PS00S01001790_item($label)
{
	$item = array
	(
		"HeaderTitle"	=> "Multi Die Bond Track-Out(BGA)",
		"ScreenTitle"	=> "*** Multi Die Bond Track-Out(BGA) ***",

		"UsrId"			=> "User ID",
		"StpNm"			=> "Step Name",
		"FinPrdNm"		=> "Type Name of after Merge",
		"LblPrinter"	=> "Label Printer",

		"MainLot"		=> "Wafer Slice",
		"SubLot"		=> "Sub Lot",
		"InpLotCnt"		=> "Number of Lots",
		"LotID"			=> "Lot ID",
		"PrdNm"			=> "Type Name",
		"LotNo"			=> "Lot No",
		"DifLotNo"		=> "Diffusion Lot No",
		"StkQty"		=> "Avail Qty",
		"AdjQty"		=> "Adjust Qty",
		"TrtQty"		=> "Input Qty",
		"PasQty"		=> "Good Qty",
		"BadQty"		=> "Reject Qty",
		"Dtl"			=> "Details",
		"ExpF"			=> "F : Last Lot Flag",

 		"RjctDtlChp"            => "Reject Details for Chip",

		"EquNm"  		=> "Equipment Name",
		"StpNm" 		=> "Step Name",
		"PkgNm" 		=> "Package Name",
		"LotNoStr" 		=> "Diffusion Lot No",
		"ChpQty" 		=> "Chip Qty",
		"SliceQty" 		=> "Slice Qty",
		"SubQty" 		=> "L/F Qty",
		"PhyQty" 		=> "Physical Qty",
		"InpQty" 		=> "Input Qty",
		"RjctQty" 		=> "Reject Qty",

		"SSStripQty"	=> "S/S Strip Qty",
		"OutQty"		=> "Output Qty",

		"UseSlNo"		=> "Use Slice No",
		"ExpSlNo"		=> "ex)1-3,5,7,9,15,20",

		"AftMultiLot"	=> "Assembly Lot",

		"NumDsp"		=> "Number of Lines",
		"1to10"			=> "* 1 - 10",
		"FinMgznId"		=> "Magazine ID",
		"BaseQty"		=> "L/F Qty",

		"RjctDtl"		=> "Reject Details",

		"Line"			=> "Line",

		"NotSet"		=> "(not set)",

		"LastApprv"		=> "Adjust Qty Approval",
		"Password"		=> "Password",
		"Cmt"			=> "Comment",
	 );

	return $item[$label];
}

function PS00S01001790_msg($label)
{
	$msg = array
	(
		# input check error
		"err_Nec_Input"			=> array("msg" => "Required input.",																"lv" => 0),
		"err_Inp_Char"			=> array("msg" => "Contains invalid characters.",													"lv" => 0),
		"err_Inp_Tag"			=> array("msg" => "This input tag form ID is not correct.",														"lv" => 0),
		"err_Inp_Dup"			=> array("msg" => "Duplicate data.",															"lv" => 0),
		"err_Inp_Over"			=> array("msg" => "Outside input range.",															"lv" => 0),

		# SQL error
		"err_Sel"				=> array("msg" => "Failed to retrieve.",														"lv" => 0),
		"err_Upd"				=> array("msg" => "Failed to update.",														"lv" => 0),
		"err_Ins"				=> array("msg" => "Failed to insert.",														"lv" => 0),

		# common error
		"err_Disabled"			=> array("msg" => "This Lot cannot be processed on this menu.",										"lv" => 0),
		"err_Dup"				=> array("msg" => "Duplicate data.",															"lv" => 0),
		"err_LotCond"			=> array("msg" => "These Lot condition do not match.",												"lv" => 0),
		"err_Get_UVLimit"		=> array("msg" => "Cannot acquire the expiration info of UV Irradiation.",											"lv" => 0),
		"err_Get_BakeDat"		=> array("msg" => "Cannot acquire the data of Baking-Out Time.",								"lv" => 0),
		"err_Get_StpThd"		=> array("msg" => "Cannot acquire a standard value of Baking Time Mangement.",								"lv" => 0),
		"err_Ovr_UVLimit"		=> array("msg" => "This Lot has been exceeded the expiration of UV Irradiation.",										"lv" => 0),
		"err_Need_ReBake"		=> array("msg" => "This Lot has been exceeded the limit time. Need to Re-Bake.",			"lv" => 0),
		"err_Fin_Rebaked"		=> array("msg" => "This Lot has been exceeded the limit time. but this Lot has already been Re-Bake.",	"lv" => 0),
		"err_Get_BakeCnt"		=> array("msg" => "Cannot acquire the Baking Count.",											"lv" => 0),
		"err_Inv_PrdMcp"		=> array("msg" => "The Product Type of specified Lot is not correct for selected Type Name of after Merge.",			"lv" => 0),
		"err_Inv_SubCnt"		=> array("msg" => "The kind of Product Type about Substrate Lot are not enough for selected Type Name.",				"lv" => 0),
		"err_Get_RtInf"			=> array("msg" => "Cannot acquire the route information of after multi-chip merge.",								"lv" => 0),
		"err_Get_BakeLmt"		=> array("msg" => "Cannot acquire a standard value of Baking Time Mangement.",								"lv" => 0),
		"err_Space"				=> array("msg" => "An error occurred by SPACE system.",												"lv" => 0),
		"err_Inp_MatMng"		=> array("msg" => "Please confirm the material control.",													"lv" => 0),
		"err_Reg_SecDigit"		=> array("msg" => "The digit of Date Code has not been registered.",												"lv" => 0),
		"err_Mis_SecDigit"		=> array("msg" => "The digit of Date Code has the wrong value.",											"lv" => 0),
		"err_Reg_DiffPlnt"		=> array("msg" => "DIFF PLANT has not been registered.",											"lv" => 0),
		"err_Ovr_SecNo"			=> array("msg" => "The sequential number of Date Code has exceeded the maximum value.",											"lv" => 0),
		"err_Wrkd_MainLot"		=> array("msg" => "Wafer has already been track-in in die-bond. Wafer cannot be merged/used as an additional wafer. Please inform supervisor.",										"lv" => 0),
		"err_Rsvd_SubLot"		=> array("msg" => "This Substrate Lot has already been used by other Lot.",							"lv" => 0),
		"err_Rsvd_MainLot"		=> array("msg" => "This Wafer Lot has already been used by other Lot.",							"lv" => 0),

		"err_Get_ExLotID"		=> array("msg" => "Cannot acquire the ex-Lot ID.",												"lv" => 0),
		"err_Get_AftPrd"		=> array("msg" => "Cannot acquire the Type Name of after merge.",												"lv" => 0),
		"err_Get_SubLot"		=> array("msg" => "Cannot acquire the Substrate lot information.",													"lv" => 0),
		"err_Inp_Less"			=> array("msg" => "Cannot specify less than acquired number of Lot.",								"lv" => 0),
		"err_TtlBad_OvrStkQty"	=> array("msg" => "The sum of Reject Details exceeds the Avail Qty.",								"lv" => 0),
                "err_TtlSub_InpQty"  => array("msg" => "The sum of Sub Wafer Input Qty cannot be less than the Main Lot Good Qty.",                                                           "lv" => 0),

		"err_Inp_LotID"			=> array("msg" => "Please input Lot ID.",												"lv" => 0),
		"err_Inp_Ovr_Stk"		=> array("msg" => "The specified Input Qty exceeds the Avail Qty.",										"lv" => 0),
		"err_Dis_InpPasBad"		=> array("msg" => "The sum of Pass and Reject Qty does not match the Input Qty.",									"lv" => 0),
		"err_Dis_InpPasBadAdj"	=> array("msg" => "The sum of Pass/Reject/Adjust Qty does not match the Input Qty.",							"lv" => 0),
		"err_Dis_StkAdjInp"		=> array("msg" => "Input/Adjust/Avail Qty do not match.",										"lv" => 0),
		"err_Dis_BadDtl"		=> array("msg" => "Reject Qty and Total of Reject Details do not match.",										"lv" => 0),
		"err_On_CompFlg"		=> array("msg" => "Need to Last Lot Flag ON.",													"lv" => 0),
		"err_Off_CompFlg"		=> array("msg" => "This Lot doesn't need to check the Last Lot Flag.",											"lv" => 0),
		"err_NoInp_Adj"			=> array("msg" => "if the Last Lot Flag is off, cannot input the Adjust Qty.",							"lv" => 0),
		"err_Dis_PrdCdMcp"		=> array("msg" => "The Type of specified Lot does not match.",										"lv" => 0),
		"err_Dis_AftQty"		=> array("msg" => "The Qty of Wafer and Substrate Lot does not match.",									"lv" => 0),
		"err_Exc_Follow"		=> array("msg" => "This Lot will be HOLD by %s error notice after Track-Out.(%s)",		"lv" => 0),
		"err_Inp_SlNo"			=> array("msg" => "Bad input format of Slice No.",									"lv" => 0),
		"err_AllReject"			=> array("msg" => "Cannot specify the Good Qty to all reject in Lot of before Multi-Chip Merge.",						"lv" => 0),
		"err_Get_User"			=> array("msg" => "There is no matching User ID. or the User Group has not been registered.",	"lv" => 0),
		"err_Not_AprvUsr"		=> array("msg" => "This user doesn't have an approval authority.",									"lv" => 0),
		"err_Wrng_Pass"			=> array("msg" => "Incorrect or wrong password for user.",														"lv" => 0),
		"err_Need_InLot"		=> array("msg" => "Need to use the Lot which has been finished Track-In.",									"lv" => 0),
		"err_Dis_OutBad"		=> array("msg" => "The sum of Good and Reject Qty does not match the Chip Qty of Assembly Lot.",						"lv" => 0),
                "err_Dis_SubBad"                => array("msg" => "The sum of Good and Reject Qty does not match the L/F Qty of Assembly Lot.",                                                "lv" => 0),
		"err_Err_Space"			=> array("msg" => "An error occurred by SPACE system.",												"lv" => 0),
		"err_not_valid_lot"     => array("msg" => "You can't use this lot because the route is not same. If you want to use this lot need to do route change.", "lv" => 0),
		
		"err_warn_ABN"			=> array("msg" => "This Lot has given the ABN error notice. When this Lot executes Track-Out, will be HOLD.",	"lv" => 3),
		"err_warn_PCS"			=> array("msg" => "This Lot has given the PCS error notice. When this Lot executes Track-Out, will be HOLD.",	"lv" => 3),
		"err_warn_MBN"			=> array("msg" => "This Lot has given the MBN error notice. When this Lot executes Track-Out, will be HOLD.",	"lv" => 3),
		"err_warn_CON"			=> array("msg" => "This Lot has given the CON error notice. When this Lot executes Track-Out, will be HOLD.",	"lv" => 3),
		"err_jdg_ABN"			=> array("msg" => "This Lot has given the ABN error judgement. When this Lot executes Track-Out, will be HOLD.",		"lv" => 3),
		"err_jdg_PCS"			=> array("msg" => "This Lot has given the PCS error judgement. When this Lot executes Track-Out, will be HOLD.",		"lv" => 3),
		"err_jdg_ABNPCS"		=> array("msg" => "This Lot has given both ABN and PCS error judgement. When this Lot executes Track-Out, will be HOLD.",	"lv" => 3),
		"err_Upd_ABN"			=> array("msg" => "Has been updated ABN Information from SPACE system during operation. Please enter again.",		"lv" => 0),
		"err_Upd_PCS"			=> array("msg" => "Has been updated PCS Information from SPACE system during operation. Please enter again.",		"lv" => 0),
		"err_Upd_MBN"			=> array("msg" => "Has been updated MBN Information from SPACE system during operation. Please enter again.",		"lv" => 0),
		"err_Upd_CON"			=> array("msg" => "Has been updated CONTAINMENT Information from SPACE system during operation. Please enter again.",	"lv" => 0),
		"warn_ABN_Exec"		=> array("msg" => "Lot will be ABN at Execute.",												"lv" => 3),
		"end_ABN_HOLD_eCAP_Err"		=> array("msg" => "Lot has been hold due to ABN, eCAP encountered an error, please report to MIS.",												"lv" => 3),

		"warn_TrcTime_NG"		=> array("msg" => "This Lot has been exceeded the limited time.",										"lv" => 3),
		"err_TrcTime_HOLD"		=> array("msg" => "This Lot has been HOLD due to the limit time exceeding.",							"lv" => 3),
		"err_Unexpected"		=> array("msg" => "An unexpected error occurred.",												"lv" => 0),
		"warn_Equ_NoCharge"		=> array("msg" => "%MTLOTID% has not been charged into equipment. After Track-Out, both device Lot and material Lot has been HOLD.",	"lv" => 3),
		"warn_Ovr_ChrgThaw"		=> array("msg" => "%MTLOTID% has been HOLD due to use before reaching the thawing time(%TIME%).",					"lv" => 3),
		"warn_Ovr_LifeTime"		=> array("msg" => "%MTLOTID% has been HOLD due to use before reaching the life time(%TIME%).",									"lv" => 3),
		"warn_Ovr_ExpTime"		=> array("msg" => "%MTLOTID% has been HOLD due to exceeding the expiration time(%TIME%).",										"lv" => 3),
		"err_JdgNG_ChldLot"		=> array("msg" => "Abnormal value has been detected by Standard Judgement about component Lot. Cannot use this Lot.",	"lv" => 0),
		"err_Rep_PluralRecord"	=> array("msg" => "Report Print Flag has been registered plural record in Master.",							"lv" => 0),

		"err_Need_Aprv"			=> array("msg" => "Contain the Last Lot. Please enter the User ID and Password who has approval authority.",			"lv" => 0),
		"err_NoNeed_Aprv"		=> array("msg" => "Not contain the Last Lot. Do not need to enter the User ID and Password.",			"lv" => 0),
		"err_NewInp"			=> array("msg" => "Not contain the Last Lot. Cannot be entered the Lot ID in new line.",					"lv" => 0),
		"err_diff_po"   	        => array("msg" => "Cannot merge the lots because of difference PO.",                                                            "lv" => 0),
		# guid
		"guid_Execute"			=> array("msg" => "Check has been completed. Please press Execute.",							"lv" => 2),

		# end message
		"end_Execute"			=> array("msg" => "Update completed successfully.",											"lv" => 1),
		"end_AllReject"			=> array("msg" => "This Lot was closed because all Qty has been rejected.",							"lv" => 1),
		"end_JdgNG_Hold"		=> array("msg" => "This Lot was held, because abnormal value in Standard Judgement.",					"lv" => 3),
		"end_Rsv_Hold"			=> array("msg" => "This Lot has been executed Hold Reservation processing.",										"lv" => 3),
		"end_ABN_Hold"			=> array("msg" => "This Lot has been HOLD due to ABN.",										"lv" => 3),
		"end_PCS_Hold"			=> array("msg" => "This Lot has been HOLD due to PCS.",										"lv" => 3),
		"end_ABNPCS_Hold"		=> array("msg" => "This Lot has been HOLD due to ABN&PCS.",									"lv" => 3),
		"end_MBN_Hold"			=> array("msg" => "This Lot has been HOLD due to MBN.",										"lv" => 3),
		"end_CON_Hold"			=> array("msg" => "This Lot has been HOLD due to CONTAINMENT.",								"lv" => 3),
		"end_Print"				=> array("msg" => "Printed label.",														"lv" => 1),
		"err_Err_backfill"              => array("msg" => "An error occurred by BACKFILL system.",           		                                               "lv" => 0),
		"err_Warn_backfill"             => array("msg" => "An warning occurred by BACKFILL system.",                    	                                       "lv" => 3),	
		"err_Slice_No_Limit"            => array("msg" => "Slice ID count is exceed than the limit.",                                                           "lv" => 0),
		"err_PO_Diff"                   => array("msg" => "SNI PO No are not same. Can't merge the lots.",                                                      "lv" => 0),
                "err_PO_Not_Found"              => array("msg" => "SNI PO No can't find.",                                                                              "lv" => 0),
	
	        "err_Po_Pol_merge"  => array("msg" => "SNI PO Number or PO Line Number are not same.",            "lv" => 0)

	);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}
?>
