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
		"T&M"			=> "T&M",
		"PRN"			=> "PRN"
	);
	return $w_item[$w_label];
}

function PS00S01001960_msg($label, $lev = false) {

	$msg = array(
		"err_Nec_Input"		=> array("msg" => "Required Input. ",												"lv" => 0),
		"err_Inp_Char"		=> array("msg" => "Please input the alphanumeric character. ",						"lv" => 0),

		"err_Sel_ParMst"	=> array("msg" => "The error occurred when the Par Master was retrieved.",        	"lv" => 0),
	);

	return array($msg[$label]["msg"],$msg[$label]["lv"]);
}
?>
