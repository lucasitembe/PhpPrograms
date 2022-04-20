<style type="text/css">
    /* .labefor{display:block;width: 100% }
    .labefor:hover{background-color: #a8d1ff;cursor: pointer}
    label.labefor { width: 100%;  */
    .rows_list {
        cursor: pointer;
    }

    .rows_list:active {
        color: #328CAF !important;
        font-weight: normal !important;
    }

    .rows_list:hover {
        color: #00416a;
        background: #dedede;
        font-weight: bold;
    }

    a {
        text-decoration: none;
    }

    input[type="checkbox"] {
        width: 25px;
        height: 25px;
        cursor: pointer;
        margin: 5px;
        margin-right: 5px;
    }

    input[type="radio"] {
        width: 25px;
        height: 25px;
        cursor: pointer;
        margin: 5px;
        margin-right: 5px;
    }

    #th {
        background: #99cad1;
    }

    #spu_lgn_tbl {
        width: 100%;
        border: none !important;
    }

    #spu_lgn_tbl tr,
    #spu_lgn_tbl tr td {
        border: none !important;
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


if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Storage_And_Supply_Work'])) {
        if ($_SESSION['userinfo']['Storage_And_Supply_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        } else {
            @session_start();
            if (!isset($_SESSION['Storage_Supervisor'])) {
                header("Location: ./engineeringsupervisorauthentication.php?InvalidSupervisorAuthentication=yes");
            }
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
//get sub department name
if (isset($_SESSION['Storage_Info']['Sub_Department_ID'])) {
    $Sub_Department_ID = $_SESSION['Storage_Info']['Sub_Department_ID'];
    //exit($Sub_Department_ID);
    $select = mysqli_query($conn, "SELECT Sub_Department_Name from tbl_sub_department where Sub_Department_ID = '$Sub_Department_ID'") or die(mysqli_error($conn));
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
$Requisition = array();
if (!empty($Requisition_ID)) {
    $Requisition = Get_Requisition($Requisition_ID);
}


$Today_Date = mysqli_query($conn, "select now() as today");
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

<style>
    .procure {
        display: none;
    }

    .spare {
        display: none;
        border: 1px white black;
    }

    .spare table tr th {
        background: gray;
        border: 1px solid #fff;
    }

    .spare table tr:nth-child(even) {
        background-color: #eee;
    }

    .spare table tr:nth-child(odd) {
        background-color: #fff;
    }
</style>

<br />
<center>
    <table width='90%'>
        <tr>
            <td>
                <fieldset>
                    <legend align=center><b>MAINTENANCE & PROCUREMENT - <span style='color: yellow; text-transform: uppercase;'><?php echo $Sub_Department_Name ?></span></b></legend>
                    <form action='' method='POST' name='' id='myForm'>
                        <table id="spu_lgn_tbl" width=100%>
                            <?php

                            //get details from tbl_enginnering_requisition
                            $get_details = mysqli_query($conn, "SELECT * FROM tbl_engineering_requisition
											WHERE Requisition_ID = '$Requisition_ID'") or die(mysqli_error($conn));
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
                                    $equipment_serial = $data2['equipment_serial'];
                                    $equipment_code = $data2['equipment_code'];
                                    $description_works_to_done = $data2['description_works_to_done'];
                                    $assigned_engineer = $data2['assigned_engineer'];
                                    $assistance_engineer = $data2['assistance_engineer'];
                                    $type_of_work = $data2['type_of_work'];
                                    $section_required = $data2['section_required'];
                                    $functional_test = $data2['functional_test'];
                                    $procurement_order = $data2['procurement_order'];
                                    $electrical_test = $data2['electrical_test'];
                                    $client_info = $data2['client_info'];
                                    $visual_test = $data2['visual_test'];
                                    $spare_required = $data2['spare_required'];
                                    $Mrv_Description = $data2['Mrv_Description'];
                                    $job_notes = $data2['job_notes'];
                                    $recommendations = $data2['recommendations'];
                                    $Indicated_Days = $data2['Indicated_Days'];

                                    $department = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Department_Name FROM tbl_department WHERE Department_ID='$Department_ID'"))['Department_Name'];

                                    $requested = mysqli_fetch_assoc(mysqli_query($conn, "Select Employee_Name from tbl_employee where Employee_ID = '$employee'"))['Employee_Name'];
                                }
                            }
                            ?>
                            <tr>
                                <td style='text-align: right; width:15%;'>Requisition Number</td>
                                <td style='text-align: right; width:15%;'>
                                    <?php
                                    echo "<input type='text' readonly='readonly' name='Requisition_ID' id='Requisition_ID' value='{$Requisition_ID}'";
                                    ?>
                                </td>
                                <td style='text-align: right; width:15%;'>Requesting Department</td>
                                <td width='10%' style>
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
                                <td style='text-align: right; width:15%;'>Requested Staff</td>
                                <td style='text-align: right; width:15%;'>
                                    <?php
                                    echo "<input type='text' readonly='readonly' name='reporter' value='{$requested}'>";

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
                <select name="assistance_engineer" id="assistance_engineer" style='width: 100%' onchange="update_notes()" multiple>
                            <option selected='selected'><?= $assistance_engineer ?></option>
                    <?php
                    $Select_Employee = mysqli_query($conn,"SELECT Employee_Name from tbl_employee WHERE Employee_Type='Engineer' AND Account_Status = 'active'");
                        while ($row = mysqli_fetch_array($Select_Employee)) {
                            $Employee_Name = $row['Employee_Name'];
                            echo "<option>" . $Employee_Name . "</option>";
                    }
                ?>
                </select>
                <?php
                // echo "<input type='text' readonly='readonly'  name='section_required' value='$assistance_engineer'>";
                ?>
            </td>
        </tr>
        <tr>
            <td style='text-align: right;'>Days To Be Done</td>
            <td colspan="2">
                <input type='number' style='width: 100%;' name='Indicated_Days' id='Indicated_Days' onchange='update_notes()' onkeyup='update_notes()' value='<?= $Indicated_Days ?>'>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <legend align=center style='text-align: center;'><b>MAINTENANCE PROCESS</b></legend>
                </>
        </tr>
        <tr>
            <td style='text-align: right; width:15%;'> Job Notes </td>
            <td width="100%" height="20%" colspan='5'>
                <textarea name='job_notes' id='job_notes' onkeyup='update_notes()' required><?= $job_notes ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan="6" style="text-align: center;">
                <?php echo "<a href='5_why_analysis.php?NewJobCard=True&Requisition_ID=" . $Requisition_ID . "' target='_blank'  class='btn btn-primary'>5 WHY ANALYSIS</a>" ?>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <legend align=center style='text-align: center;'><b>SPARE PARTS AND PROCUREMENT TRACKER</b></legend>
                </>
        </tr>
        <tr>
            <td colspan="2" style='text-align: center;'>
                <input type='checkbox' name='spare_required' id='spare_required' onchange='update_notes()' <?php if($spare_required == 'yes'){ echo "checked='checked'"; } ?> >
                <label for='spare_required'>Spare Required</label>
            </td>
            <td colspan="2" style='text-align: center;'>
                <input type='checkbox' name='procurement_order' id='procurement_order' onchange='update_notes()' <?php if($procurement_order == 'yes'){ echo "checked='checked'"; } ?> >
                <label for='procurement_order'>Procurement Order Made</label><br />
                <div class="procure">
                    <label for='procurement_order'>JOB CARD:</label>
                    <?php echo "<a href='new_job_card.php?NewJobCard=True&Requisition_ID=" . $Requisition_ID . "' target='_blank'  class='btn btn-primary'>CREATE JOB CARD</a>" ?>
                    </a>

                </div>

            </td>
            <td colspan="2" style='text-align: center;'>
                <input type='checkbox' name='client_info' id='client_info' <?php if($client_info == 'yes'){ echo "checked='checked'"; } ?> >
                <label for='client_info'>Client Informed</label>
            </td>
        </tr>
        <tr>
            <td width="100%" colspan='6'>
                <div class="spare">
                    <form action='ajax_engineering_info.php' method='POST'>
                        <table class="table">
                            <tr>
                                <td style="width: 5%;">
                                    <input type="text" name="Registration_ID" value="<?php echo $Requisition_ID ?>" hidden>
                                    <button type="button" name="add_item" class="btn btn-primary" style="height: 40px;" onclick="mantainance_drugs()">Add Items </button>
                                </td>
                                <td style="text-align: right; width: 20%">
                                    <b>Spare Consumption Description</b>
                                </td>
                                <td>
                                    <input type="text" name="Mrv_Description" id="Mrv_Description" value='<?= $Mrv_Description ?>' placeholder="Description of the Spare you want to Consume" onkeyup="update_notes()" style="height: 40px;">
                                </td>
                            </tr>
                        </table>
                        <table class="table" id='spare_list'>
                            <tr>
                                <th width="5%">S/N</th>
                                <th>Spare Used</th>
                                <th width="15%">UOM</th>
                                <th width="15%">Item Code</th>
                                <th width="15%">Quantity</th>
                                <th width="15%">Date Consumed</th>
                                <th width="5%">Action</th>
                            </tr>
                            <tbody id="SpareConsumed">

                            </tbody>

                        </table>
                    </form>
                </div>
                <div id="drugdialog"></div>
            </td>
        </tr>
        <tr>
            <td colspan="6">
                <legend align=center style='text-align: center;'><b>SAFETY TESTING</b></legend>
            </td>
        </tr>
        <tr>
            <td colspan="2" style='text-align: center;'>
                <input type='checkbox' name='visual_test' onchange='update_notes()' id='visual_test' value='yes' <?php if($visual_test == 'yes'){ echo "checked='checked'"; } ?> >
                <label for='visual_test'>Visual Inspection Completed</label>
            </td>
            <td colspan="2" style='text-align: center;'>
                <input type='checkbox' name='electrical_test' onchange='update_notes()' id='electrical_test' value='yes' <?php if($electrical_test == 'yes'){ echo "checked='checked'"; } ?> >
                <label for='electrical_test'>Electrical Safety Test Completed</label><br />
            <td colspan="2" style='text-align: center;'>
                <input type='checkbox' name='functional_test' onchange='update_notes()' <?php if($functional_test == 'yes'){ echo 'checked="checked"'; } ?> id='functional_test' value='yes'>
                <label for='functional_test'>Functional Test Completed</label>
            </td>
        </tr>
        </tr>
        <tr>
            <td style='text-align: right; width:15%;'>Comments/ Recommendations : </td>
            <td width="100%" height="20%" colspan='5'>
                <textarea name='comments_recon' id='comments_recon' onkeyup='update_notes()' required><?= $recommendations ?></textarea>
            </td>
        </tr>
        <tr>
            <td colspan='6' style='text-align: center;'>
                <input type='button' name='submit_form' id='submit_form' value='   SAVE INFORMATIONS   ' class='art-button-green' onclick='Save_notes()'>
            </td>
        </tr>
    </table>
    </form>
    </table>
</center>



<script>
    var checkbox = document.getElementById('procurement_order');
    checkbox.addEventListener('change', function() {
        var procure = document.querySelector('.procure');
        if (this.checked) {
            procure.style.display = 'block';
        } else {
            procure.style.display = 'none';
        }
    })


    var checkbox = document.getElementById('spare_required');
    checkbox.addEventListener('change', function() {
        var spare = document.querySelector('.spare');
        if (this.checked) {
            spare.style.display = 'block';
        } else {
            spare.style.display = 'none';
        }
    })


    //ADD SPARE PARTS
    function mantainance_drugs() {
        Mrv_Description = $("#Mrv_Description").val();
        if (Mrv_Description == undefined || Mrv_Description == '') {
            alert("Please Specify the Consumpton Descriptions of the Spares you want to Use");
            $("#Mrv_Description").css("border", "4px solid red");
            $("#Mrv_Description").focus()
        } else {
            $.ajax({
                type: 'POST',
                url: 'add_Engineering_item.php',
                data: {
                    add_maintanance: ''
                },
                success: function(responce) {
                    $("#drugdialog").dialog({
                        title: 'ADD CONSUMED SPARE FOR REQUISION NO: <?php echo $Requisition_ID ?>',
                        width: 800,
                        height: 600,
                        modal: true
                    });
                    $("#drugdialog").html(responce);
                    diaplay_maintanance()
                }
            })
        }

    }

    function diaplay_maintanance() {
        var Requisition_ID = $("#Requisition_ID").val();
        $.ajax({
            type: 'POST',
            url: 'add_Engineering_item.php',
            data: {
                Requisition_ID: Requisition_ID,
                select_maintanance: ''
            },
            cache: false,
            success: function(responce) {
                $('#SpareConsumed').html(responce);
            }
        });
        spare_required = '<?= $spare_required ?>';
        procurement_order = '<?= $procurement_order ?>';
        var spare = document.querySelector('.spare');
        var procure = document.querySelector('.procure');

            if(spare_required == 'yes'){
                spare.style.display = 'block';
            }
            if(procurement_order == 'yes'){
                procure.style.display = 'block';
            }
    }

    function add_maintanance(Item_ID) {
        Requisition_ID = $("#Requisition_ID").val();
        Sub_Department_ID = '<?= $Sub_Department_ID ?>';

        $.ajax({
            type: 'POST',
            url: 'add_Engineering_item.php',
            data: {
                Item_ID: Item_ID,
                Requisition_ID: Requisition_ID,
                insert_maintanance: 'insert_maintanance',
                Sub_Department_ID: Sub_Department_ID
            },
            cache: false,
            success: function(html) {
                // $("#drugdialog").dialog('close');   
                if (html != '') {
                    alert(html);
                }
                diaplay_maintanance();

            }
        });
    }

    function remove_maintanance(list_ID, Employee_ID) {
        if (confirm("Are you Sure You want to remove The Seleceted Item?")) {
            $.ajax({
                type: 'POST',
                url: 'add_Engineering_item.php',
                data: {
                    list_ID: list_ID,
                    Employee_ID: Employee_ID,
                    removemaintanance: ''
                },
                success: function(responce) {
                    diaplay_maintanance();
                }
            });
        }
    }

    function update_maintanance_time(list_ID) {
        var time = $("#time_" + list_ID).val();
        $.ajax({
            type: 'POST',
            url: 'add_Engineering_item.php',
            data: {
                list_ID: list_ID,
                time: time,
                updatetimemaintanance: ''
            },
            success: function(responce) {
                if(responce !=''){
                    alert(responce);
                    diaplay_maintanance();
                }
            }
        });
    }

    function update_maintanance_Quantity(list_ID) {
        var Quantity = $("#quantity" + list_ID).val();
        $.ajax({
            type: 'POST',
            url: 'add_Engineering_item.php',
            data: {
                list_ID: list_ID,
                Quantity: Quantity,
                updateQuantitymaintanance: ''
            },
            success: function(responce) {
                if(responce !=''){
                    alert(responce);
                    diaplay_maintanance();
                }
            }
        });
    }

    function search_maintanance_item(items) {
        $.ajax({
            type: 'POST',
            url: 'add_Engineering_item.php',
            data: {
                items: items,
                search_maintanance_item: ''
            },
            cache: false,
            success: function(html) {
                console.log(html);
                $('#Items_Fieldset').html(html);
            }
        });
    }


    //END OF SPARE PARTS

    $(document).ready(function() {
        diaplay_maintanance();
        $("#assistance_engineer").select2();
    });
</script>
<script>
    function Save_notes() {
        job_notes = $("#job_notes").val();
        finding_chr_lngth = $("#job_notes").val().length;
        Requisition_ID = '<?= $Requisition_ID ?>';
        Sub_Department_ID = '<?= $Sub_Department_ID ?>';

        if (finding_chr_lngth < 10) {
            alert("Please Fill Job Notes before saving Documentation..");
            $("#job_notes").css("border", "2px solid red");
            $("#job_notes").focus();
            exit();
        }
        if (confirm("Are You Sure Yo want to Submit this MRV?")) {
            $.ajax({
                type: "POST",
                url: "add_Engineering_item.php",
                data: {
                    Requisition_ID: Requisition_ID,
                    Check_Details: 'Check_Details'
                },
                cache: false,
                success: function(response) {
                    if(response != ''){
                        data = response;
                        post_to_store(data);   
                    }else{
                        if(confirm("You didn't use any Spare/Item on this Job from Store? \n If Yes Click OK to Proceed")){
                            finalize_process()
                        }
                    }
                }
            });
        }
    }

    function post_to_store(data){
        Document_Number = '<?= $Requisition_ID ?>';
        Sub_Department_ID = '<?= $Sub_Department_ID ?>';
        Movement_Type = 'Consumed';
        Employee_ID = '<?= $Employee_ID ?>';
        $.ajax({
            type: "POST",
            url: "store/store.common.php",
            data: {
                deduct_qty:'deduct_qty',
                Document_Number:Document_Number,
                Sub_Department_ID: Sub_Department_ID,
                Movement_Type:Movement_Type,
                Employee_ID:Employee_ID,
                Items:data
            },
            ache: false,
            success: function (response) {
                finalize_process();
            }
        });
    }
    function finalize_process(){
        job_notes = $("#job_notes").val();
        finding_chr_lngth = $("#job_notes").val().length;
        client_info = $("#client_info").val();
        visual_test = $("#visual_test").val();
        electrical_test = $("#electrical_test").val();
        functional_test = $("#functional_test").val();
        engineer_sign = $("#engineer_sign").val();
        comments_recon = $("#comments_recon").val();
        Requisition_ID = '<?= $Requisition_ID ?>';
        Assigned_Engineer_ID = '<?= $Employee_ID ?>';
        $.ajax({
                type: "POST",
                url: "update_mrv_document.php",
                data: {
                    Requisition_ID: Requisition_ID,
                    job_notes: job_notes,
                    client_info: client_info,
                    visual_test: visual_test,
                    electrical_test: electrical_test,
                    functional_test: functional_test,
                    comments_recon: comments_recon,
                    Assigned_Engineer_ID: Assigned_Engineer_ID,
                    Action: 'Submit'
                },
                cache: false,
                success: function(response) {
                    alert(response);
                    document.location = './assigned_requisition_engineering.php?section=Engineering_Works=Engineering_WorksThisPage';
                }
            });
    }
    function update_notes() {
        job_notes = $("#job_notes").val();
        finding_chr_lngth = $("#job_notes").val().length;
        Mrv_Description = $("#Mrv_Description").val();
        assistance_engineer = $("#assistance_engineer").val();
        Indicated_Days = $("#Indicated_Days").val();
        // electrical_test = $("#electrical_test").val();
        // functional_test = $("#functional_test").val();
        engineer_sign = $("#engineer_sign").val();
        comments_recon = $("#comments_recon").val();
        Requisition_ID = '<?= $Requisition_ID ?>';
        Assigned_Engineer_ID = '<?= $Employee_ID ?>';

        if(Indicated_Days == undefined || Indicated_Days == ''){
            alert("Please Indicate How long will take to Complete this Job");
            $("#Indicated_Days").css("border", "2px solid red");
            $("#Indicated_Days").focus();
            exit();
        }
        if(document.getElementById('client_info').checked){
            client_info = 'yes';
        }else{
            client_info = 'no';
        }

        if(document.getElementById('visual_test').checked){
            visual_test = 'yes';
        }else{
            visual_test = 'no';
        }

        if(document.getElementById('functional_test').checked){
            functional_test = 'yes';
        }else{
            functional_test = 'no';
        }
        if(document.getElementById("procurement_order").checked){
            procurement = 'yes';
        }else{
            procurement = 'no';
        }
        if(document.getElementById("electrical_test").checked){
            electrical_test = 'yes';
        }else{
            electrical_test = 'no';
        }
        if(document.getElementById("spare_required").checked){
            spare_required = 'yes';
        }else{
            spare_required = 'no';
        }

        $.ajax({
            type: "POST",
            url: "update_mrv_document.php",
            data: {
                Requisition_ID: Requisition_ID,
                job_notes: job_notes,
                client_info: client_info,
                visual_test: visual_test,
                electrical_test: electrical_test,
                functional_test: functional_test,
                procurement_order:procurement,
                comments_recon: comments_recon,
                Assigned_Engineer_ID: Assigned_Engineer_ID,
                spare_required:spare_required,
                Mrv_Description:Mrv_Description,
                assistance_engineer:assistance_engineer,
                Indicated_Days:Indicated_Days,
                Action: 'Update'
            },
            cache: false,
            success: function(response) {
                if(response != ''){
                    alert(response);
                }
            }
        });
    }
</script>

<?php
    include("./includes/footer.php");
?>