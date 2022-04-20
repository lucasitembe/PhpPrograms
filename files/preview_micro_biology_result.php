<?php
	session_start();
	include("./includes/connection.php");

	if(isset($_SESSION['userinfo']['Employee_Name'])){
		$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
	}else{
		$Employee_Name = '';
	}
	if(isset($_GET['payment_id'])){
		$payment_id = $_GET['payment_id'];
	}else{
		$payment_id = '';
	}
	
//get patient name
        $sql_select_patient_name_result=mysqli_query($conn,"SELECT Gender,Registration_ID,Patient_Name,TIMESTAMPDIFF(YEAR,DATE(Date_Of_Birth),CURDATE()) AS age FROM tbl_patient_registration WHERE Registration_ID IN (SELECT Registration_ID FROM tbl_payment_cache WHERE Payment_Cache_ID IN (SELECT Payment_Cache_ID FROM tbl_item_list_cache WHERE Payment_Item_Cache_List_ID='$payment_id')) LIMIT 1") or die(mysqli_error($conn));
        $patient_detail_rows=mysqli_fetch_assoc($sql_select_patient_name_result);
        
        $Patient_Name=$patient_detail_rows['Patient_Name'];
        $Gender=$patient_detail_rows['Gender'];
        $Registration_ID=$patient_detail_rows['Registration_ID'];
        $age=$patient_detail_rows['age'];
        
    //get employees
    if($Employee_ID == '0'){
        $Employees = 'EMPLOYEES : ALL';
    }else{
        $select = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Employee_ID'") or die(mysqli_error($conn));
        $no = mysqli_num_rows($select);
        if($no > 0){
            while ($row = mysqli_fetch_array($select)) {
                $Employees = 'EMPLOYEE : '.$row['Employee_Name'];
            }
        }else{
            $Employees = 'EMPLOYEES : ALL';
        }
    }
        
      
   $sql_select_validation_status_result=mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results WHERE validated='No' AND ref_test_result_ID IN (SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='$payment_id')");
if(mysqli_num_rows($sql_select_validation_status_result)>0){
    $provisional_status="<tr><td style='color:red'><b>PROVISIONAL RESULTS</b></td><tr>";
}else{
    $provisional_status="";
}

$gram_stein="";
$Culture_ID="";
$wet_prepation="";
$sign="";
$gram_stein="";
$Specimen="";
$Remarks="";
	$htm = '<table align="center" style="font-size:9" width="100%">
                    <tr><td style="text-align:left" colspan="4"><img src="./branchBanner/branchBanner.png"></td></tr>
                    <tr><td style="text-align:center;" colspan="4"><h6>MICROBIOLOGY REPORT</h6></td></tr>
                    <tr><td style="text-align:left"><b>PATIENT NAME : </b>'.$Patient_Name.'</td><td style="text-align:left"><b>REG# : </b>'.$Registration_ID.'</td><td style="text-align:left"><b>AGE : </b>'.$age.' years</td></tr>
                    <tr><td style="text-align:left"><b>SEX : </b>'.$Gender.'</td></tr>
                '.$provisional_status.'
        <tr><td colspan="4"><hr style="width:100%;height:1px;margin-top:-0.1%;margin-bottom:-0.1%" /></td></tr>
</table>';
        $modified="";
        $Validated="";
        $Saved="";
        $TimeSubmitted="";
        $TimeValidate="";
        $TimeValidate="";
        $Saved_by="";
        $validated_by="kjfuf";
        $result_date=0;
        $sql_select_patient_lab_micro_biology_result=mysqli_query($conn,"SELECT * FROM tbl_tests_parameters_results WHERE ref_test_result_ID IN (SELECT test_result_ID FROM tbl_test_results WHERE payment_item_ID='$payment_id')") or die(mysqli_error($conn));
       
        if(mysqli_num_rows($sql_select_patient_lab_micro_biology_result)>0){
            $count_sr=1;
           
           $rows=mysqli_fetch_assoc($sql_select_patient_lab_micro_biology_result);
//            $htm.="nooo";
              $modified= $rows['modified'];
              $Validated= $rows['Validated'];
              $Saved= $rows['Saved'];
              $TimeSubmitted= $rows['TimeSubmitted'];
              $TimeValidate= $rows['TimeValidate'];
              $Saved_by= $rows['SavedBy'];
              $validated_by= $rows['ValidatedBy'];
              $result_date= $rows['result_date'];
  
        }
//        die($validated_by);
        $htm.= $result_date;
        $query = "SELECT Culture_ID,wet_prepation,gram_stein,sign,deseases,payment_item_ID,Specimen,Organism1,Remarks FROM tbl_culture_results WHERE payment_item_ID='$payment_id'";
        $myQuery = mysqli_query($conn,$query);
       while($row2 = mysqli_fetch_assoc($myQuery)){
        
                       $deseases = $row2['deseases'];
                       $Culture_ID = $row2['Culture_ID'];
                       $payment_item_ID = $row2['payment_item_ID'];
                       $wet_prepation = $row2['wet_prepation'];
                       $sign = $row2['sign'];
                        $Specimen = $row2['Specimen'];
                       $gram_stein = $row2['gram_stein'];
                       $Organism1 = $row2['Organism1'];
                       $Remarks = $row2['Remarks'];
                       
         $specimen_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Specimen_Name FROM tbl_laboratory_specimen WHERE Specimen_ID='$Specimen'"))['Specimen_Name'];
         $organism_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT organism_name FROM tbl_organism WHERE organism_id='$Organism1'"))['organism_name'];
                                           
       }   
  
        $htm.="<table width='100%' border='1'>";
           $htm.="<tr><td><center><b>Specimen Type</b></center></td>
        <td  colspan='3'>
                $specimen_name 
                </td></tr>";
        
           $htm.= "<tr>
                <td><center><b>Organism</b></center></td>
                <td  colspan='3'>
                $organism_name
                </td>
           
           <div id=''>";      
        
        $htm.= "               
</td>
            </tr>";
          $htm.= "<tr >
                  <td width='10%'>
                  <center> <b>WET PREPATION</b></center>
                  </td>
                  <td colspan='3'>
                $wet_prepation
                  </td>
            
                </tr>";
           $htm.= "<tr>
                   <td rowspan='2'>
                  <center> <b> GRAM STEIN</b></center>
                  </td>    
                   <td width='50%' colspan='2'>
                       $sign
                     
                  </td>
                  <td>";
                      $query1 = "SELECT deseases_name FROM tbl_deseases WHERE culture_id='$Culture_ID'";
                 $myQuery3 = mysqli_query($conn,$query1);
                  
                  while($row3 = mysqli_fetch_assoc($myQuery3)){
                 $deseases_name = $row3['deseases_name'];
                 $htm.= "$deseases_name ,";
                    }
                  $htm.= "</td>     
            </tr>
                  <tr> <td colspan='3'>
                    $gram_stein
                  </td></tr>";
               $htm.= "<tr><td><b>Biochemical Tests</b></td><td colspan='3'>";
             $query1 = "SELECT biotest FROM tbl_biotest WHERE culture_id='$Culture_ID'";
        $myQuery3 = mysqli_query($conn,$query1);
       
       while($row3 = mysqli_fetch_assoc($myQuery3)){
                      
                         $biotest = $row3['biotest'];
       $htm.= "$biotest ,";
        }
        $htm.="</td></tr>";
        
     $htm.= "<tr><td><center><b>Antibiotic</b></center></td><td  colspan='3'><table class='table'>";
             $query2 = "SELECT antibiotic,antibiotic_result,antibiotic_id FROM tbl_antibiotic WHERE culture_id=$Culture_ID";
        $myQuery4 = mysqli_query($conn,$query2);
       while($row4 = mysqli_fetch_assoc($myQuery4)){
                      
                         $antibiotic = $row4['antibiotic'];
                         $antibiotic_result = $row4['antibiotic_result'];
                 $item_name = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Product_Name FROM tbl_items WHERE Item_ID=$antibiotic"))['Product_Name'];     
                         
       $htm.= "<tr><td>
                $item_name
               </td>";
        $htm.= "<td>
                $antibiotic_result
               </td></tr>";
        }
        $htm.= "</table></td></tr>";
         $htm.= $cached_data;
      
        $htm.= "</table>";
               
               $htm.="<b>Remarks</b>: $Remarks";
        
$doctor_ordered="";
$$Transaction_Date_And_Time="";
$sql_select_employee_name_result=mysqli_query($conn,"SELECT Employee_Name,Transaction_Date_And_Time FROM tbl_employee emp,tbl_item_list_cache ilc WHERE emp.Employee_ID=ilc.Consultant_ID AND Payment_Item_Cache_List_ID='$payment_id'") or die(mysqli_error($conn));
if(mysqli_num_rows($sql_select_employee_name_result)>0){
    $rows=mysqli_fetch_assoc($sql_select_employee_name_result);
    $doctor_ordered=$rows['Employee_Name'];
    $Transaction_Date_And_Time=$rows['Transaction_Date_And_Time'];
} 
$TimeSubmitted="";
$Query = "SELECT Product_Name,Payment_Item_Cache_List_ID,TimeSubmitted,test_result_ID,tr.Employee_ID FROM tbl_item_list_cache il INNER JOIN tbl_test_results tr ON Payment_Item_Cache_List_ID=payment_item_ID JOIN tbl_payment_cache pc ON  pc.Payment_Cache_ID=il.Payment_Cache_ID JOIN  tbl_items i ON i.Item_ID=il.Item_ID WHERE il.Check_In_Type='Laboratory' and il.Status='Sample Collected' AND Payment_Item_Cache_List_ID='$payment_id' GROUP BY Payment_Item_Cache_List_ID";
$result2 = mysqli_query($conn,$Query) or die(mysqli_error($conn));
if(mysqli_num_rows($result2)>0){
    $rows_2=mysqli_fetch_assoc($result2);
    $TimeSubmitted=$rows_2['TimeSubmitted'];
}
// die($validated_by);
$validated_by2=(int)$validated_by;
$select_result = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$validated_by2'") or die(mysqli_error($conn));
if(mysqli_num_rows($select_result)>0){
    $validated_by5=mysqli_fetch_assoc($select_result)['Employee_Name'];
}
$Saved_by2=(int)$Saved_by;
$select_result = mysqli_query($conn,"select Employee_Name from tbl_employee where Employee_ID = '$Saved_by2'") or die(mysqli_error($conn));
if(mysqli_num_rows($select_result)>0){
    $Saved_by3=mysqli_fetch_assoc($select_result)['Employee_Name'];
}

$date1 = new DateTime($result_date);
        $date2 = new DateTime($TimeSubmitted);
        $diff = $date1->diff($date2);
        $lapsed_time = $diff->d . " days, ";
        $lapsed_time .= $diff->h . " hours, ";
        $lapsed_time .= $diff->i . " minutes,";
        $lapsed_time .= $diff->s . " Seconds";

$htm.=" 
            <table width='100%'>
                <tr>
                    <td colspan='4'><hr style='width:100%;height:1px;'/></td>
                </tr>
                <tr>
                    <td style='text-align:left'>Ordering Doctor:</td><td style='text-align:left'> $doctor_ordered </td>
                    <td style='text-align:right'>Order Date:</td><td style='text-align:left'>$Transaction_Date_And_Time</td>
                </tr>
                 <tr>
                    <td style='text-align:left'>Date Of Result:</td><td style='text-align:left'> $TimeSubmitted </td>
                  <td style='text-align:right'>Reviewed By:</td><td style='text-align:left'> $validated_by5</td>
                </tr>
                <tr>
                    <td style='text-align:left'>Authorized By:</td><td style='text-align:left'> $Saved_by3 </td>
                </tr>
            </table>"; 
    
           
       
	$date_n_time_printed=date("H:i:s");
    include("./MPDF/mpdf.php");
    $mpdf=new mPDF('c','A4','','', 15,15,20,23,15,20, 'P'); 
    $mpdf->SetFooter('Printed By '.ucwords(strtolower($Employee_Name)).'  {DATE d-m-Y} '.$date_n_time_printed.' |Page {PAGENO} of {nb}| Powered By GPITG LTD');
    $mpdf->list_indent_first_level = 0; // 1 or 0 - whether to indent the first level of a list
    // LOAD a stylesheet
    $stylesheet = file_get_contents('mpdfstyletables.css');
    $mpdf->WriteHTML($stylesheet,1);    // The parameter 1 tells that this is css/style only and no body/html/text

    $mpdf->WriteHTML($htm,2);

    $mpdf->Output('mpdf.pdf','I');
    exit;
?>