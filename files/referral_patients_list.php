<?php
    include("./includes/header.php");
    include("./includes/connection.php");
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Reception_Works'])){
	    if($_SESSION['userinfo']['Reception_Works'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['section'])){
    $section = $_GET['section'];
    }else{
	$section='Admission';
    }
?>

<a href='receptionworkspage.php' class='art-button-green'>
        BACK
</a>

<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<br/><br/>
<fieldset>  
    <table width='100%'>
        <tr>
            <td style="text-align: right;">Sponsor Name</td>
            <td>    
                <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()">
                    <option value="All">All Sponsors</option>
                    <?php
                        $select = mysqli_query($conn,"select Sponsor_ID, Guarantor_Name from tbl_sponsor") or die(mysqli_error($conn));
                        while ($sponsor_rows = mysqli_fetch_assoc($select)) {
                    ?>
                            <option value='<?php echo $sponsor_rows['Sponsor_ID']; ?>'><?php echo $sponsor_rows['Guarantor_Name']; ?></option>
                    <?php
                        }
                    ?>
                </select>
            </td>
            <td>
                <select id='status' onchange="filterPatient()">
                    <option value="active">Unprocessed</option>
                    <option value="served">Processed</option>
                    <option value="removed">Removed</option>
                </select>
            </td>
            <td width="30%">
                <input type='text' name='Search_Patient' style='text-align: center;' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Enter Patient Name~~~~~~~'>                
            </td>
            <td width="30%">
                <input type='text' name='Patient_Number' style='text-align: center;' id='Patient_Number' oninput="filter_Patient_Via_Phone()" placeholder='~~~~~~~Enter Patient Number~~~~~~~'>                
            </td>
        </tr>
         
        <tr>
        	 <tr> 
                <td style="text-align:center " colspan="5">
                    <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_From" placeholder="Start Date"/>
                    <input type="text" autocomplete="off" style='text-align: center;width:20%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                    Select Hospital: <select name='Referral_ID' id='Referral_ID' style='text-align: center;display:inline' ">
                    <option value="All">All Referral Hospitals</option>
                    <?php
                        $select_referal = mysqli_query($conn,"select * from tbl_referral_hosp") or die(mysqli_error($conn));
                        while ($referal_rows = mysqli_fetch_assoc($select_referal)) {
                    ?>
                            <option value='<?php echo $referal_rows['hosp_ID']; ?>'><?php echo $referal_rows['ref_hosp_name']; ?></option>
                    <?php
                        }
                    ?>
                </select>

	        	<input type="button" value="Filter" style='text-align: center;display:inline' class="art-button-green" onclick="filterPatient()">
                  </td>
        </tr>
         
    </table>
        </fieldset>  
</center>
<br/>
<fieldset>  
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d') ?></span></b></legend>-->
    <legend align=center><b id="dateRange">PATIENTS TO REFERRAL LIST </b></legend>
       
            <center>
            <table width='100%' border='1'>
                <tr>
            <td >
                 <div align="center" style="display:none" id="referalprogress"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>
       
                 <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'referral_patients_frame.php'; ?>
                 </div>
	    </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<script>
    function filterPatient(bydate=null) {
        var Patient_Name = document.getElementById('Search_Patient').value;
        document.getElementById('Patient_Number').value = '';
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var status = document.getElementById('status').value;

        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Referral_ID = document.getElementById('Referral_ID').value;

        var range='';
        
         document.getElementById('dateRange').innerHTML ="PATIENTS TO REFERRAL LIST  "+range;
         $.ajax({
            type: "GET",
            url: "referral_patients_frame.php",
            data:{Patient_Name:Patient_Name,Sponsor:Sponsor,status:status,Date_From:Date_From,Date_To:Date_To,Referral_ID:Referral_ID},
            beforeSend: function (xhr) {
                $('#referalprogress').show();
            },
            success: function (html) {
                $('#Search_Iframe').html(html);
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientList').DataTable({
                    'bJQueryUI': true
                });
            }, complete: function (jqXHR, textStatus) {
                $('#referalprogress').hide();
            }
        });
    }
</script>
<script>
    function filter_Patient_Via_Phone() {
        var Patient_Number = document.getElementById('Patient_Number').value;
        var status = document.getElementById('status').value;
        document.getElementById('Search_Patient').value = '';
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var range='';
         document.getElementById('dateRange').innerHTML ="PATIENTS TO REFERRAL LIST  "+range;
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "referral_patients_frame.php",
            data: 'Patient_Number=' + Patient_Number+ '&Sponsor=' + Sponsor +'&status='+status,
             beforeSend: function (xhr) {
                $('#referalprogress').show();
            },
            success: function (html) {
               $('#Search_Iframe').html(html);
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientList').DataTable({
                    'bJQueryUI': true
                });
            }, complete: function (jqXHR, textStatus) {
                $('#referalprogress').hide();
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#patientList').DataTable({
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
<script>
 function  processpatient(id){
     if(!confirm("Are you sure you want to process this patient?")){
         exit;
     }
      $.ajax({
            type: "POST",
            url: 'requests/get_referral_form.php',
            data: 'actionref=process&id=' + id,
             beforeSend: function (xhr) {
                $('#referalprogress').show();
            },
            success: function (html) {
              if(html=='1'){
                  window.location.reload();
              }else{
                  alert('An error has occured please try again later');
              }
            }, complete: function (jqXHR, textStatus) {
                $('#referalprogress').hide();
            }
        });
 }
</script>

<script type="text/javascript">
	function filterReferralPatients(){
		var Date_From=$('#Date_From').val();
		var Date_To =$('#Date_To').val();
		var Referral_ID=$('#Referral_ID').val();
		alert('am comming now '+Date_From+' and '+Date_To+ ' and '+Referral_ID);
		$.ajax({
			url:,
			type:'post',
			data:{Date_From:Date_From,Date_To:Date_To,Referral_ID:Referral_ID},
			beforeSend: function (xhr) {
                $('#referalprogress').show();
            },
            success:function(html){
            	$('#Search_Iframe').html(html);
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientList').DataTable({
                    'bJQueryUI': true
                });
            },
            complete: function (jqXHR, textStatus) {
                $('#referalprogress').hide();
            }
		});
	}
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