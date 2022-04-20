<?php 
    include("./includes/header.php");
    include("includes/connection.php");
    $sql_date_time = mysqli_query($conn,"select now() as Date_Time ") or die(mysqli_error($conn));
    while($date = mysqli_fetch_array($sql_date_time)){
        $Current_Date_Time = $date['Date_Time'];
    }
    $Filter_Value = substr($Current_Date_Time,0,11);
    $Start_Date = $Filter_Value.' 00:00';
    $End_Date = $Current_Date_Time;
?>
<a href="Laboratory_Reports.php" class="art-button-green">BACK</a>
<fieldset>
        <legend align=center><b>SPECIMEN COLLECTED</b></legend>
        <center>
            <table>
                <tr>
                    <td><input type='text' id='Date_From' style="text-align: center" value="<?= $Start_Date ?>" readonly="readonly" placeholder="Start Date"/></td>
                    <td><input type='text' id="Date_To" style="text-align: center"value="<?= $End_Date ?>" readonly="readonly" placeholder="End Date"/></td>
                    <td><input type='text' id="Patient_Name" style="text-align: center"  onkeyup="filter_collected_specimen_patient()"placeholder="Patient Name"/></td>
                    <td><input type='text' id="Registration_ID" style="text-align: center"  onkeyup="filter_collected_specimen_patient()"placeholder="Patient Number"/></td>
                    <td>
                        <select style='text-align: center;padding:4px; width:100%;' onchange="filter_collected_specimen_patient()" id="subcategory_ID">
                            <?php 
                                 //Lab subcategory
                                 $query_sub_cat = mysqli_query($conn,"SELECT its.Item_Subcategory_ID,its.Item_Subcategory_Name FROM `tbl_items` i JOIN tbl_item_subcategory its ON its.Item_Subcategory_ID=i.Item_Subcategory_ID WHERE i.`Consultation_Type`='Laboratory' GROUP BY its.Item_Subcategory_ID ") or die(mysqli_error($conn));

                                echo '<option value="All">All Departments</option>';

                                 while ($row = mysqli_fetch_array($query_sub_cat)) {
                                     echo '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
                                 }
                            ?> 
                        </select>
                    </td>
                    <td>
                        <select id='Type'  style='text-align: center;padding:4px; width:100%;' onchange="filter_collected_specimen_patient()">
                            <option value="All">All Done & Not Done</option>
                            <option value="Done">Done</option>
                            <option value="Not Done">Not Done</option>
                        </select>
                    </td>
                    <td>
                        <input type="button" value="FILTER" class="art-button-green" onclick="filter_collected_specimen_patient()">
                    </td>
                    <!-- <td><input type='button' name='Preview_Button' id='Preview_Button' class='art-button-green' value='PREVIEW' onclick="Preview_Report()"></td> -->
                    <td><input type='button' onclick='print_excell()' class="art-button-green" value='PRINT REPORT EXCEL'></td>
                </tr>
            </table>
        </center>
        <center>
            <div class="box box-primary" style="height:540px;overflow-y: scroll;overflow-x: hidden">
                <table class='table table-collapse table-striped fixTableHead'>
                    <tr style='background: #dedede;'>
                        <th  style='text-align: left;width: 2%'>SN</th>
                        <th style='text-align: left;'width='16%'><b>PATIENT NAME</th>
                        <th style='text-align: left;'>REG #</th>
                        <th style='text-align: left; width: 12%'>SPONSOR</th>
                        <th style='text-align: left;'>GENDER</th>
                        <th style='text-align: left; width: 10%'>AGE</th>
                        <th style='text-align: left;'width='9%'>REQ. DOCTOR</th>
                        <th style='text-align: left;'width='9%'>ORDERED DATE</th>
                        <th style='text-align: left;'width='20%'>EXAM & RESULT</th>
                        <th style='text-align: left;'width='15%'>CATEGORY</th>
                        <th style='text-align: left; width: 6%;'width=''>STATUS</th>
                    </tr>
                    </tr>
                        <tbody id='list_of_patient_arleady_collected_specimen_body'>
                    </tbody>
                </table>
            </div>
        </center>
</fieldset>
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="css/uploadfile.css" media="screen">
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css" type="text/css" media="screen" />   
<script src="js/jquery-1.8.0.min.js"></script>
<script src="js/jquery-ui-1.8.23.custom.min.js"></script><script src="css/jquery.datetimepicker.js"></script>

<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/scripts.js"></script>
<script src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<link rel="stylesheet" href="css/ui.notify.css" media="screen">
<script src="js/jquery.notify.min.js"></script> 
<script>
    $(document).ready(function () {
        filter_collected_specimen_patient();
    });
    function filter_collected_specimen_patient(){
       var Date_From= $('#Date_From').val();
       var Date_To= $('#Date_To').val();
       var subcategory_ID= $('#subcategory_ID').val();
       var searchspecmen_id= $('#searchspecmen_id').val();
       var Patient_Name= $('#Patient_Name').val();
       var Registration_ID= $('#Registration_ID').val();
       Type = $("#Type").val();
        
       if(subcategory_ID==""){
           $('#subcategory_ID').css("border","2px solid red");
       }else{
            document.getElementById('list_of_patient_arleady_collected_specimen_body').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
            $.ajax({
                type:'POST',
                url:'laboratory_investigation_report_iframe.php',
                data:{Date_From:Date_From,Date_To:Date_To,subcategory_ID:subcategory_ID,Registration_ID:Registration_ID,Patient_Name:Patient_Name,Type:Type},
                success:function(data){
                    $("#list_of_patient_arleady_collected_specimen_body").html(data);
                }
            });
        }
    }
    function Preview_Report(){
       var Date_From= $('#Date_From').val();
       var Date_To= $('#Date_To').val();
       var subcategory_ID= $('#subcategory_ID').val();
       var searchspecmen_id= $('#searchspecmen_id').val();
        var Patient_Name= $('#Patient_Name').val();
       var Registration_ID= $('#Registration_ID').val();
       if(subcategory_ID=="All"){
           $('#subcategory_ID').css("border","2px solid red");
       }else{
           window.open("list_of_patients_arleady_collected_specimen_pdf_report.php?Date_From="+Date_From+"&Date_To="+Date_To+'&subcategory_ID='+subcategory_ID+"&Patient_Name="+Patient_Name+"&Registration_ID"+Registration_ID,"_blank");
        }
    }

    function print_excell(){
        var Date_From= $('#Date_From').val();
       var Date_To= $('#Date_To').val();
       var subcategory_ID= $('#subcategory_ID').val();
       var searchspecmen_id= $('#searchspecmen_id').val();
       var Patient_Name= $('#Patient_Name').val();
       var Registration_ID= $('#Registration_ID').val();
       Type = $("#Type").val();
    //    if(subcategory_ID=="All"){
    //        $('#subcategory_ID').css("border","2px solid red");
    //    }else{
            window.open("lab_investigation_report_excell.php?Date_From="+Date_From+"&Date_To="+Date_To + "&subcategory_ID=" + subcategory_ID + '&Patient_Name=' + Patient_Name + '&Registration_ID=' + Registration_ID + "&Type=" + Type);
        // }
    }
    
    $('#Date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:    'now'
    });
    $('#Date_From').datetimepicker({value: '', step: 1});
    $('#Date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        //startDate:'now'
    });
    $('#Date_To').datetimepicker({value: '', step: 1});
//    
</script>