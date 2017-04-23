<?php
# ======================================================================================
# [DATE]  : 2013.01.07          		[AUTHOR]  : MIS)L.Acera
# [SYS_ID]: GPRISM              		[SYSTEM]  : 
# [SUB_ID]:                     		[SUBSYS]  :
# [PRC_ID]:                     		[PROCESS] :
# [PGM_ID]: PS00S01001400M.php  		[PROGRAM] : CreatePackingLot(BGA)
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

function PS00S01001400_item($label) {
	$item = array(
			"HeaderTitle" 		=> "CreatePackingLot(LSI)",
			"ScreenTitle" 		=> "*** Create Packing Lot(LSI) ***",
			"UsrId" 		=> "User ID",
			"PrinterCode" 		=> "Printer Code",
			"InputRow" 		=> "Number of Lots",
			"LotId" 		=> "Lot ID",
			"PrdNm" 		=> "Type Name",
			"LotNoStr" 		=> "Diffusion Lot No",
			"DteCdStr"		=> "Date Code",
			"OrgQty" 		=> "Original Qty",
			"RmnQty" 		=> "Remaining Qty",
			"ChpQty"                => "Chip Qty",
			"DispRow" 		=> "Number of Lines",
			"1to10" 		=> "* 1 - 10",
			"MgznID" 		=> "Magazine Lot ID",
			"PckIDLotId" 		=> "Packing Lot ID",
			"TtlQty" 		=> "Total",
			"BlackCase" 		=> "Black Case#",
			"PckTypNm" 		=> "Packing Type Name",
			"PckTypQty"             => "Packing Lot Quantity",
			"RemLot"		=> "Remain Lot",
			"PckRmks"		=> "Comment",
			"EquCd"			=> "Equipment Code",
                        "BoxQty"		=> "Number of Boxes",
			"PPCS"			=> "Sealing Condition",
			"PCS_Items"             => "PCS Items"
	);

	return $item[$label];
}

function PS00S01001400_msg($label) {
	$msg = array(
			#------------------------------------------------------------------
			# ÆþÎÏ¥¨¥é¡¼
			#------------------------------------------------------------------
			"err_Nec_Input" 	=> array("msg" => "Required input.",								"lv" => 0),
			"err_Inp_Char" 		=> array("msg" => "Contains invalid characters.",						"lv" => 0),
			"err_Inp_Tag" 		=> array("msg" => "This input tag form ID is not correct.",					"lv" => 0),
			"err_Inp_Dup" 		=> array("msg" => "Duplicate data.",								"lv" => 0),
			# £Ó£Ñ£Ì¥¨¥é¡¼
                        "err_Pck_Input" 	=> array("msg" => "Packing Lot Qty should not be more than Standard Packing Lot Qty.",  	"lv" => 0),

                        # £Ó£Ñ£Ì¥¨¥é¡¼
                        "err_Pck_Sup" 		=> array("msg" => "Only PRN Supervisors are allowed to Pack Loose Qty.",                	"lv" => 0),

                        # £Ó£Ñ£Ì¥¨¥é¡¼
                        "err_Pck_Eq" 		=> array("msg" => "Packing Lot Qty is not equal total Packing Chip Qty.",               	"lv" => 0),

			"err_Sel" 		=> array("msg" => "Failed to retrieve.",							"lv" => 0),
			"err_Upd" 		=> array("msg" => "Failed to update.",								"lv" => 0),
                        "err_Pcl" 		=> array("msg" => "PCL Remarks do not match for all lots.",		                	"lv" => 0),

			"err_Ins" 		=> array("msg" => "Failed to insert.",								"lv" => 0),
			"err_Lot_01" 		=> array("msg" => "Cannot be used in specified Lot state.",					"lv" => 0),
                        "err_Max_01" 		=> array("msg" => "Total number of date code exceeds limit.",                           	"lv" => 0),
                        "err_Lot_03" 		=> array("msg" => "Maximum Date Code already exceeded.",                                	"lv" => 0),
			"err_Lot_02" 		=> array("msg" => "Mother Lot cannot be specified in purlal input.",				"lv" => 0),
			"err_Lot_04" 		=> array("msg" => "Maximum Date Code is  not set into product registration,only one allowed.",				"lv" => 0),
                        "err_Lot_05" 		=> array("msg" => "Only one box allowed for Lots with different Date Codes.",                                	"lv" => 0), #Change phrase
                        "err_Lot_06" 		=> array("msg" => "Last LotID is  not needed to create the required boxes.",                                	"lv" => 0), #Change phrase


			"err_Disabled" 		=> array("msg" => "Cannot be used in this menu because Step is different.",			"lv" => 0),
			"err_NotFound_RingQty" 	=> array("msg" => "Cannot be acquired Ring Qty.",						"lv" => 0),
			"guid_Execute" 		=> array("msg" => "Check has been completed. Please press Execute.",				"lv" => 2),
                        "guid_Confirm" 		=> array("msg" => "Please press Check to validate Packing Lot Qty.",                    	"lv" => 2),

			"msg_Update" 		=> array("msg" => "Update completed successfully.",						"lv" => 1),
			"err_Inp_Mtcs" 		=> array("msg" => "Please confirm the material control.",					"lv" => 0),
			"err_Get_PckRt"		=> array("msg" => "Cannot be acquired the route information for this Packing Type Name.",	"lv" => 0),

                        "err_Get_PckQty"        => array("msg" => "Cannot be acquired the Standard Packing Qty for this Packing Type Name.",    "lv" => 0),

			"err_Ovr_RingQty"	=> array("msg" => "Cannot be created a packing Lot from pilot(s) which have total 41 ring qty or more.","lv"  => 0),

			"err_Get_Chp1Ring"	=> array("msg" => "Cannot be acquired Chip Qty per 1 Ring.",					"lv" => 0),
			"err_ParentChildControl"=> array("msg" => "The other child Lot has been HOLD before F-test.",				"lv" => 0),

			"err_PackChpQty"	=> array("msg" => "Chip Qty of Packing Lot does not meet the conditions.",			"lv" => 0),
			"err_InsuffQty"		=> array("msg" => "Total Chip Qty is insufficient to make the total boxes.",			"lv" => 0),
			"err_MaxLot"         	=> array("msg" => "Number of Lot IDs exceeds the maximum allowable value:".constant("DEFAULT_MAX_INPLOT"),               "lv" => 0),

			"err_InsuffQtyWarn"	=> array("msg" => "Total Chip Qty is insufficient to make the total boxes.",			"lv" => 3),

			"err_Ovr_StkQty"	=> array("msg" => "Stock Qty does not have enough.",						"lv" => 0),
			"err_PO_notMatch"		=> array("msg" => "Only lots with the same PO are allowed to pack together. Please remove lots with different PO.",									"lv" => 0),
			"err_Multiple_PO"		=> array("msg" => "Lot have multiple PO. Please inform Supervisor.",									"lv" => 0),

			"err_PostMouldCure"	=> array("msg" => "Post mould cure timing has exceeded.",						"lv" => 0),
			"err_Diff_Suc_PrdCd"    => array("msg" => "Continuous start process lot is not the same device",                        "lv" => 0),
			"err_Diff_Suc_ReInsFlg" => array("msg" => "Continuous start process lot is not the same re-test flag",                  "lv" => 0),
	   		"err_Diff_Suc_MngFlg"   => array("msg" => "Continuous start process lot is not the same control flag",                  "lv" => 0),
			"err_Exist_LotID"       => array("msg" => "Another lot has already been process with this equipment",           	"lv" => 0),
			"err_Sel_EquMst"        => array("msg" => "The error occurred when the Equipment Master was retrieved.",        	"lv" => 0),
			"err_Sel_LotBasTbl"     => array("msg" => "Failed in a search of lot base table.",                                      "lv" => 0),
			"err_PCS_Blank"     => array("msg" => "Please indicate the Sealing Condition.",                                      "lv" => 0),
			"err_PCS_NG"     => array("msg" => "Please redo until Sealing Condition is OK.",                                      "lv" => 0),
			"err_cairn_running" 	=> array("msg" => "Cairn process is running now. This function is not available. Please try again later.", "lv" => 0),
			"err_same_qty"        => array("msg" => "Lot Purchase order numbers are not same.",		                        "lv"  => 0),
			"err_PCS"                       => array("msg" => "Please confirm PCS Items.", "lv" => 0),
			"err_Err_Space"		=> array("msg" => "An error occurred by SPACE system.", "lv" => 0),


	);

	return array(
			$msg[$label]["msg"],
			$msg[$label]["lv"]
	);
}
?>
