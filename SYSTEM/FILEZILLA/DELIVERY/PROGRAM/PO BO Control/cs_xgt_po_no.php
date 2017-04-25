<?php
require_once ($g_lang_dir . "/cs_xgt_po_no_msg.php");

define("CE_PO", "CE00S20");
define("CT_PO", "CT00S0000379");
define("CT_PDO", "CT00S0000380");

function cs_xgt_po_no($w_lot_id, &$r_dat=array() , $w_mode = 0) 
{
	global $g_msg;
        global $g_err_lv;
	$r_dat = array();

	$w_ctg_cd = constant("CT_PO");
	if($w_mode == 1){
		// enabled to get the Build Order number in case needed
		$w_ctg_cd = constant("CT_PDO");
	}

        $w_sql = "
                select
                        CTG_DAT_TXT
                from
                        ctg_tbl
                where
                        del_flg='0'
                        and lot_id = '{$w_lot_id}'
                        and ctg_cd = '{$w_ctg_cd}'
			and ctg_dvs_cd = '" . constant("CE_PO")  ."'
        ";



        $w_stmt = db_res_set($w_sql);
        $w_rtn = db_do($w_stmt);
        if($w_rtn != 0){
		$g_err_lv = 0;
		$g_msg = cs_xgt_po_no_msg("err_Sel");
        	$g_msg = xpt_err_msg($g_msg, $w_lot_id, __LINE__);
                return 4000;
        }
	while($w_row = db_fetch_row($w_stmt)){
		$r_dat[] = trim($w_row['CTG_DAT_TXT']);	
	}
	db_res_free($w_stmt);	
        return 0;
}

?>
