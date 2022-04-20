<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}

if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Admission_Works'])) {
//        if ($_SESSION['userinfo']['Admission_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
$section = $_GET['section'];
?>

<?php /*  if(isset($_SESSION['userinfo'])){
  if($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes'){
  ?>
  <a href='#?SearchListPatientBilling=SearchListPatientBillingThisPage' class='art-button-green'>
  INPATIENT LIST
  </a>
  <?php  } } */ ?>


<a href='forceadmit.php?section=<?php echo $section; ?>' class='art-button-green'>
    NURSE DISCHARGE PATIENT INITIAL PROCESS
</a>
<?php 
if(isset($_GET['from_billing'])){
  ?>
<a href='billingwork.php?BillingWork=BillingWorkThisPage' class='art-button-green'>
                BACK
</a>
 <?php  
}else{
?>
<a href='admissionworkspage.php?section=Admission&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
                BACK
            </a>
<?php 
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
<br/><br/> <fieldset>  
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
        </fieldset>  
</center>
<br/>
<fieldset>  
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d') ?></span></b></legend>-->
    <legend align=center><b id="dateRange">CLEARED PATIENT READY TO DISCHARGE LIST </b></legend>
       
            <center>
            <table width='100%' border='1'>
                <tr>
            <td >
                 <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php // include 'search_list_patient_discahrge_admited_Iframe.php'; ?>
                 </div>
	    </td>
        </tr>
            </table>
        </center>
</fieldset><br/>
<script>
    function filterPatient() {
      document.getElementById('Date_From').style.border="1px solid #C0C1C6";
      document.getElementById('Date_To').style.border="1px solid #C0C1C6";
      
        var Date_From = document.getElementById('Date_From').value;
        var Date_To = document.getElementById('Date_To').value;
        var Patient_Name = document.getElementById('Search_Patient').value;
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
        
        
         document.getElementById('dateRange').innerHTML ="CLEARED PATIENT READY TO DISCHARGE LIST  "+range;
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "search_list_patient_discahrge_admited_Iframe.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name+ '&Sponsor=' + Sponsor+ '&ward=' + ward,
            
            success: function (html) {
              if(html != ''){
               
                $('#Search_Iframe').html(html);
                $.fn.dataTableExt.sErrMode = 'throw';
                $('#patientList').DataTable({
                    'bJQueryUI': true
                });
            }
            }
        });
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#patientList').DataTable({
            "bJQueryUI": true

        });
        filterPatient();
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