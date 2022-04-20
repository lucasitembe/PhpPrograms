<?php
include("includes/connection.php");
include("includes/header.php");

    if(!isset($_SESSION['userinfo'])){
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    
$Today_Date = mysqli_query($conn,"select now() as today");
while($row = mysqli_fetch_array($Today_Date)){
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    
    $Age = $Today - $original_Date; 
}

$display = "<option value='All' selected='selected'>All Departments</option>";
    
$select_sub_departments = mysqli_query($conn, "SELECT finance_department_name, finance_department_id FROM tbl_finance_department WHERE finance_department_id = finance_department_id AND enabled_disabled = 'enabled'");
while($rows = mysqli_fetch_array($select_sub_departments)){
    $finance_department_name = $rows['finance_department_name'];
    $finance_department_id = $rows['finance_department_id'];

    $display .= "<option value='".$finance_department_id."'>".$finance_department_name."</option>";
}
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;

    }
    tr:hover{
        background-color: #d9f9fe !important;
        cursor:pointer;
    }
</style>
<a href='managementworkspage.php?ManagementWorksPage=ThisPage' class='art-button-green'>BACK</a>
<br/><br/>

<center>
    <fieldset>
    <table  class="table table-collapse" style="border-collapse: collapse;border:1px solid black; width: 100%">
        <tr>
            <td width="10%">
                <input type='text' name='Date_From' title='Incase You want to filter by Date, Fill these Date fields' placeholder='Start Date' id='date_From' style="text-align: center">    
            </td>
            <td width="10%">
                <input type='text' name='Date_To' title='Incase You want to filter by Date, Fill these Date fields' placeholder='End Date' id='date_To' style="text-align: center"></td>  
            <td width="10%"> 
                <input type='text' name='Patient_Name' title='Incase You want to filter by Name'  id='Patient_Name' style='text-align: center;' onkeyup='filterPatient()' placeholder='~~~~Search Patient Name~~~~~'>
            </td>
            <td width="10%"> 
                <input type='text' name='Patient_Number' title='Incase You want to filter by Registration Number'  id='Patient_Number' style='text-align: center;'  onkeyup='filterPatient()' placeholder='~~~Search Patient Number~~~'>
            </td>
            <td width="10%">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange='filterPatient()'  style='text-align: center;width:100%;display:inline'>
                        <option value="All">All Sponsors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_sponsor";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                
                <td width="10%">
                    <select name='Employee_ID' onchange='filterPatient()'  id='Employee_ID' style='text-align: center;width:100%;display:inline'>
                        <option value="All">All Doctors</option>
                        <?php
                        $qr = "SELECT * FROM tbl_employee WHERE Employee_Type LIKE '%Doctor%' AND Account_Status = 'active'";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Employee_ID']; ?>'><?php echo $sponsor_rows['Employee_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td width= '10%'>
                    <select name="Sub_Department_ID" id="Sub_Department_ID"  onchange='filterPatient()' style='width: 100%'>
                        <?php
                            echo $display;
                        ?>
                    </select>
                </td>
                <td width= '10%'>
                        <select name='Service_type' id='Service_type' onchange="filterPatient()" style='text-align: center;width:100%;display:inline'>
                            <option value="All" selected>All Services</option>
                            <option value="Pharmacy">Pharmacy</option>
                            <option value="Procedure">Procedure</option>
                            <option value="Radiology">Radiology</option>
                            <option value="Laboratory">Laboratory</option>
                            <option value="Surgery">Surgery</option>
                            <!-- <option value="Others">Others</option> -->
                        </select>
                </td>
                <td width='15%'>
                    <select name="Item_ID" id="Item_ID" onchange="filterPatient()" style='text-align: center;width:100%;display:inline'>
                        <option value="All" selected>All Services</option>
                        <?php
                            $Select_Items = mysqli_query($conn, "SELECT Product_Name, Item_ID FROM tbl_items WHERE Status = 'Available'") or die(mysqli_error($conn));
                                if(mysqli_num_rows($Select_Items)>0){
                                    while($dts = mysqli_fetch_assoc($Select_Items)){
                                        $Product_Name = $dts['Product_Name'];
                                        $Item_ID = $dts['Item_ID'];
                                            echo "<option value='".$Item_ID."'>".$Product_Name."</option>";
                                    }
                                }
                        ?>

                    </select>
                </td>
                <td>
                    <input type='submit' name='Print_Filter' id='Print_Filter' onclick='filterPatient()' class='art-button-green' value='FILTER'>
                
                <!-- <input type='button' name='Print_Filter' id='Print_List' onclick="Print_List()" class='art-button-green' value='PREVIEW IN EXCELL'> -->
                <!-- <input type='button'  onclick='check_assign()' class='art-button-green' value='PREPARE LIST'> -->
                </td>
        </tr>
    </table>
    </fieldset>
    <fieldset>
        <legend align=center><b>PATIENT ORDERS </b></legend>
        <!-- <div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden"> -->
        <div class="row">
            <div class="col-sm-12" id="patient_list">
                <div id='list_of_checked_in_n_discharged_tbl'>
                </div>
            </div>
        </div>
    </fieldset>
</center>



<script>
     $(document).ready(function(){
        filterPatient();
     });
     function filterPatient(){
        Patient_Name=$("#Patient_Name").val();
        Patient_Number=$("#Patient_Number").val();
        Date_From=$("#date_From").val();
        Date_To=$("#date_To").val();
        Employee_ID=$("#Employee_ID").val();
        Sub_Department_ID=$("#Sub_Department_ID").val();
        Sponsor_ID=$("#Sponsor_ID").val();
        Service_type = $("#Service_type").val();
        Item_ID = $("#Item_ID").val();
        
        document.getElementById('list_of_checked_in_n_discharged_tbl').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        
         $("#ajax_loder").show();
         $.ajax({
             type:'POST',
             url:'Patient_ordering_control_iframe.php',
             data:{
                 Patient_Name:Patient_Name,
                 Patient_Number:Patient_Number,
                 Date_From:Date_From,
                 Date_To:Date_To,
                 Employee_ID:Employee_ID,
                 Sub_Department_ID:Sub_Department_ID,
                 Sponsor_ID:Sponsor_ID,
                 Service_type:Service_type,
                 Item_ID:Item_ID
             },
             success:function (data){
                $("#patient_list").html(data); 
                $("#ajax_loder").hide();  
                $('#list_of_checked_in_n_discharged_tbl').DataTable({
                        "bJQueryUI": true
                });
             }
         });
     }

    //  function Print_List(){
    //      alert("Print_List");
    //      exit();
    //     Patient_Name=$("#Patient_Name").val();
    //     Patient_Number=$("#Patient_Number").val();
    //     Date_From=$("#date_From").val();
    //     Date_To=$("#date_To").val();
    //     Employee_ID=$("#Employee_ID").val();
    //     Sub_Department_ID=$("#Sub_Department_ID").val();
    //     Sponsor_ID=$("#Sponsor_ID").val();
    //     Service_type = $("#Service_type").val();

    //     window.open("Patient_ordering_control_Excell_report.php?Date_From="+Date_From+"&Date_To="+Date_To + "&subcategory_ID=" + subcategory_ID + '&Patient_Name=' + Patient_Name + '&Patient_Number=' + Patient_Number + "&Employee_ID=" + Employee_ID + "&Sub_Department_ID=" + Sub_Department_ID + "&Sponsor_ID=" + Sponsor_ID + "&Service_type=" + Service_type);
    //  }
 </script>
 <script src="css/jquery.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    <script>
    $('#date_From').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:    'now'
    });
    $('#date_From').datetimepicker({value:'',step:1});
    $('#date_To').datetimepicker({
    dayOfWeekStart : 1,
    lang:'en',
    //startDate:'now'
    });
    $('#date_To').datetimepicker({value:'',step:1});
    </script>

<script  type="text/javascript">
    
     $(document).ready(function (){
        $('#list_of_checked_in_n_discharged_tbl').DataTable({
            "bJQueryUI": true
        });
     });
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script>
    $(document).ready(function (e){
        $("#Sponsor_ID").select2();
        $("#Employee_ID").select2();
        $("#Sub_Department_ID").select2();
        $("#Service_type").select2();
        $("#Item_ID").select2();
    });
</script>
<?php
    include("./includes/footer.php");
?>