<?php
include("includes/connection.php");
include("includes/header.php");
include("radical_treatment_functions.php");

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Brachytherapy_ID = (isset($_GET['Brachytherapy_ID'])) ? $_GET['Brachytherapy_ID'] : 0;
$Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;
$Insertion_ID = (isset($_GET['Insertion_ID'])) ? $_GET['Insertion_ID'] : 0;

$Patients = json_decode(getPatientInfomations($conn,$Registration_ID),true);
$response = json_decode(getDoctorRequestsBrachy($conn,$Brachytherapy_ID,$Registration_ID),true);

$Select_brachytherapy = mysqli_query($conn, "SELECT Employee_Name, bri.Insertion_Employee, bri.Insertion_ID, bri.Comment_Insertion, bri.Comment_Calculation, bri.Rectum_Percentage, bri.Fraction_Number, bri.Bladder_Percentage, br.Insertion_Status, bri.calculation_Employee, bri.type_of_applicator, bri.Planned_Time, bri.Name_of_Applicator, br.Type_of_brachytherapy, br.Number_of_Fraction, br.Dose_per_Fraction FROM tbl_brachytherapy_requests br, tbl_employee em, tbl_brachytherapy_insertion bri WHERE br.Brachytherapy_ID = '$Brachytherapy_ID' AND br.Brachytherapy_ID = bri.Brachytherapy_ID AND em.Employee_ID = br.Employee_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($Select_brachytherapy)>0){
    while($dts = mysqli_fetch_assoc($Select_brachytherapy)){
        $Dr_Employee_Name =$dts['Employee_Name'];
        $Type_of_brachytherapy =$dts['Type_of_brachytherapy'];
        $Number_of_Fraction =$dts['Number_of_Fraction'];
        $Dose_per_Fraction =$dts['Dose_per_Fraction'];
        $Name_of_Applicator = $dts['Name_of_Applicator'];
        $type_of_applicator = $dts['type_of_applicator'];
        $Insertion_Employee = $dts['Insertion_Employee'];
        $Rectum_Percentage = $dts['Rectum_Percentage'];
        $Bladder_Percentage = $dts['Bladder_Percentage'];
        $calculation_Employee = $dts['calculation_Employee'];
        $Planned_Time = $dts['Planned_Time'];
        $Fraction_Number = $dts['Fraction_Number'];
        $Insertion_ID = $dts['Insertion_ID'];
        $Insertion_Status = $dts['Insertion_Status'];
        $Comment_Calculation = $dts['Comment_Calculation'];
        $Comment_Insertion = $dts['Comment_Insertion'];

        $Inserted_By = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$Insertion_Employee'"))['Employee_Name'];
        $Calculated_By = mysqli_fetch_assoc(mysqli_query($conn, "SELECT Employee_Name FROM tbl_employee WHERE Employee_ID = '$calculation_Employee'"))['Employee_Name'];
    }
}
?>
<a href="brachytherapy_parameter_patientlist.php?BrachtherapyThis=Brachytherapy" class='art-button-green'>BACK</a>

<br>
<fieldset>
    <legend align=center>BRACHYTHERAPY TREATMENT DELIVERY</legend>
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
        <div class="box box-primary" style="height: 650px;overflow-y: scroll;overflow-x: hidden;">
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
        <center>
            <h4><b>PARAMETER CALCULATION</b></h4>
        </center>
            <table class="table" style="background: #FFFFFF; width: 100%">
                <tr>
                    <th style='text-align: right;'>Planned Time (MM:SS:Ms)</th>
                    <th><input type='text' id='Planned_Time' style="width: 100%;"  disabled='disabled' value='<?= $Planned_Time ?>'></th>
                    <th style='text-align: right;'>Bladder Point Dose Percentage </th>
                    <th><input type='text' id='Bladder_Percentage' style="width: 100%;"  disabled='disabled' value='<?= $Bladder_Percentage ?>'></th>
                </tr>
                <tr>
                    <th style='text-align: right;'>Rectum Point Dose Percentage </th>
                    <th><input type='text' id='Rectum_Percentage' style="width: 100%;" disabled='disabled' value='<?= $Rectum_Percentage ?>'></th>
                    <th style='text-align: right;'>Calculated By </th>
                    <th><input type='text' style="width: 100%;" disabled='disabled' value='<?= ucwords($Calculated_By) ?>'></th>

                </tr>
                <tr>
                        <th style='text-align: right;'>Calculation Comment </th>
                        <th colspan='3'>
                        <textarea name="" id="Comment_Insertion" cols="30" rows="4" style="resize: none;" disabled='disabled'><?= $Comment_Calculation ?></textarea>
                    </th>
                </tr>
            </table>
            <center>
            <h4><b>TREATMENT DELIVERY</b></h4>
        </center>
        <table class="table" style="background: #FFFFFF; width: 100%">
                <tr style='background: #dedede;'>
                    <th style='text-align: right;'>Delivered Dose</th>
                    <th><input type='text' id='Planned_Time' style="width: 100%;"  disabled='disabled' value='<?= $Dose_per_Fraction ?>'></th>
                    <th style='text-align: right;'>Fraction Number </th>
                    <th><input type='text' id='Bladder_Percentage' style="width: 100%;"  disabled='disabled' value='<?= $Insertion_Status ?>'></th>
                </tr>
                <tr style='background: #dedede;'>
                    <th style='text-align: right;'>Commulative Dose </th>
                    <th><input type='text' id='Commulative_Dose' style="width: 100%;" placeholder='Commulative Dose'></th>
                    <th style='text-align: right;'>Treated Time (MM:SS:MS) </th>
                    <th><input type='text' style="width: 100%;" id='Treated_Time' placeholder="Treated Time"></th>

                </tr>
                <tr>
                        <th style='text-align: right;'>Remarks </th>
                        <th colspan='3'>
                        <textarea name="" id="Remarks" cols="30" rows="4" style="resize: none;"></textarea>
                    </th>
                </tr>
            </table>
            <br>
            <center><input type="button" value="SAVE TREATMENT" onclick="save_insertion(<?= $Insertion_ID ?>)" class="art-button-green"></center>
            <div class="box box-primary" style="height: 160px;overflow-y: scroll;overflow-x: hidden; margin-top: 10px;">
                <table class="table" style="background: #FFFFFF; width: 100%">
                    <tr>
                        <td><b>S/N</b></td>
                        <td><b>DATE</b></td>
                        <td><b>DELIVERED BY</b></td>
                        <td><b>DELIVERED DOSE</b></td>
                        <td><b>COMMULATIVE DOSE</b></td>
                        <td><b>FRACTION NUMBER</b></td>
                        <td><b>TREATED TIME</b></td>
                        
                    </tr>
                    <tbody id='Treated_Data'></tbody>
                </table>

            </div>

</fieldset>
<?php
include("includes/footer.php");
?>

<script>
    function save_insertion(Insertion_ID){
        Treated_Time = $("#Treated_Time").val();
        Commulative_Dose = $("#Commulative_Dose").val();
        Remarks = $("#Remarks").val();
        Employee_ID = '<?= $Employee_ID ?>';

        if(Treated_Time == '' || Treated_Time == undefined){
            alert("Please Specify Treated Time");
            $("#Treated_Time").css("border","2px solid red");
            $("#Treated_Time").focus();
            exit();
        }

        if(Commulative_Dose == '' || Commulative_Dose == undefined){
            alert("Please Specify the Commulative Dose");
            $("#Commulative_Dose").css("border","3px solid red");
            $("#Commulative_Dose").focus();
            exit();
        }

        if(confirm("You'are about to Save This Brachytherapy Treatment?, click OK to Proceed, Cancel to Edit/Review it")){
            $.ajax({
                type: "POST",
                url: "ajax_update_brachytherapy_requests.php",
                data: {
                    Treated_Time:Treated_Time,
                    Commulative_Dose:Commulative_Dose,
                    Employee_ID:Employee_ID,
                    action:'Treatment',
                    Insertion_ID:Insertion_ID,
                    Remarks:Remarks
                },
                cache: false,
                success: function (response) {
                    if(response == 200){
                        alert("Brachytherapy Insertion has been Submitted Successfully");
                        display_Datas(Insertion_ID);
                    }else{
                        alert("Brachytherapy Insertion Failed to be Submitted");
                        exit();
                    }
                }
            });
        }
    }
    function display_Datas(Insertion_ID){
        action = 'Get Data';
        $.ajax({
            type: "POST",
            url: "ajax_update_brachytherapy_requests.php",
            data: {
                Insertion_ID:Insertion_ID,
                action:action
            },
            cache: false,
            success: function (response) {
                document.getElementById('Treated_Data').innerHTML = response;

            }
        });
    }
    $(document).ready(function () {
        Insertion_ID = '<?= $Insertion_ID ?>';
        display_Datas(Insertion_ID);
    });
</script>