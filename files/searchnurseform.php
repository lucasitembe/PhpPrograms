<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    
    $_SESSION['outpatient_nurse_com'] = 'yes';
    
   //unset($_SESSION['userinfo']['Admission_Works']);
   
    
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Nurse_Station_Works'])){
	    if($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
?>

<?php
	if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes'){ 
?>
    <a href='viewnursepatient.php' class='art-button-green'>
        VIEW CHECKED
    </a>
<?php  } } ?>
<!-- <input type='button' name='patient_outpatient' id='patient_outpatient' value='DIRECT DEPT - OUTPATIENT' onclick='outpatient()' class='art-button-green' />
<input type='button' name='patient_inpatient' id='patient_inpatient' value='DIRECT DEPT - INPATIENT' onclick='inpatient()' class='art-button-green' /> -->

<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes'){ 
?>
    <a href='searchnurseform.php' class='art-button-green'>
      PATIENTS LISTS
    </a>
    <!-- <a href="opd_nursing_handling_duty.php" class="art-button-green">NURSE HANDLING DUTY</a> -->
<?php  } } ?>

<script language="javascript" type="text/javascript">
    function searchPatient(Patient_Name){
        document.getElementById('Search_Iframe').innerHTML = 
		"<iframe width='100%' height=380px  src='searchnurseform2.php?Patient_Name="+Patient_Name+"'></iframe>";
    }
</script>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<br/><br/><br/>
<center>
    <fieldset>  
    <table width="100%">
        <tr>
            <td style="text-align:center">
                <input type="text" autocomplete="off" style='text-align: center;width:16%;display:inline' id="Date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:16%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:16%;display:inline'>
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
                <input type='text' name='Search_Patient' style='text-align: center;width:16%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~~~~~~~~Search Patient Name~~~~~~~~~~~~~~~~~~~~~'>
                <input type='text' name='Search_Patient' style='text-align: center;width:16%;display:inline' id='Search_Patient_Number' oninput="filterPatient()" placeholder='~~Search Patient Number~~'>
                  <!-- <input type='text' name='Search_Patient' style='text-align: center;width:14%;display:inline' id='Search_Patient_Old_Number' oninput="filterPatient()" placeholder='~~Search Patient Old Number~~'> -->
                <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()"><br/>

                
            </td>
        </tr>

    </table>
        </fieldset>  
</center>

<fieldset>  
    <legend align=center ><b id="dateRange">PATIENT LIST <span class='dates'><?php echo date('Y-m-d')?></span> </b></legend>
    <center>
        <table width=100% border=1>
            <tr>
                <td >
                    <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                       <?php include 'searchnurseform_iframe.php'; ?>
                    </div>
                </td>
            </tr>
        </table>
    </center>
</fieldset><br/>
<script type='text/javascript'>
      function outpatient(){
        //alert('outpatient');
        var winClose=popupwindow('directdepartmentalpayments.php?location=otherdepartment&DirectDepartmentalList=DirectDepartmentalListThisForm', 'Outpatient Item Add', 1300, 700);
     
      }
    </script>
    <script type='text/javascript'>
      function inpatient(){
         var winClose=popupwindow('adhocinpatientlist.php?location=otherdepartment&AdhocInpatientList=AdhocInpatientListThisPage', 'Intpatient Item Add', 1300, 700);
     
      }
    </script>
    
    <script>
  function popupwindow(url, title, w, h) {
  var  wLeft = window.screenLeft ? window.screenLeft : window.screenX;
   var wTop = window.screenTop ? window.screenTop : window.screenY;

    var left = wLeft + (window.innerWidth / 2) - (w / 2);
    var top = wTop + (window.innerHeight / 2) - (h / 2);
    var mypopupWindow= window.showModalDialog(url, title,'dialogWidth:' + w + '; dialogHeight:' + h+'; center:yes;dialogTop:' + top + '; dialogLeft:' + left );//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);
      
      return mypopupWindow;
}


</script>
<script>
    function filterPatient() {
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Patient_Number = document.getElementById('Search_Patient_Number').value;
        // var Patient_Old_Number = document.getElementById('Search_Patient_Old_Number').value;

        var range;
         if(Date_From !='' && Date_To !=''){
              range="FROM <span class='dates'>"+Date_From+"</span> TO <span class='dates'>"+Date_To+"</span>";
        }

        document.getElementById('dateRange').innerHTML = "PATIENT LIST "+range;

        document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';

        $.ajax({
            type: "GET",
            url: "searchnurseform_iframe.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name+'&Sponsor=' + Sponsor+ '&Patient_Number=' + Patient_Number,
            success: function (html) {
                if (html != '') {
                    $('#Search_Iframe').html(html);
                    $.fn.dataTableExt.sErrMode = 'throw';
                    $('#PatientsList').DataTable({
                        'bJQueryUI': true
                    });
                }
            }
        });
    }
</script>


<script type="text/javascript">
    $(document).ready(function () {
        $('#PatientsList').DataTable({
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
<?php
    include("./includes/footer.php");
?>