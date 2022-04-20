    <?php

    include("./includes/connection.php");


    $Employee_Name = $_SESSION['userinfo']['Employee_Name'];

    $is_perf_by_signe_off = $_SESSION['hospitalConsultaioninfo']['req_perf_by_signed_off'];
    $is_const_per_day_count = $_SESSION['hospitalConsultaioninfo']['en_const_per_day_count'];



    if (isset($_POST['Registration_ID'])) {
        $Registration_ID = $_POST['Registration_ID'];
    } else {
        $Registration_ID = '';
    }
    if(isset($_POST['Exemption_ID'])){
        $Exemption_ID = $_POST['Exemption_ID'];
    }else{
        $Exemption_ID ='';
    }
    if(isset($_POST['tathimin'])){
    $select_patient = mysqli_query($conn, "SELECT Patient_Name,Gender, Date_Of_Birth, tef.Registration_ID,  Exemption_ID FROM  tbl_temporary_exemption_form tef, tbl_patient_registration pr WHERE tef.Registration_ID='$Registration_ID' AND Exemption_ID='$Exemption_ID' AND tef.Registration_ID=pr.Registration_ID ") or die(mysqli_error($conn));
    while($row = mysqli_fetch_assoc($select_patient)){
        $Exemption_ID =$row['Exemption_ID'];
        $Patient_Name = $row['Patient_Name'];
        $Gender = $row['Gender'];
        $Date_Of_Birth = $row['Date_Of_Birth'];
    

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $age = $diff->y . " Years, ";
        $age .= $diff->m . " Months, ";
        $age .= $diff->d . " Days";
    }
    ?>
    <fieldset>
        <legend align="center">  
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> "; ?></b></span>          
                          
        </legend>        
    </fieldset>
    <fieldset>
        <legend align="center" style='color:red;'>SEHEMU HII IJAZWE NA CA</legend>
        <form action="" method="POST">
            <div class="row">
                <div class="col-md-10">
                    <textarea name="tathiminiyaphro" id="tathimini"  class="form-control" rows="3"></textarea>
                </div>
                <div class="col-md-2">
                    <button class="art-button-green" type="button" name="tathiminPHRO" onclick="savetathimini(<?php echo $Exemption_ID; ?>);showtashimin()">SAVE</button>
                </div>
            </div>
        </form>
    </fieldset>
    <?php
    }


    if(isset($_POST['edit_phro'])){
        $PHRO_ID = $_POST['PHRO_ID'];
        $select_patient = mysqli_query($conn, "SELECT Patient_Name,Gender, Date_Of_Birth, tef.Registration_ID,  Exemption_ID FROM  tbl_temporary_exemption_form tef, tbl_patient_registration pr WHERE tef.Registration_ID='$Registration_ID' AND Exemption_ID='$Exemption_ID' AND tef.Registration_ID=pr.Registration_ID ") or die(mysqli_error($conn));
        while($row = mysqli_fetch_assoc($select_patient)){
            $Exemption_ID =$row['Exemption_ID'];
            $Patient_Name = $row['Patient_Name'];
            $Gender = $row['Gender'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
        

            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $age = $diff->y . " Years, ";
            $age .= $diff->m . " Months, ";
            $age .= $diff->d . " Days";
        }

        $select_tathimini_ya_PHRO = mysqli_query($conn, "SELECT tathiminiyaphro, PHRO_ID from tbl_expemption_phro where Exemption_ID='$Exemption_ID'" ) or die(mysqli_error($conn));
            if((mysqli_num_rows($select_tathimini_ya_PHRO))>0){
                while($row = mysqli_fetch_assoc($select_tathimini_ya_PHRO)){
                    $tathiminiyaphro = $row['tathiminiyaphro'];
                    $PHRO_ID = $row['PHRO_ID'];
                    
                }
            }
        ?>
    <fieldset>
        <legend align="center">  
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> "; ?></b></span>          
                          
        </legend>        
    </fieldset>
    <fieldset>
        <legend align="center" style='color:red;'>SHOULD BE EDITED BY CA WHO SAVED THIS DATA!!!</legend>
        <form action="" method="POST">
            <div class="row">
                <div class="col-md-10">
                    <textarea name="tathiminiyaphro" id="tathimini"  class="form-control" rows="3"><?php echo $tathiminiyaphro; ?></textarea>
                </div>
                <div class="col-md-2">
                    <button class="art-button-green" type="button" name="tathiminPHRO" onclick="update_tathimini(<?php echo $PHRO_ID; ?>)">UPDATE</button>
                </div>
            </div>
        </form>
    </fieldset>
    <?php
    }
