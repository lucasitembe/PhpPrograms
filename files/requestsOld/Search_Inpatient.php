<?php @session_start(); ?>

<?php

include("../includes/connection.php");

if(isset($_POST['removeptnthere'])){
 $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
 $payID=$_POST['payID'];
 $query="UPDATE tbl_item_list_cache SET romoving_staff='$Employee_ID',removing_date=NOW(),removing_status='Yes' WHERE Payment_Cache_ID='$payID' AND Check_In_Type='Laboratory'";
 $runQuery=  mysql_query($query);
 
}elseif (isset ($_POST['returnptnlToList'])) {
 $payID=$_POST['payID'];
$query="UPDATE tbl_item_list_cache SET removing_status='No' WHERE Payment_Cache_ID='$payID' AND Check_In_Type='Laboratory'";
$runQuery=  mysql_query($query);
 
}


if(isset($_POST['viewAttended'])){
   
            if($_POST['viewAttended']=='attended'){
            //View attended here
            $select_Filtered_Patients = mysql_query(
            "SELECT * FROM tbl_test_results,tbl_item_list_cache,tbl_payment_cache,tbl_patient_registration,tbl_employee WHERE payment_item_ID=Payment_Item_Cache_List_ID AND tbl_item_list_cache.Payment_Cache_ID= tbl_payment_cache.Payment_Cache_ID AND tbl_patient_registration.Registration_ID=tbl_payment_cache.Registration_ID AND tbl_employee.Employee_ID=tbl_payment_cache.Employee_ID AND tbl_item_list_cache.Status='Sample Collected'");
            }
            
            $Today_Date = mysql_query("select now() as today");
            
            while($row = mysql_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
        $htm="<center><table width =100% id='viewlabPatientList' class='display'>";
        $htm .="<thead>
                    <tr>
                        <th><b>SN</b></th>
                        <th><b>PATIENT</b></th>
                        <th><b>REG#</b></th>
                        <th><b>SPONSOR</b></th>
                        <th style='width:12%;'><b>AGE</b></th>
                        <th><b>GENDER</b></th>
                        <th><b>PHONE#</b></th>
                        <th><b>TRANS DATE</b></th>
                        <th><b>SPECIMEN COLLECTION</b></th>
                        <th><b>DOCTOR</b></th>
                        <th><b>ACTION</b></th>
                       
                    </tr>
               </thead>";

    $temp = 1;
    while($row = mysql_fetch_array($select_Filtered_Patients)){
            //$Product_Name=$row['Product_Name'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";
            
              $date1 = new DateTime($Today);
              $date2 = new DateTime($Date_Of_Birth);
              $diff = $date1 -> diff($date2);
              $age = $diff->y." Years, ";
              $age.= $diff->m." Months";
//              $age.= $diff->d." Days";
//              if(strtolower($Product_Name)=='registration and consultation fee'){
//              }else{
                $htm.="<tr>";
                $htm.="<td>".$temp."<td>".ucwords(strtolower($row['Patient_Name']))."</td>";
                $htm.="<td>".$row['Registration_ID']."</td>";
                $htm.="<td>".$row['Sponsor_Name']."</td>";
                $htm.="<td>".$age."</td>";
                $htm.="<td>".$row['Gender']."</td>";
                $htm.="<td>".$row['Phone_Number']."</td>";
                $htm.="<td>".$row['Transaction_Date_And_Time']."</td>";
                $htm.="<td>".$row['TimeSubmitted']."</td>";
                $htm.="<td>".$row['Employee_Name']."</td>";
                $htm.="<td><input type='button' class='viewSpecimen' name='".$row['Patient_Name']."' id='".$row['Registration_ID']."' item-id='".$row['payment_item_ID']."' value='View specimen' /> </td>";

                $htm.="</tr>";
                $temp++;  
                 }
                 $htm .="</table></center>";
                 echo $htm;  
        
  } else {

   $temp = 1;
    echo '<center>
            <table width =100% border=0 class="display" id="patientLabList">
            <thead>
                <tr>
                    <th><b>SN</b></th>
                    <th><b>PRIORITY</b></th>
                    <th style="width:12%;"><b>PATIENT</b></th>
                    <th><b>REG#</b></th>
                    <th style="width:2%;"><b>SPONSOR</b></th>
                    <th style="width:18%;"><b>AGE</b></th>
                    <th><b>GENDER</b></th>
                    <th style="width:14%;"><b>TRANS DATE</b></th>
                    <th><b>PHONE#</b></th>
                    <th style="width:10%;"><b>DOCTOR</b></th>
                    <th>ACTION</th>
                    
                </tr>
            </thead>';
    
    $filter=' AND DATE(Transaction_Date_And_Time)=DATE(NOW()) ';
    $navFilter= ' AND DATE(Transaction_Date_And_Time)=DATE(NOW())';
    
        //if(isset($_GET['filterlabpatientdate'])  && $_GET['filterlabpatientdate']==true){
          $Date_From=  filter_input(INPUT_GET, 'Date_From');
          $Date_To=  filter_input(INPUT_GET, 'Date_To');
          $Sponsor=  filter_input(INPUT_GET, 'Sponsor');
          $subcategory_ID=  filter_input(INPUT_GET, 'subcategory_ID');
          $Patient_Name=  filter_input(INPUT_GET, 'Patient_Name');
      
       
        if(isset($Date_To) && !empty($Date_To) && isset($Date_From) && !empty($Date_From)){
        $filter="  AND Transaction_Date_And_Time BETWEEN '". $Date_From."' AND '".$Date_To."'";
          $navFilter ="  AND Transaction_Date_And_Time BETWEEN '". $Date_From."' AND '".$Date_To."'";
        }
        
        if(!empty($Sponsor) && $Sponsor != 'All'){
             $filter .=" AND sp.Sponsor_ID='$Sponsor'";
        }
        
        if(!empty($subcategory_ID) && $subcategory_ID != 'All'){
             $filter .=" AND i.Item_Subcategory_ID='$subcategory_ID'";
              $navFilter .=" AND i.Item_Subcategory_ID='$subcategory_ID'";
        }
        
        if(!empty($Patient_Name)){
             $filter .=" AND pr.Patient_Name LIKE '%".$_GET['Patient_Name']."%'";
        }
        
       // die($filter);
    
    $select_Filtered_Patients ="SELECT 'cache' as Status_From,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Process_Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time
                                            FROM tbl_item_list_cache as il
                                            JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                            JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID 
                                            JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                            JOIN tbl_sponsor AS sp ON sp.Sponsor_ID =pr.Sponsor_ID
                                            WHERE Check_In_Type ='Laboratory' AND (il.Status='active' OR il.Status='paid') AND removing_status='No' $filter GROUP BY payment_id ORDER BY Transaction_Date_And_Time ASC  LIMIT 100";    


   $result=mysql_query($select_Filtered_Patients)or die(mysql_error());
       //date manipulation to get the patient age
        $Today_Date = mysql_query("select now() as today");
            while($row = mysql_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }

            $num_rows=  mysql_num_rows($result);
            if($num_rows>0){
                 while($row = mysql_fetch_array($result)){
                   $Patient_Payment_ID=$row['payment_id'];  
                   $priority=  mysql_query("SELECT Priority FROM tbl_item_list_cache WHERE Payment_Cache_ID='$Patient_Payment_ID' AND Priority='Urgent' AND Check_In_Type ='Laboratory'");  
                   $number_rows=  mysql_num_rows($priority);
                   if($number_rows>0){
                       $priorities='<span style="color:red;font-size:17px;font-weigh:bold">Urgent</span>';
                   }else{
                       $priorities='<span style="color:blue">Normal</span>';
                   }
                   
                   $Date_Of_Birth = $row['Date_Of_Birth'];
                  $age = floor( (strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926)." Years";

                    $date1 = new DateTime($Today);
                    $date2 = new DateTime($Date_Of_Birth);
                    
                    $diff = $date1 -> diff($date2);
                    
                    $age = $diff->y." Years, ";
                    $age .= $diff->m." Months, ";
                    $age .= $diff->d." Days";


                         if($row['Status'] =='Sample Collected'){
                        
                                   echo "<tr bgcolor='lightgreen'>
                                   <td>".$temp."</td>
                                   <td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>$priorities</a></td>
                                   <td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";         
                                }else{
                                echo "<tr><td>".$temp."</td>
                                    
                                <td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>$priorities</a></td>
                                <td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
                             
                                }
                                 echo "<td><a class='viewDetails'  href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'><center>".$age."</center></a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."&Date_From=".$Date_From."&Date_To=".$Date_To."&Sponsor=".$Sponsor."&subcategory_ID=".$subcategory_ID."' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</a></td>";
                                echo"<td>
                                    <input type='button' style='width:100%' class='removeptnt' id='".$row['payment_id']."' value='Remove'>
                                    <input type='button' style='width:100%' filter=\"".$navFilter."\" id='reg_" . $row['registration_number'] . "' onclick='doctorReview(\"" . $row['Patient_Name'] . "\"," . $row['registration_number'] . ",".$row['payment_id'].")' value='Doctor Review'/> 
                                 </td>";
                                $temp++;
                                        } 
            }  else {
                
               // echo '<p style="color:red;font-weight:bold">No records found</p>';   
            }
                echo "</tr>";
    
  
}
?>
</table>
</center>

<div id="specimenDialog" style="display: none"> 
    <div id="specimenhistoryhere">
        
    </div>
</div>


<script type="text/javascript"> 
    
     //View button here
    $('.viewSpecimen').bind('click',function(){
       var paymentID=$(this).attr('item-id');
       var patientID=$(this).attr('id');
       var name=$(this).attr('name');
       

       $('#specimenDialog').dialog({
       modal:true,
       width:'80%',
       minHeight:450,
       resizable:true,
       draggable:true
       });
       
      $.ajax({
      type:'POST', 
      url:'requests/ParameterResultsHistory.php',
      data:'action=parameterhistory&paymentID='+paymentID+'&patientID'+patientID,
      cache:false,
      success:function(html){
      //  alert(html);
        $('#specimenhistoryhere').html(html);
      }
      });

        $('#specimenDialog').dialog('option', 'title',name);

    });
	
	$('.removeptnt').bind('click',function(){
            var payID=$(this).attr('id');
            if(confirm('Are you sure you want to remove this patient from list?')){
                   $.ajax({
                    type:'POST',
                    url:"requests/Search_Inpatient.php",
                    data:"removeptnthere=remove&payID="+payID,
                    success:function(){
                   // alert(html);
                     $('#iframeDiv').load("requests/Search_Inpatient.php");
                    }
            }); 
            }
	});
      
    
//Search name textbox
//    $('#searchName').bind('keypress',function(){
//      var pname=$(this).val();
//        $.ajax({
//        type:'POST',
//        url:"requests/Search_Inpatient.php",
//        data:"viewAttended=searchByname&pname="+pname,
//        success:function(html){
//        $('#iframeDiv').html(html); 
//        }
//        });
//    });
    
    
    //Filter button begins here
    
//    $('.Filter').click(function(){
//       var fromDate=$('#dates_From').val();
//       var toDate=$('#dates_To').val();
//       if((fromDate=='') || toDate==''){
//           alert('Choose proper dates to filter results!');
//           exit();
//       }
//       
//       $.ajax({
//        type:'POST',
//        url:"requests/Search_Inpatient.php",
//        data:"viewAttended=attendedbyDate&fromDate="+fromDate+'&toDate='+toDate,
//        success:function(html){
//          $('#iframeDiv').html(html); 
//        }
//        });
//    });
</script>
