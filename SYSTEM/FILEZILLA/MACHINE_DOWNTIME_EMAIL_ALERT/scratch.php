<?php

require '/usr/local/httpd2220/htdocs/Test/Monitoring/PHPMailerAutoload.php';
require '/usr/local/httpd2220/htdocs/Test/Monitoring/utils.php';

#$conn = oci_connect("gprism", "gprism", "gprism2");
#$conn = oci_connect("gprismd", "gprismd", "gprism");
$conn = oci_connect("gprism", "gprism", "gprism2");

// init the date time
$date = date('Y-m-d');

$w_rtn = check_data( $machine_dwn, $grps );
$groups = array_unique( $grps );

// if have machine down 
if( sizeof( $machine_dwn ) > 0 ) {
	// loop the groups (2hr, 4hr)
	foreach ( $groups as $ides ) {
		// loop the machine down data
		foreach ( $machine_dwn as $id => $val ) {
			// loop the machine downtime value
			foreach ( $val as $ids => $vals ) {
				// if id match the groups, create email to sends array
				if( $ides == $ids ) { 
					$emails_to_send[$ides][]= $vals;
				}
			}
		}
    }
}

// send email for each fomrat
foreach( $groups as $vales )
{
	$body_data = '';

	$emails_to_send_email = $emails_to_send[$vales][0]['USR_EMAILS'];
	$time_details =  $emails_to_send[$vales][0]['HOURS_FORMAT'];
	$hours_details = explode( "_", $time_details );
	$time_detail = substr( $hours_details[1], 0, 2 );	
	$time_deta = preg_replace( "/[^\d]/", "", $time_detail );

	$header = array(
	' EQU_CODE ',' EQU_NAME ', ' DOWNTIME ', ' PRN_USER_NAME ', ' ENGR_ACCEPT_TIME ', ' ENGR_USER_NAME ', ' ENGR_DELAY ', ' DOWN_REASON'
		);

	$body_data = "Below machines are down for more than ". $time_deta ." hrs.";

	$body_data .= "<TABLE border='1' cellpadding='4' BORDERCOLOR='#A1A1A1' style=\"border-collapse:'collapse';\"> <TR> ";
	foreach($header as $head){
		$body_data .= "<TH style=\"background-color:'#dedede'\">". $head ."</TH>";
	}
	$body_data .= "</TR>";
	
	$h_format = '';
	$emails_to = array();
	foreach($emails_to_send[$vales] as $id=>$row) {
		$body_data .= "<TR> ";
		$body_data .= "<td>". $row['EQU_CODE'] ."</td>";					
		$body_data .= "<td>". $row['EQU_NAME'] ."</td>";
		$body_data .= "<td>". $row['DOWNTIME'] ."</td>";					
		$body_data .= "<td>". $row['PRN_USER_NAME'] ."</td>";
		$body_data .= "<td>". $row['ENGR_ACCEPT_TIME'] ."</td>";
		$body_data .= "<td>". $row['ENGR_USER_NAME'] ."</td>";
		$body_data .= "<td>". $row['ENGR_DELAY'] ."</td>";
		$body_data .= "<td>". $row['DOWN_REASON'] ."</td>";
		$h_format = $row['HOURS_FORMAT'];
		$emails_to = array_merge( $emails_to, $row['USR_EMAILS'] );
		$body_data .= "</TR>";
	}

	$seq_no = 1;				
	$body_data .= "</TABLE>";		

	$w_subject = "Machine Downtime Alert Monitoring[$hours_details[0]] - ". $hours_details[1];
    $w_msg = $body_data;
    $w_tmp_email = array_unique( $emails_to );

    $w_tmp_email = get_user_emails( $w_tmp_email );

    $w_tmp_email[] = "usg2_gprism@utacgroup.com";

    if ( !empty( $w_tmp_email )) {
	    //utils_email_send($w_tmp_email, $w_subject, $w_msg);	    
	    echo $w_subject. "<br/>";
	    echo $w_msg . "<br/>";
	    var_dump( $w_tmp_email );
	    echo "<hr>";
	}
}

echo "The End";
exit();

// check not received data based on ridge and send email to supervisors
// this function will return the email address of supervisor according to ridge
function get_mail_addresses($mch_dn, &$mail_addresses){

        global $emails;

        $mail_addresses = null;

        $mail_addresses = $emails[$mch_dn];

        return 0;
};

// this function will return the not received data based on ridge
function check_data(&$array_mch_down, &$all_grp)
{
	global $date;
	global $conn;
    global $start_datetime;

	$current_datetime =  date('Y-m-d H:i:s');

	$sql = "
		select distinct
			em.dvsn_cd,
			em.rdg_cd,
			em.equ_cd, 
			em.equ_nm_fll, 
			est.equ_st_dvs,
			to_char( log.down_date, 'YYYY-MM-DD HH24:MI:SS'), 
			usr.usr_nm_dbc, 
			to_char( est_eng.crt_dts, 'YYYY-MM-DD HH24:MI:SS'), 
			usr_eng.usr_nm_dbc eng_usr_nm,
			nm.nm_fll,
			esl.cmt,
			equ_trb.nm_fll equ_trb_nm,
			equ_trb.nm_g_sht
		from 
			equ_mst em
		join equ_st_tbl est 
			on est.equ_cd = em.equ_cd and est.equ_st_dvs != 'RY'
		join (select equ_cd, max(crt_dts) down_date from equ_st_log where del_flg = '0' and verb = 'EQDN' group by equ_cd ) log 
			on log.equ_cd = em.equ_cd
		join equ_st_log esl
			on esl.equ_cd = em.equ_cd and esl.crt_dts = log.down_date
		join usr_mst usr
			on usr.del_flg = '0' and usr.usr_id = esl.usr_id_crt
		left join equ_st_log est_eng
			on est_eng.del_flg = '0' and est_eng.crt_dts > log.down_date and est_eng.equ_st_dvs_to = 'EN' and est_eng.equ_cd = em.equ_cd
		left join usr_mst usr_eng
			on usr_eng.del_flg = '0' and usr_eng.usr_id = est_eng.usr_id_crt
		join nm_mst nm 
			on nm.del_flg='0' and nm.cd = em.rdg_cd
		join nm_mst equ_trb
			on equ_trb.del_flg='0' and equ_trb.cd = esl.equ_trb_id
		where 
			em.del_flg= '0'
		order by dvsn_cd, rdg_cd
	";

	// execute the query
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);
    $data = array();
    $w_cnt = 0;

	$w_date1 = strtotime($current_datetime);
	// get data row by row

	$j='0';
	while ($row = oci_fetch_row($stmt)) {
		
		// get the current time
		unset($array_mch_dwn);

		$w_subTime = '';
		$w_d = '';
		$w_h = '';
		$w_m  = '';
		$total_hours= '';
	
		$j++;

		if($row[4]=='EN')
		{
			$w_subTime = strtotime($row[7]) - strtotime($row[5]);

	 		$w_d = ($w_subTime/(60*60*24))%365;
	 	    $w_h = ($w_subTime/(60*60))%24;
	 	    $w_m = ($w_subTime/60)%60;
		}
		else {

			$w_subTime  = strtotime($current_datetime) - strtotime($row[5]);
			$w_d = ($w_subTime/(60*60*24))%365;
	        $w_h = ($w_subTime/(60*60))%24;
			$w_m = ($w_subTime/60)%60;
		}

		$total_hours = round(($w_d*24) + ($w_h) + ($w_m/60));

		//echo "****".$total_hours;	

		if($total_hours > '0' ) {
			#get the format of the hours
			if($total_hours >=2 && $total_hours<4){ 
				$hours_format=trim($row[9])."_2HR";
				//$hours_format_group = array(trim($row[9])."_2HR");
			}else if(($total_hours >=2 && $total_hours<6) || ($total_hours >=4 && $total_hours<6)) {
				$hours_format=trim($row[9])."_4HR";
				//$hours_format_group = array(trim($row[9])."_2HR",trim($row[9])."_4HR");
			}else if($total_hours >=6 && $total_hours<8) {
				$hours_format=trim($row[9])."_6HR";
				//$hours_format_group = array(trim($row[9])."_2HR",trim($row[9])."_4HR",trim($row[9])."_6HR");
			}else if($total_hours >=8 && $total_hours<24) {
				$hours_format=trim($row[9])."_8HR";
				//$hours_format_group = array(trim($row[9])."_2HR",trim($row[9])."_4HR",trim($row[9])."_6HR",trim($row[9])."_8HR");
			}else if($total_hours >=24) {	
				$hours_format=trim($row[9])."_24HR";
				//$hours_format_group = array(trim($row[9])."_2HR",trim($row[9])."_4HR",trim($row[9])."_6HR",trim($row[9])."_8HR",trim($row[9])."_24HR");
			}

			//echo $hours_format;
			//echo "<br/>";

			$group = trim( $row[12] );

			$w_rtn=group_emails($group, $hours_format, &$w_emails);
			if($w_rtn !='0' ) {
				echo " there is  a error getting the group emails";
			}

			// foreach($hours_format_group as $val){
			// 	$array_mch_dwn[$hours_format]['EQU_CODE'] = $row[2]; 
			// 	$array_mch_dwn[$hours_format]['EQU_NAME'] = $row[3]; 
			// 	$array_mch_dwn[$hours_format]['DOWNTIME'] =  $row[5]; 
			// 	$array_mch_dwn[$hours_format]['PRN_USER_NAME'] =  $row[6];
			// 	$array_mch_dwn[$hours_format]['ENGR_ACCEPT_TIME'] =  $row[7];
			// 	$array_mch_dwn[$hours_format]['ENGR_USER_NAME'] = $row[8];
			// 	$array_mch_dwn[$hours_format]['ENGR_DELAY'] = $total_hours; 
			// 	$array_mch_dwn[$hours_format]['DOWN_REASON'] = $row[10] ;
			// 	$array_mch_dwn[$hours_format]['USR_EMAILS'] =$w_emails;
			// 	$array_mch_dwn[$hours_format]['HOURS_FORMAT'] =$hours_format;
			// }

			$array_mch_dwn[$hours_format]['EQU_CODE'] = $row[2]; 
			$array_mch_dwn[$hours_format]['EQU_NAME'] = $row[3]; 
			$array_mch_dwn[$hours_format]['DOWNTIME'] =  $row[5]; 
			$array_mch_dwn[$hours_format]['PRN_USER_NAME'] =  $row[6];
			$array_mch_dwn[$hours_format]['ENGR_ACCEPT_TIME'] =  $row[7];
			$array_mch_dwn[$hours_format]['ENGR_USER_NAME'] = $row[8];
			$array_mch_dwn[$hours_format]['ENGR_DELAY'] = $total_hours; 
			$array_mch_dwn[$hours_format]['DOWN_REASON'] = $row[10] ;
			$array_mch_dwn[$hours_format]['USR_EMAILS'] =$w_emails;
			$array_mch_dwn[$hours_format]['HOURS_FORMAT'] =$hours_format;

			// echo $array_mch_dwn[$hours_format]['EQU_CODE'] = $row[2]; echo "</br>";
			// echo $array_mch_dwn[$hours_format]['EQU_NAME'] = $row[3];  echo "</br>";
			// echo $array_mch_dwn[$hours_format]['DOWNTIME'] =  $row[5];  echo "</br>";
			// echo $array_mch_dwn[$hours_format]['PRN_USER_NAME'] =  $row[6]; echo "</br>";
			// echo $array_mch_dwn[$hours_format]['ENGR_ACCEPT_TIME'] =  $row[7]; echo "</br>";
			// echo $array_mch_dwn[$hours_format]['ENGR_USER_NAME'] = $row[8]; echo "</br>";
			// echo $array_mch_dwn[$hours_format]['ENGR_DELAY'] = $total_hours;  echo "</br>";
			// echo $array_mch_dwn[$hours_format]['DOWN_REASON'] = $row[10] ; echo "</br>";
			// echo $array_mch_dwn[$hours_format]['USR_EMAILS'] =$w_emails; echo "</br>";
			// echo $array_mch_dwn[$hours_format]['HOURS_FORMAT'] =$hours_format; echo "</br>";
			// echo "***********************";
			// echo "</br>";

			$array_mch_down[] = $array_mch_dwn;
			$all_grp[] = $hours_format;
		}
	}

	// close ref
    OCIFreeStatement($stmt);
    oci_close($conn);
}

// Get emails based on group
function group_emails($group, $grp, &$r_emails) { 
	global $conn;

	$r_emails= array();

    $sql = "
		select distinct 
            pm.par_txt
        from 
            par_mst pm
        join 
            usr_grp_mst uom
            on uom.del_flg=0 
            and pm.par_txt='610' || substr(trim(uom.usr_id), 7, 11) 
        join
            nm_mst grp_nm
            on grp_nm.del_flg=0
            and grp_nm.cd = uom.usr_grp_cd
            and grp_nm.nm_g_sht = '{$group}'
        where 
            pm.del_flg='0'
            and pm.par_cls_cd ='P000S016' 
            and pm.par_id = '{$grp}'
    ";

    // execute the query
    $stmt = oci_parse($conn, $sql);
    oci_execute($stmt);

    // get data row by row
    while ($row = oci_fetch_row($stmt)) {
		$r_emails[]=$row[0];
	}

	OCIFreeStatement($stmt);
    oci_close($conn);

	return 0;
}

// get emails from HR data
function get_user_emails( $user_ids )
{
        $conn = oci_connect(
                "mfgsupport",
                "mfgsupport",
                "//10.81.162.223:1521/mfgsupport.sg.utacgroup.com"
        ) or die("Support Database Error occured");

        $emp_data = array();

        $user_id_whr = implode( "','", $user_ids );

        $sql = "
		select email1
                from hr_sieben_data
                where email1 is not null
                and (emp_no in ('{$user_id_whr}') or
                '610' || substr(trim(emp_no),2,6) in ('{$user_id_whr}'))
        ";
        $stmt = oci_parse($conn, $sql);
        oci_execute($stmt);

        $emp_data = array();
        while ($row = oci_fetch_array($stmt)) {
                $emp_data[] = trim( $row['EMAIL1'] );
        }

        OCIFreeStatement($stmt);
        oci_close($conn);

        return $emp_data;
}
?>
