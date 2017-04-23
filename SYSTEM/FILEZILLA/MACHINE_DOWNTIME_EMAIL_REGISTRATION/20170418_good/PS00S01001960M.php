<?php
# ======================================================================================
# [DATE]  : 2017.04.07          		[AUTHOR]  : MIS) Mydel
# [SYS_ID]: GPRISM						[SYSTEM]  : CCD
# [SUB_ID]:								[SUBSYS]  : 
# [PRC_ID]:								[PROCESS] : 
# [PGM_ID]: PS00S01001960M.php			[PROGRAM] : Machine Downtime Email Registration
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

function PS00S01001960_item($w_label) {

	$w_item = array(
		"HeaderTitle"	=> "Machine Downtime Email Master",
		"ScreenTitle" 	=> "***Machine Downtime Email Master***",
		"Name"			=> "Name",
		"Email"			=> "Email",
		"DivisionCode"	=> "Division Code",
		"Ridge"			=> "Ridge",
		"HourCode" 		=> "Time Range",
		"Pgmid" 		=> "Program ID",
		"Pgmkbn" 		=> "Program division",
		"Subsyskbn" 	=> "Subsystem division",
		"RSelect" 		=> "Selection",
		"HitCnt" 		=> "",
		"Count" 		=> "",
		"Page" 			=> "Page",
		"Check" 		=> "Check",
		"Execute" 		=> "Execute",
		"Back" 			=> "Back",
		"Clear" 		=> "Clear",
		"InputRow" 		=> "No of Rows",
		"EQP"			=> "EQP",
		"TNM"			=> "TNM",
		"PRN"			=> "PRN",
		"Email"			=> "Email",
		"UserId"		=> "UserId"
	);
	return $w_item[$w_label];
}

function PS00S01001960_msg($label, $lev = false) {

	$msg = array(
		"err_Nec_Input"				=> array("msg" => "Required Input. ",												"lv" => 0),
		"err_Inp_Char"				=> array("msg" => "Please input the alphanumeric character. ",						"lv" => 0),
		"err_Sel_ParMst"			=> array("msg" => "The error occurred when the Par Master was retrieved.",        	"lv" => 0),
		"err_Sel_DepMst"			=> array("msg" => "The error occurred when the Dep Master was retrieved.",        	"lv" => 0),
		"err_Dup_User_Id"			=> array("msg" => "User Id input cannot be duplicate. ",        					"lv" => 0),
		"err_Int_User_Id"			=> array("msg" => "User Id input should be number only. ",        					"lv" => 0),
		"err_chk_hr_data"			=> array("msg" => "Error occured in checking HR data",								"lv" => 0),
		"err_chk_Email_NotFound"	=> array("msg" => "Email address is not found in employee master. Invalid User Id are :",				"lv" => 0 ),
		"err_chk_dep_data"			=> array("msg" => "Error occured in checking Department data",					"lv" => 0),
		"err_chk_Dept_NotFound"		=> array("msg" => "Department not found in the EQP, TNM and PRN. Invalid User Id are :",						"lv" => 0 ),
		"err_Inp_Tag"				=> array("msg" => "The input tag form ID is not correct. ",							"lv" => 0),
		"err_Ins"					=> array("msg" => "Failed to insert.",												"lv" => 0),
		"err_Upd_ParMst"			=> array("msg" => "Failed to update.",												"lv" => 0),
		"suc_Ins_User_id"			=> array("msg" => "User id are maintained successfully.",							"lv" => 1),
		"suc_Ins_User_id_Exist_All"	=> array("msg" => "User id were all existing.",										"lv" => 0),
		"suc_Upd_ParMst"			=> array("msg" => "Success to update.",												"lv" => 1),
	);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}
?>
