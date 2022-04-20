<style type="text/css">
    /* .labefor{display:block;width: 100% }
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%;  */
    }
                #spu_lgn_tbl{
                    width:100%;
                    border:none!important;
                }
                #spu_lgn_tbl tr, #spu_lgn_tbl tr td{
                    border:none!important;
                    padding: 5px;
                    font-size: 14PX;
                }
</style>
<?php
    include("./includes/header.php");
    include("./includes/connection.php");

    include_once("./functions/department.php");
    include_once("./functions/employee.php");
    include_once("./functions/items.php");
    include_once("./functions/requisition.php");

    //get employee name
    
        if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }
    
    if (isset($_SESSION['userinfo'])) {
        $Employee_Name = $_SESSION['userinfo']['Employee_Name'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    } else {
        $Employee_Name = 'Unknown Officer';
        $Employee_ID = 0;
    }
    
//get sub department name
if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    //exit($Sub_Department_ID);
    $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select);
    if ($no > 0) {
        $row = mysqli_fetch_assoc($select);
        $Sub_Department_Name = $row['Sub_Department_Name'];
    } else {
        $Sub_Department_Name = '';
    }
}


    

    if (!isset($_SESSION['userinfo'])) {
        session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    /*$Requisition_ID = '';
    if (isset($_SESSION['Requisition_ID'])) {
        $Requisition_ID = $_SESSION['Requisition_ID'];
    }
     */

    /*if (isset($_GET['Requisition_ID'])) {
        $Requisition_ID = $_GET['Requisition_ID'];
    }
*/
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

$engineering
?>
        <a href='reassign_job_list.php?section=Engineering_Works=Engineering_WorksThisPage' class='art-button-green'>
            BACK
        </a>

<br/>
<center>
<table id="spu_lgn_tbl" width='90%'>
    <tr>
      <td>
        <fieldset>
        <legend align=center><b>RE-ASSIGN REQUISITION FOR ENGINEERING</b></legend>
        <form action='requisition_for_engineering.php?New_Process_Requisition=True&Requisition_ID=".$requisition_ID."' method='GET' name='' id='myForm' >
            <table width=100%>
                <?php
              
               //get details from tbl_enginnering_requisition
                                $get_details = mysqli_query($conn,"select * FROM tbl_engineering_requisition
											where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
                                $no = mysqli_num_rows($get_details);
                                if ($no > 0) {
                                    //$Process_Status = 'processed';
                                    while ($data2 = mysqli_fetch_array($get_details)) {
                                        $requisition_ID = $data2['requisition_ID'];
                                        $Department_ID = $data2['select_dept'];
                                        $employee = $data2['employee_name'];
                                        $title = $data2['title'];
                                        $floor = $data2['floor'];
                                        $requisition_date = $data2['date_of_requisition'];
                                        $equipment_name = $data2['equipment_name'];
                                        $equipment_ID = $data2['equipment_ID'];
                                        $equipment_serial = $data2['equipment_serial'];
                                        $equipment_code = $data2['equipment_code'];
                                        $description_works_to_done = $data2['description_works_to_done'];
                                        $prev_assigned_engineer = $data2['assigned_engineer'];
                                        $prev_section_required = $data2['section_required'];
                                        $prev_assistance_engineer = $data2['assistance_engineer'];
                                        $prev_type_of_work = $data2['type_of_work'];

                                        $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Department_Name FROM tbl_department WHERE Department_ID='$Department_ID'"))['Department_Name'];

                                        $muombaji = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$employee'"))['Employee_Name'];

                                        $namba = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Phone_Number FROM tbl_employee WHERE Employee_ID = '$employee'"))['Phone_Number'];
                                }}
                                    ?>
            <tr>
                                <td  style='text-align: right; width:15%;'><i>HelpDesk Name:</i></td>
                                <td colspan="2">
                                <?php
                                echo "<input type='text' readonly='readonly' value='{$Employee_Name}'>";
                                echo "<input type='text' readonly='readonly' name='helpdesk'  hidden value='{$Employee_ID}'>";
                                ?>
                                </td>
                                <td  style='text-align: right; width:15%;'><i>Requesting Staff Phone Number </i></td>
                                <td colspan="2">
                                <?php
                                echo "<input type='text' readonly='readonly' value='{$namba}'>";
                                ?>
                                </td>
            </tr>
            <tr>
                <td style='text-align: right; width:15%;'>Requisition Number</td>
                <td style='text-align: right; width:15%;'>
                    <?php
                        echo "<input type='text' readonly='readonly' name='Requisition_ID' value='{$Requisition_ID}'";
                    ?>
                </td>
		    <td width='10%' style='text-align: right;'>Department Requesting</td>
                <td width='14%'>
                                <?php
                                echo "<input type='text' value='{$department}'>"
                                ?>
                </td>
                <td width='20%' style='text-align: right;'>Requisition Date</td>
                <td width='30%'>
                    <?php
                            echo "<input type='text' readonly='readonly' name='date_of_requisition' id='Transaction_Date' value='{$requisition_date}'>";
                    ?>
                </td> 
                
                </tr>
                <tr>
                 <td style='text-align: right; width:15%;'>Name of Requesting Staff</td>
                <td style='text-align: right; width:15%;'>
                    <?php
                        echo "<input type='text' readonly='readonly'  name='employee' value='{$muombaji}'>"
                    ?>
                </td>
                    
                        <td style='text-align: right; width:15%;'>Administrative Responsibility</td>
                        <td>
                            <?php
                                   echo "<input type='text' readonly='readonly'  name='employee' value='{$title}'>";                    
                     ?>
                        </td>
                    <td style='text-align: right; width:15%;'>Place/Floor/Room</td>
                        <td>
                           <?php
                                   echo "<input type='text' readonly='readonly'  name='floor' value='{$floor}'>";                    
                     ?>
                        </td>
                </tr>
                <tr>
                    <td style='text-align: right; width:15%;'>Equipment Name</td>
                        <td colspan='2'>
                               <?php
                                   echo "<input type='text' readonly='readonly'  name='equipment' value='$equipment_name'>";                    
                     ?>
                        </td>
                    <td style='text-align: right; width:15%;'>Inventory Code</td>
                        <td colspan='2'>
                                <?php
                                   echo "<input type='text' readonly='readonly'  name='equipment_code' value='$equipment_code'>";                    
                     ?>
                        </td>
                    
                    
                    
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right; width=15%;'>Equipment Serial Number</td>
                        <td colspan='2'>
                        <?php
                                   echo "<input type='text' readonly='readonly'  name='equipment_Serial' value='$equipment_serial'>";                    
                     ?>
                        </td>
                    <td style='text-align: right; width=15%;'>Equipment ID Number</td>
                        <td colspan='2'>
                        <?php
                            echo "<input type='text' readonly='readonly'  name='equipment_ID' value='$equipment_ID'>";                    
                     ?>
                        </td>
                    
                    
                    
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right; width:15%;'> Requisition Description </td>
                        <td width="100%" height="20%" colspan='5'>
							<textarea readonly="readonly"> <?php echo $description_works_to_done ?> </textarea>
							
                    </td>
	
                </tr>
            </table>
	</form>
</fieldset>
        </td>
    </tr>
</table>
        
<br/>
<table width=90%>
                <tr>
                  <td>
                    <fieldset>
                        <legend align=center><b>JOB RE-ASSGNMENT AND COLLECTION</b></legend>
                        <form action='' method='POST' name='submit' id='myForm' >
                        <table width=100% id='spu_lgn_tbl'>
                            <tr   id="spu_lgn_tbl" >
                            <td style='text-align: right;'>Type of Work</td>
                                <td>
                                   <select name="type_of_work" id="type_of_work" style="width: 100%;">
                                   <?php 
                                        echo"<option>".$prev_type_of_work."</option>";
                                    ?>
                                       <option>Installation</option>
                                       <option>Inspection</option>
                                       <option>PPM / Service</option>
                                       <option>Breakdown / Repair</option>
                                       <option>Other</option>
                                   </select></td>
                               <td style='text-align: right;'>Section Required</td>
                                <td>
                                   <select name="section_required" id="section_required" style="width: 100%;">
                                    <?php 
                                        echo"<option>".$prev_section_required."</option>";
                                    ?>
                                       <option>Biomedical</option>
                                       <option>Electrical</option>
                                       <option>Mechanical</option>
                                       <option>Mason</option>
                                       <option>Plumber</option>
                                       <option>Carpentry</option>
                                       <option>Welding</option>
                                       <option>Painting</option>
                                   </select>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Assigned Engineer</td>
                                <td>
                                    <select name='assigned_engineer' class="Issuevalue" id='assigned_engineer' style="width:100%">
                                        <?php
                                        $Select_Employee = mysqli_query($conn,"select Employee_Name from tbl_employee WHERE Employee_Type='Engineer'");
                                      
                                        echo"<option><b>".$prev_assigned_engineer."</b></option>";
                                    
                                                    while ($row = mysqli_fetch_array($Select_Employee)) {
                                                        echo "<option name='assigned_engineer'>" . $row['Employee_Name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                               </td>
                                <td style='text-align: right;'>Assistant Engineer</td>
                                <td>
                                    <select name='assistance_engineer' class="Issuevalue" id='working_department' style="width:100%">
                                    
                                        <?php
                                        $Select_Employee = mysqli_query($conn,"select Employee_Name from tbl_employee WHERE Employee_Type='Engineer'");
                                        echo"<option>".$prev_assistance_engineer."</option>
                                        <option></option>";

                                                    while ($row = mysqli_fetch_array($Select_Employee)) {
                                                        echo "<option name='assistance_engineer'>" . $row['Employee_Name'] . "</option>";
                                        }
                                        ?>
                                    </select>
                               </td>
                            </tr>
                            <tr>
                                <td colspan=4 style='text-align: center;'>
                                     <input type='submit' name='submit_form' id='submit_form' value='   RE-ASSIGN ENGINEER   ' class='art-button-green'>
                                </td>
                            </tr>
                            
                               
</table>
                        </form>
                            </table>
                            </center>
<?php
                                      
// $update_requisition_for_engineering='';
 
 if(isset($_POST['submit_form'])){
        //$Requisition_ID=$_POST['Requisition_ID'];
        $assistance_engineer=$_POST['assistance_engineer'];
        $assigned_engineer=$_POST['assigned_engineer'];
        $section_required=$_POST['section_required'];
        $type_of_work=$_POST['type_of_work'];
        

     if(!empty($Requisition_ID)){

   $update_requisition_for_engineering="UPDATE tbl_engineering_requisition SET assigned_engineer='$assigned_engineer',assistance_engineer='$assistance_engineer',section_required='$section_required',reassigned_by='$Employee_ID', type_of_work='$type_of_work',requisition_status='assigned' WHERE requisition_ID='$Requisition_ID'";
      $save_result= mysqli_query($conn,$update_requisition_for_engineering);
      
     if ($save_result)
     {
         echo "<script>alert('Engineering requisition Re-Assigned successfully!');
         document.location = './reassign_job_list.php?section=Engineering_Works=Engineering_WorksThisPage'</script>";
    }
     else 
     {
         echo "<script>alert('Requisition Failed!')</script>";
     }
     }else{
         echo "FAILED";
     }
 }
 mysqli_close($conn);
?>
<script>
    $(document).ready(function (e){
        $("#assigned_engineer").select2();
        $("#working_department").select2();
    });
</script>
<br>
<?php
    include("./includes/footer.php");

