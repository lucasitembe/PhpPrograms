<?php
    include("./includes/header.php");
    include("./includes/connection.php");
        
    
         $requisition_ID='';
         $select_dept='';
         $employee_name='';
         $approved_by='';
         $date_of_requisition='';
         $description_works_to_done='';
         $job_card_ID='';

    include_once("./includes/header.php");
    include_once("./includes/connection.php");

    include_once("./functions/department.php");
    include_once("./functions/employee.php");
    include_once("./functions/items.php");
    include_once("./functions/requisition.php");

    //get employee name
    if (isset($_SESSION['userinfo'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Officer';
        $Employee_ID = 0;
    }
    //get department ID
    if (isset($_SESSION['userinfo'])) {
        $Employee_department = $_SESSION['userinfo']['Department_ID'];

        $select = mysqli_query($conn,"select Department_Name from tbl_department where Department_ID = '$Employee_department'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        $row = mysqli_fetch_assoc($select);
        $Department_Name = $row['Department_Name'];
    } else {
        $Department_Name = '';
    }
}
    

    //get branch id
    if (isset($_SESSION['userinfo']['Branch_ID'])) {
        $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
    } else {
        $Branch_ID = 0;
    }

    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    $Requisition_ID = '';
    if (isset($_SESSION['Requisition_ID'])) {
        $Requisition_ID = $_SESSION['Requisition_ID'];
    }

    if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }

    $Requisition = array();
    if (!empty($Requisition_ID)) {
        $Requisition = Get_Requisition($Requisition_ID);
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d H:m", strtotime($original_Date));
        $Today = $new_Date;
    }
?>
        <a href='previous_engineering_works.php' class='art-button-green'>
            PREVIOUS REQUISITION
        </a>
        <a href='engineering_works1.php' class='art-button-green'>
            BACK
        </a>

<br/><br/><br/><br/>
<center>
<table width='90%'>
    <tr>
      <td>
        <fieldset>
        <legend align=center><b>CREATE REQUISITION FOR ENGINEERING</b></legend>
        <form action='' method='post' name='submit' id='myForm' >
            <table width=100%>
            <tr>
                <td style='text-align: right; width=15%;'>Requisition Number</td>
                <td style='text-align: right; width=15%;'>
                    <?php
                        echo "<input type='text' readonly='readonly' value='' Placeholder='New'>";
                    ?>
                </td>
		    <td width='10%' style='text-align: right;'>Department Requesting</td>
                <td width='14%'>
                <select name='Store_Need'  class="Issuevalue" id='Store_Need' style="width:100%" >
                <?php
                    $Employee_department = mysqli_query($conn, "SELECT sd.Department_Name, sd.Department_ID FROM tbl_employee emp, tbl_department sd WHERE sd.Department_ID = emp.Department_ID AND emp.Employee_ID = '$Employee_ID'");
                    if(mysqli_num_rows($Employee_department)>0){
                        while($dept = mysqli_fetch_assoc($Employee_department)) {
                            $Sub_Department_Name = $dept['Department_Name'];
                            $Sub_Department_ID = $dept['Department_ID'];

                            echo "<option value='$Sub_Department_ID'>";
                            echo "$Sub_Department_Name";
                            echo "</option>";
                            //echo "<input type='text' readonly='readonly' name='department' value='{$Department_name}'>";
                            echo "<input type='text' hidden='hidden' name='department_requesting' value='{$Sub_Department_ID}'>";
                        }
                    }
                        ?>
                </td>
                <td width='20%' style='text-align: right;'>Requisition Date</td>
                <td width='20%'>
                    <?php
                        if (!empty($Requisition_ID)) {
                            echo "<input type='text' readonly='readonly' name='date_of_requisition' id='Transaction_Date' ";
                            echo "value='{$Requisition['Created_Date']}'/>";
                        } else {
                            echo "<input type='text' readonly='readonly' name='date_of_requisition' id='Transaction_Date' value='".$Today."'>";
                        }
                    ?>
                </td> 
                
                </tr>
                <tr>
                 <td style='text-align: right; width=15%;'>Employee Name</td>
                <td style='text-align: right; width=15%;'>
                    <?php
                        echo "<input type='text' readonly='readonly'  name='employee' value='{$Employee_Name}'>";
                        echo "<input type='text' hidden='hidden'  name='reporter' value='{$Employee_ID}'>";
                    
                    ?>
                </td>
                    
                        <td style='text-align: right; width=15%;'>Administrative Responsibility</td>
                        <td>
                                <input type='text' value='' name='title' placeholder='Employee Title' autoComplete='off' required>
                        </td>
                    <td style='text-align: right; width=15%;'>Place/Floor/Room</td>
                        <td>
                                <input type='text' value='' name='floor' placeholder='Place/Floor/Room' required>
                        </td>
                </tr>
                <tr>
                    <td style='text-align: right; width=15%;'>Equipment Name</td>
                        <td colspan='2'>
                                <input type='text' value='' name='equipment_name' placeholder='Equipment Name'  autoComplete='off' required>
                        </td>
                    <td style='text-align: right; width=15%;'>Inventory Code</td>
                        <td colspan='2'>
                                <input type='text' value='' name='equipment_code' placeholder='Inventory Code'  autoComplete='off'>
                        </td>
                    
                    
                    
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right; width=15%;'>Equipment Serial Number</td>
                        <td colspan='2'>
                                <input type='text' value='' name='equipment_serial' placeholder='Equipment Serial Number'  autoComplete='off'>
                        </td>
                    <td style='text-align: right; width=15%;'>Equipment ID Number</td>
                        <td colspan='2'>
                                <input type='text' value='' name='equipment_ID' placeholder='Equipment ID Number'  autoComplete='off'>
                        </td>
                    
                    
                    
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right;' width="15%"> Requisition Description </td>
                        <td width="50%" height="20%" colspan='5'>
                            <textarea name='description_works_to_done'  autoComplete='off' required> </textarea>
                    </td>
                    </td>
                </tr>
             <input type='hidden' name='requisition_ID' value='true'/>
              <input type='hidden' name='job_card_ID' value='true'/>
<!--              <input type="text"  name='select_dept'value='test department'/>-->
                <tr>
                    <td colspan=4 style='text-align: right;'>
                        <input type='submit' name='submit_form' id='submit' value='   SAVE   ' class='art-button-green'>
                        <input type='reset' name='clear' id='clear' value=' CLEAR ' class='art-button-green'>
                        <input type='hidden' name='submit' value='true'/>
                    </td>
                </tr>
            </table>
	</form>
</fieldset>
        </td>
    </tr>
</table>
        </center>
<br/>
<?php
                                      
$insert_requisition_for_engineering='';
 
 if(isset($_POST['submit_form'])){
        $description_works_to_done=$_POST['description_works_to_done'];
        $requisition_ID=$_POST['requisition_ID'];
        $select_dept=$_POST['department_requesting'];
        $employee_name=$_POST['reporter'];
        $title=$_POST['title'];
        $floor=$_POST['floor'];
        $date_of_requisition=$_POST['date_of_requisition'];
        $equipment_name=$_POST['equipment_name'];
        $equipment_serial=$_POST['equipment_serial'];
        $equipment_ID=$_POST['equipment_ID'];
        $equipment_code=$_POST['equipment_code'];


        if(!empty($description_works_to_done)){
        
   $insert_requisition_for_engineering="INSERT INTO tbl_engineering_requisition(select_dept,employee_name,title,floor,date_of_requisition,equipment_name,equipment_code,equipment_serial,equipment_ID,description_works_to_done,requisition_status) VALUES ('$select_dept','$employee_name','$title','$floor',NOW(),'$equipment_name','$equipment_code','$equipment_serial','$equipment_ID','$description_works_to_done','pending')";
      $save_result=mysqli_query($conn,$insert_requisition_for_engineering) or die(mysqli_error($conn));  
      
     if ($save_result)
     {
         echo "<script>alert('Engineering requisition submitted successfully!');
         document.location = './engineering_works1.php';</script>";
         
    }
     else 
     {
         echo "<script>alert('Requisition Failed!')</script>";
     }
       
 }else{
        echo "<script>alert('Please Tell Us what is the Problem with your Equipment/Device!')</script>";
 }
}
 mysqli_close($conn);
?>
<br><br><br>
<?php
    include("./includes/footer.php");
?>
