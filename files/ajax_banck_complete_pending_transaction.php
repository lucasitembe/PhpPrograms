<?php
include("./includes/connection.php");
if(isset($_POST['start_date'])&&isset($_POST['end_date'])&&isset($_POST['Registration_ID'])&&isset($_POST['Patient_Name'])){
  $start_date=$_POST['start_date'];
  $end_date=$_POST['end_date'];
  $Registration_ID=$_POST['Registration_ID'];
  $Patient_Name=$_POST['Patient_Name'];
  
  $filter="AND tc.transaction_date_time BETWEEN '$start_date' AND '$end_date'";
  if(!empty($Patient_Name)){
    $filter.="AND pr.Patient_Name LIKE '%$Patient_Name%'";
  }
  if(!empty($Registration_ID)){
     $filter.="AND  pr.Registration_ID ='$Registration_ID'";
  }
  $sql_select_list_of_patient_sent_to_cashier_result=mysqli_query($conn,"SELECT tm.Amount,itl.card_and_mobile_payment_transaction_id,pr.Registration_ID,pr.Patient_Name,pr.Date_Of_Birth,pr.Gender,pr.Sponsor_ID,tc.transaction_date_time,tc.patient_phone,tc.Payment_Cache_ID,tm.Payment_Number,tm.receiptNumber FROM `tbl_card_and_mobile_payment_transaction` tc,tbl_patient_registration pr , tbl_item_list_cache itl ,tbl_mobile_payemts tm WHERE pr.`Registration_ID`=tc.`Registration_ID` and itl.Payment_Cache_ID = tc.`Payment_Cache_ID` and tc.`bill_payment_code` = tm.Payment_Number AND tc.`transaction_status`='pending' AND itl.card_and_mobile_payment_transaction_id != tc.`bill_payment_code` $filter GROUP BY tm.`Payment_Number`") or die(mysqli_error($conn));
  if(mysqli_num_rows($sql_select_list_of_patient_sent_to_cashier_result)>0){
       $count_sn=1;
      while($patient_list_rows=mysqli_fetch_assoc($sql_select_list_of_patient_sent_to_cashier_result)){
         $Registration_ID=$patient_list_rows['Registration_ID'];
         $Patient_Name=$patient_list_rows['Patient_Name'];
         $Phone_Number=$patient_list_rows['patient_phone'];
         $Date_Of_Birth=$patient_list_rows['Date_Of_Birth'];
         $Gender=$patient_list_rows['Gender'];
         $Sponsor_ID=$patient_list_rows['Sponsor_ID'];
         $Payment_Date_And_Time=$patient_list_rows['transaction_date_time'];
         $Payment_Cache_ID=$patient_list_rows['Payment_Cache_ID'];
         $bill_payment_code = $patient_list_rows['Payment_Number'];
         $Amount = $patient_list_rows['Amount'];

         $card_and_mobile_payment_transaction_id=$patient_list_rows['card_and_mobile_payment_transaction_id'];
         $receiptNumber=$patient_list_rows['receiptNumber'];
         
         $Guarantor_Name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID='$Sponsor_ID'"))['Guarantor_Name'];
        
                
         echo "
                <tr class='rows_list'>
                        <td>$count_sn.</td>
                        <td>$Patient_Name</td>
                        <td>$Registration_ID</td>
                        <td>$Phone_Number</td>
                        <td>$bill_payment_code</td>
                        <td>$card_and_mobile_payment_transaction_id</td>
                        <td>$receiptNumber</td>
                        <td>".number_format($Amount)."</td>
                        <td>$Gender</td>
                        <td>$Guarantor_Name</td>
                        <td>$Payment_Date_And_Time</td>
                        <td><input type='button' class='art-button-green' value='Remove Duplicate' onclick='remove_duplicate_sangira_number(\"$card_and_mobile_payment_transaction_id\",\"$bill_payment_code\")'></td>
                    </a>
                </tr>
                ";
        $count_sn++;
          
      }
  }
}
?>
<script>
  function remove_duplicate_sangira_number(card_and_mobile_payment_transaction_id,bill_payment_code){
      document.getElementById('patient_sent_to_cashier_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
    $.ajax({
            type:'POST',
            url:'remove_duplicate_sangira_number.php',
            data:{
              card_and_mobile_payment_transaction_id:card_and_mobile_payment_transaction_id,
              bill_payment_code:bill_payment_code
              },
            success:function(response){
              alert(response);
              $("#patient_sent_to_cashier_tbl").html(response);
              filter_list_of_patient_sent_to_cashier();
            }
        });
  }

</script>



