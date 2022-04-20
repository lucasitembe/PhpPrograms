<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
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
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
        ?>

        <?php
    }
}
?>

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Admission_Works'] == 'yes') {
        ?>
       
<a href='searchpatientinward.php' class='art-button-green'>
            BACK
        </a>
        <?php
    }
}
?>

<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Age = $Today - $original_Date;
}
?>

<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>

<script language="javascript" type="text/javascript">
    function searchPatient() {
        ward_id = document.getElementById('ward').value;
        Search_Patient = document.getElementById('Search_Patient').value;
        document.getElementById('Search_Iframe').innerHTML =
                "<iframe width='100%' height=380px src='searchpartientward.php?Search_Patient=" + Search_Patient + "&ward_id=" + ward_id + "'></iframe>";
    }
</script>
<br/><br/>
<fieldset>
    <center>
        <table width='100%'>
            <tr>
                <td>    
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:20%;display:inline'>
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
                    <select width="20%"  name='Ward_id' style='text-align: center;width:20%;display:inline' onchange="filterPatient()" id="Ward_id">
                        <!--<option value="All">All Ward</option>-->
                           <?php
                        $SubDepWardID = $_SESSION['Admission_Sub_Department_ID'];
                        $check_sub_department_ward = mysqli_query($conn,"SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id='$SubDepWardID'");
                            if (mysqli_num_rows($check_sub_department_ward)>0) {
                                $data = mysqli_fetch_assoc($check_sub_department_ward);
                                $WardID = $data['ward_id'];
                            }
                        
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $Select_Ward=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID'))");
                        while($Ward_Row=mysqli_fetch_array($Select_Ward)){
                            $ward_id=$Ward_Row['Hospital_Ward_ID'];
                            $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
                            if($WardID==$ward_id){$selected="selected='selected'";}else{$selected="";}
                            ?>
                            <option value="<?php echo $ward_id?>" <?= $selected ?>><?php echo $Hospital_Ward_Name?></option>
                        <?php }
                    ?>
                    </select>
                    <input type='text' name='Search_Patient' style='text-align: center;width:21%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Name~~~~~~~'>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">

                </td>

            </tr>

        </table>
    </center>
</fieldset>
<br>
<fieldset>  

    <!--<legend align=center><b id="dateRange">LIST OF ADMITTED PATIENTS TODAY <span class='dates'><?php //echo date('Y-m-d')        ?></span></b></legend>-->
    <legend align=center><b id="dateRange">LIST OF DISCHARGED PATIENTS </b></legend>

    <center>
        <table width='100%' border='1'>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php // include 'searchpartientwarddischarged.php'; ?>
                    </div>
                   <!--<iframe width='100%' height=380px src='admittedpatientlist_Pre_Iframe.php?Patient_Name="+Patient_Name+"'></iframe>-->
                </td>
            </tr>
        </table>
    </center>
</fieldset>

<br/>
<script>
    function filterPatient() {

        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var ward = document.getElementById('Ward_id').value;


        document.getElementById('dateRange').innerHTML = "LIST OF DISCHARGED PATIENTS FROM <span class='dates'>" + Date_From + "</span> TO <span class='dates'>" + Date_To + "</span>";
        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "searchpartientwarddischarged.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name + '&Sponsor=' + Sponsor + '&ward=' + ward,
            success: function (html) {
                if (html != '') {

                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#admittedpatientslist').DataTable({
                        'bJQueryUI': true
                    });
                }
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        filterPatient();
        $('#admittedpatientslist').DataTable({
            "bJQueryUI": true

        });

        $('#Date_From,#start_date_op').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:    'now'
        });
        $('#Date_From,#start_date_op').datetimepicker({value: '', step: 30});
        $('#Date_To,#end_date_op').datetimepicker({
            dayOfWeekStart: 1,
            lang: 'en',
            //startDate:'now'
        });
        $('#Date_To,#end_date_op').datetimepicker({value: '', step: 30});
        $("#showdataResult").dialog({autoOpen: false, width: '98%', height: 550, title: 'PATIENT ORDERED ITEMS', modal: true});
        
        //autocomplete search box;

        $('select').select2();

    });
</script>


<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<script src="js/select2.min.js"></script>
<?php
include("./includes/footer.php");
?>

