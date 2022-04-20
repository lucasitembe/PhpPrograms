<?php 
$Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
$Registration_ID = $_GET['Registration_ID'];
$Patient_Payment_ID = $_GET['Patient_Payment_ID'];
if (isset($_POST['submitnurseform'])) {

    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Employee_ID'])) {
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        } else {
            $Employee_ID = 0;
        }
    }


    $Registration_ID = mysqli_real_escape_string($conn,$_POST['Registration_ID']);
    $Patient_Payment_Item_List_ID = mysqli_real_escape_string($conn,$_POST['Patient_Payment_Item_List_ID']);
    $Allegies = mysqli_real_escape_string($conn,$_POST['Allegies']);
    $Special_Condition = mysqli_real_escape_string($conn,$_POST['Special_Condition']);
    $bmi = mysqli_real_escape_string($conn,$_POST['bmi']);
    $Patient_Direction = mysqli_real_escape_string($conn,$_POST['Patient_Direction']);
    $nurse_comment = mysqli_real_escape_string($conn,$_POST['nurse_comment']);
    $emergency = mysqli_real_escape_string($conn,$_POST['emergency']);
    $Consultant_ID = mysqli_real_escape_string($conn,$_POST['Consultant']);

    $modeoftransprot = mysqli_real_escape_string($conn,$_POST['modeoftransprot']);
    $accomaniedby = mysqli_real_escape_string($conn,$_POST['accomaniedby']);
    $pastmedhist = mysqli_real_escape_string($conn,$_POST['pastmedhist']);
    $current_medications = mysqli_real_escape_string($conn,$_POST['current_medications']);

    //get employee id
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Employee_ID'])) {
            $Employee_ID = $_SESSION['userinfo']['Employee_ID'];
        } else {
            $Employee_ID = 0;
        }
    }
    //get branch id
    if (isset($_SESSION['userinfo'])) {
        if (isset($_SESSION['userinfo']['Branch_ID'])) {
            $Branch_ID = $_SESSION['userinfo']['Branch_ID'];
        } else {
            $Branch_ID = 0;
        }
    }


  if (isset($_GET['checked']) && $_GET['checked'] == 'true')  {
 
         //Update the data
//        $qry_check = mysqli_query($conn,"SELECT Nurse_ID FROM tbl_nurse WHERE employee_ID=$Employee_ID AND Patient_Payment_Item_List_ID='$Patient_Payment_Item_List_ID'") or die(mysqli_error($conn));
//        $nurse_id = mysqli_fetch_assoc($qry_check)['Nurse_ID'];
//        exit($nurse_id);
        $nurse_id=$_GET['Nurse_ID'];
        $Nurse_Sql = "UPDATE tbl_nurse SET
                    Registration_ID='$Registration_ID',Employee_ID='$Employee_ID',bmi='$bmi',Allegies='$Allegies',
					Special_Condition='$Special_Condition',Nurse_DateTime=NOW(),Patient_Payment_Item_List_ID='" . $Patient_Payment_Item_List_ID . "',nurse_comment='$nurse_comment',emergency='$emergency',modeoftransprot='$modeoftransprot',accomaniedby='$accomaniedby',pastmedhist='$pastmedhist',current_medications='$current_medications' WHERE Nurse_ID='$nurse_id'";
        $week = mysqli_query($conn,$Nurse_Sql) or die(mysqli_error($conn));

        $j = 0;
        foreach ($_POST['Vital_ID'] as $vital_id) {
            $Vital_Value = $_POST['Vital_Value'];


            $NurseVitals2 = mysqli_query($conn,"UPDATE tbl_nurse_vital SET Vital_Value='$Vital_Value[$j]'
                                          WHERE Nurse_ID='$nurse_id' AND Vital_ID='$vital_id'
                    ") or die(mysqli_error($conn));
            $j++;
        }       
        //insert patient to nurse form
//        $Nurse_Sql = "INSERT INTO tbl_nurse(
//Registration_ID,Employee_ID,bmi,Allegies,
//Special_Condition,Nurse_DateTime,Patient_Payment_Item_List_ID,nurse_comment,emergency,modeoftransprot,accomaniedby,pastmedhist,current_medications)
//VALUES
//('$Registration_ID','$Employee_ID','$bmi','$Allegies','$Special_Condition',
//NOW(),'" . $Patient_Payment_Item_List_ID . "','$nurse_comment','$emergency','$modeoftransprot','$accomaniedby','$pastmedhist','$current_medications')";
//        $week = mysqli_query($conn,$Nurse_Sql) or die(mysqli_error($conn));
//
//
//        $Nurse_ID_query = mysqli_query($conn,"select Nurse_ID from tbl_nurse 
//where employee_ID=$Employee_ID order by Nurse_DateTime DESC limit 1");
//        $Nurse_ID = mysqli_fetch_array($Nurse_ID_query);
//        $nurse_id = $Nurse_ID['Nurse_ID'];
//        $j = 0;
//        foreach ($_POST['Vital_ID'] as $vital_id) {
//            $Vital_Value = $_POST['Vital_Value'];
//
//
//            $NurseVitals2 = mysqli_query($conn,"insert into tbl_nurse_vital(Nurse_ID,Vital_ID,Vital_Value) 
//values
//($nurse_id,$vital_id,'$Vital_Value[$j]')") or die(mysqli_error($conn));
//            $j++;
//        }
    } else {
//        echo "wamburaa";
//        exit();
            $Nurse_Sql = "INSERT INTO tbl_nurse(
Registration_ID,Employee_ID,bmi,Allegies,
Special_Condition,Nurse_DateTime,Patient_Payment_Item_List_ID,nurse_comment,emergency,modeoftransprot,accomaniedby,pastmedhist,current_medications)
VALUES
('$Registration_ID','$Employee_ID','$bmi','$Allegies','$Special_Condition',
NOW(),'" . $Patient_Payment_Item_List_ID . "','$nurse_comment','$emergency','$modeoftransprot','$accomaniedby','$pastmedhist','$current_medications')";
        $week = mysqli_query($conn,$Nurse_Sql) or die(mysqli_error($conn));


        $Nurse_ID_query = mysqli_query($conn,"select Nurse_ID from tbl_nurse 
where employee_ID=$Employee_ID AND Registration_ID='$Registration_ID' order by Nurse_DateTime DESC limit 1");
        $Nurse_ID = mysqli_fetch_array($Nurse_ID_query);
        $nurse_id = $Nurse_ID['Nurse_ID'];
        $j = 0;
        foreach ($_POST['Vital_ID'] as $vital_id) {
            $Vital_Value = $_POST['Vital_Value'];
           
            if(empty($Vital_Value[$j])){}else{
            $NurseVitals2 = mysqli_query($conn,"insert into tbl_nurse_vital(Nurse_ID,Vital_ID,Vital_Value) 
                            values ($nurse_id,$vital_id,'$Vital_Value[$j]')") or die(mysqli_error($conn));
            }
            $j++;
        }    
//        //Update the data
//        $nurse_id = mysqli_fetch_assoc($qry_check)['Nurse_ID'];
//        $Nurse_Sql = "UPDATE tbl_nurse SET
//                    Registration_ID='$Registration_ID',Employee_ID='$Employee_ID',bmi='$bmi',Allegies='$Allegies',
//					Special_Condition='$Special_Condition',Nurse_DateTime=NOW(),Patient_Payment_Item_List_ID='" . $Patient_Payment_Item_List_ID . "',nurse_comment='$nurse_comment',emergency='$emergency',modeoftransprot='$modeoftransprot',accomaniedby='$accomaniedby',pastmedhist='$pastmedhist',current_medications='$current_medications' WHERE Nurse_ID='$nurse_id'";
//        $week = mysqli_query($conn,$Nurse_Sql) or die(mysqli_error($conn));
//
//        $j = 0;
//        foreach ($_POST['Vital_ID'] as $vital_id) {
//            $Vital_Value = $_POST['Vital_Value'];
//
//
//            $NurseVitals2 = mysqli_query($conn,"UPDATE tbl_nurse_vital SET Nurse_ID='$nurse_id',Vital_ID='$vital_id',Vital_Value='$Vital_Value[$j]'
//                                          WHERE Nurse_ID='$nurse_id' AND Vital_ID='$vital_id'
//                    ") or die(mysqli_error($conn));
//            $j++;
//        }
    }

    //update query into tbl_patient_payment_item_list
//    $clin_cons = '';
//    if ($Patient_Direction == 'Direct To Doctor') {
//        $clin_cons = ",Consultant_ID='$Consultant_ID',Clinic_ID=NULL";
//    } elseif ($Patient_Direction == 'Direct To Clinic') {
//        $clin_cons = ",Consultant_ID=NULL,Clinic_ID='$Consultant_ID'";
//    }

    $DialysisUpdate_Sql = mysqli_query($conn,"UPDATE tbl_patient_payment_item_list SET Nursing_Status='served' WHERE Patient_Payment_Item_List_ID = '$Patient_Payment_Item_List_ID'"
            ) or die(mysqli_error($conn));


    //	msd for entered data in the form

    echo "<script type='text/javascript'>
					alert('ADDED SUCCESSFUL');
					document.location = 'emergencyclinicalnotes.php?Registration_ID=$Registration_ID&Patient_Payment_ID=$Patient_Payment_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID&NR=true&PatientBilling=PatientBillingThisForm';
                                    </script>";
}
?>
<form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
<table class='hiv_table' style="width:100%;text-align:right;margin-top:5px;" >
    <tr>
        <td rowspan='2'>
            <fieldset class='vital' style="Height:100%;overflow-y:scroll;">

                <?php
                if (isset($_GET['checked']) && $_GET['checked'] == 'true') {
                    $result = mysqli_query($conn, "SELECT v.Vital_ID,Vital,Vital_Value FROM tbl_vital v JOIN tbl_nurse_vital nv ON v.Vital_ID=nv.Vital_ID WHERE nv.Nurse_ID=" . $_GET['Nurse_ID'] . "");
                } else {
                    $result = mysqli_query($conn, "SELECT * FROM tbl_vital");
                }

                echo "<table  border='0'  bgcolor='white' style='width: 100%;'>
<thead>
<tr>
<th width='5%'>SN</th>
<th width='25%'>Vital</th>
<th width='20%'>Value</th>
</tr>
</thead>";
                $i = 1;
                while ($row = mysqli_fetch_array($result)) {
                    $Vital_Value = '';
                    if (isset($_GET['checked']) && $_GET['checked'] == 'true') {
                        $Vital_Value = $row['Vital_Value'];
                    }
                    echo "<tr>";
                    echo "<td>" . $i . "</td>";
                    echo "<td>" . $row['Vital'] . "</td>";
                    echo "<td> <input class='form-control' value='" . $Vital_Value . "' id='" . strtolower($row['Vital']) . "' type='text' autocomplete='off' name='Vital_Value[]' placeholder='' ";
                    if (strtolower($row['Vital']) == "height" || strtolower($row['Vital']) == "weight") {
                        echo " onkeyup='calculateBMI()'";
                    }
                    echo "><input name='Vital_ID[]' type='hidden' value='" . $row['Vital_ID'] . "' ></td>";
                    $i++;
                }
                ?>
    </tr>
</table>
</tr>
</table>
<div style="width:100%;text-align:right;padding-right:10px;">
    <input type="hidden" name="Patient_Payment_Item_List_ID" value="<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>"/>
    <input type="hidden" name="Registration_ID" value="<?php echo $_GET['Registration_ID'] ?>"/>
    <input type="submit" class="art-button-green"   name='submit' id='submit' value='SAVE' onclick="return confirm('Are sure you want to save information?')"   >
    <input type='hidden' name='submitnurseform' value='true'/> 
</div>
</form>
<script type='text/javascript'>
    function calculateBMI() {
        var Weight = document.getElementById('weight').value;
        var Height = document.getElementById('height').value;
        if (Weight != '' && Height != '') {
            if (Height != 0) {
                var bmi = (Weight * 10000) / (Height * Height);
                document.getElementById('bmi').value = bmi.toFixed(2);
            }
        }
    }
</script>