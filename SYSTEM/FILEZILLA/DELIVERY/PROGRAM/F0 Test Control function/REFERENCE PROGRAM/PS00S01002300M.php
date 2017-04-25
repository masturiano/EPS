<?php
# ======================================================================================
# [DATE]  : 2017.03.14					[AUTHOR]  : MIS) WINSTON
# [SYS_ID]: GPRISM					[SYSTEM]  : GPRISM
# [SUB_ID]:						[SUBSYS]  : 
# [PRC_ID]:						[PROCESS] : 
# [PGM_ID]: PS00S01002300M.php				[PROGRAM] : Track-In(CCD)
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

function PS00S01002300_item($label)
{
	$item = array
		(
			"HeaderTitle"				=> "Track-In(CCD)",
			"ScreenTitle"				=> "*** Track-In(CCD) ***",

			"UsrId"					=> "User ID",
			"LotID"					=> "Lot ID",
			"EquCd"					=> "Equipment Code",

			"StpNm"					=> "Step Name",
			"PrdNm"					=> "Type Name",
			"RnkPtn"				=> "Rank Pattern",
			"PkgNm"					=> "Package Name",
			"DiffLotNo"				=> "Diffusion Lot No",
			"ChpQty"				=> "Chip Qty",
			"SubQty"				=> "Substrate qty",
			"SliceQty"				=> "Slice qty",
			"PhysicalQty"				=> "Physical Qty",

			"PicPrslTitle"				=> "PICTURE PERUSAL",
			"PicPrslInput"				=> "INPUT",
			"PicPrslFail"				=> "FAIL",
			"PicPrslYield"				=> "YIELD",
	
			"Test"					=> "*only for Testing",
			"PrgID"					=> "Program",
			"Tester"				=> "Tester",
			"Fixture"				=> "Fixture",
			"BoardPin"				=> "Board Pin",
			"LenPin"				=> "Len Pin",

			"PowerEyeTitle" =>	"Power Eye :",
			"PowerEyeInputQty" =>	"Input Qty",
			"PowerEyeOutputQty" =>	"Output Qty",
			"PowerEyeFailureQty" =>	"Failure Qty",
			"PowerEyeYield" =>	"Yield",
			"PowerEyeDate" =>	"Date",
			"PowerEyeUserID" =>	"User ID",



                        "PrgID_2"                               => "Program",
                        "Tester_2"                              => "Tester",
                        "Fixture_2"                             => "Fixture",
		
			"DispRow"				=> "Number of Lines",
			"1to10"					=> "* 1 - 10",
			"MgznID"				=> "Magazine Lot ID",
			"MtlWght"				=> "metal weight",
			"Cmt"					=> "Comment",
		 );

	return $item[$label];
}

function PS00S01002300_msg($label)
{
	$msg = array
	(
		"err_Nec_Input"			=> array("msg" => "Required input.",																"lv" => 0),
		"err_Inp_Char"			=> array("msg" => "Contains invalid characters.",														"lv" => 0),
		"err_Inp_Tag"			=> array("msg" => "This input tag form ID is not correct.",													"lv" => 0),
		"err_Inp_Dup"			=> array("msg" => "Duplicate data.",																"lv" => 0),
		"err_Inp_Over"			=> array("msg" => "Outside input range.",															"lv" => 0),
		"err_Sel"			=> array("msg" => "Failed to retrieve.",															"lv" => 0),
		"err_Upd"			=> array("msg" => "Failed to update.",																"lv" => 0),
		"err_Ins"			=> array("msg" => "Failed to insert.",																"lv" => 0),
		"err_Disabled"			=> array("msg" => "This Lot cannot be processed on this menu.",													"lv" => 0),
		"err_PrcFlw"			=> array("msg" => "Cannot be acquired Process Flow Information.",												"lv" => 0),
		"err_DifQty"			=> array("msg" => "Qty is different.",																"lv" => 0),
		"err_Neg"			=> array("msg" => "Qty is negative.",																"lv" => 0),
		"err_p_qty_chp"			=> array("msg" => "Quantity can't be greather than chip qty.",																"lv" => 0),
		"err_NotDatLotInf"		=> array("msg" => "Data not found in Lot Information Table.",													"lv" => 0),
		"err_Inp_MatMng"		=> array("msg" => "Please confirm the material control.",													"lv" => 0),
		"err_Err_Space"			=> array("msg" => "An error occurred by SPACE system.",														"lv" => 0),
		"err_Get_BakeDat"		=> array("msg" => "Cannot acquire the data of Baking-Out Time.",												"lv" => 0),
		"err_Wipe_stp_not_fin" => array("msg" => "SCVE Lot has not finished the WIRE & PROTECT SEAL Step",												"lv" => 0),

		"err_MaxBake"			=> array("msg" => "Lot exceeded maximum number of baking.",													"lv" => 0),
		"err_TrcTime"			=> array("msg" => "This Lot has been exceeded the limited time.",												"lv" => 0),
		"err_F0_test_expired"		=> array("msg" => "F0 test ia expired. Need to re-test.",										"lv" => 0),
		"err_TrcTimeBake1"		=> array("msg" => "This Lot has been exceeded the limited time. Need to re-Bake.",										"lv" => 0),
		"err_TrcTimeBake2"		=> array("msg" => "This Lot has been exceeded the limited time. but this Lot has already been re-Bake.",							"lv" => 0),
		"err_HairlineCrack"		=> array("msg" => "This Lot is subject to the Hairline Crack. Cannot Track-In.",										"lv" => 0),
		"err_Program"			=> array("msg" => "Combination of entered program does not exist in the Master.",										"lv" => 0),
		"err_Program2"			=> array("msg" => "Combination of entered board and len pin does not exist in the Master.",										"lv" => 0),
		"err_BP2_Control"		=> array("msg" => "The other child Lot exists in previous step.",												"lv" => 0),
		"err_BP2_Transfer"		=> array("msg" => "This lot need to be transfered to _RT flow.",												"lv" => 0),
		"err_NeedRecondition"		=> array("msg" => "Equipment needs to be reconditioned first. Execute in iGate.",										"lv" => 0),
		"err_ParentChildControl"	=> array("msg" => "The other child Lot has been HOLD before F-test.",												"lv" => 0),
		 "err_F0_parent_on_hold"                                    => array("msg" => "The Parent Lot is on HOLD.",                                                               "lv" => 0),
		"err_Ovr_ChrgThaw"		=> array("msg" => "%MTLOTID% has not been finished the thawing time.",												"lv" => 0),
		"warn_Hold_LifeTime"		=> array("msg" => "Failed to Track-In. %MTLOTID% has been HOLD due to the life time(%TIME%) exceeding.",							"lv" => 3),
		"warn_Hold_ExpTime"		=> array("msg" => "Failed to Track-In. %MTLOTID% has been HOLD due to the expired time(%TIME%) exceeding.",							"lv" => 3),
		"err_Equ_NoCharge"		=> array("msg" => "Cannot use this material due to not charged.",												"lv" => 0),
		"err_Unexpected"		=> array("msg" => "An unexpected error occurred.",														"lv" => 0),
		"err_Cancel"			=> array("msg" => "Cannot cancel this Lot.",															"lv" => 0),
		"err_Mis_Log"			=> array("msg" => "Could not be found the Log.",														"lv" => 0),
		"guid_Execute"			=> array("msg" => "Check has been completed. Please press Execute.",												"lv" => 2),
		"err_copper_wire_Expired"	=> array("msg" => "Copper Wire Used for this Lot is expired",													"lv" => 1),
		"end_Execute"                   => array("msg" => "Update completed successfully.",                                                        							"lv" => 1),
		"err_Sel_MBN"             	=> array("msg" => "",   
						   "lv" => 0),
                "err_MBN_Hold"             	=> array("msg" => "MBN Lot has not been released for this equipment because of this lots",   
						   "lv" => 0),
		"err_Err_backfill"              => array("msg" => "An error occurred by iMES system.",                                                              					"lv" => 0),
                "err_Warn_backfill"             => array("msg" => "An warning occurred by iMES system.",                                                            					"lv" => 3),
		"err_dvsn_lsi"                 => array("msg" => "Lot can't track-in using this program. Please use LSI Track-In program.",                                                                "lv" => 0),
                "err_dvsn_bga"                 => array("msg" => "Lot can't track-in using this program. Please use BGA Track-In program.",                                                                "lv" => 0),
                "err_dvsn_ld"                  => array("msg" => "Lot can't track-in using this program. Please use LD Track-In program.",                                                                 "lv" => 0),
                "err_dvsn_not_valid"           => array("msg" => "Lot can't track-in using this program.",                                                                                     		"lv" => 0),
		"err_phy_qty_blank"		=> array("msg" => "Please input the physical qty.",													"lv" => 0),
		"err_phy_qty_number"		=> array("msg" => "Please input the number.",														"lv" => 0),
		"err_phy_qty_not_correct"	=> array("msg" => "Physical qty is not tally with the calculated qty at Auto VI Track Out.",								"lv" => 0)

	);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}
?>
