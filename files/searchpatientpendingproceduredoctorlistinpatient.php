<?php
include './includes/header.php';
$DateGiven = date('Y-m-d');
?>
<input type='button' name='patient_outpatient' id='patient_outpatient' value='DIRECT DEPT - OUTPATIENT' onclick='outpatient()' class='art-button-green' />
<input type='button' name='patient_inpatient' id='patient_inpatient' value='DIRECT DEPT - INPATIENT' onclick='inpatient()' class='art-button-green' />

<?php
  if(isset($_GET['Section']) && $_GET['Section']=='Doctor'){
        ?>
<a href='doctorspageinpatientwork.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>' class='art-button-green'>
        BACK
    </a>
<?php
  }else{
      ?>
   <a href='inpatientclinicalnotes.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&consultation_ID=<?php echo $_GET['consultation_ID'] ?>&item_ID=<?php echo $_GET['item_ID'] ?>' class='art-button-green'>
        BACK
    </a>
<?php
}
?>
	<br/>
	
<?php
$withPatient=false;
$Registration='';
if(isset($_GET['sectionpatnt']) && $_GET['sectionpatnt']='doctor_with_patnt'){
    $withPatient=true;
    $Registration=$_GET['Registration_ID'];
}

$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

while ($row = mysqli_fetch_array($query)) {
    $dataSponsor.= '<option value="' . $row['Sponsor_ID'] . '">' . $row['Guarantor_Name'] . '</option>';
}
?>
<fieldset style='margin-top:30px;'>
    <legend id="resultsconsultationLablist" style="font-weight: bold;font-size: 16px;background-color: #006400; padding:10px; color:white;width: auto;" align="right">PENDING / NOT APPLICABLE PROCEDURE PATIENTS LIST</legend>
    <center>
        <hr width="100%">
    </center>

    <center>

        <table  class="hiv_table" style="width:100%">
            <tr>

                <td style="text-align: center">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                    <input type='text' autocomplete="off" name='Search_Patient' id='Search_Patient' style='text-align: center;width:35%;display:inline' oninput='searchPatient(this.value)'  placeholder='~~~~~~~~~~~~~  Global Search Patient Name  ~~~~~~~~~~~~~~~~~~~'>
                </td>
            </tr>
            <tr>
                <td id='Search_Iframe'>
                    <div id="iframeDiv" style="height: 460px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'pendingproceduredoctorlistinpatient_iframe.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<br/>
<center>
    <?php
$section="";
if(isset($_GET['Section']) && $_GET['Section']=='Doctor'){
    $section="Section=Doctor&Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&";
}else{
    $section="sectionpatnt=doctor_with_patnt&Registration_ID=".$_GET['Registration_ID']."&consultation_ID=".$_GET['consultation_ID']."&item_ID=". $_GET['item_ID']."&";
}
       
    echo '<a href="doctorprocedurelistinpatient.php.php?'.$section.'" class="art-button-green">UNCONSULTED PROCEDURES</a>';
   ?>
</center> 
<script type='text/javascript'>
    function outpatient() {
        //alert('outpatient');
        var winClose = popupwindow('directdepartmentalpayments.php?location=otherdepartment&DirectDepartmentalList=DirectDepartmentalListThisForm', 'Outpatient Item Add', 1300, 700);

    }
</script>
<script type='text/javascript'>
    function inpatient() {
        var winClose = popupwindow('adhocinpatientlist.php?location=otherdepartment&AdhocInpatientList=AdhocInpatientListThisPage', 'Intpatient Item Add', 1300, 700);

    }
</script>

<script>
    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }


</script>
<?php
include("./includes/footer.php");
?>


 <!--<script src="css/jquery.js"></script>-->
<!--    <script src="css/jquery.datetimepicker.js"></script>
<script src="css/jquery-ui.js"></script>
<script src="css/jquery-ui.css"></script>
-->
<script>
    function filterLabpatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('sponsorID').value;

        if (Date_From == '' || Date_To == '') {
            alert('Please enter both dates to filter');
            exit;
        }

        document.getElementById('iframeDiv').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }

        mm.onreadystatechange = function () {
            var data = mm.responseText;
            if (mm.readyState == 4 && mm.status == 200) {
                document.getElementById('iframeDiv').innerHTML = data;
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientLabList').DataTable({
                    "bJQueryUI": true

                });
            }
        };


        mm.open('GET', 'pendingproceduredoctorlist_iframe.php?filterlabpatientdate=true&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor, true);
        mm.send();
    }

</script>   
<script >
    function searchPatient(Patient_Name) {
        //alert(Employee_IDs);
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('sponsorID').value;
        var filterDate = '';
        if (Date_From != '' && Date_To != '') {
            filterDate = '&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor;
        } else {
            filterDate = '&Sponsor=' + Sponsor;
        }

        document.getElementById('iframeDiv').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';


        // alert('Patient_Name=' + Patient_Name + '&searchPartient=true'+filterDate);
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        }
        else if (window.ActiveXObject) {
            mm = new ActiveXObject('Micrsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
        mm.open('GET', 'pendingproceduredoctorlist_iframe.php?Patient_Name=' + Patient_Name + '&searchPartient=true' + filterDate, true);
        mm.send();

    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4 && mm.status == 200) {
            document.getElementById('iframeDiv').innerHTML = data;
            $.fn.dataTableExt.sErrMode = 'throw';
            $('#patientLabList').DataTable({
                "bJQueryUI": true

            });
        }

    }

</script> 

<script type="text/javascript">
    $(document).ready(function () {
        $('#viewlabPatientList,#patientLabList').DataTable({
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
<!--End datetimepicker-->