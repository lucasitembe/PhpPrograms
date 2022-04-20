<?php
include("./includes/header.php");
include("./includes/connection.php");
include("./includes/constants.php");
include("medical_record_encrypt_decrypt.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if(isset($_GET['Registration_ID'])){
   $Registration_ID=$_GET['Registration_ID'];
}else{
   $Registration_ID=""; 
}
if(isset($_GET['consultation_record_id'])){
   $consultation_record_id=$_GET['consultation_record_id'];
}else{
   $consultation_record_id=""; 
}
if(isset($_GET['data_from_online_server'])){
   $data_from_online_server=$_GET['data_from_online_server'];
}else{
   $data_from_online_server=""; 
}

         $select_patien_details = mysqli_query($conn,"
		SELECT pr.Sponsor_ID,Member_Number,Patient_Name, Registration_ID,Gender,Guarantor_Name,Date_Of_Birth
			FROM
				tbl_patient_registration pr,
				tbl_sponsor sp
			WHERE
				pr.Registration_ID = '" . $Registration_ID . "' AND
				sp.Sponsor_ID = pr.Sponsor_ID
				") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_patien_details);
    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_patien_details)) {
            $Member_Number = $row['Member_Number'];
            $Patient_Name = $row['Patient_Name'];
            $Registration_ID = $row['Registration_ID'];
            $Gender = $row['Gender'];
            $Guarantor_Name  = $row['Guarantor_Name'];
            $Sponsor_ID = $row['Sponsor_ID'];
            $DOB = $row['Date_Of_Birth'];
        }
    } else {
        $Guarantor_Name  = '';
        $Member_Number = '';
        $Patient_Name = '';
        $Gender = '';
        $Registration_ID = 0;
    }
    $age = date_diff(date_create($DOB), date_create('today'))->y;
$sql_select_card_no_result=mysqli_query($conn,"SELECT card_no FROM tbl_member_afya_card WHERE Registration_ID='$Registration_ID' LIMIT 1") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_card_no_result)>0){
    $card_no=mysqli_fetch_assoc($sql_select_card_no_result)['card_no'];
}
        ?>
<a href="afya_card_medical_record_details.php?Registration_ID=<?= $Registration_ID ?>" class="art-button-green">BACK</a>
<style>
    .link_text:hover{
        text-decoration: none;
        
    }
    a:hover {
  text-decoration: none!important;
  color: #2996cc;
  font-weight: bold;
}
</style>
<fieldset style="height:500px;overflow-y: auto">
    <legend align="center" style="text-align:center"><b>AFYA CARD MEDICAL RECORD DATA</b>
        <br/>
               <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> | <b>" . $Guarantor_Name  . "</b>"; ?></b></span>
    </legend>
    <table class="table table-bordered" style='background: #FFFFFF;'>
        <tr>
            <th width="50px">S/No.</th>
            <th>HOSPITAL ID</th>
            <th>HOSPITAL NAME</th>
            <th>CARD NO</th>
            <th>CONSULTATION DATE</th>
            
        </tr>
        <tbody>
            <?php 
    if(isset($_GET['data_from_online_server'])){
        //fetch online data
     $received_data_from_online_server= json_decode(retrived_data_from_online_server($card_no,$consultation_record_id));
    $count_sn=1;
        foreach($received_data_from_online_server as $medical_rec_data){
            $consultation_record_id=$medical_rec_data[0];
            $hospital_id=$medical_rec_data[1];
            $complain=$crypt->decrypt($medical_rec_data[2]);
            
            $consultation_date=$medical_rec_data[3];
            $final_diagnosis=$medical_rec_data[4];
            $provisional_diagnosis_arr=$medical_rec_data[5];
            $ordered_item_pr_consultation=$medical_rec_data[6];
            $final_diagnosis_data=explode("<<,>>",$crypt->decrypt($final_diagnosis));
            $provisional_diagnosis=explode("<<,>>",$crypt->decrypt($provisional_diagnosis_arr));

            $Hospital_name="";
            $sql_select_hospital_name_result=mysqli_query($conn,"SELECT Hospital_name FROM tbl_ehms_mr_all_hospital_list_local WHERE ehms_mr_all_hospital_list_online_id='$hospital_id'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_hospital_name_result)>0){
                $Hospital_name=mysqli_fetch_assoc($sql_select_hospital_name_result)['Hospital_name'];
            }
            echo "<tr>
                    <td style='text-align: center'>$count_sn.</td>
                    <td style='text-align: center'>$hospital_id</td>
                    <td style='text-align: center'>$Hospital_name</td>
                    <td style='text-align: center'>$card_no</td>
                    <td style='text-align: center'>$consultation_date</td>
                     <tr><td colspan='2'><b>MAIN COMPLAIN</b></td><td colspan='3'>$complain</td></tr>
                </tr>";
            //select final diagnosis name
            $diagnosis_code_name="";
            foreach($final_diagnosis_data as $disease_code){
                
                $sql_select_final_diagnosis_name_result=mysqli_query($conn,"SELECT disease_name,disease_code FROM tbl_disease WHERE disease_code = '$disease_code'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_final_diagnosis_name_result)>0){
                    $disease_rows=mysqli_fetch_assoc($sql_select_final_diagnosis_name_result);
                    $disease_name=$disease_rows['disease_name'];
                    $disease_code=$disease_rows['disease_code'];
                    $diagnosis_code_name.=$disease_name."(<b> $disease_code </b>) ,&nbsp;&nbsp;&nbsp;";
                }
            }
            //select final diagnosis name
            $diagnosis_code_name2="";
            foreach($provisional_diagnosis as $disease_code){
                
                $sql_select_final_diagnosis_name_result=mysqli_query($conn,"SELECT disease_name,disease_code FROM tbl_disease WHERE disease_code = '$disease_code'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_final_diagnosis_name_result)>0){
                    $disease_rows=mysqli_fetch_assoc($sql_select_final_diagnosis_name_result);
                    $disease_name=$disease_rows['disease_name'];
                    $disease_code=$disease_rows['disease_code'];
                    $diagnosis_code_name2.=$disease_name."(<b> $disease_code </b>) ,&nbsp;&nbsp;&nbsp;";
                }
            }
            
            echo"
                <tr>
                    <th colspan='2'>PROVISIONAL DIAGNOSIS</th>
                    <td colspan='3'>$diagnosis_code_name2</td>
                </tr>

                    ";
            echo"
                <tr>
                    <th colspan='2'>FINAL DIAGNOSIS</th>
                    <td colspan='3'>$diagnosis_code_name</td>
                </tr>

                    ";
            
            $count_sn++;
        }
    }else{
                $count_sn=1;
                $sql_select_consultation_history_result=mysqli_query($conn,"SELECT final_diagnosis,card_no,hospital_id,consultation_record_id,consultation_date FROM tbl_card_consultation_record WHERE card_no=(SELECT card_no FROM tbl_member_afya_card WHERE Registration_ID='$Registration_ID'AND consultation_record_id='$consultation_record_id'LIMIT 1)") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_consultation_history_result)>0){
                    while($cons_rows=mysqli_fetch_assoc($sql_select_consultation_history_result)){
                        $hospital_id=$cons_rows['hospital_id'];
                        $consultation_record_id=$cons_rows['consultation_record_id'];
                        $consultation_date=$cons_rows['consultation_date'];
                        $card_no=$cons_rows['card_no'];
                        $final_diagnosis=$cons_rows['final_diagnosis'];
                        $final_diagnosis_data=implode(",",explode("<<,>>",$final_diagnosis));
                        //select hospital name
                        $Hospital_name="";
                        $sql_select_hospital_name_result=mysqli_query($conn,"SELECT Hospital_name FROM tbl_ehms_mr_all_hospital_list_local WHERE ehms_mr_all_hospital_list_online_id='$hospital_id'") or die(mysqli_error($conn));
                        if(mysqli_num_rows($sql_select_hospital_name_result)>0){
                            $Hospital_name=mysqli_fetch_assoc($sql_select_hospital_name_result)['Hospital_name'];
                        }
                        echo "<tr>
                                <td style='text-align: center'>$count_sn.</td>
                                <td style='text-align: center'>$hospital_id</td>
                                <td style='text-align: center'>$Hospital_name</td>
                                <td style='text-align: center'>$card_no</td>
                                <td style='text-align: center'>$consultation_date</td>
                            </tr>";
            //select final diagnosis name
            $diagnosis_code_name="";
            foreach($final_diagnosis_data as $disease_code){
                
                $sql_select_final_diagnosis_name_result=mysqli_query($conn,"SELECT disease_name,disease_code FROM tbl_disease WHERE disease_code = '$disease_code'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_final_diagnosis_name_result)>0){
                    $disease_rows=mysqli_fetch_assoc($sql_select_final_diagnosis_name_result);
                    $disease_name=$disease_rows['disease_name'];
                    $disease_code=$disease_rows['disease_code'];
                    $diagnosis_code_name.=$disease_name."(<b> $disease_code </b>) ,";
                }
            }
            echo"
                <tr>
                    <th colspan='2'>FINAL DIAGNOSIS</th>
                    <td colspan='3'>$diagnosis_code_name</td>
                </tr>

                    ";
                        $count_sn++;
                    }
            }
        }
            ?>
        </tbody>
    </table>
    <table class="table table-bordered" style='background: #FFFFFF;'>
        <tr>
            <caption style="text-align: center"><h3>INVESTIGATION AND TREATMENT</h3></caption>
        </tr>
        <tr>
            <th>S/No.</th>
            <th>ITEM CODE</th>
            <th>ITEM NAME</th>
            <th>TYPE</th>
            <th>COMMENTS</th>
            <th>ORDERED BY</th>
            <th>STATUS</th>
            <th>ORDERED TIME</th>
            <th>RESULT</th>
        </tr>
        <tbody>
            <?php 
                if(isset($_GET['data_from_online_server'])){
                 //fetch online ordered item under this consultation
                    $count_sn=1;
                    foreach($ordered_item_pr_consultation as $ordered_item_pr_consultation_data){
                        
                       $ordered_item_code=$ordered_item_pr_consultation_data[0];
                       $Check_In_Type=$crypt->decrypt($ordered_item_pr_consultation_data[1]);
                       $ordered_by=$crypt->decrypt($ordered_item_pr_consultation_data[2]);
                       $doctor_comments=$crypt->decrypt($ordered_item_pr_consultation_data[3]);
                       $status=$crypt->decrypt($ordered_item_pr_consultation_data[4]);
                       $ordered_time=$ordered_item_pr_consultation_data[5];
                       $consultation_record_id=$ordered_item_pr_consultation_data[6];
                       $investigation_result=$ordered_item_pr_consultation_data[7];
                       //select item name
                       $Product_Name="";
                       $sql_select_item_name_result=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Product_Code='$ordered_item_code' LIMIT 1") or die(mysqli_error($conn));
                       if(mysqli_num_rows($sql_select_item_name_result)>0){
                           $Product_Name=mysqli_fetch_assoc($sql_select_item_name_result)['Product_Name'];
                       }
                       echo "<tr>
                                <td>$count_sn.</td>
                                <td>$ordered_item_code</td>
                                <td>$Product_Name</td>
                                <td>$Check_In_Type</td>
                                <td>$doctor_comments</td>
                                <td>$ordered_by</td>
                                <td>$status</td>
                                <td>$ordered_time</td>";
                       if(sizeof($investigation_result)>0){
                       echo"
                                <td>
                                    <input type='button' value='View Result' class='art-button-green' onclick='open_investigation_result($count_sn)'/>
                                </td>";
                       }
                                echo "
                            </tr>";
                       echo "
                               <tr style='display:none'>
                                <td>
                                <div id='result_dialogy$count_sn' style='display:none' >
                                    <h3>$Product_Name</h3>
                                    <table class='table' style='background:#FFFFFF'>
                                       ";
                                        $count_sn_result=1;
//                                        foreach($investigation_result as $integration_result){
                                        $ivestigation_type=$investigation_result[0];
                                        $result_date=$investigation_result[1];
                                        $result_by_short_text=$investigation_result[2];
                                        $result_by_attachment=$investigation_result[3];
                                        $result_from_integration=$investigation_result[4];
                                        $ordered_item_record_id=$investigation_result[5];
                                        $validation_status=$investigation_result[6];
                                       
                                        $attachment_name="$hospital_id::$ordered_item_record_id.pdf";
                                        if(!empty($result_by_attachment)){
                                            $result_by_attachment_decoded = base64_decode($result_by_attachment);
                                            if(file_put_contents("afyacard_files/$attachment_name", $result_by_attachment_decoded)){

                                            }
                                        }
                                            echo "<tr>
                                                    <td><b>Result Date</b>:$result_date</td>
                                                  </tr>
                                                  <tr>
                                                    <td><b>Result</b>:
                                                    ";
                                            if($ivestigation_type=="radiology"){
                                                echo "
                                                        <table class='table'>
                                                            <tr style='background:#DEDEDE'>
                                                                <th style='text-align:center' colspan='2'> RADIOLOGY REPORT FOR: $Product_Name</th>
                                                            </tr>";
                                                            $result_by_short_text_arr=json_decode($result_by_short_text,true);
                                                            foreach($result_by_short_text_arr as $radiology_report){
                                                                $report_result_rad= explode(":::::", $radiology_report);
                                                                $rad_param=$report_result_rad[0];
                                                                $rad_param_description=$report_result_rad[1];
                                                                echo "
                                                                        <tr>
                                                                            <td width='200px'>
                                                                               <b>$rad_param</b>
                                                                            </td>
                                                                            <td>
                                                                                $rad_param_description
                                                                            </td>
                                                                        </tr>
                                                                    ";
                                                            }
                                                echo "
                                                        </table>
                                                    ";
                                            }else{
                                                echo "
                                                        $result_by_short_text
                                                     ";
                                            }
                                            echo "
                                                  </td>
                                                  </tr>
                                                  <tr>
                                                    <td><b>Validation Status</b>:$validation_status</td>
                                                  </tr>
                                                  <tr>
                                                    <td><b>Attachment:</b>
                                                    ";
                                            if(trim($result_by_attachment)!=""){
                                                echo "
                                                     <table class='table'>
                                                        <tr>
                                                            <td>
                                                               <object data='afyacard_files/$attachment_name' width='100%' height='500px' type='application/pdf'>
                                                                alt :  file  
                                                               </object>
                                                            </td>
                                                        </tr>
                                                     </table>
                                                     ";
                                            }
                                            
                                            echo "
                                                 </td>
                                                 </tr>";
                                            if($ivestigation_type=="laboratory"){
                                            echo "
                                                 <tr>
                                                    <td><b>Integration</b>:
                                                        <table class='table'>
                                                            <tr style='background:#DEDEDE'>
                                                                <td width='50px'>S/No.</td>
                                                                <td>PARAMETERS</td>
                                                                <td>RESULTS</td>
                                                                <td>NORMAL VALUE</td>
                                                                <td>UNITS</td>
                                                                <td>STATUS</td>
                                                                <td>M</td>
                                                                <td>V</td>
                                                                <td>S</td>
                                                            </tr>";
                                                            $count_intgrtn_rslt=1;
                                                        foreach(json_decode($result_from_integration,true) as $integration_result){
                                                            $parameters=$integration_result['parameters'];    
                                                            $results=$integration_result['results'];    
                                                            $reference_range_normal_values=$integration_result['reference_range_normal_values'];    
                                                            $units=$integration_result['units'];    
                                                            $status_h_or_l=$integration_result['status_h_or_l'];    
                                                            $modified=$integration_result['modified'];    
                                                            $validated=$integration_result['validated'];    
                                                            $result_date=$integration_result['result_date'];    
                                                            $sent_to_doctor=$integration_result['sent_to_doctor']; 
                                                            $bold_conditional_row_left="";
                                                            $bold_conditional_row_right="";
                                                            if($parameters=="WBC"||$parameters=="RBC"||$parameters=="PLT"){
                                                                $bold_conditional_row_left="<b>";
                                                                $bold_conditional_row_right="</b>";  
                                                            }
                                                            echo "<tr>
                                                                        <td>$bold_conditional_row_left $count_intgrtn_rslt. $bold_conditional_row_right</td>
                                                                        <td>$bold_conditional_row_left $parameters $bold_conditional_row_right</td>
                                                                        <td>$bold_conditional_row_left $results $bold_conditional_row_right</td>
                                                                        <td>$bold_conditional_row_left $reference_range_normal_values $bold_conditional_row_right</td>
                                                                        <td>$bold_conditional_row_left $units $bold_conditional_row_right</td>
                                                                        <td>$bold_conditional_row_left $status_h_or_l $bold_conditional_row_right</td>
                                                                        <td>$bold_conditional_row_left $modified $bold_conditional_row_right</td>
                                                                        <td>$bold_conditional_row_left $validated $bold_conditional_row_right</td>
                                                                        <td>$bold_conditional_row_left $sent_to_doctor $bold_conditional_row_right</td>
                                                                    </tr>";
                                                            $count_intgrtn_rslt++;
                                                        }
                                            echo "
                                                        </table>
                                                    
                                                    </td>
                                                  </tr>";
                                            }
//                                        }
                       echo     "
                                    </table>
                                </div>
                                </td>
                                </tr>
                            ";
                               $count_sn++;                                             
                    }
                }else{
                $sql_selec_ordered_item_result=mysqli_query($conn,"SELECT ordered_item_code,ordered_by,check_in_type,status,ordered_time FROM tbl_card_ordered_item_record WHERE consultation_record_id='$consultation_record_id' AND ordered_item_code<>'0'") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_selec_ordered_item_result)>0){
                    $count_sn=1;
                    while($ordered_item_rows=mysqli_fetch_assoc($sql_selec_ordered_item_result)){
                       $ordered_item_code=$ordered_item_rows['ordered_item_code'];
                       $ordered_by=$ordered_item_rows['ordered_by'];
                       $check_in_type=$ordered_item_rows['check_in_type'];
                       $status=$ordered_item_rows['status'];
                       $ordered_time=$ordered_item_rows['ordered_time'];
                       $doctor_comments=$ordered_item_rows['doctor_comments'];
                       //select item name
                       $Product_Name="";
                       $sql_select_item_name_result=mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Product_Code='$ordered_item_code' LIMIT 1") or die(mysqli_error($conn));
                       if(mysqli_num_rows($sql_select_item_name_result)>0){
                           $Product_Name=mysqli_fetch_assoc($sql_select_item_name_result)['Product_Name'];
                       }
                       echo "<tr>
                                <td>$count_sn.</td>
                                <td>$ordered_item_code</td>
                                <td>$Product_Name</td>
                                <td>$check_in_type</td>
                                <td>$doctor_comments</td>
                                <td>$ordered_by</td>
                                <td>$status</td>
                                <td>$ordered_time</td>
                            </tr>";
                    }
                }
            }
            ?>
        </tbody>
    </table>
</fieldset>
<?php
 function retrived_data_from_online_server($card_no,$consultation_record_id){
        /*****gkcchief april-17-2019******/
             $post_data_array=array(
                "username"=>"gpitg",
                "password"=>"gpitgmronline",
                "card_no"=>$card_no,
                "consultation_record_id"=>$consultation_record_id,
            );
           $post_data= json_encode($post_data_array);
            $post_data;
            $header = [
                'Content-Type: application/json',
                'Accept: application/json'
            ];
            $url = ehms_mr_local_url.'/ehms_mr_local/get_online_mr_data_local.php';
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url );
            curl_setopt($ch, CURLOPT_POST, true );
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data );
            curl_setopt($ch, CURLOPT_POSTREDIR, 3);
            $result = curl_exec($ch);
            curl_close($ch); 
            return $result;
    }
?>
<script>
    function open_investigation_result(count_sn){
         $("#result_dialogy"+count_sn).dialog({
            title: 'PATIENT INVESTIGATION RESULT',
            width: '85%',
            height: 650,
            modal: true,
        });
    }
</script>
<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script>
<script>
//    $(document).ready(function () {
//        $("#result_dialogy").dialog({autoOpen: false, width: '70%', height: 400, title: '', modal: true});
//    });
</script>        
        
<?php
    include("./includes/footer.php");
?>
