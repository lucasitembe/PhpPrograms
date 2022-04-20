<?php
    
    include("./includes/connection.php");
    
    session_start();
    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];

    $is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];
    $is_const_per_day_count = $_SESSION['hospitalConsultaioninfo']['en_const_per_day_count'];

    
    if(isset($_POST['Employee_ID'])){
        $Employee_ID= $_POST['Employee_ID'];
        }else{ 
        $Employee_ID="";   
        }
        $Patient_Bill_ID  =$_POST['Patient_Bill_ID'];
    
    if (isset($_POST['Registration_ID'])) {
        $Registration_ID = $_POST['Registration_ID'];
    }else {
        $Registration_ID = '';
    }

    if (isset($_POST['Exemption_ID'])) {
        $Exemption_ID = $_POST['Exemption_ID'];
    }else {
        $Exemption_ID = '';
    } 

        if(isset($_POST['exemptionsaves'])){
            $Anaombewa= mysqli_real_escape_string($conn, $_POST['Anaombewa']);
            $ushauriwabima = mysqli_real_escape_string($conn, $_POST['ushauriwabima']);
            $tathiminiyamsaada = mysqli_real_escape_string($conn, $_POST['tathiminiyamsaada']);
            $alishawahikupewa = mysqli_real_escape_string($conn, $_POST['alishawahikupewa']);
            $kiasikinachoombewamshamaha = mysqli_real_escape_string($conn, $_POST['kiasikinachoombewamshamaha']);
            $kiwango_cha_msamaha = mysqli_real_escape_string($conn, $_POST['kiwango_cha_msamaha']);
            $mapendekezomsamaha = mysqli_real_escape_string($conn, $_POST['mapendekezomsamaha']);

            $mode_of_treatment = mysqli_real_escape_string($conn, $_POST['mode_of_treatment']);
            $taarifa_za_mgonjwa = mysqli_real_escape_string($conn, $_POST['taarifa_za_mgonjwa']);
            $patient_next_kin = mysqli_real_escape_string($conn, $_POST['patient_next_kin']);
            $next_kin_phoneNo = mysqli_real_escape_string($conn, $_POST['next_kin_phoneNo']);
            $Next_kin_relationship = mysqli_real_escape_string($conn, $_POST['Next_kin_relationship']);
            $previous_hospital_treatment = mysqli_real_escape_string($conn, $_POST['previous_hospital_treatment']);
            $time_of_treatment = mysqli_real_escape_string($conn,$_POST['time_of_treatment']);
            $Nurse_Exemption_ID = $_POST['Nurse_Exemption_ID'];
            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
            $insert_form = mysqli_query($conn, "INSERT INTO tbl_temporary_exemption_form (ushauriwabima,mode_of_treatment, Anaombewa, taarifa_za_mgonjwa,patient_next_kin,tathiminiyamsaada, mapendekezomsamaha,kiasikinachoombewamshamaha,next_kin_phoneNo,Next_kin_relationship,previous_hospital_treatment, kiwango_cha_msamaha,time_of_treatment, alishawahikupewa, Registration_ID,Nurse_Exemption_ID, Employee_ID) VALUES('$ushauriwabima','$mode_of_treatment','$Anaombewa','$taarifa_za_mgonjwa','$patient_next_kin', '$tathiminiyamsaada', '$mapendekezomsamaha', '$kiasikinachoombewamshamaha','$next_kin_phoneNo', '$Next_kin_relationship','$previous_hospital_treatment','$kiwango_cha_msamaha', '$time_of_treatment','$alishawahikupewa', '$Registration_ID','$Nurse_Exemption_ID', '$Employee_ID')") or die(mysqli_error($conn));
            
            if(!$insert_form){
                echo "failed to insert data";
            }else{
                echo "Saved successful";
            }
        }

        if(isset($_POST['exemption_update'])){
            $ushauriwabima = mysqli_real_escape_string($conn, $_POST['ushauriwabima']);
            $tathiminiyamsaada = mysqli_real_escape_string($conn, $_POST['tathiminiyamsaada']);
            $alishawahikupewa = mysqli_real_escape_string($conn, $_POST['alishawahikupewa']);
            $kiasikinachoombewamshamaha = mysqli_real_escape_string($conn, $_POST['kiasikinachoombewamshamaha']);
            $kiwango_cha_msamaha = mysqli_real_escape_string($conn, $_POST['kiwango_cha_msamaha']);
            $mapendekezomsamaha = mysqli_real_escape_string($conn, $_POST['mapendekezomsamaha']);
            $Exemption_ID=mysqli_real_escape_string($conn, $_POST['Exemption_ID']);

            $mode_of_treatment = mysqli_real_escape_string($conn, $_POST['mode_of_treatment']);
            $taarifa_za_mgonjwa = mysqli_real_escape_string($conn, $_POST['taarifa_za_mgonjwa']);
            $patient_next_kin = mysqli_real_escape_string($conn, $_POST['patient_next_kin']);
            $next_kin_phoneNo = mysqli_real_escape_string($conn, $_POST['next_kin_phoneNo']);
            $Next_kin_relationship = mysqli_real_escape_string($conn, $_POST['Next_kin_relationship']);
            $previous_hospital_treatment = mysqli_real_escape_string($conn, $_POST['previous_hospital_treatment']);
            $time_of_treatment = mysqli_real_escape_string($conn,$_POST['time_of_treatment']);


            //  $Nurse_Exemption_ID =$_POST['Nurse_Exemption_ID'];
            $Anaombewa = mysqli_real_escape_string($conn, $_POST['Anaombewa']);

            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
            $Update_form = mysqli_query($conn, "UPDATE tbl_temporary_exemption_form SET Anaombewa='$Anaombewa',  ushauriwabima='$ushauriwabima',mode_of_treatment='$mode_of_treatment',taarifa_za_mgonjwa='$taarifa_za_mgonjwa',patient_next_kin='$patient_next_kin', tathiminiyamsaada='$tathiminiyamsaada',mapendekezomsamaha='$mapendekezomsamaha',kiasikinachoombewamshamaha='$kiasikinachoombewamshamaha',next_kin_phoneNo='$next_kin_phoneNo', kiwango_cha_msamaha='$kiwango_cha_msamaha',Next_kin_relationship='$Next_kin_relationship',time_of_treatment='$time_of_treatment', previous_hospital_treatment='$previous_hospital_treatment', alishawahikupewa='$alishawahikupewa', Updated_by='$Employee_ID', Updated_at =NOW() WHERE Registration_ID='$Registration_ID' AND Exemption_ID='$Exemption_ID' ") or die(mysqli_error($conn));
            
            if(!$Update_form){
                echo "failed to update data";
            }else{
                echo "<div class='col-offset-md-3 col-md-6 col-offset-md-3'>
                        <div class='alert alert-success alert-dismissible'>
                        <a  aria-label='close' data-dismiss='alert' class='close'>&times;</a> Update successful
                        </div>
                    </div>";

            } 
        }
    
        if(isset($_POST['tathiminPHRO'])){
            $tathiminiyaphro = mysqli_real_escape_string($conn, $_POST['tathiminiyaphro']);
            $Exemption_ID = $_POST['Exemption_ID'];
            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
           
            $insert_PHRO = mysqli_query($conn, "INSERT INTO tbl_expemption_phro(tathiminiyaphro,Exemption_ID, Employee_ID )VALUES('$tathiminiyaphro','$Exemption_ID', '$Employee_ID' )") or die(mysqli_error($conn));

            if(!$insert_PHRO){
                echo "failed to insert PHRO tathimini data";
            }else{
                echo "success";
            }
        }
        if(isset($_POST['update_tathiminPHRO'])){
            $tathiminiyaphro = mysqli_real_escape_string($conn, $_POST['tathiminiyaphro']);
            $Exemption_ID = $_POST['Exemption_ID'];
            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
            $PHRO_ID = $_POST['PHRO_ID'];
           //die("UPDATE tbl_expemption_phro SET tathiminiyaphro='$tathiminiyaphro', Updated_at=NOW() WHERE PHRO_ID='$PHRO_ID' AND Employee_ID='$Employee_ID'");
            $update_PHRO_tathimin = mysqli_query($conn, "UPDATE tbl_expemption_phro SET tathiminiyaphro='$tathiminiyaphro', Updated_at=NOW() WHERE PHRO_ID='$PHRO_ID' AND Employee_ID='$Employee_ID'") or die(mysqli_error($conn));

            if(!$update_PHRO_tathimin){
                echo "failed to update PHRO tathimini data";
            }else{
                echo "success phro to update";
            }
        }

        // $select_tathimini_ya_PHRO = mysqli_query($conn, "SELECT tathiminiyaphro from tbl_expemption_phro where Exemption_ID='$Exemption_ID'" ) or die(mysqli_error($conn));

        // while($row = mysqli_fetch_assoc($select_tathimini_ya_PHRO)){
        //     $tathiminiyaphro = $row['tathiminiyaphro'];
            
        // }
        // echo $tathiminiyaphro;

        if(isset($_POST['maoniyadhs'])){
            $maoniDHS = mysqli_real_escape_string($conn, $_POST['maoniDHS']);
            $sababudhs = mysqli_real_escape_string($conn, $_POST['sababudhs']);
            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

            $save_maoni = mysqli_query($conn, "INSERT INTO tbl_exemption_maoni_dhs(maoniDHS, sababudhs,Employee_ID,Exemption_ID)VALUES('$maoniDHS','$sababudhs', '$Employee_ID', '$Exemption_ID' )") or die(mysqli_error($conn));
            if(!$save_maoni){
                echo "fail";
            }else{
                echo "Success";
            }
        }


        if(isset($_POST['nurse_save_btn'])){
            $Jina_la_balozi = mysqli_real_escape_string($conn, $_POST['Jina_la_balozi']);
            $simu_ya_balozi = mysqli_real_escape_string($conn, $_POST['simu_ya_balozi']);
            $maelezo_ya_nurse_mratibu = mysqli_real_escape_string($conn, $_POST['maelezo_ya_nurse_mratibu']);
            
            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];
            $Idara_ID = $_SESSION['bill_working_department'];
            $jina_la_Idara="";
            $select_idara = mysqli_query($conn, "SELECT  finance_department_name FROM tbl_finance_department WHERE finance_department_id='$Idara_ID'") or die(mysqli_error($conn));
            while($idara = mysqli_fetch_assoc($select_idara)){
                $jina_la_Idara = $idara['finance_department_name'];
            }
            $select_maoni_ya_nurse = mysqli_query($conn, "SELECT Nurse_Exemption_ID from tbl_nurse_exemption_form where  Employee_ID='$Employee_ID' AND Registration_ID='$Registration_ID' AND  DATE(created_at)=CURDATE()" ) or die(mysqli_error($conn));
            if((mysqli_num_rows($select_maoni_ya_nurse))>0){
                $Nurse_Exemption_ID = mysqli_fetch_assoc($select_maoni_ya_nurse);
                $sql_update = mysqli_query($conn, "UPDATE tbl_nurse_exemption_form SET maelezo_ya_nurse_mratibu='$maelezo_ya_nurse_mratibu', simu_ya_balozi='$simu_ya_balozi', Jina_la_balozi='$Jina_la_balozi' WHERE  Employee_ID='$Employee_ID' AND Registration_ID='$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID' AND  DATE(created_at)=CURDATE()") or die(mysqli_error($conn));
                if(!$sql_update){
                    echo "Failed to save";
                }else{
                    ?>
                <script>
                alert("Data updated successful");
                document.location.reload();
                </script>
                <?php
                }
            }else{
            $form_ya_maombi = mysqli_query($conn, "INSERT INTO tbl_nurse_exemption_form(Idara,Patient_Bill_ID, Jina_la_balozi,simu_ya_balozi,maelezo_ya_nurse_mratibu, Registration_ID,Employee_ID ) VALUES ('$jina_la_Idara','$Patient_Bill_ID', '$Jina_la_balozi','$simu_ya_balozi','$maelezo_ya_nurse_mratibu','$Registration_ID','$Employee_ID')") or die(mysqli_error($conn));
            
            if(!$form_ya_maombi){
                echo "<div class='alert alert-danger alert-dismissible col-offset-md-3 col-md-6 col-offset-md-3'>
                        <a href='#' aria-label='close' data-dismiss='alert' class='close'>&times;</a> Failed to save
                        </div>";
            }else{
                echo "<div class='alert alert-success alert-dismissible col-offset-md-3 col-md-6 col-offset-md-3'>
                        <a href='#' aria-label='close' class='close'>&times;</a>Data saved successful
                        </div>";
                ?>
                <!-- <script> 
                // alert("Data saved successful");
                // document.location.reload();
                </script>-->
                <?php
            }
        }
    }
        
        if(isset($_POST['btn_edit_form'])){
            $Registration_ID = $_POST['Registration_ID'];
            $Nurse_Exemption_ID = $_POST['Nurse_Exemption_ID'];
            ?>
            <fieldset>
                <center>
                <table class="table" style="background-color:#cccccc">
                    <?php 
                        $select_patient = mysqli_query($conn,"SELECT Patient_Name FROM tbl_patient_registration WHERE Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                        while($patient_row = mysqli_fetch_assoc($select_patient)){
                            $patient_name = $patient_row['Patient_Name'];
                        }

                        //die("SELECT Hospital_Ward_Name FROM tbl_admission as ad, tbl_hospital_ward as ward WHERE ad.Hospital_Ward_ID = ward.Hospital_Ward_ID AND Registration_ID = '$Registration_ID'");

                        $select_patient_ward = mysqli_query($conn,"SELECT Hospital_Ward_Name FROM tbl_admission as ad, tbl_hospital_ward as ward WHERE ad.Hospital_Ward_ID = ward.Hospital_Ward_ID AND Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
                        while($patient_ward_row = mysqli_fetch_assoc($select_patient_ward)){
                            $Hospital_Ward_Name = $patient_ward_row['Hospital_Ward_Name'];
                        }
                    ?>
                        <tr>
                            <th>Hosp. Reg Number:</th>
                            <td><?php echo $Registration_ID?></td>
                            <th>Name:</th>
                            <td><?php echo $patient_name?></td>
                            <th>Ward</th>
                            <td><?php echo $Hospital_Ward_Name?></td>
                            <th>Date</th>
                            <td><?php echo date("Y-M-d"); ?></td>
                        </tr>
                        <input type="text" id="carry_name" value="<?php echo $patient_name ?>" style="display:none">
                </table>
            </center>
            </fieldset>
            <br><br>
            <fieldset style="background-color: #fff">
                <table class="table">
                    <form action="" method="post">
                    <tr>
                        <?php 
                            $select_form_ya_maombi = mysqli_query($conn, "SELECT * FROM tbl_nurse_exemption_form WHERE Registration_ID='$Registration_ID'  AND Nurse_Exemption_ID ='$Nurse_Exemption_ID'") or die(mysqli_error($conn));

                            if((mysqli_num_rows($select_form_ya_maombi))>0){
                                while($fomu = mysqli_fetch_assoc($select_form_ya_maombi)){
                                    $Nurse_Exemption_ID = $fomu['Nurse_Exemption_ID'];
                                    $Jina_la_balozi = $fomu['Jina_la_balozi'];
                                    $simu_ya_balozi = $fomu['simu_ya_balozi'];
                                    $maelezo_ya_nurse_mratibu = $fomu['maelezo_ya_nurse_mratibu'];
                            ?>
                            <tr>
                            <td width="25%" style='text-align:right;'><b>Jina la balozi</b> </td>
                            <td width="25%"><input  class="form-control" name="" id="Jina_la_balozi" value="<?php echo $Jina_la_balozi; ?>"></td>
                            <td width="25%" style='text-align:right;'><b> Namba ya simu ya balozi</b></td>
                            <td width="25%"><input class="form-control" name="" id="simu_ya_balozi"  value="<?php echo $simu_ya_balozi; ?>"></td>
                        </tr>
                        <tr>
                            <td colspan="" style='text-align:right;' width="30%"><b>Maelezo mafupi kutoka kwa Mratibu wa idara husika</b></td>
                            <td colspan="3" width="70%"> <textarea name="maelezo_ya_nurse_mratibu" id="maelezo_ya_nurse_mratibu" cols="30" rows="4" class="form-control"><?php echo $maelezo_ya_nurse_mratibu; ?></textarea> </td>
                        </tr>
                        <tr>
                            
                            <td colspan="3"></td>
                            <td >
                                <a href="#" onclick="Exemption_update_form('<?php echo $Registration_ID;?>', '<?php echo $Nurse_Exemption_ID; ?>')" class="art-button-green" name='btn_update_form'>UPDATE FORM</a>
                            </td>
                        </tr>
                        <?php }
                            }else{
                                echo "no result found";
                            }
                            ?>
                    </form>
                </table>
            </fieldset>
            <?php
       
}
    if(isset($_POST['btn_update_form'])){
        $Jina_la_balozi = mysqli_real_escape_string($conn, $_POST['Jina_la_balozi']);
        $simu_ya_balozi = mysqli_real_escape_string($conn, $_POST['simu_ya_balozi']);
        $maelezo_ya_nurse_mratibu = mysqli_real_escape_string($conn, $_POST['maelezo_ya_nurse_mratibu']);
        $Registration_ID = $_POST['Registration_ID'];
        $Nurse_Exemption_ID = $_POST['Nurse_Exemption_ID'];
        $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

        $Update_form = mysqli_query($conn, "UPDATE tbl_nurse_exemption_form SET Jina_la_balozi='$Jina_la_balozi', simu_ya_balozi='$simu_ya_balozi', maelezo_ya_nurse_mratibu='$maelezo_ya_nurse_mratibu', Updated_by='$Employee_ID', Updated_at=NOW() WHERE Registration_ID='$Registration_ID' AND Nurse_Exemption_ID='$Nurse_Exemption_ID'") or die(mysqli_error($conn));

        if(!$Update_form){
            echo "<div class='col-offset-md-3 col-md-6 col-offset-md-3'>
                    <div class='alert alert-danger alert-dismissible'>
                    <a href='#' aria-label='close' data-dismiss='alert' class='close'>&times;</a> Failed to save
                    </div>
                </div>";
        }else{
            echo "<div class='col-offset-md-3 col-md-6 col-offset-md-3'>
            <div class='alert alert-success alert-dismissible'>
            <a href='#' aria-label='close' data-dismiss='alert' class='close'>&times;</a> Updated successful
            </div>
        </div>";
        }
    }

   
    if(isset($_POST['updatemaoniyadhs'])){
            $MaoniDHS_ID = mysqli_real_escape_string($conn, $_POST['MaoniDHS_ID']);
            $maoniDHS = mysqli_real_escape_string($conn, $_POST['maoniDHS']);
            $sababudhs = mysqli_real_escape_string($conn, $_POST['sababudhs']);
            $Employee_ID=$_SESSION['userinfo']['Employee_ID'];

            $save_maoni = mysqli_query($conn, "UPDATE tbl_exemption_maoni_dhs SET maoniDHS='$maoniDHS', sababudhs='$sababudhs',Updated_at=now() WHERE Employee_ID='$Employee_ID' AND MaoniDHS_ID='$MaoniDHS_ID'") or die(mysqli_error($conn));
            if(!$save_maoni){
                echo "fail";
            }else{
                echo "Success";
            }
    }