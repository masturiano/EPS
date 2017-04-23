<?php
class IntonesapwipReaders extends CI_Controller
{


    function __construct()
    {
        parent::__construct();
        $this->load->model('interface/intonesapwipreader');
        $this->load->config('interface_config');

    }


    public function index()
    {
		
		        
         $w_date = date('Ymd_His');

$this->g_log = "/tmp/capture_wip_onesap_data_".$w_date.".log";
$this->w_file_name_gprism = "/opt/htdocs/interfaces/receive/wip_onesap/gprism_onesap_wip.dat";

$this->w_file_name_as400 = "/opt/htdocs/interfaces/receive/wip_onesap/as400_onesap_wip.txt";

$this->w_file_name='/opt/htdocs/interfaces/send/wip_onesap/usg2_onesap_wip_withNA.csv';
$this->w_file_name_no_na='/opt/htdocs/interfaces/send/wip_onesap/usg2_onesap_wip.csv'; # File for content without #N/A

# Check report is exists

//$w_rtn = $this->generate_report($this->w_file_name_gprism, $this->w_file_name_as400, $this->w_file_name, $this->g_log);
$w_rtn = $this->generate_report($this->w_file_name_gprism, $this->w_file_name_as400, $this->w_file_name, $this->w_file_name_no_na, $this->g_log);
if($w_rtn != 0){
        $this->put_log($g_log, "Error in Generating Report.", true);
}else{
        $this->put_log($g_log, "Successfully Generated the Report.", true);
}

exit();
        
}


    


    function put_log($w_file, $w_msg, $w_date = true)
{
        $fp = fopen($w_file, 'a');
        if($w_date == true){
               // $w_msg = date('Y-m-d H:i:s') ."\t". $w_msg;
        }
    

    if(is_array($w_msg)) {
         $w_tmp_data_row = "SG21,";


       
        $w_tmp_data_row .= $w_msg['prd_nm'].",";
        $w_tmp_data_row .= $w_msg['service_types'] .",";
        $w_tmp_data_row .= $w_msg['flow_types'] .",";
        $w_tmp_data_row .= $w_msg['stp_cd'] .",";
        $w_tmp_data_row .= $w_msg['lot_onesap'].",";
        $w_tmp_data_row .= $w_msg['before_qty'].",";
        $w_tmp_data_row .= $w_msg['after_qty'].",";



                
    }
    else {
        fwrite($fp, $w_msg."\n");
    }

        fclose($fp);
}

function save_file($w_file, $w_msg, $w_date = true){

        $fp = fopen($w_file, 'a');
        if($w_date == true){
               // $w_msg = date('Y-m-d H:i:s') ."\t". $w_msg;
        }
    
        if(is_array($w_msg)){
                $w_tmp_data_row = "SG21,";                                              # PLANT
                $w_tmp_data_row .= $w_msg['qbs_nos'].",";                                # PART_NAME
                $w_tmp_data_row .= $w_msg['service_types'] .",";                        # SERVICE_TYPE
                $w_tmp_data_row .= $w_msg['flow_types'] .",";                           # FLOW_TYPE
                $w_tmp_data_row .= $w_msg['stp_cd'] .",";                               # STP_CD
                $w_tmp_data_row .= $w_msg['lot_onesap'].",";                            # ONESAP_LOT
                $w_tmp_data_row .= $w_msg['before_qty'].",";                            # PROCESS_BEFORE_QTY
                $w_tmp_data_row .= $w_msg['after_qty'].",";                             # PROCESS_AFTER_QTY
                $w_tmp_data_row .= $w_msg['lot_st_dvs'].",";                            # STATUS
                $creation_date = date('m/d/Y',strtotime($w_msg['crt_dts']));
                if($creation_date != "1970/01/01" && $creation_date != "1999/11/30" && $creation_date != ""){
                        $w_tmp_data_row .= date('m/d/Y',strtotime($w_msg['crt_dts'])).",";      # LOT_CREATE_DATE
                }
                else{
                        $w_tmp_data_row .= "--"; 
                }
                fwrite($fp, $w_tmp_data_row."\n"); 

                # Old data
                // $w_tmp_data_row = "SG21,";
                // $w_tmp_data_row .= $w_msg['qbs_nos'].",";
                // $w_tmp_data_row .= $w_msg['service_types'] .",";
                // $w_tmp_data_row .= $w_msg['flow_types'] .",";
                // $w_tmp_data_row .= $w_msg['stp_cd'] .",";
                // $w_tmp_data_row .= $w_msg['lot_onesap'].",";
                // $w_tmp_data_row .= $w_msg['before_qty'].",";
                // $w_tmp_data_row .= $w_msg['after_qty'].",";
                // fwrite($fp, $w_tmp_data_row."\n");        
        }
        else{
                fwrite($fp, $w_msg."\n");
        }
        fclose($fp);
}

function convert_array_gprism (){
        global $w_file_name_gprism;
        global $result_gpm;
        global $gpm_array;

        if (file_exists($this->w_file_name_gprism) ) {
           
                $lines = file($this->w_file_name_gprism);   

                foreach ($lines as $line_num => $line) {
                //$a_line = preg_split('/\t/', $line);

                $a_line = explode(',', trim($line));
                //var_dump($a_line);
               
                        if($line_num!='0'){
                                // $gpm_array['prd_cat']=$a_line[0];
                                $gpm_array['prd_nm']=$a_line[1];
                                $gpm_array['lot_id']=$a_line[2];
                                $gpm_array['stp_cd']=$a_line[3];
                                $gpm_array['chp_qty']=$a_line[4];
                                $gpm_array['lot_st_dvs']=$a_line[5];
                                $gpm_array['crt_dts']=$a_line[6];

                                $result_gpm[]=$gpm_array;
                        }
                        //if(count($a_line) <> 6 && $line_num!='0' ){ // Old condition
                        if(count($a_line) <> 7 && $line_num!='0' ){
                                $AS_process = 0;
                                break;
                        }       
                        $line_by_line_AS[] = $line;
                }
        } 
        return $result_gpm;
}





function convert_array_as400 (){
global $w_file_name_as400;
global $result_gpm;
 if (file_exists($this->w_file_name_as400) ) {
            /*
            check the file content
            
                check if the tabs are equal by columns from DB

                {if correct record in log
                else Error in log}

            */
            $lines = file($this->w_file_name_as400);    
            foreach ($lines as $line_num => $line) {
                $a_line = preg_split('/\t/', $line);

               
                //$a_line = explode(',', trim($line));
                //var_dump($a_line);
               // if($line_num!='0') {
               //$gpm_array['prd_cat']=$a_line[0];
                $gpm_array['prd_nm']=$a_line[0];
                $gpm_array['lot_id']=$a_line[2];
                $gpm_array['stp_cd']=$a_line[1];
                $gpm_array['chp_qty']=$a_line[3];
                $gpm_array['lot_st_dvs']=trim($a_line[4]);
                $gpm_array['crt_dts']=$a_line[5];


                $result_as400[]=$gpm_array;
           // }




                
                /*if ( count($a_line) <> 5  ){
                     $AS_process = 0;
                     break;
                }    */        
                $line_by_line_AS[] = $line;
            }
             
            
        }


return $result_as400;
}

function combine_arrays($gpm_wip, $as400_wip) {

    foreach($as400_wip as $id=>$val) {
        

       $gpm_wip[]=   $val;

    }

    return($gpm_wip); 

}

function split_qty_before_after ($wip_data) {


   

    foreach($wip_data as $ids=>$values) {
         

            if($values['lot_st_dvs']=='OW' or $values['lot_st_dvs']=='EW' or $values['lot_st_dvs']=='AFTER'){

             $wip_data[$ids]['before_qty'] = '0';
             $wip_data[$ids]['after_qty'] = $values['chp_qty'];


            }

             else {
                    $wip_data[$ids]['before_qty'] = $values['chp_qty'];
                    $wip_data[$ids]['after_qty'] = '0';
                 }
     }


     return $wip_data;



   

 
}

public function generate_report($w_file_name_gprism, $w_file_name_as400, $w_file_name, $w_file_name_no_na, $g_log){
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
    global $w_file_name_no_na;
    global $w_onesap_data;
    global $w_onesap_data2;
    global $w_onesap_data3;
    global $gpm_wip;
    global $w_file_name_gprism;
    global $w_header;
    global $split_wip_quantity;

    
    if(file_exists($this->w_file_name_gprism)){
        //unlink($w_file_name);
    
    $gpm_wip=$this->convert_array_gprism();


    $as400_wip=$this->convert_array_as400();




    $gpm_wip= $this->combine_arrays($gpm_wip, $as400_wip);


    $split_wip_quantity=$this->split_qty_before_after($gpm_wip);



   
    #$newfile_name=$w_file_name.$w_date;
    #rename($w_file_name,$newfile_name);
}







        # ------------------------------------------------------
        #   Getting the WIP Data
        # ------------------------------------------------------    
    $w_wip_data = array();


        # ------------------------------------------------------
        #   Getting the IMES Data
        # ------------------------------------------------------
    
         $w_onesap_data = $this->intonesapwipreader->get_qbs_nos();

         $w_onesap_data2 = $this->intonesapwipreader->get_onesap_flow();

         $w_onesap_data3 = $this->intonesapwipreader->get_onesap_lots();


   

    $qbs_nos=$this->vlookup_qbsnos($split_wip_quantity, $w_onesap_data);
	
    $onesap_lotid=$this->vlookup_onesap($qbs_nos, $w_onesap_data3);
    $flow_type=$this->vlookup_flowtype($onesap_lotid, $w_onesap_data2);
	
    $service_type=$this->vlookup_servicetype($flow_type, $w_onesap_data);



    # ------------------------------------------------------
    #   Finalize CSV header
    # ------------------------------------------------------

    # -- CSV Header

    # Old Header
    // $w_header .= "PRD_CAT,";
    // $w_header .= "PRD_NM,";
    // $w_header .= "LOT_ID,";
    // $w_header .= "STP_CD,";
    // $w_header .= "CHP_QTY,";
    // $w_header .= "LOT_ST_DVS,";

    # New Header
    $w_header .= "PLANT,"; // SG21
    $w_header .= "PART_NAME,"; // PRD_NM
    $w_header .= "SERVICE_TYPE,"; // ==> SERVICE TYPES
    $w_header .= "FLOW_TYPE,"; // ==> FLOW TYPES
    $w_header .= "STP_CD,"; // STP_CD
    $w_header .= "ONESAP_LOT,"; // ==> LOT ID
    $w_header .= "PROCESS_BEFORE_QTY,";
    $w_header .= "PROCESS_AFTER_QTY,";
    $w_header .= "STATUS,"; // LOT_ST_DVS
    $w_header .= "LOT_CREATE_DATE,"; // CRT_DTS
    
        # ------------------------------------------------------
        #   Creation and downloading of report
        # ------------------------------------------------------

    $this->put_csv($w_header, $service_type, $this->w_file_name);
    $this->put_csv_no_na($w_header, $service_type, $this->w_file_name_no_na); # Function without #N/A content

    $this->put_log($this->g_log, $w_onesap_data);

    exit();
    

    # -- Exit after downloading of report
    

}

// =================================================
// ----- Get Start Step and End Step
// =================================================

function change_service_type($wip) {
    global $mismatch_arr;

    foreach($wip as $id=>$val)
    {

        if($wip[$id]['flow_types']!='E' && $wip[$id]['service_types']!='F' && $wip[$id]['flow_types']!='#N/A' && $wip[$id]['service_types']!='#N/A' ) {

            if($wip[$id]['flow_types']!=$wip[$id]['service_types']) {

                $mismatch_arr[]=$wip[$id];

            }

        }

    }

    return $mismatch_arr;

}


function vlookup_qbsnos($arr, $lookup_arr){
$set='0';
foreach($arr as $id=>$val){
$return_val=  $this->intonesapwipreader->get_qbs_nos($val['prd_nm']);
if($return_val[0]['SEMIFG_QBS_NO'] !='')
{
$arr[$id]['qbs_nos']= $return_val[0]['SEMIFG_QBS_NO'];
}
else {
$arr[$id]['qbs_nos']= '#N/A';
} 

/*    foreach($lookup_arr as $ids=>$vals) {
        if($val['prd_nm']==$vals['PART_ID']) {
           
             $arr[$id]['qbs_nos'] = $vals['SEMIFG_QBS_NO'];
            $set='2';
            break;
        }
        
                            }

        if($set!='2') {
       
        $arr[$id]['qbs_nos'] = '#N/A';
        }
        $set='0';

*/
                        }
return $arr;

}

// =================================================
// -----  Create CSV file with report information
// =================================================
function put_csv($w_header, $service_type, $w_file_name) {

        # -- Get language info
        global $g_log;
        global $w_file_name;

        //put_log($g_log, "Writing Report.");
    
        # -- Initialize sheet and fle name
        $w_csv_name = $this->w_file_name;
        $w_gprism= $this->w_file_name_gprism;
        $w_as400= $this->w_file_name_as400;

        if(file_exists($w_csv_name)){
                //unlink($w_file_name);
                $w_date = date('Ymd_His');
                $newfile_name=$w_csv_name.$w_date;
                rename($w_csv_name,$newfile_name);
        }

        $this->save_file($this->w_file_name, $w_header, false);

        $filter_flowtype_e = $this->filter_flowtype_e($service_type);
        $filter_servicetype_f = $this->filter_servicetype_f($filter_flowtype_e);
        $check_flow_service = $this->check_flow_service($filter_servicetype_f);
        $unfilter_flowtype_e = $this->unfilter_flowtype_e($service_type,$check_flow_service);
        $unfilter_servicetype_f = $this->unfilter_servicetype_f($service_type,$unfilter_flowtype_e);
        $correct_qbs = $this->correct_qbs($unfilter_servicetype_f);

        // print "<pre>";
        // print_r($check_flow_service);
        // print "</pre>";
        // exit;

        // for ($i=0; $i<count($service_type); $i++) {
        //         $this->save_file($this->w_file_name, $service_type[$i], false);
        // }
        for ($i=0; $i<count($correct_qbs); $i++) {
                $this->save_file($this->w_file_name, $correct_qbs[$i], false);
        }
        $w_date = date('Ymd_His');
        if(file_exists($w_gprism)){
                //unlink($w_file_name);
        	$newfile_name1=$w_gprism.$w_date;
        	rename($w_gprism,$newfile_name1);
        }
        if(file_exists($w_as400)){
                //unlink($w_file_name);
                $newfile_name2=$w_as400.$w_date;
                rename($w_as400,$newfile_name2);
        }

        //exit();
}

/**
* Create csv file without #N/A content
*
* @param string $w_header
* @param array $service_type
* @param string $w_file_name_no_na
* @author Mydel 
* @return write data to csv file without #N/A
*/
function put_csv_no_na($w_header, $service_type, $w_file_name_no_na) {

        # -- Get language info
        global $g_log;
        global $w_file_name_no_na;

        //put_log($g_log, "Writing Report.");
    
        # -- Initialize sheet and fle name
        $w_csv_name = $this->w_file_name_no_na;
        $w_gprism= $this->w_file_name_gprism;
        $w_as400= $this->w_file_name_as400;

        if(file_exists($w_csv_name)){
                //unlink($w_file_name_no_na);
                $w_date = date('Ymd_His');
                $newfile_name=$w_csv_name.$w_date;
                rename($w_csv_name,$newfile_name);
        }

        $this->save_file($this->w_file_name_no_na, $w_header, false);

        $filter_flowtype_e = $this->filter_flowtype_e($service_type);
        $filter_servicetype_f = $this->filter_servicetype_f($filter_flowtype_e);
        $check_flow_service = $this->check_flow_service($filter_servicetype_f);
        $unfilter_flowtype_e = $this->unfilter_flowtype_e($service_type,$check_flow_service);
        $unfilter_servicetype_f = $this->unfilter_servicetype_f($service_type,$unfilter_flowtype_e);
        $correct_qbs = $this->correct_qbs($unfilter_servicetype_f);
        $remove_na = $this->remove_na($correct_qbs);

        // print "<pre>";
        // print_r($remove_na);
        // print "</pre>";
        // exit;

        // for ($i=0; $i<count($service_type); $i++) {
        //         $this->save_file($this->w_file_name_no_na, $service_type[$i], false);
        // }
        for ($i=0; $i<count($remove_na); $i++) {
                $this->save_file($this->w_file_name_no_na, $remove_na[$i], false);
        }
        $w_date = date('Ymd_His');
        if(file_exists($w_gprism)){
                //unlink($w_file_name_no_na);
                $newfile_name1=$w_gprism.$w_date;
                rename($w_gprism,$newfile_name1);
        }
        if(file_exists($w_as400)){
                //unlink($w_file_name_no_na);
                $newfile_name2=$w_as400.$w_date;
                rename($w_as400,$newfile_name2);
        }

        exit();
}

/**
* Filter out E from flow type
*
* @param array $array
* @author Mydel
* @return array 
*/
function filter_flowtype_e($array){
        for ($i=0; $i<count($array); $i++) {
                if($array[$i]['flow_types'] != "E"){
                        $no_flowtype_e[] = $array[$i];
                }
        }
        return $no_flowtype_e;
}

/**
* Filter out F from service type
*
* @param array $array
* @author Mydel
* @return array 
*/
function filter_servicetype_f($array){
        for ($i=0; $i<count($array); $i++) {
                if($array[$i]['service_types'] != "F"){
                        $no_servicetype_f[] = $array[$i];
                }
        }
        return $no_servicetype_f;
}

/**
* Check if flow type and service type are same
* Change service type to copy flow type if not the same
*
* @param array $array
* @author Mydel
* @return array 
*/
function check_flow_service($array){
        for ($i=0; $i<count($array); $i++) {
                if(($array[$i]['flow_types'] != $array[$i]['service_types']) && ($array[$i]['flow_types'] != "#N/A" && $array[$i]['service_types'] != "#N/A")){
                        $array[$i]['service_types'] = $array[$i]['flow_types'];
                        $change_service_type_value[] = $array[$i];
                }
                else{
                        $nochange_service_type_value[] = $array[$i];
                }
        }
        $merge_array = array_merge((array)$change_service_type_value,(array)$nochange_service_type_value);
        return $merge_array;
}

/**
* Return the flowtype E into array
*
* @param array $array_1
* @param array $array_2
* @author Mydel
* @return array 
*/
function unfilter_flowtype_e($array_1,$array_2){
        for ($i=0; $i<count($array_1); $i++) {
                if($array_1[$i]['flow_types'] == "E"){
                        $flowtype_e_value[] = $array_1[$i];
                }
        }
        $merge_array = array_merge((array)$flowtype_e_value,(array)$array_2);
        return $merge_array;
}

/**
* Return the flowtype E into array
*
* @param array $array_1
* @param array $array_2
* @author Mydel
* @return array 
*/
function unfilter_servicetype_f($array_1,$array_2){
        for ($i=0; $i<count($array_1); $i++) {
                if($array_1[$i]['service_types'] == "F" && $array_1[$i]['flow_types'] != "E"){
                        $servicetype_f_value[] = $array_1[$i];
                }
        }
        $merge_array = array_merge((array)$servicetype_f_value,(array)$array_2);
        return $merge_array;
}

/**
* Correct the QBS base to the Flowtype letter
*
* @param array $array
* @author Mydel
* @return array 
*/
function correct_qbs($array){
        for ($i=0; $i<count($array); $i++) {
                $qbs_nos = $array[$i]['qbs_nos'];
                $qbs_nos_first_three_string = substr($qbs_nos, 0, 3);
                $qbs_nos_last_string = substr($qbs_nos, -1);
                $qbs_nos_remove_last_string = substr($qbs_nos, 0, -1);
                $flowtypes = $array[$i]['flow_types'];
                if($qbs_nos_first_three_string == "QBS" && $qbs_nos_last_string != $flowtypes){
                    $array[$i]['qbs_nos'] = $qbs_nos_remove_last_string.$flowtypes;
                    $change_qbs_value[] = $array[$i];
                }
                else{
                    $no_change_qbs_value[] = $array[$i];
                }
        }
        $merge_array = array_merge((array)$change_qbs_value,(array)$no_change_qbs_value);
        return $merge_array;
}

/**
* Filter the  all fields to get the fields without N/A fields                                          
* Remove all the #N/A field columns
*
* @param array $array
* @author Mydel
* @return array 
*/
function remove_na($array){
        for ($i=0; $i<count($array); $i++) {
                if(strtoupper($array[$i]['prd_nm']) == "#N/A" || 
                   strtoupper($array[$i]['lot_id']) == "#N/A" || 
                   strtoupper($array[$i]['stp_cd']) == "#N/A" || 
                   strtoupper($array[$i]['chp_qty']) == "#N/A" || 
                   strtoupper($array[$i]['lot_st_dvs']) == "#N/A" ||
                   strtoupper($array[$i]['before_qty']) == "#N/A" ||
                   strtoupper($array[$i]['after_qty']) == "#N/A" ||
                   strtoupper($array[$i]['qbs_nos']) == "#N/A" ||
                   strtoupper($array[$i]['lot_onesap']) == "#N/A" ||
                   strtoupper($array[$i]['flow_types']) == "#N/A" ||
                   strtoupper($array[$i]['service_types']) == "#N/A"
                ){
                        # Exclude data
                }
                else{
                        # Include data
                        $no_na[] = $array[$i];
                }
        }
        return $no_na;
}


function vlookup_onesap($arr, $lookup_arr){


   

$set='0';
foreach($arr as $id=>$val){

   $return_val1=  $this->intonesapwipreader->get_onesap_lots($val['lot_id']);
if($return_val1[0]['LOT_ONESAP'] !='')
{
$arr[$id]['lot_onesap']= $return_val1[0]['LOT_ONESAP'];
}
else {
$arr[$id]['lot_onesap']= '#N/A';
} 
   /* 
    foreach($lookup_arr as $ids=>$vals) {
       
        if($val['lot_id']==$vals['LOT_ID']) {
           
            $arr[$id]['lot_onesap'] = $vals['LOT_ONESAP'];
            $set='2';
            break;
        }
        
                            }

        if($set!='2') {
       $arr[$id]['lot_onesap']='#N/A';
        }
        $set='0';
*/

                        }
return $arr;

}


function vlookup_flowtype($arr, $lookup_arr){
    
$set='0';
foreach($arr as $id=>$val){
 $return_val2=  $this->intonesapwipreader->get_onesap_flow($val['stp_cd']);
if($return_val2[0]['FLOW_TYPE'] !='')
{
$arr[$id]['flow_types']= $return_val2[0]['FLOW_TYPE'];
}
else {
$arr[$id]['flow_types']= '#N/A';
}
  
/* 
    foreach($lookup_arr as $ids=>$vals) {
        if($val['stp_cd']==$vals['STAGE_CODE']) {
           
             $arr[$id]['flow_types'] = $vals['FLOW_TYPE'];
            $set='2';
            break;
        }
        
             }

        if($set!='2') {
        $arr[$id]['flow_types']='#N/A';
        }
        $set='0';
*/

                        }

return $arr;

}


function vlookup_servicetype($arr, $lookup_arr){
    
$set='0';
foreach($arr as $id=>$val){
$return_val3=  $this->intonesapwipreader->get_service_type($val['prd_nm']);
if($return_val3[0]['COST_CODE'] !='')
{
$arr[$id]['service_types']= $return_val3[0]['COST_CODE'];
}
else {
$arr[$id]['service_types']= '#N/A';
}
   /* 
    foreach($lookup_arr as $ids=>$vals) {
        if($val['prd_nm']==$vals['PART_ID']) {
          
            $arr[$id]['service_types'] = $vals['COST_CODE'];
            $set='2';
            break;
        }
        
                            }

        if($set!='2') {
        $arr[$id]['service_types'] ='#N/A';
        }
        $set='0';
*/

                        }


return $arr;

}

}