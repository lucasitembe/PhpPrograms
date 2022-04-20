<?php
include_once("./includes/connection.php");
if(isset($_POST['end_date'])){
   $end_date=$_POST['end_date'];
   $sql=mysqli_query($conn,"SELECT employee,SpCode,SpSysId,BillId ,TrxSts FROM tbl_gepgBillCanclReq WHERE BillId='$end_date'") or die(mysqli_error($conn)); 
}else{
    $sql=mysqli_query($conn,"SELECT employee,SpCode,SpSysId,BillId ,TrxSts FROM tbl_gepgBillCanclReq") or die(mysqli_error($conn));  
}
$count=1;
$Patient_Name = null;
$Registration_ID = null;
$employee = null;
if(mysqli_num_rows($sql)>0){
    while($amount_rows=mysqli_fetch_assoc($sql)){
        $sql1 = mysqli_query($conn,"SELECT BillId,CustomerPayerName,GeneratedBy FROM bill WHERE BillId='".$amount_rows['BillId']."'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql1)>0){
             while($name_rows=mysqli_fetch_assoc($sql1)){
                $Patient_Name = $name_rows['CustomerPayerName'];
                $GeneratedBy =$name_rows['GeneratedBy'];
            }
        }  
       
        $sql2 = mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID='$GeneratedBy'") or die(mysqli_error($conn));
        if(mysqli_num_rows($sql2)>0){
            while($name_employee=mysqli_fetch_assoc($sql2)){
            $employee = $name_employee['Employee_Name'];
           }
        }
       
        echo "<tr>
                <td>$count.</td>
                <td>".$employee."</td>
                <td>".$Patient_Name."</td>
                <td>".$amount_rows['BillId']."</td>
                <td>".$amount_rows['TrxSts']."</td>
             </tr>";
       $count++;
    }
}
?>