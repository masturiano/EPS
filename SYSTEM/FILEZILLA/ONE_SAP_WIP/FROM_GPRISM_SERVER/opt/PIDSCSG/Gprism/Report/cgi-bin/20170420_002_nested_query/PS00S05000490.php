<?php
$w_date = date('Ymd_His');
$argv = $_SERVER['argv'];
include("config_gprism_wip.php");

//$generate_date = date('Ym');
$generate_date ='201607';


$g_log = "/tmp/capture_wip_onesap_data_".$w_date.".log";
$w_file_name = "/dat/Gprism/FTP/wip_onesap_interface/onesap_wip.dat";

put_log($g_log, "..............................................", false);
put_log($g_log, "Capture WIP Data.", false);
put_log($g_log, "..............................................", false);

# Check report is exists
if(file_exists($w_file_name)){
        //unlink($w_file_name);
	$newfile_name=$w_file_name.$w_date;
	rename($w_file_name,$newfile_name);
}

# Connect
$conn = oci_connect("gprism", "gprism", "GPRISM");
if (!$conn) {
        put_log($g_log, "Error in Database Connection.");
}else{
        put_log($g_log, "Successfully Connected to Database.", true);
}

$w_rtn = generate_report();
if($w_rtn != 0){
        put_log($g_log, "Error in Generating Report.", true);
}else{
        put_log($g_log, "Successfully Generated the Report.", true);
}

#Close Connection
oci_close($conn);

exit;

function put_log($w_file, $w_msg, $w_date = true)
{
        $fp = fopen($w_file, 'a');
        if($w_date == true){
               // $w_msg = date('Y-m-d H:i:s') ."\t". $w_msg;
        }
	//fwrite($fp, $w_msg."\n");

	if(is_array($w_msg)) {
                fwrite($fp, print_r($w_msg, TRUE));
	}
	else {
		fwrite($fp, $w_msg."\n");
	}

        fclose($fp);
}


function generate_report(){
	global $g_log;
	global $gw_str_dts;
	global $gw_end_dts;
	global $gw_dvsn_cd;
	global $gw_rdg_cd;
	global $gw_max_stp_cd;
	global $gw_min_stp_cd;
	global $generate_date;
	global $config;
	global $w_file_name;
	


        # ------------------------------------------------------
        #   Getting the WIP Data
        # ------------------------------------------------------	
	$w_wip_data = array();
	get_wip($w_wip_data, $generate_date);

	# ------------------------------------------------------
	#   Finalize CSV header
	# ------------------------------------------------------

	# -- CSV Header
	$w_header .= "PRD_CAT,";
	$w_header .= "PRD_NM,";
	$w_header .= "LOT_ID,";
	$w_header .= "STP_CD,";
	$w_header .= "CHP_QTY,";
	$w_header .= "LOT_ST_DVS,";
	$w_header .= "CRT_DTS,";
	
        # ------------------------------------------------------
        #   Creation and downloading of report
        # ------------------------------------------------------
	put_csv($w_header, $w_wip_data);
	
	put_log($g_log, $w_wip_data);

	
	

	//$remote_upload=exec("send-ftp-Gprism-wip-onesap.csh");
	//if($remote_upload) {
	//echo "Success";
	//}


	# -- Exit after downloading of report
	exit;

}

// =================================================
// ----- Get Start Step and End Step
// =================================================
function get_wip( &$r_wip_data) {

        # -- Initialize variables for query
        global $g_log;
        global $conn;
        global $generate_date;


	$r_max_stp_cd = "";
	$r_min_stp_cd = "";

        put_log($g_log, "Getting WIP Data.");

        
    $w_sql = "
	select distinct 
	nm2.nm_fll nm_fll,
	pm.prd_nm prd_nm, 
	lbs.lot_id lot_id, 
	lbs.stp_cd stp_cd,
	lbs.chp_qty chp_qty,
	lbs.lot_st_dvs lot_st_dvs,
	case when 
		(select count(*) from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'PDCR') <> 0 and
		(select count(*) from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'IOCR') = 0
	then
		(select to_char(ll.crt_dts, 'yyyy-mm-dd hh24:mi:ss') from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'PDCR')
	end as pdcr,
	case when 
		(select count(*) from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'PDCR') = 0 and
		(select count(*) from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'IOCR') <> 0
	then
		(select to_char(ll.crt_dts, 'yyyy-mm-dd hh24:mi:ss') from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'IOCR')
	end as iocr,
	case when
		(select count(*) from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'PDCR') <> 0 and
		(select count(*) from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'IOCR') <> 0
	then
		case when 
			(select count(*) from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'PDCR') < (select count(*) from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'IOCR')
		then 
			(select to_char(ll.crt_dts, 'yyyy-mm-dd hh24:mi:ss') from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'PDCR')
		else
			(select to_char(ll.crt_dts, 'yyyy-mm-dd hh24:mi:ss') from lot_log ll where ll.lot_id = lbs.lot_id_str and ll.verb = 'IOCR')
		end
	end as compare_pdcr_iocr
	from lot_bas_tbl lbs	
	join prd_org_mst pom on pom.del_flg='0' and pom.rt_cd=lbs.rt_cd																
	join prd_mst pm on pm.del_flg='0' and pm.prd_cd=pom.prd_cd_fin									
	join nm_mst nm on nm.del_flg='0' and nm.cd=pm.pkg_cd									
	join nm_mst nm2 on nm2.del_flg='0' and nm2.cd=nm.rdg_cd									
	where lbs.lot_st_dvs not in ('PD','CL') and lbs.chp_qty!='0' and lbs.lot_id not in (select lot_id from lot_inf_tbl  where ctg_cd='CT00S0000277')
        ";


       

        // execute the query
        $w_stmt = oci_parse($conn, $w_sql);
        if (!$w_stmt) {
                put_log($g_log, "Error in SQL Parsing(LOT_BAS_TBL).", true);
                return 4000;
        }

        $r = oci_execute($w_stmt);
        if(!$r){
                put_log($g_log, "Error in Execution(LOT_BAS_TBL).", true);
                return 4000;
        }

      
		
		
	
        # -- Fetch results and put into an array
        while($w_row = oci_fetch_assoc($w_stmt)){
		
		$w_tmp_data_row = "";
		$w_tmp_data_row = trim($w_row['NM_FLL']) .",";
		$w_tmp_data_row .= trim($w_row['PRD_NM']) .",";
		$w_tmp_data_row .= trim($w_row['LOT_ID']) .",";
		$w_tmp_data_row .= trim($w_row['STP_CD']) .",";
		$w_tmp_data_row .= trim($w_row['CHP_QTY']) .",";
		$w_tmp_data_row .= trim($w_row['LOT_ST_DVS']) .",";
		if(strlen($w_row['PDCR']) > 0){
			$w_tmp_data_row .= date('Y-m-d H:i:s',strtotime($w_row['PDCR']));
		}
		if(strlen($w_row['IOCR']) > 0){
			$w_tmp_data_row .= date('Y-m-d H:i:s',strtotime($w_row['IOCR']));
		}
		if(strlen($w_row['COMPARE_PDCR_IOCR']) > 0){
			$w_tmp_data_row .= date('Y-m-d H:i:s',strtotime($w_row['COMPARE_PDCR_IOCR']));
		}
		
		$r_wip_data[] = $w_tmp_data_row;

        }

       

        $num = count($r_wip_data);

		if($num=='0') {

			echo "No records found";
			exit();
		}

        # -- Release resources for the sql query
        OCIFreeStatement($w_stmt);

        return 0;
}

// =================================================
// -----  Create CSV file with report information
// =================================================
function put_csv($w_header, $w_arr_csv) {

	# -- Get language info
	global $g_log;
	global $w_file_name;

	put_log($g_log, "Writing Report.");
	
	# -- Initialize sheet and fle name
	$w_csv_name = $w_file_name;
		
	put_log($w_csv_name, $w_header, false);

	for ($i=0; $i<count($w_arr_csv); $i++) {
		put_log($w_csv_name, $w_arr_csv[$i], false);
	}
}

