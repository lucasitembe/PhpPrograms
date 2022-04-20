<?php @session_start(); ?>
<!--<link rel="stylesheet" href="table.css" media="screen">-->
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>

<script type="text/javascript">
    $('#dates_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:  'now'
    });
    $('#dates_From').datetimepicker({value:'',step:30});
    $('#dates_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    startDate:'now'
    });
    $('#dates_To').datetimepicker({value:'',step:30});
    
</script>

<script type="text/javascript">
    $(document).ready(function(){
      $('#viewlabPatientList,#patientLabList').DataTable({
          "bJQueryUI":true
      });
    });
</script>

<?php

include("../includes/connection.php");
if(isset($_POST['viewAttended'])){ 
        
  } else {
//
//    if(isset($_POST['action'])){   
//     $txt=$_POST['patient'];   
//    } else {
//      $txt='';
//    }
   $temp = 1;
    echo '<center>
            <table width =100% border=0 class="display" id="patientLabList">
            <thead>
                <tr>
                    <th><b>SN</b></th>
                    <th><b>STATUS</b></th>
                    <th style="width:12%;"><b>PATIENT</b></th>
                    <th><b>REG#</b></th>
                    <th style="width:2%;"><b>SPONSOR</b></th>
                    <th style="width:18%;"><b>AGE</b></th>
                    <th><b>GENDER</b></th>
                    <th style="width:14%;"><b>TRANS DATE</b></th>
                    <th><b>PHONE#</b></th>
                    <th style="width:10%;"><b>DOCTOR</b></th>
                    <th style="width:10%;"><b>DATE REMOVED</b></th>
                    <th style="width:10%;"><b>REMOVED BY</b></th>
		    <th><b>ACTION</b></th>
                    
                </tr>
            </thead>';
    
    
    
      $select_Filtered_Patients ="SELECT 'cache' as Status_From,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,
                                pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                pc.Payment_Cache_ID as payment_id,il.Process_Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time,il.romoving_staff,il.removing_date
                                 FROM tbl_item_list_cache as il
                                 JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
                                 JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
                                 JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
                                 WHERE Check_In_Type ='Laboratory' AND (il.Status='active' OR il.Status='paid') AND removing_status='Yes' GROUP BY pr.Registration_ID ORDER BY Transaction_Date_And_Time asc";  
        
    
//    if(isset($_POST['action'])){
//           
//    if($txt=="All Patients"){
//      
//    
//    }  else {
//    
//    $select_Filtered_Patients ="SELECT 'cache' as Status_From,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,
//                                pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
//                                pc.Payment_Cache_ID as payment_id,il.Process_Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time,il.romoving_staff,il.removing_date
//                                 FROM tbl_item_list_cache as il
//                                 JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
//                                 JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID AND Billing_Type='".$txt."'
//                                 JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
//                                 WHERE Check_In_Type ='Laboratory' AND (il.Status='active' OR il.Status='paid') AND removing_status='Yes' GROUP BY pr.Registration_ID ORDER BY Transaction_Date_And_Time asc";    
//
//} 
//        
//    }
//    
//    else {
//        
//     $select_Filtered_Patients ="SELECT 'cache' as Status_From,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,
//                                pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
//                                pc.Payment_Cache_ID as payment_id,il.Process_Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time,il.romoving_staff,il.removing_date
//                                 FROM tbl_item_list_cache as il
//                                 JOIN tbl_items as i ON i.Item_ID = il.Item_ID 
//                                 JOIN tbl_payment_cache as pc ON pc.Payment_Cache_ID = il.Payment_Cache_ID
//                                 JOIN tbl_patient_registration AS pr ON pr.Registration_ID =pc.Registration_ID
//                                 WHERE Check_In_Type ='Laboratory' AND (il.Status='active' OR il.Status='paid') AND removing_status='Yes' GROUP BY pr.Registration_ID ORDER BY Transaction_Date_And_Time asc";  
//}

   $result=mysqli_query($conn,$select_Filtered_Patients)or die(mysqli_error($conn));
                                        //date manipulation to get the patient age
        $Today_Date = mysqli_query($conn,"select now() as today");
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }

            $num_rows=  mysqli_num_rows($result);
                  if($num_rows>0){
                  while($row =@mysqli_fetch_array($result)){
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
                                   <td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>Done</a></td>
                                   <td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";         
                                }else{
                                echo "<tr><td>".$temp."</td>
                                    
                                <td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>Not Taken</a></td>
                                <td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Patient_Name']."</a></td>";
                             
                                }
                                echo "<td><a class='viewDetails'  href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['registration_number']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Sponsor_Name']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'><center>".$age."</center></a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Gender']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Transaction_Date_And_Time']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Phone_Number']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['Doctors_Name']."</a></td>";
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['removing_date']."</a></td>";
//                                $getsatffname=mysqli_query($conn,"SELECT * FROM tbl_employee WHERE Employee_ID='".$row['romoving_staff']."'");
//                                $result=  mysqli_fetch_assoc($getsatffname);
                                echo "<td><a class='viewDetails' href='laboratory_sample_collection_details.php?Status_From=".$row['Status_From']."&patient_id=".$row['registration_number']."&payment_id=".$row['payment_id']."&Required_Date=".$row['Required_Date']."' target='_parent' style='text-decoration: none;'>".$row['romoving_staff']."</a></td>";
                                //}
                                echo"<td><input type='button' class='removeptnt' id='".$row['payment_id']."' value='Remove'></td>";
                                $temp++;
                                
                                } 
            }  else {
                
              //  echo '<p style="color:red;font-weight:bold">No records found</p>';   
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
