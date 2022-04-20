<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ./index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Admission_Works'])) {
        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if(isset($_SESSION['systeminfo']['Transfer_Patient_Module_Status']) && strtolower($_SESSION['systeminfo']['Transfer_Patient_Module_Status']) == 'enabled'){
    header("Location: ./wardtransferpage.php?WardTransfer=WardTransferThisPage");
}

if (isset($_GET['section'])) {
    $section = $_GET['section'];
} else {
    $section = "Admission";
}
if (strtolower($section) == 'admission') {
    echo "<a href='admissionworkspage.php?section=" . $section . "&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION MAIN WORKPAGE
            </a>
            <a href='admissionreports.php?section=" . $section . "&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
            ADMISSION REPORTS
            </a>
            ";
}

if (strtolower($section) == 'reception') {
    echo "<a href='receptionReports.php?Section=Reception&ReceptionReportThisPage' class='art-button-green'>BACK</a>";
} else {
    echo "<a href='admissionworkspage.php?section=&Admission&reports=AllReports&ActiveReports' class='art-button-green'>BACK</a>";
}
?>

<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style> 
<br/><br/>
<fieldset style="background-color:white;">
    <legend align="center" style="background-color:#006400;color:white;padding:5px;"><b>WARD PATIENT TRANSFER</b></legend>
    <center>
        <table width='99%' style="border:none !important; border-color:transparent !important;background-color:white;">
            <tr>
                <td style="text-align:center">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:18%;display:inline'>
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
                    <select id='Branch_ID' name='Branch_ID' onchange="filterPatient()">
                        <option value="All">ALL</option>
                        <?php
                        $select_branch = "SELECT * FROM tbl_branches";
                        $result = mysqli_query($conn,$select_branch);
                        while ($row = mysqli_fetch_assoc($result)) {
                            ?>
                            <option value='<?php echo $row['Branch_ID']; ?>'><?php echo strtoupper($row['Branch_Name']); ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <select id='Gender' name='Gender' onchange="filterPatient()">
                        <option value="All">ALL</option>
                        <option>Male</option>
                        <option>Female</option>
                    </select>
                    <select width="20%"  name='Ward_id' style='text-align: center;width:15%;display:inline' onchange="filterPatient()" id="Ward_id">
                        <option value="All">All Ward</option>
                        <?php
                        $Select_Ward = mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward");
                        while ($Ward_Row = mysqli_fetch_array($Select_Ward)) {
                            $ward_id = $Ward_Row['Hospital_Ward_ID'];
                            $Hospital_Ward_Name = $Ward_Row['Hospital_Ward_Name'];
                            ?>
                            <option value="<?php echo $ward_id ?>"><?php echo $Hospital_Ward_Name ?></option>
                        <?php }
                        ?>
                    </select>
                    <input type='text' name='Search_Patient' style='text-align: center;width:21%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Name~~~~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                </td>
            </tr>
        </table>
    </center>
    <center>
        <table width=100% border=1>
            <tr>
                <td>
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'ward_transfer_frame.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<br/>

<script type="text/javascript">
    $(document).ready(function () {
        $('#patients-list').DataTable({
            "bJQueryUI": true
        });
    });
</script>

<script>
    function filterPatient() {
        var ward = document.getElementById('Ward_id').value;
        var Gender = document.getElementById('Gender').value;
        var Branch_ID = document.getElementById('Branch_ID').value;
        var Patient_Name = document.getElementById("Search_Patient").value;
        var Sponsor = document.getElementById("Sponsor_ID").value;

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        $.ajax({
            type: 'GET',
            url: 'ward_transfer_frame.php',
            data: 'Patient_Name=' + Patient_Name + '&ward=' + ward + '&Gender=' + Gender + '&Branch_ID=' + Branch_ID + '&Sponsor=' + Sponsor,
            cache: false,
            beforeSend: function (xhr) {
                //$("#progressStatus").show();
            },
            success: function (html) {
                if (html != '') {
                    // $("#progressStatus").hide();
                    $("#Search_Iframe").html(html);

                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#patients-list').DataTable({
                        "bJQueryUI": true

                    });
                }
            }, error: function (html) {

            }
        });

    }
</script>
<script type="text/javascript" language="javascript">
    function Get_Ward_Beds(Hospital_Ward_ID, Admission_id) {
        if (Hospital_Ward_ID == null || Hospital_Ward_ID == '') {
            alert('Please select hospital ward');
            $('#Hospital_Ward_ID_' + Admission_id).focus();
            document.getElementById('Hospital_Ward_ID_' + Admission_id).style.border = '1px solid red';
            document.getElementById('Bed_ID_' + Admission_id).innerHTML = "<option selected='selected'></option>";
            exit;
        }
        //var Hospital_Ward_ID = document.getElementById("Hospital_Ward_ID").value;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = function () {
            var data = mm.responseText.split('#$%^$##%$&&');
            //if (mm.readyState == 4 && mm.status == 200) {
            document.getElementById('Bed_ID_' + Admission_id).innerHTML = data[0];
            //}
        }; //specify name of function that will handle server response....
        mm.open('GET', 'Get_Ward_Beds.php?Hospital_Ward_ID=' + Hospital_Ward_ID, true);
        mm.send();


    }

</script>				
<script type="text/javascript" language="javascript">
    function checkPatientNumber(bed_id, Admission_id) {
        var Hospital_Ward_ID = document.getElementById('Hospital_Ward_ID_' + Admission_id).value;

        if (bed_id == null || bed_id == '') {
            alert('Please select bed number');
            $('#Bed_ID_' + Admission_id).focus();
            document.getElementById('Bed_ID_' + Admission_id).style.border = '1px solid red';
            exit;
        }
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = function () {
            var data = mm.responseText;
            if (mm.readyState == 4 && mm.status == 200) {
                if (parseInt(data) > 0) {
                    alert('There are already ' + data + ' patient(s) in this bed.Do you still want to admit a patient in this bed.');
                    if (confirm("You are about to change this patient ward and bed.Are you sure you want to make this transfer?")) {
                        transfet_patient_ward(Hospital_Ward_ID, bed_id, Admission_id);
                    }
                }
                {
                    if (confirm("You are about to change this patient ward and bed.Are you sure you want to make this transfer?")) {
                        transfet_patient_ward(Hospital_Ward_ID, bed_id, Admission_id);
                    }
                }

            }
        }

        mm.open('GET', 'Get_bed_patients_number.php?bed_id=' + bed_id, true);
        mm.send();
    }

</script>	
<script>
    function transfet_patient_ward(Hospital_Ward_ID, bed_id, Admission_id) {
       // alert(Hospital_Ward_ID+" "+bed_id+" "+Admission_id);exit;
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }


        mm.onreadystatechange = function () {
            var data = mm.responseText;
            if (mm.readyState == 4 && mm.status == 200) {
                if(parseInt(data)==1){
                    alert("Patient transfered successifuly"); 
                    filterPatient();
                }else{
                     alert("An error has occured.Please try again!");
                }
               
                //
            }
        }

        mm.open('GET', 'do_transfert_ward.php?bed_id=' + bed_id+'&ward_id=' + Hospital_Ward_ID+'&admission_id=' + Admission_id, true);
        mm.send();
    }
</script>

<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>