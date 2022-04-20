<?php
include './includes/connection.php';
$sn = 1;
$filter='';
$filterTerminal='';
    
if (isset($_GET['start_date'])) {
    $start_date=$_GET['start_date'];
    $end_date=$_GET['end_date'];
    $Terminal_ID=$_GET['Terminal_ID'];
    $employee_id=$_GET['employee_id'];
    
    $filter=" ";
    
    
    if(!empty($start_date) && !empty($end_date)){
          $filter=" AND ba.Transaction_Date BETWEEN '$start_date' AND '$end_date'";
    }
    
    if(!empty($Terminal_ID) && $Terminal_ID != 'all'){
          $filterTerminal=" WHERE Terminal_ID = '$Terminal_ID' ";
    }if(!empty($employee_id) && $employee_id != 'all'){
          $filter .=" AND b.Employee_ID = '$employee_id' ";
    }
} 
//
//echo $filter;
//
//exit;

if(isset($_GET['start_date'])){

$sql = "SELECT DISTINCT(Terminal_ID) FROM tbl_bank_api_payments_details $filterTerminal";

 $result = ' <table id="" class="table table-striped table-bordered" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>S/N</th>
                            <th>Terminal ID</th>
                            <th>Prepared By</th>
                            <th style="text-align:right">Amount Paid</th>
                        </tr>
                    </thead>
                    <tbody>';
 
 $sql_result=  mysqli_query($conn,$sql) or die(mysqli_error($conn));
 
 $grand_total=0;
while ($row = mysqli_fetch_assoc($sql_result)) {
    $terminal_id=$row['Terminal_ID'];
    $query_two="SELECT SUM(Amount_Paid) AS AMOUNT,Employee_Name FROM tbl_bank_api_payments_details ba
                JOIN tbl_bank_transaction_cache b ON b.Payment_Code=ba.Payment_Code
                JOIN tbl_employee e ON e.Employee_ID=b.Employee_ID
                WHERE Terminal_ID='$terminal_id' $filter";
    
    $get_dt=  mysqli_query($conn,$query_two) or die(mysqli_error($conn));
    $rs=mysqli_fetch_assoc($get_dt);
    $employee='';
     if( !empty($employee_id) && $employee_id != 'all'){
         $employee=$rs['Employee_Name'];
     }
        $status="";
        $result .= '<tr>';
        $result .= '<td>' . $sn++ . '</td>';
        $result .= '<td  style="text-align:center">' . $terminal_id . '</td>';
        $result .= '<td  style="text-align:center">' .$employee . '</td>';
        $result .= '<td  style="text-align:right">' . number_format($rs['AMOUNT'],2 ). '</td>';
        $result .= '</tr>';
        
        $grand_total +=$rs['AMOUNT'];
}

 $result .='  
     <tr><td colspan="4"><hr/></td></tr>
      <tr><td colspan="3"  style="text-align:right"><b>Grand Total</b></td><td  style="text-align:right"><b>' . number_format($grand_total,2 ) . '</b></td></tr>

</tbody>
                   </table>';

}
echo $result;

