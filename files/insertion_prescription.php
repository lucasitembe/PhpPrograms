<?php
include("includes/connection.php");
include("includes/header.php");
include("radical_treatment_functions.php");

$Employee_ID = $_SESSION['userinfo']['Employee_ID'];

$Brachytherapy_ID = (isset($_GET['Brachytherapy_ID'])) ? $_GET['Brachytherapy_ID'] : 0;
$Registration_ID = (isset($_GET['Registration_ID'])) ? $_GET['Registration_ID'] : 0;

$Patients = json_decode(getPatientInfomations($conn,$Registration_ID),true);
$response = json_decode(getDoctorRequestsBrachy($conn,$Brachytherapy_ID,$Registration_ID),true);

$Select_brachytherapy = mysqli_query($conn, "SELECT Employee_Name, Type_of_brachytherapy, Number_of_Fraction, Dose_per_Fraction FROM tbl_brachytherapy_requests br, tbl_employee em WHERE br.Brachytherapy_ID = '$Brachytherapy_ID' AND em.Employee_ID = br.Employee_ID") or die(mysqli_error($conn));
if(mysqli_num_rows($Select_brachytherapy)>0){
    while($dts = mysqli_fetch_assoc($Select_brachytherapy)){
        $Dr_Employee_Name =$dts['Employee_Name'];
        $Type_of_brachytherapy =$dts['Type_of_brachytherapy'];
        $Number_of_Fraction =$dts['Number_of_Fraction'];
        $Dose_per_Fraction =$dts['Dose_per_Fraction'];
    }
}
?>
<a href="brachytherapy_patient_list.php?BrachtherapyThis=Brachytherapy" class='art-button-green'>BACK</a>

<br>
<br>
<fieldset>
    <legend align=center>INSERTION PRESCRIPTION</legend>
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
        <div class="box box-primary" style="height: 340px;overflow-y: scroll;overflow-x: hidden;">
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
                    <th><select id='type_of_applicator' style='width: 100%; height: 30px;'>
                            <option value="" selected='selected'>SELECT TYPE OF APPLICATOR</option>
                            <option value="Ring Only">Ring Only</option>
                            <option value="Tanderm & Ring">Tanderm & Ring</option>
                            <option value="Cylinder">Cylinder</option>
                        </select>
                    </th>
                </tr>
                <tr>
                    <th style='text-align: right;'>Name of Applicator </th>
                    <th><input type='text' style="width: 100%;" id='Name_of_Applicator' placeholder="Name of Applicator"></th>
                </tr>
                <tr>
                    <th style='text-align: right;'>Comment </th>
                    <th colspan='3'>
                        <!-- <input type='text' style="width: 100%;" id='Comment_Insertion' placeholder="Name of Applicator"> -->
                        <textarea name="" id="Comment_Insertion" cols="30" rows="3" style="resize: none;"></textarea>
                    </th>
                </tr>

            </table>
            <br><br>
            <center><input type="button" value="SAVE INSERTION" onclick="save_insertion(<?= $Brachytherapy_ID ?>)" class="art-button-green"></center>
        </div>
</fieldset>
<br>
<?php
include("includes/footer.php");
?>

<script>
    function save_insertion(Brachytherapy_ID){
        type_of_applicator = $("#type_of_applicator").val();
        Name_of_Applicator = $("#Name_of_Applicator").val();
        Comment_Insertion = $("#Comment_Insertion").val();
        Employee_ID = '<?= $Employee_ID ?>';

        if(Name_of_Applicator == '' || Name_of_Applicator == undefined){
            alert("Please Specify the Name of the Applicator");
            $("#Name_of_Applicator").css("border","2px solid red");
            $("#Name_of_Applicator").focus();
            exit();
        }

        if(type_of_applicator == '' || type_of_applicator == undefined){
            alert("Please Specify the Type of Applicator");
            $("#type_of_applicator").css("border","3px solid red");
            $("#type_of_applicator").focus();
            exit();
        }
        if(confirm("You'are about to Save This Brachytherapy Insertion Prescription, click OK to Proceed, Cancel to Edit/Review it")){
            $.ajax({
                type: "POST",
                url: "ajax_update_brachytherapy_requests.php",
                data: {
                    Brachytherapy_ID:Brachytherapy_ID,
                    Name_of_Applicator:Name_of_Applicator,
                    type_of_applicator:type_of_applicator,
                    Employee_ID:Employee_ID,
                    Comment_Insertion:Comment_Insertion,
                    action:'Insertion'
                },
                cache: false,
                success: function (response) {
                    if(response == 200){
                        alert("Brachytherapy Insertion has been Submitted Successfully");
                        document.location = "brachytherapy_patient_list.php?BrachtherapyThis=Brachytherapy";
                    }else{
                        alert("Brachytherapy Insertion Failed to be Submitted");
                        exit();
                    }
                }
            });
        }
    }
</script>