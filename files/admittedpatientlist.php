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
    if(!isset($_SESSION['userinfo'])){
	@session_destroy();
	header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_SESSION['userinfo'])){
	if(isset($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'])){
	    if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] != 'yes'){
		header("Location: ./index.php?InvalidPrivilege=yes");
	    }
	}else{
	    header("Location: ./index.php?InvalidPrivilege=yes");
	}
    }else{
	@session_destroy();
	    header("Location: ../index.php?InvalidPrivilege=yes");
    }
    if(isset($_GET['Registration_ID'])){
        $Registration_ID = $_GET['Registration_ID'];
    }
?>
<?php
    if(isset($_SESSION['userinfo'])){
        if($_SESSION['userinfo']['Doctors_Page_Inpatient_Work'] == 'yes'){
?>
    <script type="text/javascript">
	    function gotolink(){
		var url = "<?php
		if($Registration_ID!=''){
		    echo "Registration_ID=$Registration_ID&";
		}
		if(isset($_GET['Patient_Payment_ID'])){
		    echo "Patient_Payment_ID=".$_GET['Patient_Payment_ID']."&";
		    }
		if(isset($_GET['Patient_Payment_Item_List_ID'])){
		    echo "Patient_Payment_Item_List_ID=".$_GET['Patient_Payment_Item_List_ID']."&";
		    }
		?>SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage";
		var patientlist = document.getElementById('patientlist').value;
		
		if(patientlist=='PATIENT LIST'){
		    document.location = "admittedpatientlist.php?"+url;
		}else{
		    alert("Choose Type Of Patients To View");
		}
	    }
	</script>
	
	<label style='border: 1px ;padding: 8px;margin-right: 7px;' class='art-button-green'>
	<select id='patientlist' name='patientlist'>
	<option>
	    PATIENT LIST
	</option>
	</select>
	<input type='button' value='VIEW' onclick='gotolink()'>
	</label>
<?php 
?>
    <a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>
        BACK
    </a>
<?php  } } ?>
 

<br/><br/><br/>
<center>
    <fieldset>  
    <table width='100%'>
        <tr>
            <td style="text-align:center">    
                <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_From" placeholder="Start Date"/>
                <input type="text" autocomplete="off" style='text-align: center;width:15%;display:inline' id="Date_To" placeholder="End Date"/>&nbsp;
                <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:17%;display:inline'>
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
                <select width="20%"  name='Ward_id' style='text-align: center;width:17%;display:inline' onchange="filterPatient()" id="Ward_id">
                     
                    <?php
                        $session_ward_id = $_SESSION['doctors_selected_ward'];
                        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
                        $Select_Ward=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE Hospital_Ward_ID IN (SELECT ward_id FROM tbl_sub_department_ward WHERE sub_department_id IN(SELECT Sub_Department_ID FROM tbl_employee_sub_department WHERE Employee_ID='$Employee_ID')) AND ward_status='active'");
                        while($Ward_Row=mysqli_fetch_array($Select_Ward)){
                            $ward_id=$Ward_Row['Hospital_Ward_ID'];
                            $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];
                            if($session_ward_id===$ward_id){$sel="selected='selected'";}else{$sel="";}
                            ?>
                            <option value="<?php echo $ward_id?>" <?= $sel ?> ><?php echo $Hospital_Ward_Name?></option>
                        <?php }
                    ?>
                </select>
                <input type='text' name='Search_Patient' style='text-align: center;width:21%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Name~~~~~~~'>
                <input type='text' name='Search_Patient_number' style='text-align: center;width:21%;display:inline' id='Search_Patient_number' oninput="filterPatient()" placeholder='~~~~~~~Search Patient Number~~~~~~~'>
                <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                
            </td>

        </tr>

    </table>
        </fieldset>  
</center>
<br/>
<fieldset>  
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d') ?></span></b></legend>-->
    <legend align=center><b id="dateRange">ADMITTED LIST </b></legend>
       
            <center>
            <table width='100%' border='1'>
                <tr>
            <td >
                 <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'admittedpatientlist_Pre_Iframe.php'; ?>
                 </div>
	    </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<script type="text/javascript">
    $(document).ready(function () { 
        filterPatient(); //called on load
    });

    function filterPatient() {
      document.getElementById('Date_From').style.border="1px solid #C0C1C6";
      document.getElementById('Date_To').style.border="1px solid #C0C1C6";
      
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
        var Search_Patient_number = document.getElementById('Search_Patient_number').value;
        var Sponsor = document.getElementById('Sponsor_ID').value;
        var ward = document.getElementById('Ward_id').value;
        var range='';
        
        if(Date_From !='' && Date_To !=''){
              range="FROM <span class='dates'>"+Date_From+"</span> TO <span class='dates'>"+Date_To+"</span>";
        }
        
        if(Date_From =='' && Date_To !=''){
             alert("Please enter start date");
             
             document.getElementById('Date_From').style.border="2px solid red";
             exit;
        }if(Date_From !='' && Date_To ==''){
             alert("Please enter end date");
             document.getElementById('Date_To').style.border="2px solid red";
             exit;
        }
        
        
         document.getElementById('dateRange').innerHTML ="ADMITTED LIST "+range;
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "admittedpatientlist_Pre_Iframe.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name+ '&Sponsor=' + Sponsor+ '&ward=' + ward+'&Search_Patient_number='+Search_Patient_number,
            
            success: function (html) {
              if(html != ''){
               
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
<script type='text/javascript'>
    function patientnoshow(Patient_Payment_Item_List_ID, Patient_Name) {

        var Confirm_Noshow = confirm("Are You Sure You Want To No Show " + Patient_Name + "?");
        if (Confirm_Noshow) {
            if (window.XMLHttpRequest) {
                mm = new XMLHttpRequest();
            }
            else if (window.ActiveXObject) {
                mm = new ActiveXObject('Micrsoft.XMLHTTP');
                mm.overrideMimeType('text/xml');
            }
            mm.onreadystatechange = AJAXP; //specify name of function that will handle server response....
            mm.open('GET', 'patientnoshow.php?Patient_Payment_Item_List_ID=' + Patient_Payment_Item_List_ID, true);
            mm.send();
            return true;
        } else {
            return false;
        }
    }
    function AJAXP() {
        var data = mm.responseText;
        if (mm.readyState == 4) {
            document.location.reload();
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
<?php
    include("./includes/footer.php");
?>