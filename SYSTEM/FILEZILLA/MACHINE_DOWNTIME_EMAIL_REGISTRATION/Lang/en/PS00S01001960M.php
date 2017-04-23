<?php
# ===============================================================================
# [DATE]  : 2005.03.25			[AUTHOR]  : DOS)Y.Kawakami
# [SYS_ID]: GPRISM				[SYSTEM]  : 統合生産管理システム
# [SUB_ID]:						[SUBSYS]  : 工程管理サブシステム
# [PRC_ID]:						[PROCESS] :
# [PGM_ID]: PS00S01001960.php	[PROGRAM] : プログラムマスタ選択画面
# [MDL_ID]:						[MODULE]  :
# -------------------------------------------------------------------------------
# [COMMENT]
#
# -------------------------------------------------------------------------------
# [UPDATE_LOG]
#
# [UPDATE_PERSON]       [UPDATE]    [COMMENT]
# ====================  ==========  ============================================
#
# -------------------------------------------------------------------------------
function PS00S01001960_item($w_label) {

	$w_item = array(
				  "HeaderTitle"			=> "Machine Downtime Email Master"
				, "ScreenTitle"			=> "***Machine Downtime Email Master***"
				, "Name"				=> "Name"
					 , "Email"                                => "Email"
				, "DivisionCode"                          => "Division Code",
				  "Ridge"                                 => "Ridge"
				,"HourCode"   => "Time Range"
				, "Pgmid"				=> "Program ID"
				, "Pgmkbn"				=> "Program division"
				, "Subsyskbn"			=> "Subsystem division"
				, "RSelect"				=> "Selection"
				, "HitCnt"				=> ""
				, "Count"				=> ""
				, "Page"				=> "Page"
				 , "Check"                                => "Check"
				 , "Execute"                                => "Execute"
				 , "Back"                                => "Back"
	);

	return $w_item[$w_label];
}

function PS00S01001960_msg($w_label, $w_lev = false) {

	$w_msg = array(
				# DB
				"err_Sel_PgmMst"			=> array("msg" => "It failed in the program master's retrieval. ",					"lv" => 0),
				"guid_Copy"    		     => array("msg" => "Is the copy executed?",                                 "lv" => 2),
				"err_Del_PgmMst"			=> array("msg" => "It failed in the program master's deletion. ",					"lv" => 0),

				# ガイダンス
				"guid_Select"			=> array("msg" => "Please select it. ",									"lv" => 2),
				"guid_Delete"			=> array("msg" => "Is the deletion executed?",								"lv" => 2),
				"guid_ReSearch"			=> array("msg" => "Because the search condition had been changed, it retrieved it again. ",			"lv" => 2),

                # 完了メッセージ
				"end_Delete"			=> array("msg" => "It deleted it. ",										"lv" => 1),
				  "err_Email"                    => array("msg" => "Please enter a valid email address. ",                                                                            "lv" => 0),

 "err_Select_Div"                    => array("msg" => "Please select the division ",                                                                            "lv" => 0),
 "err_Select_Rid"                    => array("msg" => "Please select the ridge ",                                                                            "lv" => 0),
 "err_Select_Tim"                    => array("msg" => "Please select the time range ",                                                                            "lv" => 0),

 "end_Update"                    => array("msg" => "Email addresses are maintained successfully ",     "lv" => 1),

				   "err_Email_exist"                    => array("msg" => "Please enter unique email address. ",                                                                            "lv" => 0),

				"err_chk_hr_data" => array( "msg" => "Error occured in checking HR data", "lv" => 0 ),

				"err_no_email" => array( "msg" => "No email address found.", "lv" => 0 ),

				# その他
				"err_Fnd_Cd"			=> array("msg" => "The data that corresponds to the condition doesn't exist. ",				"lv" => 0),
				"err_ChkRadio"			=> array("msg" => "Please select the details line. ",							"lv" => 0),
				"err_NotData"			=> array("msg" => "There is no data in the selected line. ",					"lv" => 0),
				"err_UpdLev"			=> array("msg" => "Data was updated from other terminals. ",				"lv" => 0),
				"err_MenuTbl"			=> array("msg" => "This program ID is registered as a menu. ",				"lv" => 0),
				"err_pgm_mst_delete"    => array("msg" => "It failed in the program master's deletion. "                          ,"lv" => 0),
				"err_Char"				=> array("msg" => "The prohibition character is included. ",							"lv" => 0),
				"err_InpOver"			=> array("msg" => "The number of maximum input digits is exceeded. ",						"lv" => 0),
		);


	if($w_lev == false){
		return array($w_msg[$w_label]["msg"],$w_msg[$w_label]["lv"]);
	} else {
		return $w_msg[$w_label]["msg"];
	}

}
?>
