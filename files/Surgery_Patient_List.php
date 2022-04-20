<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
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
    if (isset($_GET['Section'])) {
        if (strtolower($_GET['Section']) == 'inpatient') {
            echo "<a href='doctorsinpatientworkspage.php?DoctorsInpatient=DoctorsInpatientThisPage' class='art-button-green'>BACK</a>";
        } else {
            echo "<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>BACK</a>";
        }
    } else {
        echo "<a href='doctorsworkspage.php?RevenueCenterWork=RevenueCenterWorkThisPage' class='art-button-green'>BACK</a>";
    }
}

$pre_paid = $_SESSION['hospitalConsultaioninfo']['set_pre_paid'];

?>
<script type='text/javascript'>
    function access_Denied() {
        alert("Access Denied");
        document.location = "./index.php";
    }
</script>
<style>
    select{
        padding:5px;
    }

    .dates{
        color:#cccc00;
    }

    .daterange{
        background-color: rgb(3, 125, 176);
        color: white;
        display: block;
        width: 99.2%;
        padding: 4px;
        font-family: times;
        font-size: large;
        font-weight: bold;
    }
</style> 
<br/><br/>
<?php
if (isset($_POST[''])) {
    $Date_From = mysqli_real_escape_string($conn,$_POST['Date_From']);
    $Date_To = mysqli_real_escape_string($conn,$_POST['Date_To']);
} else {
    $Date_From = '';
    $Date_To = '';
}

$employee_ID = $_SESSION['userinfo']['Employee_ID'];
?>

<br/>
<fieldset style='overflow-y:scroll; height:440px'>
    <center>

        <legend  align="right" style="background-color:#006400;color:white;padding:5px;"><form action='individualdoctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data"></legend>	 

        <!--<form action='doctorsPerformanceSummaryFilter.php?DoctorsPerformanceReportThisPage=ThisPage' method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">-->
        <br/>
        <table width='95%'>
            <tr>
                <td style="text-align: center"><b>From</b></td>
                <td style="text-align: center">
                    <input type='text' name='Date_From' id='date_From' required='required'>    
                </td>
                <td style="text-align: center">To</td>
                <td style="text-align: center"><input type='text' name='Date_To' id='date_To'        required='required'></td>    
                <td style="text-align: center">
                    <select name='Sponsor_ID' id='Sponsor_ID' onchange="filterPatient()" style='text-align: center;width:100%;display:inline'>
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
                </td>
                
                <td style="text-align: center">
                    <select name='Employee_ID' id='Employee_ID' style='text-align: center;width:100%;display:inline'>
                        <option value="All">All Employees</option>
                        <?php
                        $qr = "SELECT * FROM tbl_employee";
                        $sponsor_results = mysqli_query($conn,$qr);
                        while ($sponsor_rows = mysqli_fetch_assoc($sponsor_results)) {
                            ?>
                            <option value='<?php echo $sponsor_rows['Employee_ID']; ?>'><?php echo $sponsor_rows['Employee_Name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
                <td>
                    <input type="text" id="patient_Name" placeholder="~~~Search Patient Name~~~"> 
                </td>
                <td>
                    
                </td>
                <td style='text-align: center;'>
                    <input type='submit' name='Print_Filter' id='Print_Filter' class='art-button-green' value='FILTER'>
                </td>
                
                <td style='text-align: center;'>
                    <input type='button' name='Print_Filter' id='Print_List' class='art-button-green' value='PRIVIEW &amp; PRINT'>
                </td>
            </tr>	
        </table>
        </form> 
    </center>
    <!--End datetimepicker-->
    <?php
    // date_default_timezone_set('Africa/Dar_es_Salaam');
    $Date_From = ''; //@$_POST['Date_From'];
    $Date_To = ''; //@$_POST['Date_To'];

    $sqltodaydate = mysqli_query($conn,'SELECT NOW() AS todayDate')or die(mysqli_error($conn));
    $today = mysqli_fetch_assoc($sqltodaydate)['todayDate'];

    //die($today);
    if (!isset($_GET['Date_From'])) {
        $Date_From = $today;
    } else {
        $Date_From = $_GET['Date_From'];
    }
    if (!isset($_GET['Date_To'])) {
        $Date_To = $today;  //date('Y-m-d H:m');;
    } else {
        $Date_To = $_GET['Date_To'];
    }if (!isset($_GET['Sponsor_ID'])) {
        $Sponsor = "All";
    } else {
        $Sponsor = $_GET['Sponsor_ID'];
    }
    ?>
    <br>
    <legend align='center' style="background-color:#037DB0;color: white;padding: 5px;text-align: center">
        <b>SURGERY PATIENTS LIST</b><br/>
        <!--<b>FROM <span class='dates'><?php echo date('j M, Y H:i:s', strtotime($Date_From)) ?></span> TO <span class='dates'><?php echo date('j M, Y H:i:s', strtotime($Date_To)) ?></span></b>-->
    </legend>   
<center>
<div id="displayme">
<?php
echo '<center><table width =100% border="1" id="doctorperformancereportsummarised" class="display">';
echo "<thead><tr>
			    <th width=3% style='text-align:left'>SN</th>
                            <th style='text-align:left'>STATUS</th>
			    <th style='text-align:left'>DOCTOR'S NAME</th>
			    <th style='text-align: left;'>PATIENT NAME</th>
                            <th style='text-align: left;'>PATIENT #</th>
                            <th style='text-align: left;'>SPONSOR</th>
                            <th style='text-align: left;'>SURGERY NAME</th>
                            <th style='text-align: left;'>SURGRY DATE</th>
                            <th style='text-align: left;'>SURGERY DURATION</th>
                            <th style='text-align: left;'>TRANSACTION DATE</th>
                            <th style='text-align: left;'>LOCATION</th>
                            <th style='text-align: left;'>PATIENTS PHONE NUMBER</th>
		     </tr></thead>";

$result = mysqli_query($conn,"SELECT 'cache' as Status_From,Post_operative_ID,Payment_Item_Cache_List_ID,Surgery_hour,Surgery_min,il.payment_type,pc.Billing_Type,il.Transaction_Type as transaction,pr.Patient_Name,pc.Sponsor_Name,pr.Date_Of_Birth,te.Employee_Name,Product_Name,il.Service_Date_And_Time, sd.Sub_Department_Name AS Procedure_Location,
                                           pr.Gender,pr.Phone_Number,pr.Registration_ID as registration_number,pc.Receipt_Date as Required_Date,
                                           pc.Payment_Cache_ID as payment_id,il.Status as Status,il.Consultant as Doctors_Name,il.Transaction_Date_And_Time as Transaction_Date_And_Time
                                            FROM tbl_employee te,tbl_sub_department AS sd, tbl_sponsor AS sp,tbl_patient_registration AS pr,tbl_payment_cache as pc,tbl_items as i,tbl_item_list_cache as il
                                            WHERE i.Item_ID = il.Item_ID 
                                            AND pc.Payment_Cache_ID = il.Payment_Cache_ID 
                                            AND pr.Registration_ID =pc.Registration_ID
                                            AND sp.Sponsor_ID =pr.Sponsor_ID
                                            AND te.Employee_ID=il.Consultant_ID
                                            AND sd.Sub_Department_ID =il.Sub_Department_ID
                                            AND Check_In_Type ='Surgery' AND (il.Status='active' OR il.Status='paid') AND removing_status='No' GROUP BY Payment_Item_Cache_List_ID ORDER BY Transaction_Date_And_Time DESC  LIMIT 7");

$sn=1;
while($select_doctor_row = mysqli_fetch_array($result)){
echo "<tr><td>".$sn++."</td>";
                    $billing_Type = strtolower($select_doctor_row['Billing_Type']);
                    $status = strtolower($select_doctor_row['Status']);
                    $transaction_Type = strtolower($select_doctor_row['transaction']);
                    $payment_type = strtolower($select_doctor_row['payment_type']);

                    if (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "cash")) {
                        $tatus= 'Not paid';
                    } elseif (($billing_Type == 'outpatient cash' && $status == 'active' && $transaction_Type == "credit")) {
                        $tatus= 'Not Billed';
                
                    }  elseif (($billing_Type == 'outpatient credit' && $status == 'active' && $transaction_Type == "cash")) {
                        $tatus= 'Not paid';
                    } elseif (($billing_Type == 'inpatient cash' && $status == 'active') || ($billing_Type == 'inpatient credit' && $status == 'active' && $transaction_Type == "cash")) {

                        if ($pre_paid == '1') {
                            $tatus= 'Not paid';
                        } else {
                            if ($payment_type == 'pre'  && $status == 'active') {
                                   $tatus= 'Not paid';
                            } else {
                                $tatus= 'Not Billed';
                            }
                        }
                    } elseif (($billing_Type == 'outpatient cash' && $status == 'paid') || ($billing_Type == 'outpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
                        $tatus= 'Paid';
                    } elseif (($billing_Type == 'inpatient cash' && $status == 'paid') || ($billing_Type == 'inpatient credit' && $status == 'paid' && $transaction_Type == "cash")) {
                        $tatus= 'Paid';
                    } else {
                        if ($payment_type == 'pre') {
                            $tatus= 'Not paid';
                        } else {
                            $tatus= 'Not Billed';
                        }
                    }




echo "<td>".$tatus."</td>";
echo "<td style='text-align:left'>".$select_doctor_row['Employee_Name']."</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Patient_Name'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['registration_number'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Sponsor_Name'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Product_Name'] . "</td>";
echo "<td class='Date_Time' id='".$select_doctor_row['Payment_Item_Cache_List_ID']."' style='text-align:left'>". $select_doctor_row['Service_Date_And_Time'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Surgery_hour'] .'hrs :'.$select_doctor_row['Surgery_min']. "min</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Transaction_Date_And_Time'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Procedure_Location'] . "</td>";
echo "<td style='text-align:left'>" . $select_doctor_row['Phone_Number'] . "</td>
</tr>";
}
?>
 </table>
</div>
</center>
</center>
</fieldset>
<!--<table>
    <tr>
        <td style='text-align: center;'>
            <a href="previewFilterDoctorPerformance.php?Date_From=<?php echo $Date_From ?>&Date_To=<?php echo $Date_To ?>&PreviewFilterPerformanceReportThisPage=ThisPage" target="_blank">

                <input type='submit' name='previewFilterDoctorPerformance' id='previewFilterDoctorPerformance' target='_blank' class='art-button-green' value='PREVIEW ALL'>

            </a>
        </td>

    </tr>
</table>-->
<?php
include("./includes/footer.php");
?>
<link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
<link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
<script src="media/js/jquery.js"></script>
<script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
<script src="css/jquery.datetimepicker.js"></script>
<script>
    $('#date_From').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_From').datetimepicker({value: '', step: 1});
    $('#date_To').datetimepicker({
        dayOfWeekStart: 1,
        lang: 'en',
        startDate: 'now'
    });
    $('#date_To').datetimepicker({value: '', step: 1});
    
    $('#Print_Filter').on('click',function(e){
      e.preventDefault();
     var date_From=$('#date_From').val();
     var date_To=$('#date_To').val();
     var Employee_ID=$('#Employee_ID').val();
     if(date_From=='' || date_From=='NULL' || date_To=='' || date_To=='NULL'){
         alert('Select dates to continue');
         return false;
     }
     var Sponsor_ID=$('#Sponsor_ID').val();
       $.ajax({
           type:'POST',
           url:'Surgery_Patients_List_filter.php',
           data:'action=filter&date_From='+date_From+'&date_To='+date_To+'&Sponsor_ID='+Sponsor_ID+'&Employee_ID='+Employee_ID,
           cache:false,
           success:function(me){
               $('#displayme').html(me);
           }
       });
//       alert(date_From +' & '+date_To+' '+Sponsor_ID);
    });
    
    $('#patient_Name').on('input',function(){
     var Patient_Name=$(this).val();
     var date_From=$('#date_From').val();
     var date_To=$('#date_To').val();
     var Employee_ID=$('#Employee_ID').val();
     if(date_From=='' || date_From=='NULL' || date_To=='' || date_To=='NULL'){
         alert('Select dates to continue');
         return false;
     }
     var Sponsor_ID=$('#Sponsor_ID').val();
       $.ajax({
           type:'POST',
           url:'Surgery_Patients_List_filter.php',
           data:'action=filterInput&date_From='+date_From+'&date_To='+date_To+'&Sponsor_ID='+Sponsor_ID+'&Employee_ID='+Employee_ID+'&Patient_Name='+Patient_Name,
           cache:false,
           success:function(me){
               $('#displayme').html(me);
           }
       });
    });
    
    $('#Print_List').on('click',function(){
     var Patient_Name=$('#patient_Name').val();
     var date_From=$('#date_From').val();
     var date_To=$('#date_To').val();
     var Employee_ID=$('#Employee_ID').val();
     if(date_From=='' || date_From=='NULL' || date_To=='' || date_To=='NULL'){
         alert('Select dates to continue');
         return false;
     }
     var Sponsor_ID=$('#Sponsor_ID').val(); 
     
     window.open('Print_Patient_Surgery_List.php?action=filter&date_From='+date_From+'&date_To='+date_To+'&Sponsor_ID='+Sponsor_ID+'&Employee_ID='+Employee_ID+'&Patient_Name='+Patient_Name);
        
    });
</script>
<script>
    $('#doctorperformancereportsummarised').dataTable({
        "bJQueryUI": true,
    });

</script>