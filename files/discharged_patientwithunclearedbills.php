<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Management_Works'])) {
        if ($_SESSION['userinfo']['Management_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
    
?>
<?php
if (isset($_SESSION['userinfo'])) {
    echo "<a href='clearedpatientbillingwork.php?ClearedPatientsBillingWorks=ClearedPatientsBillingWorks' class='art-button-green'>CLEARED BILLS</a>";
}

$Sub_Department_Name = $_SESSION['Admission'];

$qr = "SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_Name LIKE  '%$Sub_Department_Name%'";
            $ward_results = mysqli_query($conn,$qr);
            if(mysqli_num_rows($ward_results)>0){
                while ($ward_rows = mysqli_fetch_assoc($ward_results)) {
                    $Hospital_Ward_ID = $ward_rows['Hospital_Ward_ID'];
                    $Hospital_Ward_Name = $ward_rows['Hospital_Ward_Name'];
                    
                    $Display = "<option name='duty_ward' value='$Hospital_Ward_ID' selected='selected'>$Hospital_Ward_Name</option>";

                }
                
            }
 
?>
<input type="button" value="BACK" onclick="history.go(-1)" class="art-button-green">

<link rel='stylesheet' href='fixHeader.css'>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
        font-size: 10px;
    }
    /* tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    } */
</style> 

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>


<br/><br/>
<fieldset>

    <center>
        <table width=90%>
            <tr>
                <td style="text-align: center;">
                    <!-- <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="start_date" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="end_date" placeholder="End Date"/>&nbsp; -->
                        
                    <input type='text' name='Search_Patient' id='Search_Patient' style="text-align: center;width:15%" onkeyup='Filter_Patient_List()' placeholder=' ~~ Enter Patient Name ~~~'>

                    <input type="text" name="Patient_Number" id="Patient_Number" style="text-align: center;width:15%" onkeyup='Filter_Patient_List()' placeholder=' ~~ Enter Patient Number ~~~'>
                    <select id="Sponsor_ID" name="Sponsor_ID" onchange="Filter_Patient_List()"  style="width:20%">
                        <option selected="selected" value="0">All Sponsor</option>
                        <?php
                        $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor order by Guarantor_Name") or die(mysqli_error($conn));
                        $num = mysqli_num_rows($select);
                        if ($num > 0) {
                            while ($data = mysqli_fetch_array($select)) {
                                echo '<option value="' . $data['Sponsor_ID'] . '">' . $data['Guarantor_Name'] . '</option>';
                            }
                        }
                        ?>
                    </select>
                    <select id="patient_type" onchange="Filter_Patient_List()"   style="width:15%" >
                        <option value="Discharged">Discharged</option>
                        <option value="All">All Patient Type</option>
                        <option value="Admitted">Admitted</option>
                        <option value="pending">To Bill</option>
                    </select>
                    <select id="Hospital_Ward_ID" name="Hospital_Ward_ID" onchange="Filter_Patient_List();" style="width:15%">
                        <option selected="selected" value="0">All Ward</option>
                        <?php
                        $select = mysqli_query($conn,"select Hospital_Ward_ID, Hospital_Ward_Name from tbl_hospital_ward order by Hospital_Ward_Name") or die(mysqli_error($conn));
                        $nm = mysqli_num_rows($select);
                        echo $Display;
                        if ($nm > 0) {
                            while ($dt = mysqli_fetch_array($select)) {
                                ?>
                                <option value="<?php echo $dt['Hospital_Ward_ID']; ?>"><?php echo $dt['Hospital_Ward_Name']; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                    <input type="button" name="Filter" id="Filter" value="FILTER" class="art-button-green" onclick="Filter_Patient_List()">
                    <input type="button" name="Filter" id="Filter" value="PREVIEW PDF" class="art-button-green" onclick="Patient_List_Searchpdf()">
                    <input type="button" name="Filter" id="Filter" value="PREVIEW EXCEL" class="art-button-green" onclick="Patient_List_Searchexcel()">
            </tr>
        </table>
    </center>
</fieldset>
<br/>
<link rel='stylesheet' href='fixHeader.css'>
<script src="css/jquery.js"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script>
        $(document).ready(function () {
            // $('#patients_list').DataTable({
            //     "bJQueryUI": true
            // });

            $('#start_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:    'now'
            });
            $('#start_date').datetimepicker({value: '', step: 1});
            $('#end_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:'now'
            });
            $('#end_date').datetimepicker({value: '', step: 1});
        });
</script>

        <fieldset style='overflow-y: scroll;overflow-x: scroll; height: 400px; background-color:white' id='Patients_Fieldset_List'>
    
        </fieldset>
<fieldset>
    <label class="col-md-3">
       Absconde <div style="background:black;" style="width:100px;height:30px">_</div>
    </label>
    <label class="col-md-3">
       Nurse Discharge <div style="background:green;" style="width:100px;height:30px">_</div>
    </label>
    <label class="col-md-3">
       Doctor Discharge <div style="background:greenyellow;" style="width:100px;height:30px">_</div>
    </label>
    <label class="col-md-3">
       Death <div style="background:red;" style="width:100px;height:30px">_</div>
    </label>
</fieldset>
            <div id="Get_Patient_Details" style="width:50%;" >

            </div>

            <div id="Preview_Transaction_Details" style="width:50%;" >

            </div>

            <link rel="stylesheet" href="css/select2.min.css" media="screen">
            <script src="media/js/jquery.js" type="text/javascript"></script>
            <script src="css/jquery-ui.js"></script>
            <script src="js/select2.min.js"></script>

            <script>
                $(document).ready(function () {
                    $("#Get_Patient_Details").dialog({autoOpen: false, width: "90%", height: 630, title: 'INPATIENT BILLING DETAILS', modal: true});
                    $("select").select2();
                });
            </script>

            <script>
                $(document).ready(function () {
                    $("#Preview_Transaction_Details").dialog({autoOpen: false, width: "75%", height: 450, title: 'TRANSACTION DETAILS', modal: true});

                    Filter_Patient_List();
                });
            </script>


          

            <script type="text/javascript">
                function Filter_Patient_List() {
                    var Sponsor_ID = document.getElementById("Sponsor_ID").value;
                    var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
                    var patient_type = document.getElementById("patient_type").value;                    
                    var Patient_Number = document.getElementById("Patient_Number").value;
                    var Search_Patient = document.getElementById("Search_Patient").value;
                    document.getElementById('Patients_Fieldset_List').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
                    $.ajax({
                        type:'GET',
                        url:'Patient_Billing_List_uncleared.php',
                        data:{Sponsor_ID:Sponsor_ID,Hospital_Ward_ID:Hospital_Ward_ID,patient_type:patient_type,Patient_Number:Patient_Number,Patient_Name:Search_Patient},
                        success:function(responce){
                            $("#Patients_Fieldset_List").html(responce);

                            // $('#patients_list').DataTable({
                            //     "bJQueryUI": true
                            // });
                            
                           // setTimeout(function(){ location.reload() }, 3000);
                        }
                    });
                   
                }

                function Patient_List_Searchpdf(){
                    var Sponsor_ID = document.getElementById("Sponsor_ID").value;
                    var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
                    var patient_type = document.getElementById("patient_type").value;                    
                    var Patient_Number = document.getElementById("Patient_Number").value;
                    var Search_Patient = document.getElementById("Search_Patient").value;
                    window.open('Patient_Billing_List_uncleared_pdf.php?Sponsor_ID='+Sponsor_ID+'&Hospital_Ward_ID='+Hospital_Ward_ID+'&patient_type='+patient_type+'&Patient_Number='+Patient_Number+'&Search_Patient='+Search_Patient,'_blank');
     
                }

                function Patient_List_Searchexcel(){
                    var Sponsor_ID = document.getElementById("Sponsor_ID").value;
                    var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
                    var patient_type = document.getElementById("patient_type").value;                    
                    var Patient_Number = document.getElementById("Patient_Number").value;
                    var Search_Patient = document.getElementById("Search_Patient").value;
                    window.open('Patient_Billing_List_uncleared_excel.php?Sponsor_ID='+Sponsor_ID+'&Hospital_Ward_ID='+Hospital_Ward_ID+'&patient_type='+patient_type+'&Patient_Number='+Patient_Number+'&Search_Patient='+Search_Patient,'_blank');
     
                }
            </script>

            <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
            <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
            <script src="media/js/jquery.js" type="text/javascript"></script>
            <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
            <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
            <script src="css/jquery-ui.js"></script>
            <br/>
            <?php
            include("./includes/footer.php");
            ?>