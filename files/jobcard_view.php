<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    include("./get_item_balance_for_particular_subdepartment.php");

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

//     if (isset($_SESSION['Storage_Info'])) {
//         $Current_Store_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
//         $Current_Store_Name = $_SESSION['Storage_Info']['Sub_Department_Name'];
//     }
    
// //get sub department name
// if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
//     $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
//     //exit($Sub_Department_ID);
//     $select = mysqli_query($conn,"select Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
//     $no = mysqli_num_rows($select);
//     if ($no > 0) {
//         $row = mysqli_fetch_assoc($select);
//         $Sub_Department_Name = $row['Sub_Department_Name'];
//     } else {
//         $Sub_Department_Name = '';
//     }
// }


    

    if (!isset($_SESSION['userinfo'])) {
        session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }


    if (isset($_GET['jobcard_ID'])) {
        $jobcard_ID = $_GET['jobcard_ID'];
    }



    $Today_Date = mysqli_query($conn,"select now() as today");
    while ($row = mysqli_fetch_array($Today_Date)) {
        $original_Date = $row['today'];
        $new_Date = date("Y-m-d H:m", strtotime($original_Date));
        $Today = $new_Date;
    }
?>
<script src='js/functions.js'></script>
<script type='text/javascript'>
    function access_Denied(){ 
   alert("Access Denied! Only Assigned Personnel Allowed!");
    }
</script>
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
        .rows_list{ 
                        cursor: pointer;
                    }
                    .rows_list:active{
                        color: #328CAF!important;
                        font-weight:normal!important;
                    }
                    .rows_list:hover{
                        color:#00416a;
                        background: #dedede;
                        font-weight:bold;
                    }
                    a{
                        text-decoration: none;
                    }
                    
                input[type="checkbox"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }

                input[type="radio"]{
                    width: 25px; 
                    height: 25px;
                    cursor: pointer;
                    margin: 5px;
                    margin-right: 5px;
                }
                #th{
                    background:#99cad1;
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
            .row th{
        background: grey;
    }

    .row tr th{
        border: 1px solid #fff;
    }
    .row tr:nth-child(even){
        background-color: #eee;
    }
    .row tr:nth-child(odd){
        background-color: #fff;

    }
        .procure {
            display: none;
        }

        .spare {
            display: block;
            border: 1px white black;
        }

        .spare table tr th {
            background: gray;
            border: 1px solid #fff;
        }
        .spare table tr:nth-child(even){
            background-color: #eee;
        }
        .spare table tr:nth-child(odd){
            background-color: #fff;
        }

</style>
<input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">


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
        <legend align=center><b>ENGINEERING JOB CARD No: <?php echo $jobcard_ID?></b></legend>
        <form action='' method='POST' name='' id='myForm' >
            <table   id="spu_lgn_tbl" width=100%>
                <?php
              
               //get details from tbl_enginnering_requisition
                                                       
                                $got_details = mysqli_query($conn, "SELECT Requisition_ID, Labor_Charge, Quantity, fabrication_charge, fabrication_charge_quantity, department_requesting, defects, part_date, requesting_engineer, diagnosis_action, poor_installation, water_penetration, rough_handling, wind_storm, mains_unstable, employee, dirty, Comments, engineers, `safety`, `function`, Others, assistance, wear_tear,  Certified_by FROM tbl_jobcards WHERE jobcard_ID = '$jobcard_ID'") or die(mysqli_error($conn));

                                $sql = mysqli_num_rows($got_details);
                                if ($sql > 0) {
                                    while ($data = mysqli_fetch_array($got_details)){
                                   $Requisition_ID=$data['Requisition_ID'];
                                   $department_requesting=$data['department_requesting'];
                                   $defects=$data['defects'];
                                   $part_date=$data['part_date'];
                                   $requesting_engineer=$data['requesting_engineer'];
                                   $diagnosis_action=$data['diagnosis_action'];
                                   $poor_installation=$data['poor_installation'];
                                   $water_penetration=$data['water_penetration'];
                                   $rough_handling=$data['rough_handling'];
                                   $wind_storm=$data['wind_storm'];
                                   $mains_unstable=$data['mains_unstable'];
                                   $employee=$data['employee'];
                                   $dirty=$data['dirty'];
                                   $Comments=$data['Comments'];
                                   $engineers=$data['engineers'];
                                   $safety=$data['safety'];
                                   $function=$data['function'];
                                   $assistance_engineer=$data['assistance_engineer'];
                                   $Others=$data['Others'];
                                   $assistance=$data['assistance'];
                                   $assigned=$data['assigned'];
                                   $assistance=$data['assistance'];
                                   $engineer_sign=$data['engineer_sign'];
                                   $wear_tear=$data['wear_tear'];
                                    $Certified_by =$data['Certified_by'];
                                    $Labor_Charge = $data['Labor_Charge'];
                                    $LQuantity = $data['Quantity'];
                                    $fabrication_charge = $data['fabrication_charge'];
                                    $fabrication_charge_quantity = $data['fabrication_charge_quantity'];
                                    }
                                }else{
                                    $Requisition_ID = '';
                                   $department_requesting = '';
                                   $defects = '';
                                   $part_date = '';
                                   $requesting_engineer = '';
                                   $diagnosis_action = '';
                                   $poor_installation = '';
                                   $water_penetration = '';
                                   $rough_handling = '';
                                   $wind_storm = '';
                                   $mains_unstable = '';
                                   $employee = '';
                                   $dirty = '';
                                   $Comments = '';
                                   $engineers = '';
                                   $safety = '';
                                   $function = '';
                                   $assistance_engineer = '';
                                   $Others = '';
                                   $assistance = '';
                                   $assigned = '';
                                   $assistance = '';
                                   $engineer_sign = '';
                                   $wear_tear = '';
                                   $Store_Order_ID = '';
                                    $Certified_by  = '';
                                    $LQuantity = '';
                                    $Labor_Charge = '';
                                    $fabrication_charge_quantity='';
                                    $fabrication_charge='';
                                }
                                $get_details = mysqli_query($conn,"SELECT select_dept, employee_name, title, floor, date_of_requisition, equipment_name, equipment_ID, equipment_serial, equipment_code, description_works_to_done, assigned_engineer, type_of_work, section_required FROM tbl_engineering_requisition
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
                                        $type_of_work = $data2['type_of_work'];
                                        $section_required = $data2['section_required'];

                                        $requesting_employee = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name from tbl_employee where Employee_ID='$employee'"))['Employee_Name'];
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
                                echo "<input type='text' readonly='readonly'  name='department_requesting' value='{$department_requesting}'>";
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
                        echo "<input type='text' name='reporter' value='{$requesting_employee}'>";
                    
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
                                <td style='text-align: right;'>Assigned Staff</td>
                                <td colspan="2">
                                    <?php
                                        echo "<input type='text' readonly='readonly'  name='assigned_engineer' value='$assigned_engineer'>";                    
                                    ?>                                  
                               </td>
                                <td style='text-align: right;'>Assistant Staff</td>
                                <td colspan="2">
                                <?php
                                        echo "<input type='text' readonly='readonly'  name='assistance' value='$assistance_engineer'>";                    
                                    ?>
                               </td>
                            </tr>
                            <tr>
                            <td style='text-align: right; width:15%;'><b> Defects: </b></td>
                            <td width="100%" height="20%" colspan='5'>
							<textarea readonly="readonly"> <?php echo $defects ?> </textarea>
							
                    </td>
                            </tr>
                            <tr>
                            <td style='text-align: right; width:15%;'><b> Diagnosis/Action: </b></td>
                            <td width="100%" height="20%" colspan='5'>
							<textarea name='diagnosis_action' readonly='readonly'><?php echo $diagnosis_action?> </textarea>
							
                    </td>
                            </tr>
                            <tr>
                                <td rowspan="3" style='text-align: right;'>
                                                <label for=''>Reason of Failure*</label>
                                            </td>
                                            <td colspan="2" style='text-align: left;'>
                                            <input type='checkbox' name='wear_tear' id='wear_tear' value='yes' 
                                                <?php
                                        if (strtolower($wear_tear) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='wear_tear'>Wear & Tear</label><br/>
                                            <td colspan="2" style='text-align: left;'>
                                            <input type='checkbox' name='rough_handling' id='rough_handling' value='yes' 
                                                <?php
                                        if (strtolower($rough_handling) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='rough_handling'>Rough Handling</label>
                                            </td>
                                            <td colspan="2" style='text-align: left;'>
                                            <input type='checkbox' name='poor_installation' id='poor_installation' value='yes' 
                                                <?php
                                        if (strtolower($poor_installation) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='poor_installation'>Poor Installation</label>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td colspan="2" style='text-align: left;'>
                                        <input type='checkbox' name='water_penetration' id='water_penetration' value='yes' 
                                                <?php
                                        if (strtolower($water_penetration) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='water_penetration'>Water Penetration</label><br/>
                                            <td colspan="2" style='text-align: left;'>
                                            <input type='checkbox' name='mains_unstable' id='mains_unstable' value='yes' 
                                                <?php
                                        if (strtolower($mains_unstable) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='mains_unstable'>Mains Unstable</label>
                                            </td>
                                            <td colspan="2" style='text-align: left;'>
                                            <input type='checkbox' name='dirty' id='dirty' value='yes' 
                                                <?php
                                        if (strtolower($dirty) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='dirty'>Dirty</label>
                                            </td>
                                        </tr>
                                        <tr>
                                        <td colspan="2" style='text-align: left;'>
                                        <input type='checkbox' name='wind_storm' id='wind_storm' value='yes' 
                                                <?php
                                        if (strtolower($wind_storm) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
                                                <label for='wind_storm'>Wind & Storm</label><br/>
                                            <td colspan="2" style='text-align: left;'>
                                            <input type='checkbox' name='Others' id='Others' value='yes' 
                                                <?php
                                        if (strtolower($Others) == 'yes') {
                                            echo "checked='checked'";
                                        }?>>
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
                <td style='text-align: right;'>Requesting Staff: </td>
                <td>
                    <?php
                            echo "<input type='datetime' readonly='readonly' width='100%' name='requesting_engineer' id='engineer' value='$requesting_engineer'>";
                    ?>
                </td>
                <td style='text-align: right;'> </td>
                <td>
                <?php
                    echo "<input type='text' readonly='readonly' width='100%' hidden name='job_certified' value='$assistance_engineer'>"
                ?>
                </td>
                                        </tr>
                                        <tr>
                            <td style='text-align: right; width:15%;'> Engineer's Comments: </td>
                            <td width="100%" height="20%" colspan='5'>
							<textarea readonly='readonly'> <?php echo $Comments ?> </textarea>
							
                    </td>
                            </tr>
                                        <tr >
                    <td width="100%" colspan='6'><legend><center>JOB CARD ITEMS</center></legend>
                    <div class="spare" style="height: 350px;overflow-y: scroll;overflow-x: hidden">
                    <form action='ajax_engineering_info.php' method='POST'>
                    <input type="text"  name="jobcard_ID" value="<?php echo $jobcard_ID?>" hidden>
                    <input type="text"  name="Registration_ID" value="<?php echo $Requisition_ID?>" hidden>
                    <!-- <button type="button" name="add_item" class="btn btn-primary" onclick="mantainance_drugs()" >Add Items  </button> -->
                        <table class="table" id='spare_list'>
                            <tr>
                                <th width="2%">S/N</th>
                                <th>ITEM(s) ORDERED</th>
                                <th width="7%">UOM</th>
                                <th width="7%">ITEM CODE</th>
                                <th width="5%">QUANTITY</th>
                                <th width="10%">PRICE</th>
                                <th width="10%">TOTAL</th>
                            </tr>
                            <?php 
                                $spare_used = mysqli_query($conn,"SELECT uk.Sub_Department_ID, ap.Employee_ID, ap.jobcard_ID, Product_Name, ap.Quantity, ap.Price, ap.Item_ID, i.Unit_Of_Measure, i.Product_Code, ap.created_at FROM  tbl_jobcards uk, tbl_jobcard_orders ap, tbl_items i WHERE i.Item_ID=ap.Item_ID AND ap.jobcard_ID='$jobcard_ID' AND uk.jobcard_ID='$jobcard_ID' ORDER BY jobcard_ID DESC  ") or die(mysqli_error($conn));

                                
                                $Total_Price=0;
                                $Grand_total=0;
                                $num=1;
                                $no = mysqli_num_rows($spare_used);
                                if($no > 0){
                                    while($row = mysqli_fetch_assoc($spare_used)){
                                        $jobcard_ID = $row['jobcard_ID'];
                                        $Sub_Department_ID = $row['Sub_Department_ID'];
                                        $Item_Balance = $row['Item_Balance'];
                                        $Item_ID = $row['Item_ID'];
                                        $Jobcard_Order_ID = $row['Jobcard_Order_ID'];
                                        $Employee_ID  = $row['Employee_ID'];
                                        $jobcard_ID = $row['jobcard_ID'];
                                        $Product_Name = $row['Product_Name'];
                                        $Quantity = $row['Quantity'];
                                        $created_at = $row['created_at'];
                                        $Price = $row['Price'];
                                        $Unit_Of_Measure = $row['Unit_Of_Measure'];
                                        $Product_Code = $row['Product_Code'];
                                        $no = mysqli_num_rows($select);


                                        $subtotal = $Quantity * $Price;
                                        $Sub_Total = $Sub_Total + $subtotal;

                                        // if($no > 0){
                                        //     while($row = mysqli_fetch_array($select)){
                                        //         $total = $total + ($row['Price'] - 0) * $Quantity);
                                        //     }
                                        // }
                                        

                                        echo "<tr><td>$num</td>
                                        <td>$Product_Name</td>
                                        <td><input type='text' readonly='readonly' value='$Unit_Of_Measure'></td>
                                        <td><input type='text' readonly='readonly' value='$Product_Code'></td>
                                        <td style='text-align:center;'><input type='text' readonly='readonly' id='Quantity$Item_ID' placeholder='Enter Quantity' value='$Quantity' onkeyup='update_maintanance_Quantity($jobcard_ID, $Item_ID)'></td>
                                        <td><input type='text'readonly='readonly'id='Price$Item_ID' value=".number_format($Price)." placeholder='Enter The Price' onkeyup='update_maintanance_time($jobcard_ID,$Item_ID)'></td>

                                        <td style='text-align:right;'><input type='text' name='subtotal' readonly='readonly' style='text-align:right;' value='".number_format($subtotal,2)."' placeholder='Total Price'></td></tr>";
                                        $num++;

                                        //SUB TOTAL HII HAPA JUU
                                    }

                                    //GRAND TOTAL NATAKA IKAE HAPA KWENYE HII ECHO
                                    echo "<tr style='background-color: #fff;'>
                                    <td colspan='5'><p style='font-size: 17; font-weight: bold;'>SUB TOTAL</td>
                                    <td colspan='2'><input type='text' name='total' readonly='readonly' style='text-align:right; font-weight: bold; font-size: 18; border-style: none;' value='".number_format($Sub_Total,2)."' placeholder='Total Price'></td>
                                    </tr>";
                                }else{
                                    echo"<tr><td colspan='5'><b>NO SPARE(s) ORDERED FOR THIS JOBCARD</b><td><tr>";
                                }                            

                            ?>

                        </table>
                        </form>
                        </div>
                        <table class="table" id='spare_list'>
                            <?php

                            if(!empty($Labor_Charge)){
                                $total_labor = $Labor_Charge * $LQuantity;
                            echo "<tr>
                                        <td colspan ='2'><p style='font-size: 17; font-weight: bold;'>LABOR CHARGE</td>
                                        <td></td>
                                        <td></td>
                                        <td style='text-align:center;'>".$LQuantity."</td>
                                        <td style='text-align:right;'>".number_format($Labor_Charge)."</td>
                                        <td style='text-align:right;'>".number_format($total_labor,2)."</td>
                                </tr>";
                            }


                            if(!empty($fabrication_charge)){
                                $total_Fabrication = $fabrication_charge * $fabrication_charge_quantity;
                            echo "<tr>
                                        <td colspan ='2'><p style='font-size: 17; font-weight: bold;'>FABRICATION COSTS</td>
                                        <td></td>
                                        <td></td>
                                        <td style='text-align:center;'>".$fabrication_charge_quantity."</td>
                                        <td style='text-align:right;'>".number_format($fabrication_charge)."</td>
                                        <td style='text-align:right;'>".number_format($total_Fabrication,2)."</td>
                                </tr>";
                            }
                            $Grand_total = $total_labor + $Sub_Total + $total_Fabrication;
                            echo "<tr style='background-color: #dedede; !important;'>
                                <td colspan='4'><p style='font-size: 17; font-weight: bold;'>ESTIMATED GRAND TOTAL</td>
                                <td colspan='3' style='text-align:right; font-weight: bold; font-size: 17;'>".number_format($Grand_total,2)."/=</td>  
                                </tr>";

                            ?>
                        </table>
                </td>
                                
                            </tr>
                            <tr>
                            <?php 
                            $previous_status = mysqli_query($conn,"SELECT status, Certified_by, Certified_at, Authorised_at, Authorised_by, Authorize_Comment, Certify_Comment from tbl_jobcards where jobcard_ID='$jobcard_ID'");
                            $no = mysqli_num_rows($previous_status);
                            if ($no > 0) {
                                //$Process_Status = 'processed';
                                while ($data2 = mysqli_fetch_array($previous_status)) {
                                    $Certify_Comment = $data2['Certify_Comment'];
                                    $Authorised_by = $data2['Authorised_by'];
                                    $Authorize_Comment = $data2['Authorize_Comment'];
                                    $status = $data2['status'];
                                    $Certified_at = $data2['Certified_at'];
                                    $Certified_by = $data2['Certified_by'];
                                    $Authorised_at = $data2['Authorised_at'];

                                    $Authorizer = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Authorised_by'"))['Employee_Name'];

                                    $Certifier = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Certified_by'"))['Employee_Name'];

                                        if($status == 'Certified'){ ?>
                                            <td style="text-align: right; width:15%;"> Certified by: </td>
                                            <td colspan="2">
                                            <input type="text" readonly="readonly" value="<?php echo $Certifier; ?>">
                                            </td>
                                            <td style="text-align: right; width:15%;"> Certified at: </td>
                                            <td colspan="2">
                                            <input type="text" readonly="readonly" value="<?php echo $Certified_at; ?>">
                                            </td>
                                            </tr><tr>
                                            <td style="text-align: right; width:15%;"> Certifier's Comment: </td>
                                            <td colspan="5">
                                            <textarea id="Certify_Comment" readonly="readonly" name="Certify_Comment"> <?php echo $Certify_Comment?></textarea>
                                            </td>
                                    <?php
                                        }elseif($status == 'Authorized'){ ?>
                                            <td style="text-align: right; width:15%;"> Certified by: </td>
                                            <td colspan="2">
                                            <input type="text" readonly="readonly" value="<?php echo $Certifier; ?>">
                                            </td>
                                            <td style="text-align: right; width:15%;"> Certified at: </td>
                                            <td colspan="2">
                                            <input type="text" readonly="readonly" value="<?php echo $Certified_at; ?>">
                                            </td>
                                            </tr><tr>
                                            <td style="text-align: right; width:15%;"> Certifier's Comment: </td>
                                            <td colspan="5">
                                            <textarea id="Certify_Comment" readonly="readonly" name="Certify_Comment"> <?php echo $Certify_Comment?></textarea>
                                            </td>
                                            </tr><tr>
                                            <td style="text-align: right; width:15%;"> Authorized by: </td>
                                            <td colspan="2">
                                            <input type="text" readonly="readonly" value="<?php echo $Authorizer; ?>">
                                            </td>
                                            <td style="text-align: right; width:15%;"> Authorized at: </td>
                                            <td colspan="2">
                                            <input type="text" readonly="readonly" value="<?php echo $Authorised_at; ?>">
                                            </td>
                                            </tr><tr>
                                            <td style="text-align: right; width:15%;"> Certifier's Comment: </td>
                                            <td colspan="5">
                                            <textarea id="Certify_Comment" readonly="readonly" name="Certify_Comment"> <?php echo $Authorize_Comment?></textarea>
                                            
                                    <?php
                                        }
                                    }
                                }

                            ?>
                            <form action="" method="post" name="form">
                            <td><input type='text' name="jobcard_ID" hidden id="jobcard_ID" value='<?php echo $jobcard_ID ?>'></td>
                            <tr>
                                <?php
                                    $jobcard_status = mysqli_fetch_assoc(mysqli_query($conn,"SELECT `status` FROM tbl_jobcards where jobcard_ID='$jobcard_ID'"))['status'];

                                    if($jobcard_status == 'Pending'){
                                        if ($_SESSION['userinfo']['Certify_Job'] == 'yes'){ ?>

                                        <td style="text-align: right; width:15%; font-weight: bold;"> Certifying Comments: </td>
                                        <td width="100%" height="20%" colspan="5">
                                        <textarea id="Certify_Comment" name="Certify_Comment" required> <?php echo $Certify_Comment?></textarea>
                                        </td>
                                        </tr><tr>
                                        <td colspan='3'></td>
                                        <td><input type="submit" name="Certify" value="   CERTIFY JOB     " class="btn btn-primary" onclck="Submit_data()" id="Submit_data"></td>
                                        <td><input type="submit" name="Reject_Certify" value="     REJECT   " class="btn btn-primary"></td>
                                        </tr>
                                        <?php

                                          }else{ ?>
                                              <td><button type="button" name="Certify" value="Certify" class="Certify" onclick="return access_Denied()" class="btn btn-primary" >  CERTIFY JOB  </button></td>
                                              <td><button type="button" name="Reject" onclick="return access_Denied()" class="btn btn-primary">   REJECT   </button></td>
                                        <td colspan="3"></td>
                                        <?php  } 

                                    }elseif($jobcard_status == 'Certified'){
                                        if ($_SESSION['userinfo']['Authorize_Job'] == 'yes'){ ?>

                                            <td style='text-align: right; width:15%; font-weight: bold;'> Authorizer's Comments: </td>
                                            <td width='100%' height='20%' colspan='5'>
                                            <textarea name='Authorize_Comment' required><?php echo $Authorize_Comment; ?></textarea>
                                        </td>
                                        </tr>
                                        <tr>
                                        <td colspan='3'></td>
                                            <td><input type='submit' name='Authorize' class='btn btn-primary' value='    AUTHORIZE JOB    '></td>
                                            <td><input type='submit' name='Reject_Authorize' class='btn btn-primary' value='   REJECT   '></td>
                                          </tr>
                                         <?php

                                            }else{ ?>

                                            <td><button type='button' name='Authorize' onclick='return access_Denied()' class='btn btn-primary'>  AUTHORIZE JOB  </button></td>
                                            <td><button type='button' name='Reject' onclick='return access_Denied()' class='art-button-green'>   REJECT   </button></td>
                                      <td colspan='3'>
                                      </td>
                                      <?php                          
                                            }

                                    }elseif($jobcard_status == 'Authorized'){

                                                if ($_SESSION['userinfo']['Approve_Job'] == 'yes'){ ?>

                                                <tr>
                                                <td style="text-align: right; width:15%; font-weight: bold;"> Approval Comments: </td>
                                                <td width="100%" height="20%" colspan="5">
                                                    <textarea name="Approval_Comment" required></textarea>
                                                </td>
                                                </tr>
                                                <td colspan="3"></td>
                                                <td><input type="submit" name="Approve" class="btn btn-primary" value="    APPROVE JOB CARD    "></td>
                                                <td><input type="submit" name="Reject_Approval" value="   REJECT   " class="btn btn-primary"></td>
                                                </tr>
                                                    <?

                                                    }else{ ?>
                                                    <td colspan='6'></td>
                                                    <!-- <td><button type="button" name="Approve" onclick="return access_Denied()" class="btn btn-primary">  APPROVE JOB CARD  </button></td>
                                                    <td><button type="button" name="Reject" onclick="return access_Denied()" class="btn btn-primary">   REJECT   </button></td>
                                              <td colspan="3"></td> -->
                                              <?php
                                            }
                                    }
                                        
                                ?>
                                
                                
                            </tr>
                            </table>
                            </form>
                                                             
</table>
</fieldet>
</center>
</table>

<?php
$update_requisition_for_engineering='';
 
if(isset($_POST['Certify'])){
       //$Requisition_ID=$_POST['Requisition_ID'];
       $Certify_Comment=$_POST['Certify_Comment'];
       $jobcard_ID=$_POST['jobcard_ID'];
       $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
       

    if(!empty($Certify_Comment)){   
       
  $update_requisition_for_engineering="UPDATE tbl_jobcards SET status='Certified', Certified_by='$Employee_ID', Certified_at=NOW(), Certify_Comment='$Certify_Comment' WHERE jobcard_ID='$jobcard_ID'";
     $save_result= mysqli_query($conn,$update_requisition_for_engineering);
     
    if ($save_result)
    {
        echo "<script>alert('Jobcard was Certified Successfully!');
        document.location = './Job_Card.php?section=Engineering_Works=Engineering_WorksThisPage'</script>";
   }
    else 
    {
        echo "<script>alert('Requisition Failed!')</script>";
    }
    }else{
        echo "<script>alert('Failed to Certify the Jobcard!')</script>";
    }
}elseif ($_POST['Reject_Certify']) {
        $Certify_Comment=$_POST['Certify_Comment'];
        $jobcard_ID=$_POST['jobcard_ID'];
        $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        
 
     if(!empty($Certify_Comment)){   
        
   $update_requisition_for_engineering="UPDATE tbl_jobcards SET status='Rejected', Certified_by='$Employee_ID', Certified_at=NOW(), Certify_Comment='$Certify_Comment' WHERE jobcard_ID='$jobcard_ID'";
      $save_result= mysqli_query($conn,$update_requisition_for_engineering);
      
     if ($save_result)
     {
         echo "<script>alert('Jobcard was Rejected!');
         document.location = './Job_Card.php?section=Engineering_Works=Engineering_WorksThisPage'</script>";
    }
     else 
     {
         echo "<script>alert('Rejection Failed!')</script>";
     }
     }else{
         echo "<script>alert('Failed to Reject the Jobcard!')</script>";
     }

     //AUTHORIZATION OF JOBCARD
}elseif ($_POST['Authorize']) {
    $Authorize_Comment=$_POST['Authorize_Comment'];
    $jobcard_ID=$_POST['jobcard_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    

 if(!empty($Authorize_Comment)){   
    
$update_requisition_for_engineering="UPDATE tbl_jobcards SET status='Authorized', Authorised_by='$Employee_ID', Authorised_at=NOW(), Authorize_Comment='$Authorize_Comment' WHERE jobcard_ID='$jobcard_ID'";
  $save_result= mysqli_query($conn,$update_requisition_for_engineering);
  
 if ($save_result)
 {
     echo "<script>alert('Jobcard was Successfully Authorized!');
     document.location = './Authorize_list.php?section=Engineering_Works=Engineering_WorksThisPage'</script>";
}
 else 
 {
     echo "<script>alert('Authorization Failed!')</script>";
 }
 }else{
     echo "<script>alert('Failed to Authorize the Jobcard!')</script>";
 }
     //REJECTION OF AUTHORIZATION
 }elseif ($_POST['Reject_Authorize']){
    $Authorize_Comment=$_POST['Authorize_Comment'];
    $jobcard_ID=$_POST['jobcard_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    

 if(!empty($Authorize_Comment)){   
    
    $update_requisition_for_engineering="UPDATE tbl_jobcards SET status='Rejected', Authorised_by='$Employee_ID', Authorised_at=NOW(), Authorize_Comment='$Authorize_Comment' WHERE jobcard_ID='$jobcard_ID'";
    $save_result= mysqli_query($conn,$update_requisition_for_engineering);
    
   if ($save_result)
   {
       echo "<script>alert('Jobcard was Rejected!');
       document.location = './Authorize_list.php?section=Engineering_Works=Engineering_WorksThisPage'</script>";
  }
   else 
   {
       echo "<script>alert('Rejection Failed!')</script>";
   }
   }else{
       echo "<script>alert('Failed to Reject the Jobcard!')</script>";
  
 }

 //APPROVAL OF JOBCARDS
 }elseif ($_POST['Approve']){
    $Approval_Comment=$_POST['Approval_Comment'];
    $jobcard_ID=$_POST['jobcard_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    
    $Tittle_new = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Ti"));

 if(!empty($Approval_Comment)){   
            
            $update_requisition_for_engineering="UPDATE tbl_jobcards SET status='Approved', Approved_by='$Employee_ID', Approved_at=NOW(), Approval_Comment='$Approval_Comment' WHERE jobcard_ID='$jobcard_ID'";
            $save_result= mysqli_query($conn,$update_requisition_for_engineering);
            
        if ($save_result){

        $select_jobcard_datas = mysqli_query($conn, "SELECT * FROM tbl_jobcards WHERE jobcard_ID='$jobcard_ID'") or die(mysqli_error($conn));
        while($jobs = mysqli_fetch_assoc($select_jobcard_datas)){
            $Sub_Department_ID = $jobs['Sub_Department_ID'];
            $descriptions = $jobs['descriptions'];
            $Approved_by = $jobs['Approved_by'];
            $Certified_by = $jobs['created_by'];

                $Update_store_ID = mysqli_query($conn, "INSERT INTO tbl_store_orders(Created_Date_Time, Created_Date, Sent_Date_Time, Sub_Department_ID, Employee_ID, Supervisor_ID, Approval_Date_Time, Supervisor_Comment, Order_Status, Control_Status, Branch_ID, jobcard_ID, Order_Description) VALUES('$part_date', '$part_date', '$part_date', '$Sub_Department_ID', '$Certified_by', '$Employee_ID', NOW(), '$Approval_Comment', 'Approved', 'available', '1', '$jobcard_ID', '$descriptions')") or die(mysqli_error($conn));

                $Last_Store_Order_ID = mysqli_insert_id($conn);


                $Update_store_ID_least = mysqli_query($conn, "INSERT INTO tbl_document_approval_control(document_number, document_type, approve_employee_id, document_approval_level_title) VALUES('$Last_Store_Order_ID', 'store_order', '$Approved_by', '')") or die(mysqli_error($conn));

                $select_items_cache = mysqli_query($conn, "SELECT Item_ID, Quantity FROM tbl_jobcard_orders WHERE Jobcard_ID = '$jobcard_ID'");
                while($items = mysqli_fetch_array($select_items_cache)){
                    $job_Item_ID = $items['Item_ID'];
                    $job_Quantity = $items['Quantity'];

                    $insert = mysqli_query($conn, "INSERT INTO tbl_store_order_items(Store_Order_ID, Quantity_Required, Item_ID, Item_Status, `Status`, Procurement_Status) VALUES('$Last_Store_Order_ID', '$job_Quantity', '$job_Item_ID', 'active', 'active', 'active')") or die(mysqli_error($conn));

                    
                }
                
        }

        echo "<script>alert('Jobcard was Approved Successfully!');
        document.location = './Approve_list.php?section=Engineering_Works=Engineering_WorksThisPage'</script>";
        }else{
            echo "<script>alert('Approval Failed!')</script>";
        }
   }else{
       echo "<script>alert('Failed to Approve the Jobcard!')</script>";
  
 }

 //REJECT APPROVAL
 }elseif ($_POST['Reject_Approval']){
    $Approval_Comment=$_POST['Approval_Comment'];
    $jobcard_ID=$_POST['jobcard_ID'];
    $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
    

 if(!empty($Approval_Comment)){   
    
    $update_requisition_for_engineering = "UPDATE tbl_jobcards SET status='Rejected', Approved_by='$Employee_ID', Approved_at=NOW(), Rejection_Reason='$Approval_Comment' WHERE jobcard_ID='$jobcard_ID'";
    $save_result= mysqli_query($conn,$update_requisition_for_engineering);
    
   if ($save_result){
       echo "<script>alert('Jobcard was Rejected!');
       document.location = './Approve_list.php?section=Engineering_Works=Engineering_WorksThisPage'</script>";
  }else{
       echo "<script>alert('Rejection Failed!')</script>";
   }
   }else{
       echo "<script>alert('Failed to Reject the Jobcard!')</script>";
  
 }
 }


mysqli_close($conn);
?>


<?php
    include("./includes/footer.php");
?>