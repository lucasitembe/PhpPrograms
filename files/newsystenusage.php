<?php
    session_start();
    // include("connectionn.php");
    include("./includes/header.php");

    
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

?>
<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">
<link rel="stylesheet" type="text/css" href="bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css" />
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
<a href='#' onclick="history.back()" class='art-button-green'>BACK</a>

<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>DOCTORS USING
            OLD SYSTEM TO SEE THE PATIENT</b></legend>
    <center>
        <table class="hiv_table" style="width:100%;margin-top:5px;">
            <tr>
                <td style="width: 20px;text-align:center ">
                    <input type="text" autocomplete="off" style='text-align: center;width:10%;display:inline'
                        id="clinicdate" placeholder="Clinic Date" />
                    <b>Date From:</b> <input type="text" autocomplete="off"
                        style='text-align: center;width:10%;display:inline' id="date_From" placeholder="Start Date" />
                    <b>Date To: </b><input type="text" autocomplete="off"
                        style='text-align: center;width:10%;display:inline' id="date_To" placeholder="End Date" />&nbsp;
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
        <table class="hiv_table" style="width:100%">
            <tr>
                <td colspan='8'>
                    <div style="width:100%; height:450px;overflow-x: hidden;overflow-y: auto;margin: 2px 2px 20px 2px;"
                        id="Search_Iframe">
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

                        // $selectupdate_bill = mysqli_query($conn, "SELECT Registration_ID,Sponsor_ID, b.Bill_ID, b.Bill_Date, b.Employee_ID, claim_month FROM tbl_bills b, tbl_claim_folio cf WHERE b.Bill_ID=cf.Bill_ID AND Bill_Date =CURDATE() GROUP BY cf.Bill_ID ") or die(mysqli_error($conn));

                        // if(mysqli_num_rows($selectupdate_bill)>0){
                        //     while($row = mysqli_fetch_assoc($selectupdate_bill)){
                        //         $Registration_ID = $row['Registration_ID'];
                        //         $Bill_ID = $row['Bill_ID'];
                        //         $Bill_Date = $row['Bill_Date'];
                        //         $approvedBy =$row['Employee_ID'];
                        //         $Sponsor_ID = $row['Sponsor_ID'];
                        //         $claim_month= $row['claim_month'];
                        //         $claim_year=$row['claim_year'];

                        //         $updatebill = mysqli_query($conn, "UPDATE tbl_patient_payments SET Bill_ID='$Bill_ID', Billing_Process_Status='billed' WHERE Billing_Process_Date='$Bill_Date' AND Billing_Process_Employee_ID='$approvedBy' AND MONTHNAME(Receipt_Date)='$claim_month' AND Sponsor_ID='$Sponsor_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                        //         if($updatebill){
                        //             echo "updated";
                        //         }
                        //     }
                        // }else{
                        //     echo "No result found";
                        // }

                        // $select_consultation_clinic = mysqli_query($conn, "SELECT Patient_Name,Employee_Name,  c.Registration_ID, Clinic_Name,Consultation_Date_And_Time,  Guarantor_Name, Clinic_consultation_date_time FROM  tbl_consultation c, tbl_patient_registration pr, tbl_sponsor s, tbl_clinic ci, tbl_employee e  WHERE s.Sponsor_ID=pr.Sponsor_ID AND c.employee_ID=e.Employee_ID AND c.Clinic_ID=ci.Clinic_ID AND  c.Registration_ID=pr.Registration_ID AND DATE(Clinic_consultation_date_time) =DATE('0000-00-00 00:00') ") or die(mysqli_error($conn));
                        // if(mysqli_num_rows($select_consultation_clinic)>0){
                        //     while($rows = mysqli_fetch_assoc($select_consultation_clinic)){
                        //         $Patient_Name = $rows['Patient_Name'];
                        //         $Registration_ID = $rows['Registration_ID'];
                        //         $Clinic_Name = $rows['Clinic_Name'];
                        //         $Guarantor_Name = $rows['Guarantor_Name'];
                        //         $Consultation_Date_And_Time = $rows['Consultation_Date_And_Time'];
                        //         $Clinic_consultation_date_time = $rows['Clinic_consultation_date_time'];
                        //         $Employee_Name = $rows['Employee_Name'];

                        //         echo "<tr><td>$Patient_Name</td>
                        //             <td>$Registration_ID</td>
                        //             <td>$Employee_Name</td>
                        //             <td>$Clinic_Name</td>
                        //             <td>$Consultation_Date_And_Time</td>
                        //             <td>$Clinic_consultation_date_time</td>
                        //         </tr>";
                        //     }
                        // }
                            $num=1;
                            $selectconsultation =mysqli_query($conn, "SELECT c.Patient_Payment_Item_List_ID, c.Registration_ID, Check_In_ID,consultation_ID FROM tbl_consultation c, tbl_patient_payment_item_list ppl, tbl_patient_payments pp WHERE c.Patient_Payment_Item_List_ID=ppl.Patient_Payment_Item_List_ID AND Check_In_ID IS NULL AND ppl.Patient_Payment_ID=pp.Patient_Payment_ID AND pp.Registration_ID=c.Registration_ID AND Consultation_Date_And_Time >'2021-11-01 14:21:19'  ORDER BY consultation_ID ASC LIMIT 10000") or die(mysqli_error($conn));
                            if(mysqli_num_rows($selectconsultation)>0){
                                while($rw = mysqli_fetch_assoc($selectconsultation)){
                                    $Patient_Payment_Item_List_ID = $rw['Patient_Payment_Item_List_ID'];
                                    $consultation_ID = $rw['consultation_ID'];
                                    $Check_In_ID =$rw['Check_In_ID'];
                                    $Registration_ID = $rw['Registration_ID'];
                                    // echo "<tr><td>$Registration_ID</td><td>$Patient_Payment_Item_List_ID</td><td>$consultation_ID</td><td>$Check_In_ID</td><td></td></tr>";
                                    // die("UPDATE tbl_consultation SET Check_InID='$Check_In_ID' WHERE consultation_ID='$consultation_ID'");
                                    $updateconsultation = mysqli_query($conn, "UPDATE tbl_consultation SET Check_InID='$Check_In_ID' WHERE consultation_ID='$consultation_ID' AND Registration_ID='$Registration_ID' AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
                                    if($updateconsultation){
                                        echo "updated==".$Registration_ID."</br>";
                                    }
                                }
                            }
                             
                    ?>
                            <tbody>
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
$('#date_From').datetimepicker({
    value: '',
    step: 1
});
$('#date_To').datetimepicker({
    dayOfWeekStart: 1,
    lang: 'en',
    startDate: 'now'
});
$('#date_To').datetimepicker({
    value: '',
    step: 1
});
$('#clinicdate').datetimepicker({
    value: '',
    step: 1
});
</script>
<script type="text/javascript">
function filter_datas() {
    var clinicdate = $("#clinicdate").val();
    if (clinicdate == '') {
        exit()
    }
    $.ajax({
        type: "POST",
        url: 'Ajax_patient_debt.php',
        data: {
            clinicdate: clinicdate,
            clinic_date_update: ''
        },
        success: function(responce) {
            alert(responce);
        }

    })
}

function filter_data() {
    var fromDate = $("#date_From").val();
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
$(document).ready(function() {
    $('select').select2();
});
</script>