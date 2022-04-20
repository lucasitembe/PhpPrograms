<?php
   include("./includes/connection.php");
   include("./includes/header.php");
   

   session_start();
    if (isset($_GET['Date_From'])) {
        $Date_From = $_GET['Date_From'];
    } else {
        $Date_From = '';
    }
    
    
    if (isset($_GET['Date_To'])) {
        $Date_To = $_GET['Date_To'];
    } else {
        $Date_To = '';
    }


    $Registration_ID = $_GET['Registration_ID'];
    $Admision_ID = $_GET['Admision_ID'];
    // $Employee_ID = $_GET['Employee_ID'];
    $consultation_ID = $_GET['consultation_ID'];
	$Employee_ID = $_SESSION['userinfo']['Employee_ID'];
	$Employee_Name = $_SESSION['userinfo']['Employee_Name'];
    $Admision_ID = $_GET['Admision_ID'];
    $ward_room_id = $_GET['ward_room_id'];
    
    
    
?>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    tr:hover{
        background-color:#eeeeee;
        cursor:pointer;
    }
</style>
<style>
    table,tr,td{
        border-collapse:collapse !important;
        border:none !important;
    }
    th{
        text-align:right;
    }


   
</style>
<a href="admittedpatientlist.php?SearchListDoctorsPagePatientBilling=SearchListDirectCashPatientBillingThisPage" class='art-button-green'>BACK</a>
<br/>
<br/>
<br/>
<center>
<fieldset>
<legend align=center><b>PATIENT OVERSTAY NOTIFICATION FORM</b></legend>
    <table  width="40%" class="table" style="background: #FFFFFF;">
        <caption><b>PATIENT INFORMATION</b></caption>
        <tr>
            <td><b>PATIENT NAME</b></td>
            <td><b>REGISTRATION No.</b></td>
            <td><b>WARD</b></td>
            <td><b>AGE</b></td>
            <td><b>GENDER</b></td>
            <td><b>MEMBERSHIP #</b></td>
            <td><b>PHONE NUMBER</b></td>
            <td><b>AUTHORIZATION #</b></td>
            
        </tr>
        <?php 
            $Patient_Name ="";
            $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
            $Region ="";
            $District ="";
            $Ward ="";
            $village ="";
            $sql_select_patient_information_result = mysqli_query($conn,"SELECT Patient_Name, Sponsor_ID, Date_Of_Birth,Region,District,Ward,village,Gender,Member_Number,Phone_Number FROM tbl_patient_registration WHERE Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
            if(mysqli_num_rows($sql_select_patient_information_result)>0){
                while($pat_details_rows=mysqli_fetch_assoc($sql_select_patient_information_result)){
                    $Patient_Name =$pat_details_rows['Patient_Name'];
                    $Date_Of_Birth =$pat_details_rows['Date_Of_Birth'];
                    $Region =$pat_details_rows['Region'];
                    $District =$pat_details_rows['District'];
                    $Ward =$pat_details_rows['Ward'];
                    $village =$pat_details_rows['village'];
                    $Gender =$pat_details_rows['Gender'];
                    $Phone_Number = $pat_details_rows['Phone_Number'];
                    $Member_Number = $pat_details_rows['Member_Number'];
                    $Sponsor_ID = $pat_details_rows['Sponsor_ID'];
                }
            }
             //today function
            $Today_Date = mysqli_query($conn,"select now() as today");
            while($row = mysqli_fetch_array($Today_Date)){
                $original_Date = $row['today'];
                $new_Date = date("Y-m-d", strtotime($original_Date));
                $Today = $new_Date;
                $age ='';
            }
                $date1 = new DateTime($Today);
                $date2 = new DateTime($Date_Of_Birth);
                $diff = $date1 -> diff($date2);
                $age = $diff->y." Years, ";
                $age .= $diff->m." Months, ";
                $age .= $diff->d." Days";
                //select doctor name
                
                //select admission ward 
                $Hospital_Ward_Name="";
                
                $sql_select_admission_ward_result=mysqli_query($conn,"SELECT hw.Hospital_Ward_Name, ci.Check_In_ID, ci.AuthorizationNo FROM tbl_hospital_ward hw, tbl_admission ad, tbl_check_in ci, tbl_check_in_details cid WHERE hw.Hospital_Ward_ID = ad.Hospital_Ward_ID AND ad.Registration_ID='$Registration_ID' AND ad.Admision_ID = '$Admision_ID' AND ad.Admission_Status<>'Discharged' AND cid.consultation_ID = '$consultation_ID' AND ad.Admision_ID = cid.Admission_ID AND ci.Check_In_ID = cid.Check_In_ID") or die(mysqli_error($conn));
                if(mysqli_num_rows($sql_select_admission_ward_result)>0){
                    while($wodini = mysqli_fetch_assoc($sql_select_admission_ward_result)){
                        $Hospital_Ward_Name = $wodini['Hospital_Ward_Name'];
                        $AuthorizationNo = $wodini['AuthorizationNo'];
                        $Check_In_ID = $wodini['Check_In_ID'];
                    }
                }else{
                    $Hospital_Ward_Name = '<b>NOT ADMITTED</b>';
                }
                echo "<tr>
                    <td>$Patient_Name</td>
                    <td>$Registration_ID</td>
                    <td>$Hospital_Ward_Name</td>
                    <td>$age</td>
                    <td>$Gender </td>
                    <td>$Member_Number </td>
                    <td>$Phone_Number </td>
                    <td>$AuthorizationNo </td>
                  </tr>";
                  
                  $select_magonjwa = mysqli_query($conn, "SELECT disease_code FROM tbl_disease_consultation dc, tbl_disease d WHERE d.disease_ID = dc.disease_ID AND consultation_ID = '$consultation_ID'");
                  $idadi = mysqli_num_rows($select_magonjwa);
                    while($disease = mysqli_fetch_assoc($select_magonjwa)){
                        $disease_code = $disease['disease_code'];
                        // $magonjwa = $disease_code;
                        $magonjwa = $disease_code.", ".$magonjwa;
                    }
            
                    $employee_datas = mysqli_query($conn, "SELECT kada, Phone_Number, Employee_Title, Employee_Job_Code, employee_signature FROM tbl_employee WHERE Employee_ID = '$Employee_ID'");
                        while($deal = mysqli_fetch_assoc($employee_datas)){
                            $kada = $deal['kada'];
                            $Employee_Job_Code = $deal['Employee_Job_Code'];
                            $employee_signature = $deal['employee_signature'];
                            $employee_Phone_Number = $deal['Phone_Number'];
                            $Employee_Title = $deal['Employee_Title'];


                            if($Employee_Title == "Intern Doctor"){
                                $Status_Doctor = 1;
                            }else{
                                $Status_Doctor = 2;
                            }

                        $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:20px'>";

                        if(empty($employee_Phone_Number)){
                            $employee_Phone_Number = '<b>NOT INSERTED</b>';
                        }

                        }


                        $url = "./doctorspageinpatientwork.php?Registration_ID=$Registration_ID&consultation_ID=$consultation_ID&Admision_ID=$Admision_ID";

        ?>
    </table>
  
    <!--div for adding clinical information -->
    <form action="" id="clinical">
        <table class="table" >

        <!-- <caption style="font-size: 16px;"><b>BIOPSY/HISTOLOGICAL EXAMINATION REQUESTING FORM</b></caption> -->
        
                <tbody >
                    <tr>
                        <th style='text-align: right; width: 20%;'>Disease(s) Code Number:</th>
                        <th colspan='7' style='text-align: left;'>
                            <input type="text" name=""  disabled='disabled' id="" value='<?php echo $magonjwa; ?>'>
                        </th>
                    </tr>
                    <tr>
                        <th style='text-align: right;' placeholder='Specify Reason for OverStaying' required='required'>Reason for Overstay</th>
                        <td colspan="7">
                            <?php
                            if($Status_Doctor == 1){
                                echo '<textarea name="Reason_For_Overstaying" id="Reason_For_Overstaying" cols="30" rows="3" disabled="disabled"></textarea>';
                            }else{
                                echo '<textarea name="Reason_For_Overstaying" id="Reason_For_Overstaying" cols="30" rows="3"></textarea>';
                            }
                            ?>
                        </td>
                    </tr>
                    <tr style='border: 2px solid #fff;'>
                        <td style='text-align: right;'><b>Name of Notifying Officer:</b></td>
                        <td style='text-align: left; width: 12%'><?php echo $Employee_Name; ?></td>
                        </td>
                        <td style='width: 4%; text-align: right;'><b>Designation:</b>
                        </td>
                        <td style='width: 8%;text-align: left;'><?php echo $Employee_Job_Code; ?></td>
                        <td style='width: 4%;text-align: right;'><b>Qualification:</b>
                        </td>
                        <td style='text-align: left; width: 6%'><?php echo $kada; ?></td>
                        <td style='width: 8%;text-align: right;'><b>Phone Number:</b>
                        </td>
                        <td style='text-align: left; width: 18%'><?php echo $employee_Phone_Number; ?></td>

                    <tr style='border: 2px solid #fff;'>
                        <td style='text-align: right;'><b>Signature</b></td>
                        <td><?php echo $signature; ?></td>
                        <td style='text-align: right;'><b>Employee Type</b></td>
                        <td><?php echo $Employee_Title; ?></td>
                    </tr>
                    <tr>
                        <td colspan='5'>
                        <input type="button" id="clinical_btn" style="border-radius:4px; padding: 5px; font-weight: bold;" value="    SUBMIT OVERSTAY FORM    " class="btn art-button pull-right" onclick="save_blood_request()">          
                        </td>
                </tr>
                </tbody>
        </table>

    </form>
</fieldset>
<script>
    function save_blood_request(){
        Reason_For_Overstaying = $("#Reason_For_Overstaying").val();
        Sponsor_ID = '<?= $Sponsor_ID ?>';
        Registration_ID = '<?= $Registration_ID; ?>';
        Admision_ID = '<?= $Admision_ID; ?>';
        ward_room_id = '<?= $ward_room_id; ?>';
        Employee_ID = '<?= $Employee_ID; ?>';
        consultation_ID = '<?= $consultation_ID ?>';
        Check_In_ID = '<?= $Check_In_ID ?>';
        Status_Doctor = '<?= $Status_Doctor ?>';

        if(Status_Doctor == 1){
            alert("This Form can not be filled by An Intern Doctor, Please, Ask your Superior to Assist You \n Thank You!");
            exit();
        }


        if(Registration_ID != '0' && Reason_For_Overstaying != null){
            if (confirm("You are about to Submit OverStaying Notification Form, Do you want to Proceed?")) {
                $.ajax({
                    url: "ajax_save_overstay_form.php",
                    type: "post",
                    data: {Registration_ID:Registration_ID,Employee_ID:Employee_ID,consultation_ID:consultation_ID,Reason_For_Overstaying:Reason_For_Overstaying,Sponsor_ID:Sponsor_ID,Admision_ID:Admision_ID,ward_room_id:ward_room_id,Check_In_ID:Check_In_ID},
                    cache: false,
                    success: function(responce){
                        alert(responce);
                        document.location = '<?php echo $url ?>';
                    }
                });
            }
		}else{
            alert("Please, Fill the amount of blood you want to request before Saving");
            exit();
        }
	}
</script>

<script>
var radio = document.getElementById('Within');
var radio2 = document.getElementById('to_be_given');
radio.addEventListener('change',function(){
    var month = document.querySelector('.instruction');
    var month2 = document.querySelector('.instruction2');
    var month3 = document.querySelector('.instruction3');
    if(this.checked){
        month.style.display='inline';
        month2.style.display='inline';
        month3.style.display='inline';
    }else{
        month.style.display='none';
        month2.style.display='none';
        month3.style.display='none';
    }
})
radio2.addEventListener('change',function(){
    var month = document.querySelector('.instruction');
    var month2 = document.querySelector('.instruction2');
    var month3 = document.querySelector('.instruction3');
    if(this.checked){
        month.style.display='none';
        month2.style.display='none';
        month3.style.display='none';
    }else{
        month.style.display='inline';
        month2.style.display='inline';
        month3.style.display='inline';
    }
})
</script>

<script type="text/javascript">
        $(document).ready(function () {
            $('#patients-list').DataTable({
                "bJQueryUI": true
            });

            $('#operation_on').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:    'now'
            });
            $('#operation_on').datetimepicker({value: '', step: 1});
            $('#end_date').datetimepicker({
                dayOfWeekStart: 1,
                changeMonth: true,
                changeYear: true,
                showWeek: true,
                showOtherMonths: true,
                lang: 'en',
                //startDate:'now'
            });
            $('#end_date').datetimepicker({value: '', step: 1});
        });
    </script>
        <link rel="stylesheet" href="media/css/jquery.dataTables.css" media="screen">
    <link rel="stylesheet" href="media/themes/smoothness/dataTables.jqueryui.css" media="screen">
    <script src="media/js/jquery.js" type="text/javascript"></script>
    <script src="media/js/jquery.dataTables.js" type="text/javascript"></script>
    <script src="css/jquery.datetimepicker.js" type="text/javascript"></script>
    <script src="css/jquery-ui.js"></script>
<br/>
<br/>
<br/>
<br/>
<?php
include("includes/footer.php");
?>