<?php
include("./includes/connection.php");
if(isset($_GET['Employee_Name'])){
   $Employee_Name=$_GET['Employee_Name'];
}else{
   $Employee_Name="";
}
?>
 <tr>
    <td style="width:50px"><b>S/No</b></td>
    <td style="width:80%"><b>Employee Name</b></td>
    <td style="width:50px"><b>Action</b></td>
</tr>
<?php 
    $sql_select_employee_result=mysqli_query($conn,"SELECT Employee_Name,Employee_ID FROM tbl_employee WHERE Employee_Name LIKE '%$Employee_Name%' AND Account_Status='active'") or die(mysqli_error($conn));
    if(mysqli_num_rows($sql_select_employee_result)>0){
        $count=1;
        while($employee_rows=mysqli_fetch_assoc($sql_select_employee_result)){
           $Employee_Name=$employee_rows['Employee_Name'];
           $Employee_ID=$employee_rows['Employee_ID'];
           echo "
               <tr>
                    <td>$count</td>
                    <td>$Employee_Name</td>
                    <td>
                        <input type='button' class='art-button-green' onclick='assign_employee_approval_level(\"$Employee_ID\")' value='ASSIGN'/>
                    </td>
                </tr>
                ";
           $count++;
        }
    }
?>

