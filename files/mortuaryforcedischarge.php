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
$section = $_GET['section'];
?>
<!-- 
<a href='forceadmit.php?from=forceDischarge&section=<?php echo $section; ?>' class='art-button-green'>
   DISCHARGE ON REQUEST
</a> -->
<?php
// if(isset($_SESSION['userinfo']['Patients_Billing_Works'])){
//      if($_SESSION['userinfo']['Patients_Billing_Works'] == 'yes'){
//       echo "<a href='billingwork.php?from=forceDischarge&BillingWork=BillingWorkThisPage' class='art-button-green'>
//                CLEAR BILL
//             </a>";
            
//      }
// }
// if (isset($_SESSION['userinfo'])) {
//     if ($_SESSION['userinfo']['Revenue_Center_Works'] == 'yes') {
//         if ($section == 'Admission') {
            
//             echo"<a href='admissionworkspage.php?section=Admission&SearchListDirectCashPatientBilling=SearchListDirectCashPatientBillingThisPage' class='art-button-green'>
//                 BACK
//             </a>";
//         }
//     }
// }else{
    
    if(isset($_GET['from']) && $_GET['from'] == "forceAdmit") {
        echo "<a href='forceadmit' class='art-button-green'>BACK</a>";
    } else {
        echo "<a href='morguepage.php?MorgueSupervisorsPage=MorguePanelPage' class='art-button-green'>BACK</a>";
    }
    
            
        // }
    

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
                <select width="20%"  name='Ward_id' style='text-align: center;width:15%;display:inline' onchange="filterPatient()" id="Ward_id">
                      <option value="All">All Ward</option>
                    <?php
                        $Select_Ward=mysqli_query($conn,"SELECT Hospital_Ward_ID,Hospital_Ward_Name FROM tbl_hospital_ward WHERE ward_type <> 'ordinary_ward' AND ward_status = 'active'");
                        while($Ward_Row=mysqli_fetch_array($Select_Ward)){
                            $ward_id=$Ward_Row['Hospital_Ward_ID'];
                            $Hospital_Ward_Name=$Ward_Row['Hospital_Ward_Name'];?>
                            <option value="<?php echo $ward_id?>"><?php echo $Hospital_Ward_Name?></option>
                        <?php }
                    ?>
                </select>
                <input type='text' name='Search_Patient' style='text-align: center;width:15%;display:inline' id='Search_Patient' oninput="filterPatient()" placeholder='~~~~~~~Search By Name~~~~~~~'>
                <input type='text' name='Patient_Number' style='text-align: center;width:15%;display:inline' id='Patient_Number' oninput="filterPatient()" placeholder='~~~~~~~Search By Number~~~~~~~'>
                <input type="button" value="Filter" class="art-button-green" onclick="filterPatient()">
                
            </td>

        </tr>

    </table>
        </fieldset>  
</center>
<br/>
<fieldset> 
    
            <!--<legend align=center><b id="dateRange">ADMITTED LIST TODAY <span class='dates'><?php //echo date('Y-m-d') ?></span></b></legend>-->
    <legend align=center><b id="dateRange">LIST OF ADMITTED BODIES</b></legend>
            <center>
            <table width='100%' border='1'>
                <tr>
            <td >
                 <div id="Search_Iframe" style="height: 400px;overflow-y: auto;overflow-x: hidden">
                        <?php include 'search_list_mortuary_discahrge_admited_Iframe_force.php'; ?>
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
        var Patient_Number = document.getElementById('Patient_Number').value;
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
        
        
         document.getElementById('dateRange').innerHTML ="LIST OF ADMITTED BODIES "+range;
         document.getElementById('Search_Iframe').innerHTML = '<div align="center" style="" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>';
        $.ajax({
            type: "GET",
            url: "search_list_mortuary_discahrge_admited_Iframe_force.php",
            data: 'Date_From=' + Date_From + '&Date_To=' + Date_To + '&Patient_Name=' + Patient_Name+ '&Sponsor=' + Sponsor+ '&ward=' + ward +'&Patient_Number='+ Patient_Number,
            
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
<script>
  function forceadmit(admission_ID,Registration_ID,Patient_Bill_ID,Sponsor_ID,Folio_Number,Check_In_ID){
  //alert('Select discharge reason');
  
//  alert(Patient_Bill_ID+"=>"+Registration_ID+"=>"+Sponsor_ID+"=>"+Folio_Number+"=>"+Check_In_ID);
  var resId=$('#reason_'+admission_ID);
  var Discharge_Reason=resId.val();
  if(Discharge_Reason=='')
  {
  alert('Select discharge reason');
  resId.css('border','3px solid red');
  exit;
  }
  

      if(confirm("Are you sure you want to discharge this Body. Continue?")){
            if (window.XMLHttpRequest) {
                myobj = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myobj = new ActiveXObject('Micrsoft.XMLHTTP');
                myobj.overrideMimeType('text/xml');
            }

            myobj.onreadystatechange = function () {
              var  data = myobj.responseText;
                if (myobj.readyState == 4) {
                   if(data == '1'){
                       alert("Processed successifully.Body is in discharge state now!");
                       ApApprove_Patient_Bill(admission_ID,Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID);
//                       filterPatient();
                   }else{
                     alert('An error has occured try again or contact system administrator');
                   }
                   // document.getElementById('Patients_Fieldset_List').innerHTML = data6;
                }
            }; //specify name of function that will handle server response........

            myobj.open('GET', 'doctor_discharge_release_force.php?admission_ID=' + admission_ID + '&Discharge_Reason=' + Discharge_Reason+'&fromnurse=fromnurse' , true);
            myobj.send();
        }
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
<!--kuntakinte ADD FUNCTION OF CHECK BILL HERE-->
<script>
    function Approve_Patient_Bill(Admision_ID,Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
		
//        var Transaction_Type = document.getElementById("Transaction_Type").value;
        var Transaction_Type ="cash";
//        var Admision_ID = '';
        var Confirm_Message = confirm("Are you sure you want to approve selected bill?");
        if (Confirm_Message == true) {
            if (window.XMLHttpRequest) {
                myObjectVerifyItems = new XMLHttpRequest();
            } else if (window.ActiveXObject) {
                myObjectVerifyItems = new ActiveXObject('Micrsoft.XMLHTTP');
                myObjectVerifyItems.overrideMimeType('text/xml');
            }
            myObjectVerifyItems.onreadystatechange = function () {
                data01 = myObjectVerifyItems.responseText;
                
                if (myObjectVerifyItems.readyState == 4) {
                   
                    var feedback = data01;
                    if (feedback == 'yes') {
                        Approve_Bill_Process(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID);
                    } else if (feedback == 'not') {
                        $("#Not_Ready_To_Bill").dialog("open"); //not ready to bill
                    } else if (feedback == 'true') {
                        $("#Patient_Already_Cleared").dialog("open");
                    } else if (feedback == 'mortuary_not') {
						$("#Body_Not_Ready_To_Bill").dialog("open");
                        //alert("Mwili bado haujaruhusiwa kutoka!!");
                    } else {
                        $("#Approval_Warning_Message").dialog("open"); //something happened
                    }
                }
            }; //specify name of function that will handle server response........
            myObjectVerifyItems.open('GET', 'Approve_Patient_Bill_Verify.php?Transaction_Type=' + Transaction_Type + '&Admision_ID=' + Admision_ID, true);
            myObjectVerifyItems.send();
        }
    }
        </script>
        <script type="text/javascript">
function Approve_Bill_Process(Admision_ID,Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID, Registration_ID) {
        var Transaction_Type = document.getElementById("Transaction_Type").value;
//        var Registration_ID = '<?php echo $Registration_ID; ?>';
//        var Admision_ID = '<?php echo $Admision_ID; ?>';
        // var totalmorgueprice = '<?php echo $totalmorgueprice; ?>';
        var Grand_Total = $("#Grand_Total").val();
        if (window.XMLHttpRequest) {
            myObjectApprove = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            myObjectApprove = new ActiveXObject('Micrsoft.XMLHTTP');
            myObjectApprove.overrideMimeType('text/xml');
        }
        myObjectApprove.onreadystatechange = function () {
            data99991 = myObjectApprove.responseText;
            if (myObjectApprove.readyState == 4) {
                var feedback = data99991;
                //alert(feedback)
                if (feedback == '100') { //refund required
				alert("Bill Cleared Successfully");
//                    Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                    $("#Add_Item").hide();
                    $("#Refund_Required").dialog("open");
                } else if (feedback == '200') { //no enough paymments to clear bill
                    $("#No_Enough_Payments").dialog("open");
                } else if (feedback == '300') { //error occur during the process
                    $("$Error_During_Process").dialog("open");
                } else if (feedback == '400') { //credit bill ~ no complexity
//                    Display_Transaction(Patient_Bill_ID, Folio_Number, Sponsor_ID, Check_In_ID)
                    $("#Credit_Bill").dialog("open");
                }else{
			//alert("empty query");		// alert("Bill Cleared Successfully");
				}
            }
        }; //specify name of function that will handle server response........

        myObjectApprove.open('GET', 'Approve_Patient_Bill.php?Patient_Bill_ID=' + Patient_Bill_ID + '&Folio_Number=' + Folio_Number + '&Sponsor_ID=' + Sponsor_ID + '&Check_In_ID=' + Check_In_ID + '&Transaction_Type=' + Transaction_Type + '&Registration_ID=' + Registration_ID + '&Admision_ID=' + Admision_ID + '&Grand_Total=' + Grand_Total, true);
        myObjectApprove.send();
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