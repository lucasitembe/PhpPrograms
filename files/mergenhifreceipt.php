<?php
    session_start();
    // include("connectionn.php");
    include("./includes/header.php");

    
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

    if (isset($_GET['Registration_ID'])) {
        $Registration_ID= $_GET['Registration_ID'];
    } else {
        $Registration_ID= 0;
    }

    if (isset($_GET['Check_In_ID'])) {
        $Check_In_ID= $_GET['Check_In_ID'];
    } else {
        $Check_In_ID= 0;
    }

    if (isset($_GET['consultation_ID'])) {
        $consultation_ID= $_GET['consultation_ID'];
    } else {
        $consultation_ID= 0;
    }

?>
<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
     <link rel="stylesheet" type="text/css" href="bootstrap.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/>
    <link rel="shortcut icon" href="images/icon.png">
    
    <link rel="stylesheet" href="style.css" media="screen">
   
    	<!-- New Date Picker -->
	<link rel="stylesheet" href="pikaday.css">
        
    <!--[if lte IE 7]><link rel="stylesheet" href="style.ie7.css" media="screen" /><![endif]-->
    
    <link rel="stylesheet" href="style.responsive.css" media="all">
 
    <script src="https://code.jquery.com/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.1/js/select2.min.js"></script>
		
    <script src="jquery.js"></script>
    <script src="script.js"></script>
    <script src="script.responsive.js"></script>
<a href="patientrecorddate.php" class="art-button-green">Patient Record</a>
<a href='managementworkspage.php?DhisWork=DhisWorkThisPage' class='art-button-green'> BACK </a>

<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>PROCESS CONSULTATION ITEMS </b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">
                <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="clinicdate" placeholder="Clinic Date"/>
                    <b>Date From:</b> <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date"/>
                    <b>Date To: </b><input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date"/>&nbsp;
                    <!-- <b>Debit Status :</b>
                    <select name="bill_staus" id="bill_staus">
                        <option value="">---select status---</option>
                        <option value="Paid">Paid</option>
                        <option value="Not cleared">Un Paid</option>
                
        		    </select> -->
                    
                    <input type="button" name="filter" value="FILTER" class="art-button-green" onclick="filter_datas();"> 
                    <!-- <input type="button" name="filter" value="PREVIEW PDF" class="art-button-green" onclick="filter_data();">  -->

                </td>
            </tr>

        </table>
    </center>
    <center>
        <table  class="hiv_table" style="width:100%">
            <tr>
                <td colspan='8'>
                    <div style="width:100%; height:450px;overflow-x: hidden;overflow-y: auto;margin: 2px 2px 20px 2px;"  id="Search_Iframe">
                    <table class='table'>
                        <thead>
                            <tr>
                                <td>Patient Name</td>
                                <td>Reg#</td>
                                <td>CHECK ID</td>
                                <td>Billng type</td>
                                <td>BILL ID</td>
                                <td>Clinic time</td>
                            </tr> 
                            <tbody id='databody'>
                    <?php
                            $num=1;
                            $selectconsultation =mysqli_query($conn, "SELECT Price,Billing_Type,pc.Registration_ID, Patient_Payment_ID, ilc.Payment_Cache_ID FROM  tbl_item_list_cache ilc, tbl_payment_cache pc WHERE  ilc.Payment_Cache_ID=pc.Payment_Cache_ID AND Patient_Payment_ID IS NOT NULL AND consultation_id='$consultation_ID' AND pc.Registration_ID='$Registration_ID'  ") or die(mysqli_error($conn));
                            // AND Check_In_ID='$Check_In_ID' 
                            if(mysqli_num_rows($selectconsultation)>0){
                                while($rw = mysqli_fetch_assoc($selectconsultation)){
                                    $Payment_Cache_ID = $rw['Payment_Cache_ID'];
                                    $Registration_ID = $rw['Registration_ID'];
                                    $Billing_Type=$rw['Billing_Type'];
                                    $Patient_Payment_ID = $rw['Patient_Payment_ID'];
                                    // || strtolower($Billing_Type)=='inpatient credit'strtolower($Billing_Type)=='outpatient credit' ||
                                    if( strtolower($Billing_Type)=='inpatient credit' ){                    
                                                          
                                        $updatecheckin = mysqli_query($conn, "UPDATE tbl_patient_payments SET Check_In_ID='$Check_In_ID' WHERE Patient_Payment_ID='$Patient_Payment_ID' AND Bill_ID IS NULL AND Registration_ID='$Registration_ID' AND Check_In_ID<>'$Check_In_ID'") or die(mysqli_error($conn));
                                        if($updatecheckin){
                                            echo $Patient_Payment_ID."Updated<br/>";
                                        }
                                    }
                                    
                                }
                            }
                             
                    ?>      <tbody>
                        </table>
                    </div>
                </td>
            </tr>
            <?php
              
            ?>
        </table>

    </center>
</fieldset>

<?php
include("./includes/footer.php");
?>

<link rel="stylesheet" href="js/css/ui-lightness/jquery-ui-1.8.23.custom.css">
    <script src="js/jquery-1.8.0.min.js"></script>
    <script src="js/jquery-ui-1.8.23.custom.min.js"></script>
    <script src="css/jquery.datetimepicker.js"></script>
    
    <link rel="stylesheet" href="css/select2.min.css" media="screen">
<script src="js/select2.min.js"></script> 

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
    $('#clinicdate').datetimepicker({value: '', step: 1});

</script>
<script type="text/javascript">
    function filter_datas(){
        var clinicdate = $("#clinicdate").val();
        if(clinicdate==''){
            exit()
        }
        $.ajax({
            type:"POST",
            url:'Ajax_patient_debt.php',
            data:{clinicdate:clinicdate, clinic_date_update:''},
            success:function(responce){
                alert(responce);
            }

        })
    }
    function filter_data(){
        var fromDate=$("#date_From").val();
//         var toDate=$("#date_To").val();
//         var bill_staus=$("#bill_staus").val();
//         var Sponsor_ID=$("#Sponsor_ID").val();
// //        var medicine_name=$("#medicine_name").val();
//         if(fromDate.trim()!=='' && toDate.trim()!==''){
//                 $('#Search_Iframe').html('<div align="center" style="display:block;" id="progressStatus"><img src="images/ajax-loader_1.gif" width="" style="border-color:white "></div>');
//                 $.ajax({
//                     url:'patientwith_imbalance_bills_iframe.php',
//                     type:'post',
//                     data:{fromDate:fromDate,toDate:toDate,bill_staus:bill_staus,Sponsor_ID:Sponsor_ID},
//                     success:function(result){
//                         if (result != '') {
//                             $('#Search_Iframe').html(result);
//                         }
//                     }
//                 });
//             }else{
//             alert('FILL THE START DATE AND END DATE');
//         }
    }
   
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('select').select2();
    });
</script>