<?php 
    include("./includes/header.php");
    include("./includes/connection.php");

    $output = "";
    $sqlQuery = "";
    $counter = 1;

    if(isset($_GET['Date_From']) && isset($_GET['Date_To'])){
        $Date_From = $_GET['Date_From'];
        $Date_To = $_GET['Date_To'];
        $sqlQuery = mysqli_query($conn,"SELECT * FROM tbl_patient_edit WHERE Edit_date BETWEEN '$Date_From' AND '$Date_To'");
    }else{
        $sqlQuery = mysqli_query($conn,"SELECT * FROM tbl_patient_edit ORDER BY patient_edit_id DESC LIMIT 20 ");
    }

    if(mysqli_num_rows($sqlQuery) > 0){
        while($data = mysqli_fetch_assoc($sqlQuery)){
            $Registration_ID = $data['Registration_ID'];
            $Old_sponsor = $data['Old_sponsor'];
            $Sponsor_ID = $data['Sponsor_ID'];
            $Edit_date = $data['Edit_date'];
            $Employee_ID = $data['Employee_ID'];
            $oldnmae = $data['Old_name'];
            //$fetchPatientNameold = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Old_name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'"))['Old_name'];

            $fetchPatientName = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'"))['Patient_Name'];
            $fetchOldSponsor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID = '$Old_sponsor'"))['Guarantor_Name'];
            $fetchNewSponsor = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Guarantor_Name FROM tbl_sponsor WHERE Sponsor_ID = '$Sponsor_ID'"))['Guarantor_Name'];
            $fetchEmployeeName = mysqli_fetch_assoc(mysqli_query($conn,"SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Employee_ID'"))['Employee_Name'];

            $output .= "<tr>
                            <td style='text-align:center'>$counter</td>
                            <td>{$Registration_ID}</td>
                            <td>{$fetchPatientName}</td>
                            <td>{$oldnmae}</td>
                            <td>{$fetchOldSponsor}</td>
                            <td>{$fetchNewSponsor}</td>
                            <td>{$Edit_date}</td>
                            <td>{$fetchEmployeeName}</td>
                        </tr>";
            $counter++;
        }
    }else{
        $output = "<tr><td colspan='7' style='text-align:cnter;color:red;padding:5px;'><center><b>Data Not Found</b></center></td></tr>";
    }

?>

<style>
    table tr td{
        padding: 8px !important;
    }
</style>


<a href="receptionReports.php?Section=Reception&Reception=ReceptionThisPage" class='art-button-green' style="font-family: Arial, Helvetica, sans-serif;">BACK</a>

<fieldset height='1500px'>
    <legend>SPONSOR EDIT PATIENT</legend>
    
    <div style="text-align: center;">
        <center>
            <table width='50%'>
                <tr>
                    <td>
                        <input type="text" id="Date_From" placeholder="Start Date" style="text-align: center;" value="<?=$Date_From?>">
                    </td>
                    <td>
                        <input type="text" id="Date_To" placeholder="End Date" style="text-align: center;" value="<?=$Date_To?>">
                    </td>
                    <td>
                        <a href="#" class="art-button-green" style="font-family: Arial, Helvetica, sans-serif;" onclick="filterDate()">FILTER</a>
                    </td>
                </tr>
            </table>
        </center>
    </div>

    <table width='100%' style="background-color: #fff;">
        <thead style="background-color: #eee;">
            <tr>
                <td style="text-align: center;">S/N</td>
                <td>Patient Number</td>
                <td>Patient Name</td>
                <td>Old Patient Name</td>
                <td>Old Sponsor Name</td>
                <td>New Sponsor Name</td>
                <td>Edit Date Time</td>
                <td>Edited By</td>
            </tr>
        </thead>

        <tbody>
            <?=$output?>
        </tbody>
    </table>
</fieldset>

<script>
    function filterDate(){
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;

        if(Date_From == "" || Date_To == ""){
            alert("Enter Start Date and End Date correctly");
        }else{
            location.href="patient_spounsor_edit.php?Date_From="+Date_From+"&Date_To="+Date_To;
        }
    }
</script>

<script type="text/javascript">
    $(document).ready(function () {
        $('#admittedpatientslist').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From').datetimepicker({value: '', step: 30});
        $('#Date_To').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To').datetimepicker({value: '', step: 30});
    });
</script>

<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<?php include("./includes/footer.php"); ?>