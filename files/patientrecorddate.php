<?php
    session_start();
    include("./includes/connection.php");
    include("./includes/header.php");

    
    if (!isset($_SESSION['userinfo'])) {
        @session_destroy();
        header("Location: ../index.php?InvalidPrivilege=yes");
    }

?>
<a href='newsystemusage.php?DhisWork=DhisWorkThisPage' class='art-button-green'> BACK </a>

<fieldset style='margin-top:15px;'>
    <legend align="center" style="text-align:center;background-color:#006400;color:white;padding:5px;"><b>PATIENT WITH CONSULTATION DATE 0000:00:00 00:00</b></legend>
    <center>
        <table  class="hiv_table" style="width:100%;margin-top:5px;">
            <tr> 
                <td style="width: 20px;text-align:center ">             
                    
                    <input type="button" name="filter" value="UPDATE" class="art-button-green" onclick="filter_datas();"> 
                    
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
                                <td>Consulted By</td>
                                <td>Consultation Time</td>
                                <td>Consultation time</td>
                                <td>Clinic time</td>
                            </tr> 
                            <tbody id='databody'>
                    <?php

                       // die("SELECT Patient_Name,Employee_Name,  c.Registration_ID, Clinic_Name,Consultation_Date_And_Time,  Guarantor_Name, Clinic_consultation_date_time FROM  tbl_consultation c, tbl_patient_registration pr, tbl_sponsor s, tbl_clinic ci, tbl_employee e  WHERE s.Sponsor_ID=pr.Sponsor_ID AND c.employee_ID=e.Employee_ID AND c.Clinic_ID=ci.Clinic_ID AND  c.Registration_ID=pr.Registration_ID AND DATE(Clinic_consultation_date_time) =DATE('0000-00-00 00:00') ");
                        $select_consultation_clinic = mysqli_query($conn, "SELECT Patient_Name,Employee_Name, Payment_Date_And_Time, c.Registration_ID, Consultation_Date_And_Time,   Clinic_consultation_date_time FROM  tbl_consultation c, tbl_patient_registration pr,  tbl_employee e, tbl_payment_cache pc  WHERE  c.employee_ID=e.Employee_ID AND pc.consultation_id=c.consultation_id AND  c.Registration_ID=pr.Registration_ID AND DATE(Consultation_Date_And_Time) =DATE('0000-00-00 00:00')  ORDER BY c.Registration_ID ASC") or die(mysqli_error($conn));
                        if(mysqli_num_rows($select_consultation_clinic)>0){
                            while($rows = mysqli_fetch_assoc($select_consultation_clinic)){
                                $Patient_Name = $rows['Patient_Name'];
                                $Registration_ID = $rows['Registration_ID'];
                                $Payment_Date_And_Time = $rows['Payment_Date_And_Time'];
                                $Guarantor_Name = $rows['Guarantor_Name'];
                                $Consultation_Date_And_Time = $rows['Consultation_Date_And_Time'];
                                $Clinic_consultation_date_time = $rows['Clinic_consultation_date_time'];
                                $Employee_Name = $rows['Employee_Name'];

                                echo "<tr><td>$Patient_Name</td>
                                    <td>$Registration_ID</td>
                                    <td>$Employee_Name</td>                                    
                                    <td>$Consultation_Date_And_Time</td>
                                    <td>$Clinic_consultation_date_time</td>
                                    <td>$Payment_Date_And_Time</td>
                                </tr>";
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
        
        $.ajax({
            type:"POST",
            url:'Ajax_newsystem_update.php',
            data:{update_consultationdate:''},
            success:function(responce){
                alert(responce);
            }

        })
    }
   
   
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('select').select2();
    });
</script>