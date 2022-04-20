<link rel="stylesheet" href="table.css" media="screen">
<?php
@session_start();
include("./includes/connection.php");
$temp = 1;
$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
if(isset($_GET['Patient_Name'])){
    $Patient_Name = $_GET['Patient_Name'];
}else{
    $Patient_Name = '';
}

if(isset($_GET['Patient_Number'])){
    $Patient_Number = $_GET['Patient_Number'];
}else{
    $Patient_Number = '';
}
if(isset($_GET['Phone_Number'])){
    $Phone_Number = $_GET['Phone_Number'];
}else{
    $Phone_Number = '';
}

//Find the current date to filter check in list

$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
}

//get employee id
if(isset($_SESSION['userinfo']['Employee_ID'])){
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
}else{
    $Employee_ID = 0;
}

    $hospitalConsultType=$_SESSION['hospitalConsultaioninfo']['consultation_Type'];
    $emp='';
    
    if($hospitalConsultType=='One patient to one doctor'){
        $emp="AND tlc.Consultant_ID =".$_SESSION['userinfo']['Employee_ID']." ";
    }
    
      
    
$select_Filtered_Patients = mysqli_query($conn,"SELECT * FROM
					tbl_test_results as trs,tbl_tests_parameters_results as tprs,tbl_item_list_cache tlc,
					tbl_payment_cache,tbl_patient_registration,
					tbl_employee,tbl_consultation tc,tbl_patient_payment_item_list  tpipi WHERE
					payment_item_ID=Payment_Item_Cache_List_ID AND
					tlc.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND
					tc.consultation_id=tbl_payment_cache.consultation_id AND
					tc.Patient_Payment_Item_List_ID=tpipi.Patient_Payment_Item_List_ID AND tpipi.Process_Status !='signedoff' AND
				    trs.test_result_ID=tprs.ref_test_result_ID AND
				   tprs.Submitted='Yes' AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND
					tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID $emp
					and tbl_patient_registration.Patient_Name like '%$Patient_Name%'
					GROUP BY tbl_patient_registration.Registration_ID
					ORDER BY test_result_ID ASC") or die(mysqli_error($conn));
					
 $Today_Date = mysqli_query($conn,"select now() as today");
                while($row = mysqli_fetch_array($Today_Date)){
                    $original_Date = $row['today'];
                    $new_Date = date("Y-m-d", strtotime($original_Date));
                    $Today = $new_Date;
                    $age ='';
                }
  $htm="<center><table width =100% border=0>";
  $htm .="<tr id='thead'><td style='width:5%;'><b>SN</b></td>
    
    <td><b>PATIENT NAME</b></td>
	 <td style='width:8%;'><b>REG NO.</b></td>
                <td><b>SPONSOR</b></td>
                    <td style='width:15%;'><b>AGE</b></td>
                        <td width=5%><b>GENDER</b></td>
                        <td><b>DOCTOR NAME</b></td>
                            <td><b>PHONE No.</b></td>
                                </tr>";

    $temp = 1;
    while($row = mysqli_fetch_array($select_Filtered_Patients)){
            //$Product_Name=$row['Product_Name'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
			
			//Retrieve Patient_Payment_ID,Payment_Item_Cache_List_ID by consultationID
              $queryPay="SELECT ppl.Patient_Payment_ID,ppl.Patient_Payment_Item_List_ID 
			           FROM tbl_patient_payment_item_list AS ppl LEFT JOIN tbl_consultation AS tcon ON ppl.Patient_Payment_Item_List_ID=tcon.Patient_Payment_Item_List_ID				   
					   WHERE consultation_ID='".$row['consultation_id']." AND Registration_ID=".$row['Registration_ID']."'
					   ";
					   
					   //echo $row['consultation_ID'].'<br/>';
			  $resultPay=mysqli_query($conn,$queryPay) or die(mysqli_error($conn));
			  $pay=mysqli_fetch_assoc($resultPay);
			  
			  
			  
              $date1 = new DateTime($Today);
              $date2 = new DateTime($Date_Of_Birth);
              $diff = $date1 -> diff($date2);
              $age = $diff->y." Years, ";
              $age.= $diff->m." Months";
//              $age.= $diff->d." Days";
//              if(strtolower($Product_Name)=='registration and consultation fee'){
//              }else{Payment_Item_Cache_List_ID
                $htm.="<tr>";
                $htm.="<td id='thead'>".$temp."</td><td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."&Patient_Payment_ID=".$pay['Patient_Payment_ID']."&Patient_Payment_Item_List_ID=".$pay['Patient_Payment_Item_List_ID']."' target='_parent' style='text-decoration: none';>".ucwords(strtolower($row['Patient_Name']))."</a></td>";
                $htm.="<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."' target='_parent' style='text-decoration: none';>".$row['Registration_ID']."</a></td>";
                $htm.="<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."' target='_parent' style='text-decoration: none';>".$row['Sponsor_Name']."</a></td>";
                $htm.="<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."' target='_parent' style='text-decoration: none';>".$age."</a></td>";
                $htm.="<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."' target='_parent' style='text-decoration: none';>".$row['Gender']."</a></td>";
                $htm.="<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."' target='_parent' style='text-decoration: none';>".$row['Employee_Name']."</a></td>";
                $htm.="<td><a href='laboratory_result_details.php?Registration_ID=".$row['Registration_ID']."' target='_parent' style='text-decoration: none';>".$row['Phone_Number']."</a></td>";
                
               
                $htm.="</tr>";
                $temp++;  
                 }
                 $htm .="</table></center>";
                 echo $htm;
?>

<script>
 $('.searchresults').click(function(){
      var patient=$(this).attr('name');
   var id=$(this).attr('id');
   $.ajax({
      type:'POST', 
      url:'requests/testResults.php',
      data:'action=getResult&id='+id,
      cache:false,
      success:function(html){
      //  alert(html);
        $('#showLabResultsHere').html(html);
      }
   });
     
     
      $('#labResults').dialog({
       modal:true,
       width:'90%',
       minHeight:450,
       resizable:true,
       draggable:true,
   }).dialog("widget")
     .next(".ui-widget-overlay")
     .css({
          background:"rgb(100,100,100)",
          opacity:1
    });
    
    $("#labResults").dialog('option', 'title', patient+'  '+'No.'+id);
 });
</script>
