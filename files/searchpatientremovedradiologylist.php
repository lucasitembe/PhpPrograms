<?php
include './includes/header.php';
$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

 if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Radiology_Works'])){
	    if($_SESSION['userinfo']['Radiology_Works'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes'){
			header("Location: ./index.php?InvalidPrivilege=yes");
	    } else {
                if($_SESSION['userinfo']['Radiology_Works'] == 'yes'){   
					@session_start();
                    if(!isset($_SESSION['Radiology_Supervisor'])){ 
                        header("Location: ./deptsupervisorauthentication.php?SessionCategory=Radiology&InvalidSupervisorAuthentication=yes");
                    }
				}
            }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }

$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];
$DateGiven = date('Y-m-d');
?>
<input type='button' name='patient_outpatient' id='patient_outpatient' value='DIRECT DEPT - OUTPATIENT' onclick='outpatient()' class='art-button hide' />
<input type='button' name='patient_inpatient' id='patient_inpatient' value='DIRECT DEPT - INPATIENT' onclick='inpatient()' class='art-button hide' />
<a href='consultedpatientradiologylist.php' class='art-button-green'>
        CONSULTED PATIENTS
    </a>
<a href='radiologyworkspage.php?RadiologyWorkPage=RadiologyWorkPageThisPage' class='art-button-green'>
        BACK
    </a>
	<br/>
	
<?php


$query = mysqli_query($conn,"SELECT Sponsor_ID,Guarantor_Name FROM tbl_sponsor") or die(mysqli_error($conn));
$dataSponsor = '';
$dataSponsor.='<option value="All">All Sponsors</option>';

//subcategory

$query2 = mysqli_query($conn,"SELECT sb.Item_Subcategory_ID,sb.Item_Subcategory_Name FROM `tbl_item_subcategory` sb INNER JOIN tbl_items i ON i.`Item_Subcategory_ID` = sb.`Item_Subcategory_ID` WHERE i.Consultation_Type='Radiology' GROUP BY i.`Item_Subcategory_ID` ORDER BY sb.Item_Subcategory_Name ") or die(mysqli_error($conn));
$dataSubCategory = '';
$dataSubCategory.='<option value="All">All Subcategory</option>';

while ($row = mysqli_fetch_array($query2)) {
    $dataSubCategory.= '<option value="' . $row['Item_Subcategory_ID'] . '">' . $row['Item_Subcategory_Name'] . '</option>';
}
//echo $count;
?>
<fieldset style='margin-top:30px;'>
    <legend id="resultsconsultationLablist" style="font-weight: bold;font-size: 16px;background-color: #006400; padding:10px; color:white;width: auto;" align="right">REMOVED RADIOLOGY PATIENTS LIST</legend>
    <center>
        <hr width="100%">
    </center>

    <center>

        <table  class="hiv_table" style="width:100%">
            <tr>

                <td style="text-align: center">
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    <select id="subCatID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSubCategory ?>
                    </select>
                    <select id="sponsorID" style='text-align: center;padding:4px; width:15%;display:inline'>
                        <?php echo $dataSponsor ?>
                    </select>
                    <input type="button" value="Filter" class="art-button-green" onclick="filterLabpatient()">
                    <input type='text' autocomplete="off" name='Search_Patient' id='Search_Patient' style='text-align: center;width:25%;display:inline' oninput="filterLabpatient()"  placeholder='~~~~~~~~~~~~~  Global Search Patient Name  ~~~~~~~~~~~~~~~~~~~'>
                </td>
            </tr>
            <tr>
                <td id='Search_Iframe'>
                    <div id="iframeDiv" style="height: 460px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'radiologyremovedlist_iframe.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset>
<br/>
<center>
    <?php
        echo '<a href="searchpatientradiologylist.php" class="art-button-green">UNCONSULTED RADIOLOGY</a>';
        echo '<a href="searchpatientpendingradiologylist.php" class="art-button-green">PENDING / NOT DONE</a>';
   ?>
</center> 
<script>
    function filterLabpatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('sponsorID').value;
        var Patient_Name=document.getElementById('Search_Patient').value;
        var SubCategory=document.getElementById('subCatID').value;
        
        if (Date_From != '' && Date_To == '') {
            alert('Please enter both dates to filter');
            document.getElementById('Search_Patient').value='';
            exit;
        }
        
        if (Date_From == '' && Date_To != '') {
            alert('Please enter both dates to filter');
            document.getElementById('Search_Patient').value='';
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


        }; //specify name of function that will handle server response........


        mm.open('GET', 'radiologyremovedlist_iframe.php?filterlabpatientdate=true&Date_From=' + Date_From + '&Date_To=' + Date_To + '&Sponsor=' + Sponsor+ '&Patient_Name=' + Patient_Name+ '&SubCategory=' + SubCategory, true);
        mm.send();
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




<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js" type="text/javascript"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
<script src="css/jquery-ui.js"></script>
<!--End datetimepicker-->
