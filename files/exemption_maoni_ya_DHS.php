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
if(isset($_POST['maoniDHS'])){
    $select_patient = mysqli_query($conn, "SELECT Nurse_Exemption_ID, Patient_Name,Gender, Date_Of_Birth, tef.Registration_ID,  Exemption_ID FROM  tbl_temporary_exemption_form tef, tbl_patient_registration pr WHERE tef.Registration_ID='$Registration_ID' AND Exemption_ID='$Exemption_ID' AND tef.Registration_ID=pr.Registration_ID ") or die(mysqli_error($conn));
while($row = mysqli_fetch_assoc($select_patient)){
    $Exemption_ID =$row['Exemption_ID'];
    $Patient_Name = $row['Patient_Name'];
    $Gender = $row['Gender'];
    $Date_Of_Birth = $row['Date_Of_Birth'];
    $Nurse_Exemption_ID =$row['Nurse_Exemption_ID'];


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
    <legend align="center" style='color:red;'>SEHEMU HII IJAZWE NA MKURUGENZI</legend>
    <form action="" method="POST">
    <table width="100%">
        Maoni /idhinisho la Mkurugenzi
        <tbody>
            <tr>
                <td >
                    <span><b>Ndio</b><input type="radio" name="maoniDHS" id="dhsndio" value="NDIO"></span>
                    <span><b>Hapana</b>  <input type="radio" name="maoniDHS" id="dhshapana" value="HAPANA"></span>                   
                </td>                
                <td >
                    <span class="form-inline"><b>Sababu/Maoni </b> <textarea class="form-control" name="sababudhs" id="sababuDHS" rows="3" cols="75"></textarea></span>
                </td>                
            </tr>
            <tr>
                
                <td>
                    <button class="art-button-green " type="button" name="maoniyadhs" onclick="savemaoni(<?php echo $Exemption_ID; ?>)">SAVE</button>
                </td>
            </tr>
            </tbody>
    </table>
          
    </form>
</fieldset>

<?php
}
if(isset($_POST['MaoniDHS_ID'])){
    $MaoniDHS_ID = mysqli_real_escape_string($conn, $_POST['MaoniDHS_ID']);
    $Exemption_ID = $_POST['Exemption_ID'];
    $Registration_ID = $_POST['Registration_ID'];

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
    $select_maoni_ya_DHS = mysqli_query($conn, "SELECT maoniDHS, sababudhs,MaoniDHS_ID, emd.Employee_ID, Employee_Name, emd.created_at, employee_signature from tbl_exemption_maoni_dhs emd, tbl_employee e where Exemption_ID='$Exemption_ID' AND MaoniDHS_ID='$MaoniDHS_ID' AND emd.Employee_ID =e.Employee_ID" ) or die(mysqli_error($conn));

    if((mysqli_num_rows($select_maoni_ya_DHS))>0){
        while($row = mysqli_fetch_assoc($select_maoni_ya_DHS)){
            $MaoniDHS_ID = $row['MaoniDHS_ID'];
            $maoniDHS = $row['maoniDHS'];
            $sababudhs = $row['sababudhs'];
            $Employee_Name = $row['Employee_Name'];            
            $created_at = $row['created_at'];
            $employee_signature = $row['employee_signature'];
                if($employee_signature==""||$employee_signature==null){
                    $signature="_______________________";
                }else{
                    $signature="<img src='../esign/employee_signatures/$employee_signature' style='height:25px'>";
                }
        }
    }
    $checkedndio="";
    $checkedhapana="";
    if($maoniDHS=="NDIO"){
        $checkedndio="checked='checked'";
    }else{
        $checkedhapana="checked='checked'";
    }
    ?>
    <fieldset>
        <legend align="center">  
        <span style='color:yellow'><?php echo "<b>" . $Patient_Name . "</b>  | " . $Gender . "</b> | <b>" . $age . " years</b> "; ?></b></span>          
                        
        </legend>        
    </fieldset>
    <fieldset>
        <legend align="center" style='color:red;'>SEHEMU HII IJAZWE NA DHS</legend>
        <form action="" method="POST">
        <table width="100%">
            Maoni /idhinisho la DHS
            <tbody>
                <tr>
                    <td >
                        <span><b>Ndio</b><input type="radio" name="maoniDHS" <?php echo $checkedndio; ?> id="dhsndio" value="<?php echo $maoniDHS; ?>"></span>
                        <span><b>Hapana</b>  <input type="radio" name="maoniDHS" <?php echo $checkedhapana; ?> id="dhshapana" value="<?php echo $maoniDHS; ?>"></span>                   
                    </td>                
                    <td >
                        <span class="form-inline"><b>Sababu </b> <textarea class="form-control" name="sababudhs" id="sababuDHS" rows="3" cols="125"><?php echo $sababudhs; ?></textarea></span>
                    </td>                
                </tr>
                <tr>
                    
                    <td>
                        <button class="art-button-green " type="button" name="maoniyadhs" onclick="updatemaoni(<?php echo $MaoniDHS_ID; ?>)">UPDATE</button>
                    </td>
                </tr>
                </tbody>
        </table>
            
        </form>
    </fieldset>
    <?php
}