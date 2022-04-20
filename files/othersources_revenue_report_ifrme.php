<!----<link rel="stylesheet" href="table.css" media="screen">-->

<style>
 table,tr,td{
  border-collapse:collapse !important;
  border:none !important;
 }
 tr:hover{
  background-color:#eeeeee;
  cursor:pointer;
 }
</style>
<?php
@session_start();
    include("./includes/connection.php");
    $temp = 1;
    $filter=' ';
    $limit =' LIMIT 25';
    if(isset($_POST['Start_Date'])){
     $Start_Date = $_POST['Start_Date'];
    }else{
     $Start_Date = '';
    }
    if(isset($_POST['End_Date'])){
     $End_Date = $_POST['End_Date'];
    }else{
     $End_Date = '';
    }
    if(isset($_POST['employee_id'])){
     $employee_id = $_POST['employee_id'];
    }else{
     $employee_id = '';
    }
   if($employee_id=="all"){
     $Emplyeefilter="";
//      $limit=" ";
    }else{
     $Emplyeefilter=" AND osp.Employee_ID ='$employee_id'";
    }
    if(!empty($Start_Date) && !empty($End_Date)){
      $filter=" WHERE osp.Payment_Date_And_Time BETWEEN '$Start_Date' AND '$End_Date' $Emplyeefilter";
      $limit=" ";
    }

     //$Employee_ID =$_POST['Employee_ID'];
    //$Date_From = $_POST['fromDate'];
    //$Date_To = $_POST['toDate'];
    //$Transaction_Type = $_POST['Transaction'];

    echo '<center><table width =100% border="1" id="dtTableperformancedetails" class="display" style="background-color:white;">';
?>   <thead><tr style='background-color:#006400; color:white;'>
	    <th style = 'width:5%;'>SN</th>
      <th style='text-align: left;'>DATE & TIME</th>
      <th style='text-align: left;'>CUSTOMER/SPONSOR NAME</th>
      <th width=10% style='text-align: left;'>RECEIPT NO</th>
      <th>CHEQUE NUMBER</th>
      <th>CUSTOMER TYPE</th>
      <th>AMOUNT</th>
<?php

    $select_list=mysqli_query($conn,"SELECT osp.Payment_ID,osp.Customer_ID,osp.Payment_Date_And_Time,osp.cheque_number,osp.customer_type from tbl_other_sources_payments osp $filter  ORDER BY osp.Payment_Date_And_Time DESC $limit");
    while ($row=mysqli_fetch_assoc($select_list)) {
        $Customer_ID=$row['Customer_ID'];
        $Payment_ID=$row['Payment_ID'];
        if($row['customer_type']==='CUSTOMER'){
            $customer_name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID=$Customer_ID"))['Patient_Name'];
        }
        else if($row['customer_type']==='SPONSOR'){
            $customer_name=mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_Sponsor WHERE Sponsor_ID=$Customer_ID"))['Guarantor_Name'];
        }
        $amount=mysqli_fetch_assoc(mysqli_query($conn,"SELECT SUM((Price-Discount) * Quantity) as amount FROM tbl_other_sources_payment_item_list WHERE Payment_ID=$Payment_ID"))['amount'];
        //$customer_name="empty";
        echo "<tr>
            <td style='text-align:center;'>{$temp}</td><td>".$row['Payment_Date_And_Time']."</td><td>".$customer_name."</td><td style='text-align:center;'><a href='#' style='display:block; text-decoration:none;' onclick='print_receipt(".$row['Payment_ID'].",".$Customer_ID.");'>".$row['Payment_ID']."</a></td><td  style='text-align:center;'>".$row['cheque_number']."</td><td style='text-align:center;'>".$row['customer_type']."</td><td style='text-align:right;'>".number_format($amount)."&emsp;&emsp;</td>
        </tr>";
        $temp++;
        $total_amount += $amount;
    }
?></table>
  <input type='hidden' name='total_amount' value='this amount' id='total_amount'>
<span style='float:right;'><b>Total Amount: &nbsp;<?php echo number_format($total_amount);?></b>&emsp;&emsp;</span>
<br><br> <input type="submit" name="" class="art-button-green" value="Preview" style="float:right;" onclick="Preview_List();">
</center>

<script type="text/javascript">
    function print_receipt(payment_id,Registration_ID){
        window.open("other_source_receipt_print.php?Payment_ID="+payment_id+"&Registration_ID="+Registration_ID);
    }
</script>
