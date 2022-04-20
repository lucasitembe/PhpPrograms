
<?php
include("./includes/connection.php");
@$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];
echo '<center><table width =100% border="1" id="doctorperformancereportsummarised" class="display">';
echo "<thead>
        <tr>
            <th width=3% style='text-align:left'>SN</th>
            <th style='text-align:left'>STATUS</th>
            <th style='text-align:left'>DOCTOR'S NAME</th>
            <th style='text-align: left;'>PATIENT NAME</th>
            <th style='text-align: left;'>PATIENT #</th>
            <th style='text-align: left;'>SPONSOR</th>
            <th style='text-align: left;'>SURGERY NAME</th>
            <th style='text-align: left;'>SURGRY DATE</th>
            <th style='text-align: left;'>SURGERY DURATION</th>
            <th style='text-align: left;'>TRANSACTION DATE</th>
            <th style='text-align: left;'>LOCATION</th>
            <th style='text-align: left;'>PATIENTS PHONE NUMBER</th>
        </tr>
    </thead>";


if (isset($_POST['action'])){
    $Like_Name;
    $date_From=$_POST['date_From'];
    $date_To=$_POST['date_To'];
    $Sponsor_ID=$_POST['Sponsor_ID'];
    $Employee_ID=$_POST['Employee_ID'];
    
   if($_POST['action']=='filter') {
    $Like_Name='';
    $between_Date="AND Service_Date_And_Time BETWEEN '$date_From' AND '$date_To'";
  
    if($Sponsor_ID=='All'){
        $sponsor='';
    }  else {
       $sponsor="AND sp.Sponsor_ID='$Sponsor_ID'"; 
    }
    
    if($Employee_ID=='All'){
        $Employee_Name='';
    }else{
       $Employee_Name="AND il.Consultant_ID='$Employee_ID'"; 
    }
    
    
   }else if($_POST['action']=='filterInput'){
     $between_Date="AND Service_Date_And_Time BETWEEN '$date_From' AND '$date_To'";
  
    if($Sponsor_ID=='All'){
        $sponsor='';
    }  else {
       $sponsor="AND sp.Sponsor_ID='$Sponsor_ID'"; 
    }
    
    if($Employee_ID=='All'){
        $Employee_Name='';
    }else{
       $Employee_Name="AND il.Consultant_ID='$Employee_ID'"; 
    }
    
    $employee_search=$_POST['Patient_Name'];
    
    if($employee_search=='' || $employee_search=='NULL'){
      $Like_Name='';  
        
    }  else {
      $Like_Name="AND pr.Patient_Name LIKE '%$employee_search%'";  
    }
    
   }
   
  
}else{
    
  $sponsor=''; 
  $between_Date='';
  $Employee_Name='';
}

$result = mysqli_query($conn,"SELECT 'cache' as Status_From,Payment_Item_Cache_List_ID,Surgery_hour,Surgery_min,il.payment_type,pc.Billing_Type,il.Transaction_Type as transaction,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,te.Employee_Name,Product_Name,il.Service_Date_And_Time, sd.Sub_Department_Name AS Procedure_Location,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time
                                            FROM tbl_items as i ,tbl_sub_department AS sd,tbl_payment_cache as pc,tbl_patient_registration AS pr,tbl_sponsor AS sp,tbl_employee te,tbl_item_list_cache as il
                                            WHERE i.Item_ID = il.Item_ID 
                                            AND pc.Payment_Cache_ID = il.Payment_Cache_ID 
                                            AND pr.Registration_ID =pc.Registration_ID
                                            AND sp.Sponsor_ID =pr.Sponsor_ID
                                            AND te.Employee_ID=il.Consultant_ID
                                            AND sd.Sub_Department_ID =il.Sub_Department_ID
                                            AND Check_In_Type ='Surgery' AND (il.Status='active' OR il.Status='paid') AND removing_status='No' $between_Date $sponsor $Employee_Name $Like_Name GROUP BY Payment_Item_Cache_List_ID ORDER BY Transaction_Date_And_Time DESC  LIMIT 100");

$sn=1;
while($select_doctor_row = mysqli_fetch_array($result)){
echo "<tr><td>".$sn++."</td>";
                                        $billing_Type = strtolower($select_doctor_row['Billing_Type']);
                    $status = strtolower($select_doctor_row['Status']);
                    $transaction_Type = strtolower($select_doctor_row['transaction']);
                    $payment_type = strtolower($select_doctor_row['payment_type']);

                    if (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "cash")) {
                        $tatus= 'Not paid';
                    } elseif (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "credit")) {
                        $tatus= 'Not Billed';
                
                    }  elseif (($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
                        $tatus= 'Not paid';
                    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

                        if ($pre_paid == '1') {
                            $tatus= 'Not paid';
                        } else {
                            if ($payment_type == 'pre'  && $status == 'active') {
                                   $tatus= 'Not paid';
                            } else {
                                $tatus= 'Not Billed';
                            }
                        }
                    } elseif (($billing_Type == 'outpatient cash' && $status == 'paid') || ($billing_Type == 'outpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
                        $tatus= 'Paid';
                    } elseif (($billing_Type == 'inpatient cash' && $status == 'paid') || ($billing_Type == 'inpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
                        $tatus= 'Paid';
                    } else {
                        if ($payment_type == 'pre') {
                            $tatus= 'Not paid';
                        } else {
                            $tatus= 'Not Billed';
                        }
                    }


                    
echo "<td>".$tatus."</td>";
echo "<td style='text-align:left'>".$select_doctor_row['Employee_Name']."</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Patient_Name'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['registration_number'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Sponsor_Name'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Product_Name'] . "</td>";
echo "<td class='Date_Time' id='".$select_doctor_row['Payment_Item_Cache_List_ID']."' style='text-align:left'>". $select_doctor_row['Service_Date_And_Time'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Surgery_hour'] .'hrs :'.$select_doctor_row['Surgery_min']. "min</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Transaction_Date_And_Time'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Procedure_Location'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Phone_Number'] . "</td>
</tr>";
}
?>
 </table>
 
 <div id="changeDateDiv" style="display: none">
     <input type='text' name='Date_From' id='date_Fromx' required='required' style="padding-left:5px" autocomplete="off">
      <br /><br />
      <input type='hidden' name='Date_From' id='date_From_val' value="">
      <center> <input type="button" value="Save Changes" class="SaveChangedDate"></center>
 </div>
 
 
 <style>
     .Date_Time:hover{
         cursor: pointer;
     }
 </style>
 
 <script>
    $('#doctorperformancereportsummarised').dataTable({
        "bJQueryUI": true,
    });

    $('.Date_Time').on('click',function(){ 
        var id=$(this).attr('id');
        $('#date_From_val').val(id);
        $('#changeDateDiv').dialog({
            modal: true,
            width: '30%',
            resizable: true,
            draggable: true,
            title: 'Change Surgery Date'
//            close: function (event, ui) {
//               
//            }
        });

    });
    
     $('#date_Fromx').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_Fromx').datetimepicker({value: '', step: 1});

  $('.SaveChangedDate').on('click',function(){
     var pay_ID=$('#date_From_val').val();
  
     var DateOfService=$('#date_Fromx').val();
     $.ajax({
        type:'POST',
        url:'requests/Update_sugery_date.php',
        data:'action=update&pay_ID='+pay_ID+'&DateOfService='+DateOfService,
        cache:false,
        success:function(e){
            alert(e);
        }
     });
  });
</script>

<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="css/jquery-ui.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
