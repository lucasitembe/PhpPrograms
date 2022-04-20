<?php
include("./includes/connection.php");
if(isset($_GET['document_type_to_approve'])){
   $document_type_to_approve=$_GET['document_type_to_approve'];
}else{
   $document_type_to_approve="";
}
if(isset($_GET['document_approval_level_title_id'])){
   $document_approval_level_title_id=$_GET['document_approval_level_title_id'];
}else{
   $document_approval_level_title_id="";
}
$sql_select_assigned_employee_result=mysqli_query($conn,"SELECT Employee_Name,assigned_approval_level_id FROM tbl_employee emp,tbl_document_approval_level dal,tbl_employee_assigned_approval_level eal WHERE emp.Employee_ID=eal.assgned_Employee_ID AND dal.document_approval_level_id=eal.document_approval_level_id AND document_approval_level_title_id='$document_approval_level_title_id' AND document_type='$document_type_to_approve'") or die(mysqli_error($conn));
  ?>
<tr>
    <td style="width:50px"><b>S/No.</b></td>
    <td><b>Employee Name</b></td>
    <td style="width:100px"><b>Action</b></td>
</tr>
      <?php          
if(mysqli_num_rows($sql_select_assigned_employee_result)>0){
    $nom=mysqli_num_rows($sql_select_assigned_employee_result);
    
    $count=1;
    while($assigned_rows=mysqli_fetch_assoc($sql_select_assigned_employee_result)){
        
        $Employee_Name=$assigned_rows['Employee_Name'];
        $assigned_approval_level_id=$assigned_rows['assigned_approval_level_id'];
        echo " <tr>
                    <td>$count</td>
                    <td>$Employee_Name</td>
                    <td>
                        <input type='button' value='REMOVE'class='art-button-green' onclick='remove_this_employee_approval_level(\"$assigned_approval_level_id\")'/>
                    </td>
                </tr>
            ";
        $count++;
    }
}