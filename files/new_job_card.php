<script src='js/functions.js'></script>
<script type="text/javascript" language="javascript">
    function getItemList(Item_Category_Name) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        //getPrice();
        var ItemListType = document.getElementById('Type').value;
        getItemListType(ItemListType);
        document.getElementById('BalanceNeeded').value = '';
        document.getElementById('BalanceStoreIssued').value = '';
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemList.php?Item_Category_Name=' + Item_Category_Name, true);
        mm.send();
    }
    function AJAXP() {
        var data1 = mm.responseText;
        document.getElementById('Item_Name').innerHTML = data1;
    }
</script>

<script type="text/javascript" language="javascript">
    function getItemListType(Type) {
        var Item_Category_Name = document.getElementById('Item_Category').value;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        //   //getPrice();
        document.getElementById('BalanceNeeded').value = 'a';
        document.getElementById('BalanceStoreIssued').value = 'v';
        mm.onreadystatechange = AJAXP2; //specify name of function that will handle server response....
        mm.open('GET', 'GetItemListType.php?Item_Category_Name=' + Item_Category_Name + '&Type=' + Type, true);
        mm.send();
    }
    function AJAXP2() {
        var data2 = mm.responseText;
        document.getElementById('Item_Name').innerHTML = data2;
    }
</script>
<style type="text/css">
    /* .labefor{display:block;width: 100% }
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%;  
    }
    */
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
    include("./get_item_balance_for_particular_subdepartment.php");
    // include("./storeordering_navigation.php");
    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if(isset($_SESSION['userinfo'])) {
        if(isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
            if($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes'){
                header("Location: ./index.php?InvalidPrivilege=yes");
            }else{
                @session_start();
                if(!isset($_SESSION['Storage_Supervisor'])){
                    header("Location: ./engineeringsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
                }
            }
        }else{
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    }else{
         @session_destroy(); header("Location: ../index.php?InvalidPrivilege=yes");
         }

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

    if (isset($_SESSION['Storage_Info'])) {
        $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
        $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
    }
    
//get sub department name
if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    //exit($Sub_Department_ID);
    $select = mysqli_query($conn,"SELECT Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
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

    $Today_Date = mysqli_query($conn,"SELECT now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d H:m", strtotime($original_Date));
        $Today = $new_Date;
    }

?>
        <a href='assigned_requisition_engineering.php?section=Engineering_Works=Engineering_WorksThisPage' class='art-button-green'>
            BACK
        </a>

        <style>
        
        
        .procure {
            display: none;
        }
        </style>

<br/><br/>
<center>
<table width='90%'>
    <tr>
      <td>
        <fieldset>
        <legend align=center><b>ENGINEERING JOB CARD UNDER - <span style='color: yellow; text-transform: uppercase;'><?php echo $Sub_Department_Name ?></span></b></legend>
        <form action='' method='POST' name='' id='myForm' >
            <table   id="spu_lgn_tbl" width=100%>
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

                                        $department = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Department_Name FROM tbl_department WHERE Department_ID='$Department_ID'"))['Department_Name'];

                                        $requested = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name from tbl_employee where Employee_ID = '$employee'"))['Employee_Name'];
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
                            echo "<input type='text' readonly='readonly' name='date_of_requisition' id='Transaction_Date' value='{$department}'>"
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
                 <td style='text-align: right; width:15%;'>Received From:</td>
                <td style='text-align: right; width:15%;'>
                    <?php
                        echo "<input type='text' readonly='readonly' name='date_of_requisition' id='Transaction_Date' value='{$requested}'>"
                    
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
                    <td style='text-align: right; width:15%;'>Equipment Serial Number</td>
                        <td colspan='2'>
                        <?php
                                   echo "<input type='text' readonly='readonly'  name='equipment_Serial' value='$equipment_serial'>";                    
                     ?>
                        </td>
                    <td style='text-align: right; width:15%;'>Equipment ID Number</td>
                        <td colspan='2'>
                        <?php
                            echo "<input type='text' readonly='readonly'  name='equipment_ID' value='$equipment_ID'>";                    
                     ?>
                        </td>
                    
                    
                    
                        </select>
                    </td>
                </tr>
                <tr>
                    <td style='text-align: right; width:15%;'><b>Complaints / Faults:</b></td>
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
                                        echo "<input type='text' readonly='readonly'  name='assigned_engineer' value='$assigned_engineer'>";                    
                                    ?>                                  
                               </td>
                                <td style='text-align: right;'>Assistant Engineer</td>
                                <td colspan="2">
                                <?php
                                        echo "<input type='text' readonly='readonly'  name='assistance' value='$assistance_engineer'>";                    
                                    ?>
                               </td>
                            </tr>
                            <tr>
                            <td style='text-align: right; width:15%;'><b> Defects: </b></td>
                            <td width="100%" height="20%" colspan='5'>
							<textarea name='defects' required> </textarea>
							
                    </td>
                            </tr>
                            <tr>
                            <td style='text-align: right; width:15%;'><b> Diagnosis/Action: </b></td>
                            <td width="100%" height="20%" colspan='5'>
							<textarea name='diagnosis_action' required> </textarea>
							
                    </td>
                            </tr>
                            <tr>
                                <td rowspan="3" style='text-align: right;'>
                                                <label for=''>Reason of Failure*</label>
                                            </td>
                                            <td colspan="2" style='text-align: left;'>
                                                <input type='checkbox' name='wear_tear' id='wear_tear' value='yes'>
                                                <label for='wear_tear'>Wear & Tear</label><br/>
                                            <td colspan="2" style='text-align: left;'>
                                                <input type='checkbox' name='rough_handling' id='rough_handling' value='yes'>
                                                <label for='rough_handling'>Rough Handling</label>
                                            </td>
                                            <td colspan="2" style='text-align: left;'>
                                                <input type='checkbox' name='poor_installation' id='poor_installation' value='yes'>
                                                <label for='poor_installation'>Poor Installation</label>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td colspan="2" style='text-align: left;'>
                                                <input type='checkbox' name='water_penetration' id='water_penetration' value='yes'>
                                                <label for='water_penetration'>Water Penetration</label><br/>
                                            <td colspan="2" style='text-align: left;'>
                                                <input type='checkbox' name='mains_unstable' id='mains_unstable' value='yes'>
                                                <label for='mains_unstable'>Mains Unstable</label>
                                            </td>
                                            <td colspan="2" style='text-align: left;'>
                                                <input type='checkbox' name='dirty' id='dirty' value='yes'>
                                                <label for='dirty'>Dirty</label>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td colspan="2" style='text-align: left;'>
                                                <input type='checkbox' name='wind_storm' id='wind_storm' value='yes'>
                                                <label for='wind_storm'>Wind & Storm</label><br/>
                                            <td colspan="2" style='text-align: left;'>
                                                <input type='checkbox' name='Others' id='Others' value='yes'>
                                                <label for='Others'>Others</label>
                                            </td>
                        
                                        </tr> 
                                        <tr>
                                            <td style='text-align: right;'>Date Part Requested</td>
                <td>
                    <?php
                            echo "<input type='text' readonly='readonly' name='part_date' id='Transaction_Date' value='".$Today."'>";
                    ?>
                </td>
                <td style='text-align: right;'>Requesting Engineer </td>
                <td>
                    <?php
                            echo "<input type='datetime' readonly='readonly' width='100%' name='requesting_engineer' id='engineer' value='$Employee_Name'>";
                    ?>
                </td>
                <td style='text-align: right;'>Job Certified by </td>
                <td>            
                                <input type='text' Placeholder='Job Certifier' readonly='readonly'></td>
                                <tr>
                                            <td style='text-align: right; color: red; font-weight: bold;'> JobCard Description </td>
                                            <td width="100%" height="20%" colspan='5'>
                                            <textarea name='descriptions'> </textarea>
                                            
                                            </td>
                            </tr>
                            <tr>
                                            <td style='text-align: right; width:15%;'> Comments </td>
                                            <td width="100%" height="20%" colspan='5'>
                                            <textarea name='Comments'> </textarea>
                                            
                                            </td>
                            </tr>

                            <tr>
                                <td style='text-align: right; color: red; font-weight: bold;'>**** Labor Charges </td>
                                <td>            
                                <input type='number' id='labor_charge' name='labor_charge' Placeholder='Labor Charges' style='text-align: right;' onkeyup='total_this()'></td>
                                <td style='text-align: right; color: red; font-weight: bold;'>**** Quantity </td>
                                <td>            
                                <input type='text' id='labor_charge_quantity' name='labor_charge_quantity' Placeholder='Quantity' onkeyup='total_this()'></td>
                                <td style='text-align: right; color: red; font-weight: bold;'>**** Total Labor Charge </td>
                                <td>            
                                <input type='text' id='total_labor_charge' name='total_labor_charge' Placeholder='Total Labor Charge' readonly='readonly' style='text-align: right;'></td>
                            </tr>
                            <tr>
                                <td style='text-align: right; color: red; font-weight: bold;'>**** Fabrication </td>
                                <td>            
                                <input type='number' id='fabrication_charge' name='fabrication_charge' Placeholder='Fabrication Cost' style='text-align: right;' onkeyup='total_this_fabrication()'></td>
                                <td style='text-align: right; color: red; font-weight: bold;'>**** Quantity </td>
                                <td>            
                                <input type='text' id='fabrication_charge_quantity' name='fabrication_charge_quantity' Placeholder='Quantity' onkeyup='total_this_fabrication()'></td>
                                <td style='text-align: right; color: red; font-weight: bold;'>**** Total Fabrication Cost</td>
                                <td>            
                                <input type='text' id='total_lfabrication_charge' name='total_lfabrication_charge' Placeholder='Total Fabrication Cost' readonly='readonly' style='text-align: right;'></td>
                            </tr>
                            <tr>
                                <td colspan='6' style='text-align: center;'>
                                     <input type='submit' name='submit_form' id='submit_form' value='   SAVE INFORMATIONS   ' class='art-button-green'>
                                </td>
                            </tr>  
</table
</fieldet>
</center>
</table>
<script>
    function total_this() {
           var labor_charge = document.getElementById('labor_charge').value;
           var labor_charge_quantity = document.getElementById('labor_charge_quantity').value;
           var result = parseInt(labor_charge) * parseInt(labor_charge_quantity);
           if (!isNaN(result)) {
               document.getElementById('total_labor_charge').value = result;
           }
       }

       function total_this_fabrication() {
           var fabrication_charge = document.getElementById('fabrication_charge').value;
           var fabrication_charge_quantity = document.getElementById('fabrication_charge_quantity').value;
           var results = parseInt(fabrication_charge) * parseInt(fabrication_charge_quantity);
           if (!isNaN(results)) {
               document.getElementById('total_lfabrication_charge').value = results;
           }
       }
</script>

<?php
$update_requisition_for_engineering='';


 
if(isset($_POST['submit_form'])){
        $jobcard_ID=$_POST[''];
       $Requisition_ID=$_POST['Requisition_ID'];
       $department_requesting=$_POST['department_requesting'];
       $defects=$_POST['defects'];
       $part_date=$_POST['part_date'];
       $requesting_engineer=$_POST['requesting_engineer'];
       $diagnosis_action=$_POST['diagnosis_action'];
       $poor_installation=$_POST['poor_installation'];
       $water_penetration=$_POST['water_penetration'];
       $rough_handling=$_POST['rough_handling'];
       $wind_storm=$_POST['wind_storm'];
       $mains_unstable=$_POST['mains_unstable'];
       $employee=$_POST['employee'];
       $dirty=$_POST['dirty'];
       $Comments=$_POST['Comments'];
       $engineers=$_POST['engineers'];
       $safety=$_POST['safety'];
       $function=$_POST['function'];
       $job_certified=$_POST['job_certified'];
       $Others=$_POST['Others'];
       $assistance=$_POST['assistance'];
       $wear_tear=$_POST['wear_tear'];
       $labor_charge=$_POST['labor_charge'];
       $labor_charge_quantity=$_POST['labor_charge_quantity'];
       $Current_Store_ID=$_POST[''];
       $Labor_Charge_description=$_POST['Labor_Charge_description'];
       $descriptions = $_POST['descriptions'];
       $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
       $fabrication_charge = $_POST['fabrication_charge'];
       $fabrication_charge_quantity = $_POST['fabrication_charge_quantity'];

    
    if(!empty($Requisition_ID)){
       //  if(!empty($completed)){
           $update_requisition_for_engineering = "INSERT INTO tbl_jobcards (Requisition_ID,department_requesting,defects,part_date,requesting_engineer,created_by,diagnosis_action,poor_installation,water_penetration,rough_handling,wind_storm,mains_unstable,employee,dirty,Comments,engineers,`safety`,`function`,job_certified,Others,assistance,wear_tear,Sub_Department_ID, Labor_Charge, Quantity,Labor_Charge_description,descriptions, fabrication_charge_quantity, fabrication_charge) VALUES ('$Requisition_ID','$department_requesting','$defects',NOW(),'$Employee_Name','$Employee_ID','$diagnosis_action','$poor_installation','$water_penetration','$rough_handling','$wind_storm','$mains_unstable','$employee','$dirty','$Comments','$engineers','$safety','$function','$job_certified','$Others','$assistance','$wear_tear','$Sub_Department_ID', '$labor_charge', '$labor_charge_quantity','$Labor_Charge_description','$descriptions', '$fabrication_charge_quantity', '$fabrication_charge')";

     $save_result= mysqli_query($conn,$update_requisition_for_engineering) or die(mysqli_error($conn));

     
    if ($save_result)
    {  
        $last_Inserted_ID = mysqli_insert_id($conn);
       header("location:./jobcard_items.php?jobcard_ID=$last_Inserted_ID&status=new&NPO=True&StoreOrder=StoreOrderThisPage");
   }
    else 
    {
        echo "<script>alert('Job Card Creation Process Failed!')</script>";
    }
    }else{
        echo "FAILED";
    }
}
mysqli_close($conn);
?>

<?php
    include("./includes/footer.php");
?>