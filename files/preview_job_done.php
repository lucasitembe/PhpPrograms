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

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }

    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d H:m", strtotime($original_Date));
        $Today = $new_Date;
    }

$engineering
?>
        <a href='assigned_requisition_engineering.php?section=Engineering_Works=Engineering_WorksThisPage' class='art-button-green'>
            BACK
        </a>

        <!-- <style>
        
        
        .procure {
            display: none;
        }
        </style> -->

<br/><br/>
<center>
<table width='90%'>
    <tr>
      <td>
        <fieldset>
        <legend align=center><b>SELECTED REQUISITION</b></legend>
        <form action='' method='POST' name='' id='myForm' >
            <table width=100%>
                <?php
              
               //get details from tbl_enginnering_requisition
                                $get_details = mysqli_query($conn,"select * FROM tbl_engineering_requisition
											where Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
                                $no = mysqli_num_rows($get_details);
                                if ($no > 0) {
                                    //$Process_Status = 'processed';
                                    while ($data2 = mysqli_fetch_array($get_details)) {
                                        //$requisition_ID = $data2['requisition_ID'];
                                        $Department_ID = $data2['select_dept'];
                                        $employee = $data2['employee_name'];
                                        $title = $data2['title'];
                                        $floor = $data2['floor'];
                                        $requisition_date = $data2['date_of_requisition'];
                                        $equipment_name = $data2['equipment_name'];
                                        $equipment_ID = $data2['equipment_ID'];
                                        $equipment_serial=$data2['equipment_serial'];
                                        $equipment_code=$data2['equipment_code'];
                                        $description_works_to_done = $data2['description_works_to_done'];
                                        $assigned_engineer = $data2['assigned_engineer'];
                                        $assistance_engineer = $data2['assistance_engineer'];
                                        $type_of_work = $data2['type_of_work'];
                                        $section_required = $data2['section_required'];
                                        $job_notes=$data2['job_notes'];
                                        $spare_required=$data2['spare_required'];
                                        $part_date=$data2['part_date'];
                                        $procurerer=$data2['procurerer'];
                                        $issue_date=$data2['issue_date'];
                                        $issuer=$data2['issuer'];
                                        $engineers=$data2['engineers'];
                                        $procurement_order=$data2['procurement_order'];
                                        $form_id=$data2['form_id'];
                                        $client_info=$data2['client_info'];
                                        $visual_test=$data2['visual_test'];
                                        $electrical_test=$data2['electrical_test'];
                                        $functional_test=$data2['functional_test'];
                                        $engineer_sign=$data2['engineer_sign'];
                                        $final_status=$data2['final_status'];
                                        $recommendations=$data2['recommendations'];
                                }}
                                    ?>
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
                    //echo "<input type='text' readonly='readonly'  name='department_requesting' value=''>";
                            $Sub_Department = Get_Sub_Department($Department_ID);
                                echo "<input type='text' readonly='readonly' hidden='hidden' name='department_requesting' value='{$Sub_Department}'>";
                                echo "<input type='text' readonly='readonly'  name='department_requesting' value='{$Sub_Department_Name}'>";
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
                 <td style='text-align: right; width:15%;'>Employee Name</td>
                <td style='text-align: right; width:15%;'>
                    <?php
                        echo "<input type='text'  hidden='hidden' name='reporter' value='{$Employee_ID}'>";
                        echo "<input type='text'  readonly='readonly'  name='employee' value='{$Employee_Name}'>";
                    
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
                            <tr>
                            <td style='text-align: right;'>Type of Work</td>
                            <td colspan="2">
                                   <?php
                                    echo "<input type='text' readonly='readonly' value='$type_of_work'"
                                   ?></td>
                               <td style='text-align: right;'>Section Required</td>
                               <td colspan="2">
                               <?php
                                   echo "<input type='text' readonly='readonly'  name='section_required' value='$section_required'>";                    
                     ?>
                        </td>
                                </td>
                            </tr>
                            <tr>
                                <td style='text-align: right;'>Assigned Engineer</td>
                                <td colspan="2">
                                    <?php
                                        echo "<input type='text' readonly='readonly'  name='section_required' value='$assigned_engineer'>";                    
                                    ?>                                  
                               </td>
                                <td style='text-align: right;'>Assistant Engineer</td>
                                <td colspan="2">
                                <?php
                                        echo "<input type='text' readonly='readonly'  name='section_required' value='$assistance_engineer'>";                    
                                    ?>
                               </td>
                            </tr>
                            <tr>
                                <td colspan="6"><legend align=center style='text-align: center;'><b>MAINTENANCE & PROCUREMENT</b></legend></>
                            </tr>
                            <tr>
                            <td style='text-align: right; width:15%;'> Job Notes </td>
                            <td width="100%" height="20%" colspan='5'>
							    <textarea> <?php echo $job_notes ?> </textarea>
							
                    </td>
                            </tr>
                            <tr>
                                <td colspan="6"><legend align=center style='text-align: center;'><b>SPARE PARTS AND PROCUREMENT TRACKER</b></legend></>
                            </tr>
                            <tr>
                                <td colspan="2" style='text-align: center;'>
                                                <input type='checkbox' name='spare_required' id='spare_required' value='yes' <?php
                                        if (strtolower($spare_required) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='spare_required'>Spare Required</label>
                                            </td>
                                            <td colspan="2" style='text-align: center;'>
                                                <input type='checkbox' name='procurement_order' id='procurement_order' value='yes'  readonly="readonly" <?php
                                                 if (strtolower($procurement_order) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='procurement_order'>Procurement Order Made</label><br/>
                                                <div class="procure">
                                                <label for='procurement_order'>FORM ID:</label>
                                                <?php echo "<input type='text' name='form_id' id='form_id' value='$form_id'>"?>
                                                </div>
                                                
                                                </td>
                                            <td colspan="2" style='text-align: center;'>
                                                <input type='checkbox' name='client_info' id='client_info' value='yes' <?php
                                        if (strtolower($client_info) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='client_info'>Client Informed</label>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style='text-align: right;'>Date Part Requested</td>
                <td>
                    <?php
                            echo "<input type='text'  readonly='readonly' name='part_date' id='part_date' value='$part_date'>";
                    ?>
                </td>
                <td style='text-align: right;'>Procurement Officer:</td>
                <td>
                    <?php
                            echo "<input type='datetime'   readonly='readonly' name='procurerer' id='procurerer' value='$procurerer'>";
                    ?>
                </td>
                <td style='text-align: right;'>Engineer Signature: </td>
                <td>
                    <?php
                            echo "<input type='datetime'  readonly='readonly' name='engineer_sign' id='engineer' value='$engineer_sign'>";
                    ?>
                </td>
                                        </tr>
                         <tr>
                                            <td style='text-align: right;'>Date Issued</td>
                <td>
                    <?php
                            echo "<input type='date' name='issue_date' id='part_date' placeholder='Select Date' value='{$requisition_date}'>";
                    ?>
                </td>
                <td style='text-align: right;'>Procurement Officer:</td>
                <td>
                    <?php
                            echo "<input type='datetime'  name='issuer' id='procurerer'>";
                    ?>
                </td>
                <td style='text-align: right;'>Engineer Signature: </td>
                <td>
                    <?php
                            echo "<input type='datetime' name='engineers' id='engineer'>";
                    ?>
                </td>
                                        </tr>
                                        <tr>
                                        <td colspan="6"><legend align=center style='text-align: center;'><b>SAFETY TESTING</b></legend></>
                            </tr>
                            <tr>
                                <td colspan="2" style='text-align: center;'>
                                                <input type='checkbox' name='visual_test' id='visual_test' value='yes' 
                                                <?php
                                        if (strtolower($visual_test) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='spare_required'>Visual Inspection Completed</label>
                                            </td>
                                            <td colspan="2" style='text-align: center;'>
                                                <input type='checkbox' name='electrical_test' id='electrical_test' value='yes' 
                                                <?php
                                        if (strtolower($electrical_test) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='procurement_order'>Electrical Safety Test Completed</label><br/>
                                            <td colspan="2" style='text-align: center;'>
                                                <input type='checkbox' name='functional_test' id='functional_test' value='yes' <?php
                                        if (strtolower($functional_test ) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='client_info'>Functional Test Completed</label>
                                            </td>
                                        </tr>
                            <tr>
                                        <td colspan="6"><legend align=center style='text-align: center;'><b>FINAL STATUS & JOB CLOSURE</b></legend></>
                            </tr>
                            <tr>
                                <td colspan='6'><center>
                                    <label>Job Final Status:  <?php echo "$final_status"?></b> &nbsp;&nbsp;</center> 
                                </td>
                            </tr>
                            <tr>
                            <td style='text-align: right; width:15%;'> Recommendations </td>
                            <td width="100%" height="20%" colspan='5'>
							<textarea name='recommendations'> </textarea>
							
                    </td>
                            </tr>
                            <tr>
                                <td colspan='6'><center>
                                <input type='checkbox' name='completed' id='functional_test' value='completed'>
                                <label for='complete'>I &nbsp;&nbsp;<b><?php echo "$assigned_engineer"?></b> &nbsp;&nbsp;Completed the work assigned to me successfully.</center>
                                </td>
                            </tr>
                            <tr>
                                        <td colspan="6"><legend align=center style='text-align: center;'><b>STAFF FEEDBACK</b></legend></>
                            </tr>
                            <tr>
                                <td colspan='6'><center>
							    <label> Staff/User Satisfaction Score:  <b><?php echo "$satisfy"?></b>
                            </tr>
                           
</table>
                        </form>
                            </table>
                            </center>

<!-- <script>
var checkbox = document.getElementById('procurement_order');
checkbox.addEventListener('change',function(){
    var procure = document.querySelector('.procure');
    if(this.checked){
        procure.style.display='block';
    }else{
        procure.style.display='none';
    }
})
</script> -->

<?php
                                      
// $update_requisition_for_engineering='';
 
//  if(isset($_POST['submit_form'])){
//         //$Requisition_ID=$_POST['Requisition_ID'];
//         $job_notes=$_POST['job_notes'];
//         $final_status=$_POST['final_status'];
//         $issue_date=$_POST['issue_date'];
//         $issuer=$_POST['issuer'];
//         $engineers=$_POST['engineers'];
//         $visual_test=$_POST['visual_test'];
//         $electrical_test=$_POST['electrical_test'];
//         $functional_test=$_POST['functional_test'];
//         $completed=$_POST['completed'];
//         $recommendations=$_POST['recommendations'];
//         $final_status=$_POST['final_status'];

        
//      if(!empty($Requisition_ID)){
//         //  if(!empty($completed)){
//             $update_requisition_for_engineering = "UPDATE tbl_engineering_requisition SET job_notes='$job_notes',issue_date='$issue_date',issuer='$issuer',engineers='$engineers',visual_test='$visual_test',electrical_test='$electrical_test',functional_test='$functional_test',recommendations='$recommendations',completed='$completed',final_status='$final_status' WHERE requisition_ID='$Requisition_ID'";

//       $save_result= mysqli_query($conn,$update_requisition_for_engineering);
//     //      }else{
//     //         $update_requisition_for_engineering="UPDATE tbl_engineering_requisition SET job_notes='$job_notes',spare_required='$spare_required',part_date='$part_date',procurerer='$procurerer',issue_date='$issue_date',issuer='$issuer',engineers='$engineers',procurement_order='$procurement_order',form_id='$form_id',client_info='$client_info',visual_test='$visual_test',electrical_test='$electrical_test',functionl_test',engineer_sign='$engineer_sign',recommendations='$recommendations' WHERE requisition_ID='$Requisition_Ial_test='$functionaD'";
//     // $save_result= mysqli_query($conn,$update_requisition_for_engineering);
        
      
//      if ($save_result)
//      {
//         echo "<script>
//         alert('Engineering Information was saved successfully!');
//         document.location = './completed_engineering_requisition.php?section=Engineering_Works=Engineering_WorksThisPage';
//         </script>";
//      }
//      else 
//      {
//          echo "<script>alert('Requisition Failed!')</script>";
//      }
//      }else{
//          echo "FAILED";
//      }
//  }
//  mysqli_close($conn);
?>
<br>
<?php
    include("./includes/footer.php");

