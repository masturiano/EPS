<?php
# ===============================================================================
# [DATE]  : 2004.06.08         [AUTHOR]  : DOS)Fujita
# [SYS_ID]: GPRISM             [SYSTEM]  : 非自動化標準ＣＩＭ
# [SUB_ID]:                    [SUBSYS]  : 
# [PRC_ID]:                    [PROCESS] :
# [PGM_ID]: PS00S01000400.php  [PROGRAM] : 次工程渡し(メッセージ)
# [MDL_ID]:                    [MODULE]  :
# -------------------------------------------------------------------------------
# [COMMENT]
#
# -------------------------------------------------------------------------------
# [UPDATE_LOG]
#
# [UPDATE_PERSON]       [UPDATE]    [COMMENT]
# ====================  ==========  ============================================
# DOS)K.Yamamoto		2005.03.16	メッセージ err_Nxt_Prc 追加
# ZXS)K.Maeda           2006.02.27	PSCID用にPSSEM01000050をコピーして作成
# dos)doi				I120413-0068395	ID版PS00C01000121から移植(リング数及びバー数の表示機能追加)
# -------------------------------------------------------------------------------

function PS00S01000400_item($label) {

		$item = array(
			"HeaderTitle"			=> "Next Step Transfer"
		,	"ScreenTitle"			=> "*** Next Step Transfer ***"
		,	"UsrID"					=> "User ID"
		,	"LotID"					=> "Lot ID"
		,	"IoBlockName"			=> "Step Name"
		,	"KindName"				=> "Type Name"
		,	"AimRank"				=> "Aim Rank"
		,	"DiffFactoryNo"			=> "Diffusion Lot No"
		,	"AssFactoryNo"			=> "Assembly Lot No"
		,	"SecretNo"				=> "Date Code"
		,	"PackageName"			=> "Package Name"
		,	"ChipQty"				=> "Chip Qty"
		,	"SlQty"					=> "Slice Qty"
		,	"LFQty"					=> "Leadframe Qty"
		,	"NextIoBlockName"		=> "Next Step Name"
		,	"BondedNo"				=> "Bonded No",
			"BarQty"				=> "Bar Qty",
			"RingQty"				=> "Ring Qty",
		
			"RsvHoldInfo"			=> "Reservation Reason: %s, Contact: %s, Release Date set %s days after from today.",
		);

		return $item[$label];
}

function PS00S01000400_msg($label) {

	$msg = array(

			"err_Irregular"			=> array("msg" => "Contains invalid characters.",										"lv" => 0)
		,	"err_LotStepPacking"		=> array("msg" => "Cannot do Next Step Transfer at Packing.",										"lv" => 0)
		,	 "err_LotStepPlating"            => array("msg" => "Cannot do Next Step Transfer at Plating.",                                                                           "lv" => 0)

		,	"err_Alphabet"			=> array("msg" => "Please enter in alphanumeric.",									"lv" => 0)
		,	"err_LotState"			=> array("msg" => "This Lot state is not Out-Wait/End-Wait.",							"lv" => 0)
		,	"err_Nxt_Prc"			=> array("msg" => "This Lot do not have next Step.",							"lv" => 0)
		,	"comp_CHECK"			=> array("msg" => "Check has been completed. Please press Execute.",				"lv" => 2)
		,	"comp_UPDATE"			=> array("msg" => "Update process was completed successfully.",									"lv" => 1),

		"err_Sel"				=> array("msg" => "Failed to retrieve.",														"lv" => 0),
                "err_sel_dvs"                           => array("msg" => "Error in selecting continus division value",
                                                                        "lv" => 0),
                "err_no_end_stp"                        => array("msg" => "There is no end step to continue.",
                                                                        "lv" => 0),
                "err_sel_stps"                          => array("msg" => "Error in selecting steps to loop",
                                                                        "lv" => 0),
                "err_no_equ_cd"                         => array("msg" => "There is no equipment",
                                                                        "lv" => 0),
                "err_sel_equ_cd"                        => array("msg" => "Error in selecting equipment",
                                                                        "lv" => 0),
                "err_iomv"                              => array("msg" => "Error in IOMV",
                                                                        "lv" => 0),
                "err_ioot"                              => array("msg" => "Error in IOOT",
                                                                        "lv" => 0),
                "err_ioin"                              => array("msg" => "Error in IOIN",
                                                                        "lv" => 0),
		"err_upd_lev"				=> array("msg" => "IOMV : LOT_BAS_TBL has been updated by other terminals.",
									"lv" => 0),
		"End_Rsv_Hold"			=> array("msg" => "This Lot has been executed Hold Reservation processing.",						"lv" => 3),
		);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}
?>
