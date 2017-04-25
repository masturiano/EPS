<?php
require_once ($g_lang_dir . "/cs_xpt_f0_test_msg.php");

define("AW_F0_TEST_PRD", "AW21S0000009");
define("CT_F0_TEST_TRACKIN", "CT21S0000001");
define("CT_F0_TEST_TRACKOUT", "CT21S0000001");
define("CT_F0_TEST_PARENT_LOT", "CT21S0000025");
define("CE_LOT_INF", "CE00S02");

################################################################
# This function will check the product is F0_TEST Product or not
# based on the product information master.
# ------------------------------------------------------------
# Parameters : w_lot_id - Lot ID
#		w_trackout_date - Track out date
#		w_verb - Verb
#		w_usr_id - Input User ID		 
#
# Return :      0 (success)
#               4000 (error)
################################################################
function cs_xpt_ccd_f0_test__insert_track_out_date($w_lot_id, $w_track_out_dts, $w_verb, $w_usr_id) {
    global $gw_scr;
    global $g_msg;
    global $g_err_lv;
    global $g_low_dts;
    global $g_cpu_dts;

    $w_ctg_cd = constant("CT_F0_TEST_TRACKOUT");
    $w_ce_lot = constant("CE_LOT_INF");

    $w_rtn = cs_xpt_ccd_f0_test__get_limit($w_lot_id, $w_limit);
    if($w_rtn != 0) {
	return $w_rtn;
    }

    $w_track_out_dts = strtotime($w_track_out_dts);
    $w_track_out_dts = strtotime("+".$w_limit." days", $w_track_out_dts);
    $w_ctg_dat_txt =  date('Y-m-d h:i:s', $w_track_out_dts);

    $w_arr = array(
            "DEL_FLG" => "0",
            "LOT_ID" => $w_lot_id,
            "CTG_DVS_CD" => $w_ce_lot,
            "CTG_CD" => $w_ctg_cd,
            "SL_ID" => " ",
            "CTG_DAT_TXT" => $w_ctg_dat_txt,
            "CTG_DAT_VAL" => null,
            "CRT_VERB" => $w_verb,
            "CRT_DTS" => $g_cpu_dts,
            "USR_ID_CRT" => $w_usr_id,
            "UPD_VERB" => " ",
            "UPD_DTS" => $g_cpu_dts,
            "USR_ID_UPD" => " ",
            "UPD_LEV" => 1
    );
    if (DEBUG_MODE == 1) {
        dbg_array('INSERT LOT_INF_TBL', $w_arr);
    }

    $w_rtn = db_insert("LOT_INF_TBL", $w_arr);
    if ($w_rtn != 0) {
	$g_msg = cs_xpt_f0_test_msg("err_Insert")."(LOT_INF_TBL)";
        return $w_rtn;
    }

}

# This function will insert the mother lot id to lot_inf_tbl
function cs_xpt_ccd_f0_test__insert_mother_lot_id($w_lot_id, $w_mother_lot_id, $w_verb, $w_usr_id) {
    global $gw_scr;
    global $g_msg;
    global $g_err_lv;
    global $g_low_dts;
    global $g_cpu_dts;

    $w_ctg_cd = constant("CT_F0_TEST_PARENT_LOT");
    $w_ce_lot = constant("CE_LOT_INF");
    $w_ctg_dat_txt = $w_mother_lot_id;

    $w_arr = array(
            "DEL_FLG" => "0",
            "LOT_ID" => $w_lot_id,
            "CTG_DVS_CD" => $w_ce_lot,
            "CTG_CD" => $w_ctg_cd,
            "SL_ID" => " ",
            "CTG_DAT_TXT" => $w_ctg_dat_txt,
            "CTG_DAT_VAL" => null,
            "CRT_VERB" => $w_verb,
            "CRT_DTS" => $g_cpu_dts,
            "USR_ID_CRT" => $w_usr_id,
            "UPD_VERB" => " ",
            "UPD_DTS" => $g_cpu_dts,
            "USR_ID_UPD" => " ",
            "UPD_LEV" => 1
    );
    if (DEBUG_MODE == 1) {
        dbg_array('INSERT LOT_INF_TBL', $w_arr);
    }

    $w_rtn = db_insert("LOT_INF_TBL", $w_arr);
    if ($w_rtn != 0) {
	$g_msg = cs_xpt_f0_test_msg("err_Insert")."(LOT_INF_TBL)";
        return $w_rtn;
    }

}


# This function will get the parent lot and check it onhold or not.
function cs_xpt_ccd_f0_test__is_parent_onhold($w_lot_id, &$r_status) {
        global $g_msg;
        global $g_err_lv;
        global $g_cpu_dts;

        $r_status = true;
        $w_ctg_cd = constant("CT_F0_TEST_PARENT_LOT");

        $w_sql = "
            SELECT
		LBT.LOT_ST_DVS
            FROM
                LOT_BAS_TBL LBT,
                LOT_INF_TBL LIT
            WHERE
                LIT.DEL_FLG = '0' AND
                LIT.CTG_CD = '{$w_ctg_cd}' AND
		LIT.LOT_ID = '{$w_lot_id}' AND
                LIT.CTG_DAT_TXT = LBT.LOT_ID 
		
        ";
        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                $g_msg = cs_xpt_f0_test_msg("err_Sel"); 
                return 4000;
        }
        while($w_row = db_fetch_row($w_stmt)){
                if( "HD" != trim($w_row['LOT_ST_DVS'])) {
			$r_status = false;
		}

        }
        db_res_free($w_stmt);



        return 0;
}


function  cs_xpt_ccd_f0_test__get_limit($w_lot_id, &$r_limit) {
        global $g_msg;
        global $g_err_lv;
        global $g_cpu_dts;

        $r_limit = 180;
        $w_aw_cd = constant("AW_F0_TEST_PRD");

        $w_sql = "
            SELECT
                PIM.NUM_DAT LIMIT
            FROM
                LOT_BAS_TBL LBT,
                PRD_INF_MST PIM
            WHERE
                PIM.DEL_FLG = '0' AND
                LBT.PRD_CD = PIM.PRD_CD AND
                PIM.DAT_CD = '{$w_aw_cd}'  AND
                LBT.LOT_ID = '{$w_lot_id}'
        ";
        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                $g_msg = cs_xpt_f0_test_msg("err_Sel");
		return 4000;
        }
	while($w_row = db_fetch_row($w_stmt)){
                $r_limit = trim($w_row['LIMIT']);
        }

        db_res_free($w_stmt);
        return 0;
}

function  cs_xpt_ccd_f0_test__is_expire($w_lot_id,$w_ctg_cd,  &$r_expiry_status ) {
        global $g_msg;
        global $g_err_lv;
        global $g_cpu_dts;

        $r_expiry_status = false;

        $w_sql = "
            SELECT
                LIT.CTG_DAT_TXT TRK_IN_DTS
            FROM
                LOT_INF_TBL LIT
            WHERE
                LIT.DEL_FLG = '0' AND
                LIT.CTG_CD = '{$w_ctg_cd}' AND
                LIT.LOT_ID = '{$w_lot_id}'
        ";
        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                $g_msg = cs_xpt_f0_test_msg("err_Sel");
                return 4000;
        }
        while($w_row = db_fetch_row($w_stmt)){
                $s_lot_id = "";
                $w_trackout_date = trim($w_row['TRK_IN_DTS']);
                $w_epc_cpu_dts   = cs_xpt_ccd_f0_test__CnvEpc($g_cpu_dts);
                $w_epc_trkout_time = cs_xpt_ccd_f0_test__CnvEpc($w_trackout_date);
                if($w_epc_trkout_time < $w_epc_cpu_dts){
                    $r_expiry_status = true;
                    $g_msg = cs_xpt_f0_test_msg("err_Need_Retest");
                    return 4000;
                }

        }

        db_res_free($w_stmt);
        return 0;
}


/*
old code
function  cs_xpt_ccd_f0_test__is_expire($w_lot_id,$w_ctg_cd,  &$r_expiry_status ) {
        global $g_msg;
        global $g_err_lv;
        global $g_cpu_dts;

        $r_expiry_status = false;
        // $w_ctg_cd = constant("CT_F0_TEST_TRACKIN");
	$w_aw_cd = constant("AW_F0_TEST_PRD");

        $w_sql = "
            SELECT 
                LIT.CTG_DAT_TXT TRK_IN_DTS,
                PIM.NUM_DAT LIMIT
            FROM
                LOT_BAS_TBL LBT,
                LOT_INF_TBL LIT,
                PRD_INF_MST PIM 
            WHERE
                PIM.DEL_FLG = '0' AND
                LIT.DEL_FLG = '0' AND
                LBT.LOT_ID = LIT.LOT_ID AND
                LBT.PRD_CD = PIM.PRD_CD AND
                PIM.DAT_CD = '{$w_aw_cd}'  AND
                LIT.CTG_CD = '{$w_ctg_cd}' AND
                LBT.LOT_ID = '{$w_lot_id}' 
        ";
        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                $g_msg = cs_xpt_f0_test_msg("err_Sel");
                return 4000;
        }
	$w_limit = "";
        while($w_row = db_fetch_row($w_stmt)){
                $s_lot_id = "";
                $w_trackin_date = trim($w_row['TRK_IN_DTS']);
                $w_limit = trim($w_row['LIMIT']);
                $w_epc_cpu_dts   = cs_xpt_ccd_f0_test__CnvEpc($g_cpu_dts);
                $w_epc_trkin_time = cs_xpt_ccd_f0_test__CnvEpc($w_trackin_date);
                #------------------------------------------------------------------
                # convert to minute for elapsed time
                #------------------------------------------------------------------
                $w_lead_time = ($w_epc_cpu_dts - $w_epc_trkin_time) / 60/60;
                $w_lead_day = $w_lead_time / 24;
                if($w_lead_day > $w_limit){
                    $r_expiry_status = true;
                    $g_msg = cs_xpt_f0_test_msg("err_Need_Retest");
                    return 4000;   
                }

        }
	
	if($w_limit == "") {
		$g_msg = cs_xpt_f0_test_msg("err_No_Mst");
		return 4000;
	}

        db_res_free($w_stmt);
        return 0;
}
*/


# This function will return the f0 test date
function cs_xpt_ccd_f0_test__get_date($w_lot_id, &$r_date) {
        global $g_msg;
        global $g_err_lv;
        global $g_cpu_dts;

        $r_date = array();
	 $w_ctg_cd = constant("CT_F0_TEST_TRACKOUT");

        $w_sql = "
            SELECT
		DISTINCT
                LIT.CTG_DAT_TXT
            FROM
                LOT_INF_TBL LIT
            WHERE
                LIT.DEL_FLG = '0' AND
                LIT.CTG_CD = '{$w_ctg_cd}' AND
                LIT.LOT_ID = '{$w_lot_id}' 
	    ORDER BY 
		LIT.CTG_DAT_TXT

        ";
        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
                $g_msg = cs_xpt_f0_test_msg("err_Sel");
                return 4000;
        }
        while($w_row = db_fetch_row($w_stmt)){
		$r_date[] = trim($w_row['CTG_DAT_TXT']);
        }

        db_res_free($w_stmt);
        return 0;
}


#==================================================================
# convert to unix time
#==================================================================
function cs_xpt_ccd_f0_test__CnvEpc($w_dts)
{
    list($y,$m,$d,$h,$i,$s) = preg_split("/[\-: ]/", $w_dts);
    return mktime($h,$i,$s,$m,$d,$y);
}
?>
