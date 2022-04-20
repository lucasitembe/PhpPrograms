<?php
include("includes/connection.php");
include("includes/header.php");
include("radical_treatment_functions.php");

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Brachytherapy_ID = (isset($_GET['Brachytherapy_ID'])) ? $_GET['Brachytherapy_ID'] : 0;
$Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;

$Patients = json_decode(getPatientInfomations($conn,$Registration_ID),true);
$response = json_decode(getDoctorRequestsBrachy($conn,$Brachytherapy_ID,$Registration_ID),true);

$Select_brachytherapy = mysqli_query($conn, "SELECT em.Employee_Name, bri.Insertion_Employee, bri.Comment_Insertion, bri.type_of_applicator, bri.Insertion_ID, bri.Name_of_Applicator, br.Type_of_brachytherapy, br.Number_of_Fraction, br.Dose_per_Fraction FROM tbl_brachytherapy_requests br, tbl_employee em, tbl_brachytherapy_insertion bri WHERE br.Brachytherapy_ID = '$Brachytherapy_ID' AND br.Brachytherapy_ID = bri.Brachytherapy_ID AND em.Employee_ID = br.Employee_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($Select_brachytherapy)>0){
    while($dts = mysqli_fetch_assoc($Select_brachytherapy)){
        $Dr_Employee_Name =$dts['Employee_Name'];
        $Type_of_brachytherapy =$dts['Type_of_brachytherapy'];
        $Number_of_Fraction =$dts['Number_of_Fraction'];
        $Dose_per_Fraction =$dts['Dose_per_Fraction'];
        $Name_of_Applicator = $dts['Name_of_Applicator'];
        $type_of_applicator = $dts['type_of_applicator'];
        $Insertion_Employee = $dts['Insertion_Employee'];
        $Insertion_ID = $dts['Insertion_ID'];
        $Comment_Insertion = $dts['Comment_Insertion'];

        $Inserted_By = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Insertion_Employee'"))['Employee_Name'];
    }
}
?>
<a href="brachytherapy_parameter_patientlist.php?BrachtherapyThis=Brachytherapy" class='art-button-green'>BACK</a>

<br>
<br>
<fieldset>
    <legend align=center>BRACHYTHERAPY PARAMETER CALCULATION</legend>
        <div class="box box-primary" style="height: 120px;overflow-y: scroll;overflow-x: hidden">
            <table class="table" style="background: #FFFFFF; width: 100%">
                <tr>
                    <td><b>PATIENT NAME</b></td>
                    <td><b>REGISTRATION No.</b></td>
                    <td><b>PRESCRIBED DOCTOR</b></td>
                    <td><b>AGE</b></td>
                    <td><b>GENDER</b></td>
                    <td><b>SPONSOR</b></td>
                    <td><b>ADDRESS</b></td>
                    
                </tr>
                <?php
                        foreach($Patients as $details) :
                            $date1 = new DateTime($Today);
                            $date2 = new DateTime($details['Date_Of_Birth']);
                            $diff = $date1 -> diff($date2);
                            $age = $diff->y." Years, ";
                            $age .= $diff->m." Months, ";
                            $age .= $diff->d." Days";
                            echo "<tr>
                                        <td>".$details['Patient_Name']."</td>
                                        <td>".$Registration_ID."</td>
                                        <td>".$Dr_Employee_Name."</td>
                                        <td>".$age."</td>
                                        <td>".$details['Gender']."</td>
                                        <td>".$details['Guarantor_Name']."</td>
                                        <td>".$details['Region']."/".$details['District']."</td></tr>";
                        
                        endforeach;
                ?>
                <tr>
                    <th style='text-align: right;'>DIAGNOSED DISEASE</th>
                    <td colspan='6'> 
                    <?php foreach($response as $data) : ?>
                        <?= $data['disease_name']; ?> (<b><?= $data['disease_code']; ?></b>); 
                    <?php endforeach; ?>
                    </td>
                </tr>
            </table>
        </div>
        <div class="box box-primary" style="height: 540px;overflow-y: scroll;overflow-x: hidden;">
            <table class="table" style="background: #FFFFFF; width: 100%">
                <tr>
                    <th style='text-align: right;'>Fraction Number </th>
                    <th><input type='text' style="width: 100%;" disabled='disabled' value='<?= $Number_of_Fraction ?>'></th>
                    <th style='text-align: right;'>Dose per Fraction </th>
                    <th><input type='text' style="width: 100%;" disabled='disabled' value='<?= $Dose_per_Fraction ?>'></th>
                </tr>
                <tr>
                    <th style='text-align: right;'>Type of BrachyTherapy </th>
                    <th><input type='text' style="width: 100%;" disabled='disabled' value='<?= $Type_of_brachytherapy ?>'></th>

                    <th style='text-align: right;'>Type of Applicator </th>
                    <th><input type='text' style="width: 100%;" disabled='disabled' value='<?= $type_of_applicator ?>'></th>
                </tr>
                <tr>
                    <th style='text-align: right;'>Name of Applicator </th>
                    <th><input type='text' style="width: 100%;" disabled='disabled' value='<?= $Name_of_Applicator ?>'></th>
                    <th style='text-align: right;'>Inserted By </th>
                    <th><input type='text' style="width: 100%;" disabled='disabled' value='<?= ucwords($Inserted_By) ?>'></th>

                </tr>
                <tr>
                        <th style='text-align: right;'>Insertion Comment </th>
                        <th colspan='3'>
                        <textarea name="" id="Comment_Insertion" cols="30" rows="4" style="resize: none;" disabled='disabled'><?= $Comment_Insertion ?></textarea>
                    </th>
                </tr>
            </table>
            <br>
        <center>
            <h4><b>BRACHYTHERAPY PARAMETER CALCULATION</b></h4>
        </center>
            <table class="table" style="background: #FFFFFF; width: 100%">
                <tr>
                    <th style='text-align: right;'>Planned Time (MM:SS:Ms)</th>
                    <th><input type='text' id='Planned_Time' style="width: 100%;"></th>
                    <th style='text-align: right;'>Bladder Point Dose Percentage </th>
                    <th><input type='text' id='Bladder_Percentage' style="width: 100%;" placeholder="Bladder Point Dose Percentage"></th>
                </tr>
                <tr>
                    <th style='text-align: right;'>Rectum Point Dose Percentage </th>
                    <th><input type='text' id='Rectum_Percentage' style="width: 100%;" placeholder="Rectum Point Dose Percentage"></th>

                </tr>
                <tr>
                        <th style='text-align: right;'>Calculation Comment </th>
                        <th colspan='3'>
                        <textarea name="" id="Comment_Calculation" cols="30" rows="4" style="resize: none;"></textarea>
                    </th>
                </tr>
            </table>
            <br><br>
            <center><input type="button" value="SAVE CALCULATION" onclick="save_insertion(<?= $Insertion_ID ?>)" class="art-button-green"></center>

</fieldset>
<br>
<?php
include("includes/footer.php");
?>

<script>
    function save_insertion(Insertion_ID){
        Bladder_Percentage = $("#Bladder_Percentage").val();
        Rectum_Percentage = $("#Rectum_Percentage").val();
        Planned_Time = $("#Planned_Time").val();
        Comment_Calculation = $("#Comment_Calculation").val();
        Employee_ID = '<?= $Employee_ID ?>';

        if(Rectum_Percentage == '' || Rectum_Percentage == undefined){
            alert("Please Specify Rectum Point Dose Percentage");
            $("#Rectum_Percentage").css("border","2px solid red");
            $("#Rectum_Percentage").focus();
            exit();
        }

        if(Planned_Time == '' || Planned_Time == undefined){
            alert("Please Specify the Planned Time");
            $("#Planned_Time").css("border","3px solid red");
            $("#Planned_Time").focus();
            exit();
        }

        if(Bladder_Percentage == '' || Bladder_Percentage == undefined){
            alert("Please Specify Bladder Point Dose Percentage");
            $("#Bladder_Percentage").css("border","3px solid red");
            $("#Bladder_Percentage").focus();
            exit();
        }


        if(confirm("You'are about to Save This Brachytherapy Insertion Prescription, click OK to Proceed, Cancel to Edit/Review it")){
            $.ajax({
                type: "POST",
                url: "ajax_update_brachytherapy_requests.php",
                data: {
                    Insertion_ID:Insertion_ID,
                    Bladder_Percentage:Bladder_Percentage,
                    Rectum_Percentage:Rectum_Percentage,
                    Planned_Time:Planned_Time,
                    Employee_ID:Employee_ID,
                    Comment_Calculation:Comment_Calculation,
                    action:'Calculation'
                },
                cache: false,
                success: function (response) {
                    if(response == 200){
                        alert("Brachytherapy Insertion has been Submitted Successfully");
                        document.location = "brachytherapy_parameter_patientlist.php?BrachtherapyThis=Brachytherapy";
                    }else{
                        alert("Brachytherapy Insertion Failed to be Submitted");
                        exit();
                    }
                }
            });
        }
    }
</script>