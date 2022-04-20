<?php
include("./includes/header.php");
include("./includes/connection.php");

if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
//    if (isset($_SESSION['userinfo']['Nurse_Station_Works'])) {
//        if ($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes') {
//            header("Location: ./index.php?InvalidPrivilege=yes");
//        }
//    } else {
//        header("Location: ./index.php?InvalidPrivilege=yes");
//    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
?>



<!-- new date function (Contain years, Months and days)--> 
<?php
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $age = '';
}
?>
<!-- end of the function -->

<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes') {
        ?>
        <a href='viewnursepatient.php' class='art-button-green'>
            VIEW CHECKED
        </a>
        <?php
    }
}
?>


<?php
if (isset($_SESSION['userinfo'])) {
    if ($_SESSION['userinfo']['Nurse_Station_Works'] == 'yes') {
         $Registration_ID = $_GET['Registration_ID'];
         $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
         
          $Registration_ID=$_GET['Registration_ID'];
          $sql_select_cons_id="SELECT consultation_ID FROM tbl_consultation WHERE Registration_ID='$Registration_ID'";
          $sql_select_cons_id_result=mysqli_query($conn,$sql_select_cons_id) or die(mysqli_error($conn));
          $rows_cons=mysqli_fetch_assoc($sql_select_cons_id_result);
                         $consultation_ID=$rows_cons['consultation_ID'];
        ?>
        <a href='searchnurseform.php' class='art-button-green'>
            PATIENTS LISTS
        </a>

        <?php
            if(isset($_GET['from']) && $_GET['from'] == "patienFileRecord") {
        ?>
            <a  href="javascript:void(0)" onclick="closeTab();" class='art-button-green'>BACK</a>
        <?php
            } else {
        ?>
            <a  href="nursecommunicationpage.php?Registration_ID=<?php echo $Registration_ID; ?>&consultation_ID=<?php echo  $consultation_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $Patient_Payment_Item_List_ID; ?>"     class='art-button-green' >
                BACK
            </a>
        <?php
            }
        ?>
         

         
        <?php
    }
}

$Allegies = '';
$Special_Condition = '';
$bmi = '';
$Patient_Direction = '';
$nurse_comment = '';
$emergency = '';
$Consultant_ID = '';
$modeoftransprot = '';
$accomaniedby = '';
$pastmedhist = '';
$current_medications = '';
$nurseServed = '';
?>

<?php
include('get_today_vital_sign.php');
//to select patient info from patient table,sponsor, patient payments, patient payment list,Employee 
if (isset($_GET['Registration_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];

    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
    $today = Date("Y-m-d");
    $Registration_ID;
    $response = returnTodayVitalSign($Registration_ID,$today);
//    print_r($response);



    $sql = "SELECT pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name,ppl.Patient_Direction,ppl.Consultant_ID,em.Employee_Type,em.Employee_Name "
            . "FROM tbl_patient_payment_item_list ppl "
            . "INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID "
            . "JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID "
            . "JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID "
            . "LEFT JOIN tbl_employee em ON em.Employee_ID = ppl.Consultant_ID "
            . "WHERE "
            . "Patient_Payment_Item_List_ID='" . $Patient_Payment_Item_List_ID . "' "
            . "";

    //die($sql);
    if (isset($_GET['checked']) && $_GET['checked'] == 'true') {
        $sql = "SELECT pr.Registration_ID,pr.Gender,pr.Patient_Name,pr.Phone_Number,pr.Member_Number,pr.Date_Of_Birth,ppl.Transaction_Date_And_Time,ppl.Patient_Payment_Item_List_ID,pp.Patient_Payment_ID,sp.Guarantor_Name,ppl.Patient_Direction,ppl.Consultant_ID,em.Employee_Type,em.Employee_Name 
                FROM tbl_patient_payment_item_list ppl 
                INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID 
                JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID 
                JOIN tbl_sponsor sp ON sp.Sponsor_ID = pr.Sponsor_ID 
                LEFT JOIN tbl_employee em ON em.Employee_ID = ppl.Consultant_ID 
                WHERE
                ppl.Nursing_Status='served' AND 
               Patient_Payment_Item_List_ID=' $Patient_Payment_Item_List_ID'";
    }


    $select_Patient = mysqli_query($conn,$sql) or die(mysqli_error($conn));

    $no = mysqli_num_rows($select_Patient);

    if ($no > 0) { 
        while ($row = mysqli_fetch_array($select_Patient)) {
            $Registration_ID = $row['Registration_ID'];
            $Patient_Name = $row['Patient_Name'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Patient_Payment_ID = $row['Patient_Payment_ID'];
            $Patient_Direction = $row['Patient_Direction'];
            $Consultant_ID = $row['Consultant_ID'];
            $Consultant = $row['Employee_Name'];
            $Employee_Type = $row['Employee_Type'];
        }
        $Age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        // if($age == 0){

        $date1 = new DateTime($Today);
        $date2 = new DateTime($Date_Of_Birth);
        $diff = $date1->diff($date2);
        $Age = $diff->y . " Years, ";
        $Age .= $diff->m . " Months, ";
        $Age .= $diff->d . " Days";
    } else {
        $Registration_ID = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Guarantor_Name = '';
        $Patient_Direction = '';
        $Patient_Payment_ID = '';
        $Consultant_ID = '';
        $Consultant = '';
        $Employee_Type = '';
        $Age = '';
    }
} else {
    $Registration_ID = '';
    $Patient_Name = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Guarantor_Name = '';
    $Patient_Direction = '';
    $Patient_Payment_ID = '';
    $Consultant_ID = '';
    $Consultant = '';
    $Employee_Type = '';
    $Age = '';
}
?>

<?php
//validation of nurse form
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
					document.location = 'nursecommunicationpage.php?Registration_ID=$Registration_ID&consultation_ID=$consultation_ID&Patient_Payment_Item_List_ID=$Patient_Payment_Item_List_ID';
                                    </script>";
}

//function of list of patients from reception


if (isset($_GET['checked']) && $_GET['checked'] == 'true') {
    $result = mysqli_query($conn,"SELECT n.Employee_ID,Employee_Name AS nurseServed,bmi,Allegies,Patient_Direction,Consultant_ID,
					Special_Condition,Nurse_DateTime,
                                        nurse_comment,emergency,modeoftransprot,
                                        accomaniedby,pastmedhist,current_medications 
                                        FROM tbl_nurse n 
                                        JOIN tbl_patient_payment_item_list ppl ON ppl.Patient_Payment_Item_List_ID=n.Patient_Payment_Item_List_ID
                                        JOIN tbl_employee em ON em.Employee_ID = n.Employee_ID                                        
                                        WHERE Nurse_ID=" . $_GET['Nurse_ID'] . ""
            ) or die(mysqli_error($conn));

    if (mysqli_num_rows($result) > 0) {
        $rowData = mysqli_fetch_array($result);
        $Allegies = $rowData['Allegies'];
        $Special_Condition = $rowData['Special_Condition'];
        $bmi = $rowData['bmi'];
        $Patient_Direction = $rowData['Patient_Direction'];
        $nurse_comment = $rowData['nurse_comment'];
        $emergency = $rowData['emergency'];
        $Consultant_ID = $rowData['Consultant_ID'];
        $nurseServed = $rowData['nurseServed'];

        $modeoftransprot = $rowData['modeoftransprot'];
        $accomaniedby = $rowData['accomaniedby'];
        $pastmedhist = $rowData['pastmedhist'];
        $current_medications = $rowData['current_medications'];

        $Today = date("Y-m-d", strtotime($rowData['Nurse_DateTime']));
    }
}


//end of function of count
//function of list of patients from reception
//$sqlVitals = "SELECT Count(pr.Registration_ID) as vita "
//            . "FROM tbl_patient_payment_item_list ppl "
//            . "INNER JOIN tbl_patient_payments pp ON pp.Patient_Payment_ID = ppl.Patient_Payment_ID "
//            . "JOIN tbl_patient_registration pr ON pp.Registration_ID = pr.Registration_ID "
//            . "WHERE "
//            . "ppl.Nursing_Status='not served' AND "
//            . "ppl.Patient_Direction IN ('Direct To Doctor Via Nurse Station','Direct To Clinic Via Nurse Station') AND "
//            . "Patient_Payment_Item_List_ID != '" . $Patient_Payment_Item_List_ID . "' "
//            . "";
//
//$Vital = mysqli_query($conn,$sqlVitals) or die(mysqli_error($conn));
//
//$rowvtal = mysqli_fetch_array($Vital);
//$vita = $rowvtal['vita'];

//die($Patient_Direction);
?>

<br>
<br>
<style>
    select{
        padding:5px;
        width: 100%;
        font-size: 15px;
    }

    .dates{
        color:#cccc00;
    }
</style>
<center>
    <form action="#" method='post' name='myForm' id='myForm' onsubmit="return validateForm();" enctype="multipart/form-data">
        <fieldset>
            <legend align="right"><b>NURSE STATION</b></legend>
            <center>
                <table class='hiv_table' style="width:100%;text-align:right;" >

                    <tr>
                        <td width='8%' class="kulia"  style="text-align:right">Patient Name</td>
                        <td width='20%' colspan="3"><input type="text" name='Patient_Name'  disabled='disabled' id='Patient_Name' value="<?php echo$Patient_Name; ?>" /> </td>

                        <td width='8%' class="kulia"  style="text-align:right">Patient No</td>
                        <td width='5%'><input type="text" name='Registration_ID'id="Registration_ID"  readonly='readonly'  value="<?php echo$Registration_ID; ?>" /> </td>

                        <td width='6%' class="kulia"  style="text-align:right">Sponsor</td>
                        <td width='8%' colspan="2"><input type="text" disabled='disabled' name="Guarantor_Name" id="Guarantor_Name" value="<?php echo$Guarantor_Name; ?>" ></td>
                    </tr>
                    <tr>
                        <td width='5%' class="kulia"  style="text-align:right">Gender</td>
                        <td><input type="text" width='3%' name="Gender" id="Gender"  value="<?php echo$Gender; ?>" disabled='disabled' ></td>

                        <td width='5%' class="kulia" style="text-align:right">Age</td>
                        <td><input type="text" width='10%' disabled='disabled' name="Age" id="Age" value="<?php echo$Age; ?>" ></td>

                        <td width='8%' class="kulia"  style="text-align:right">Visit Date</td>
                        <td ><input width='4%' type="text" disabled='disabled'  name="Visit_Date" id="Visit_Date" value="<?php echo $Today; ?>" ></td>
                        
                        <!-- *************************************************************************************************************************** -->
                     <!-- <td width='10%' class="kulia"  style="text-align:right">Queue for Nursing</td> -->
                     <?php

                    
                           // $patient_payment_item_id = mysqli_query($conn, "SELECT `Patient_Payment_Item_List_ID`,c.Status,t.Product_Name
                           // FROM tbl_patient_payment_item_list i,tbl_patient_payments p,tbl_item_list_cache c,tbl_items t
                           // WHERE p.Patient_Payment_ID = i.Patient_Payment_ID AND
                           // p.Patient_Payment_ID = c.Patient_Payment_ID AND
                           // i.Item_ID = t.Item_ID AND
                           // p.Registration_ID = '$Registration_ID' AND
                           // c.Check_In_Type = 'Surgery' AND
                           // i.Patient_Payment_Item_List_ID IN ($Patient_Payment_Item_List_ID)
                           // ORDER BY p.Payment_Date_And_Time DESC") or die(mysqli_error($conn));
                           $patient_payment_item_id = mysqli_query($conn,"SELECT i.Product_Name,p.Patient_Payment_Item_List_ID FROM tbl_items i INNER JOIN tbl_patient_payment_item_list p ON i.Item_ID = p.Item_ID
                                                                      WHERE p.Patient_Payment_Item_List_ID IN($Patient_Payment_Item_List_ID)") or die(mysqli_error($conn));

                           $found = mysqli_num_rows($patient_payment_item_id);

                           // if($found > 0)
                           // {

                             ?>
                             <td colspan="2">
                               <div class="form-group">
                                <label for="sel1" style="color:red;">Select Surgery:</label>
                                <select class="form-control" id="sel1" name="Patient_Payment_Item_List_ID">
                                  <?php
                                    while($r = mysqli_fetch_assoc($patient_payment_item_id)){
                                      echo "<option value='".$r['Patient_Payment_Item_List_ID']."'>".$r['Product_Name']."</option>";
                                    }
                                   ?>

                                </select>
                                </div>
                             </td>
                             <?php

                           //}
                      ?>



                     <!-- <td width='10%' class="kulia"  style="text-align:right">Queue for Nursing</td>
                     <td ><input width='4%' type="text" disabled='disabled' name="vita" id="vita" value="< ?php echo $vita; ?>"/></td> -->
                     <!-- **************************************************************************************************************************** -->


                    </tr>
                    <?php
                    if (isset($_GET['checked']) && $_GET['checked'] == 'true') {
                        echo "<tr>
                                  <td  style='text-align:right'>Last served by</td>
                                  <td colspan='3'><input type='text' value='" . $nurseServed . "' /></td>
                                  <td style='text-align:right'  colspan='2'>Currently Servicing Employee</td>
                                  <td  colspan='2'><input type='text' value='" . $_SESSION['userinfo']['Employee_Name'] . "' /></td>
                               </tr>";
                    } else {
                        echo "<tr  colspan='8'>
                                  <td style='text-align:right'>Currently Servicing Employee</td>
                                  <td><input type='text' value='" . $_SESSION['userinfo']['Employee_Name'] . "' /></td>
                               </tr>";
                    }
                    ?>
                </table >
            </center>
            <hr>
            <!-- end of selection-->

            <table class='hiv_table' style="width:100%;text-align:right;margin-top:5px;" >
                <tr>
                    <td rowspan='2'>
                        <fieldset class='vital' style="Height:300px;overflow-y:scroll;">

                            <?php
                            if (isset($_GET['checked']) && $_GET['checked'] == 'true') {
                                $result = mysqli_query($conn,"SELECT v.Vital_ID,Vital,Vital_Value FROM tbl_vital v JOIN tbl_nurse_vital nv ON v.Vital_ID=nv.Vital_ID WHERE nv.Nurse_ID=" . $_GET['Nurse_ID'] . "");
                            } else {
                                $result = mysqli_query($conn,"SELECT * FROM tbl_vital");
                            }

                            echo "<table  border='0'  bgcolor='white' >
<thead>
<tr>
<th width='5%'>SN</th>
<th width='25%'>Vital</th>
<th width='20%'>Value</th>
<th width='25%' colspan='2'>Evolution</th>
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
                                echo "<td > <input value='" . $Vital_Value . "' id='" . strtolower($row['Vital']) . "' type='text' autocomplete='off' name='Vital_Value[]' placeholder='' ";
                                if (strtolower($row['Vital']) == "height" || strtolower($row['Vital']) == "weight") {
                                    echo " onkeyup='calculateBMI()'";
                                }
                                echo "><input name='Vital_ID[]' type='hidden' value='" . $row['Vital_ID'] . "' ></td>";
                                if (isset($_GET['Registration_ID'])) {
                                    $Registration_ID=$_GET['Registration_ID'];
                                    $Vital_ID=$row['Vital_ID'];
                                    $sql_select_number_of_performed_vital_result=mysqli_query($conn,"SELECT Registration_ID FROM tbl_nurse tn INNER JOIN tbl_nurse_vital tnv ON tn.Nurse_ID=tnv.Nurse_ID WHERE tnv.Vital_ID='$Vital_ID' AND Registration_ID='$Registration_ID'") or die(mysqli_error($conn));
                                    $number_of_vital_sign_today=mysqli_num_rows($sql_select_number_of_performed_vital_result);
                                    ?>
                                    <td>
                                        <style>
                                            .mybutton{
                                                color: #FFFFFF!important;
                                                font-size: 13px!important;
                                            }
                                             .mybutton:hover{
                                                background: #038AC4!important;
                                                color: yellow!important;
                                            }
                                            .mybutton:active{
                                                background: #03577D!important;
                                            }
                                        </style>
                                        <div class="nurse_tabs" style="width:100px;Height:30px;padding:0px;"> 
                                           <!-- <input type='button' value='View Result' onclick='Show_Vitals(<?php echo $row['Vital_ID']; ?>)' class='art-button-green' />-->
                                            <button type="button" onclick='Show_Vitals(<?php echo $row['Vital_ID']; ?>)' style="height:30px!important;"class='art-button mybutton'>View Result <span style="background:red"class="badge badge-info"><?= $number_of_vital_sign_today ?></span></button>
                                        </div>
                                    </td>
                                    <?php
                                } else {
                                    ?>
                                    <td><a href='#'  class='art-button-green'>View Result</a></td>
                                    <?php
                                }
                                $i++;
                            }
                            ?>	
                </tr>
            </table>
        </fieldset >
        <br>
        <div><span style="font-size:14px;margin-left:4px;margin-right:2px;"><b>BMI</b></span>
            <span><input type="text" name="bmi" id="bmi" value='<?php echo $bmi ?>'
                         style="width:100px">
                <span class="nurse_tabs" style="width:120px;height:30px;">	
                    <input type='button'  value='View Result' onclick='Show_Bmi()' class='art-button-green' />

                    <!--copy this line below -->
                    <input type='button'  value='Preview' onclick='Show_Preview(<?php echo $Registration_ID; ?>)' class='art-button-green' />;
                </span>
            </span>
        </div>
        </td>
        <td>	
            <fieldset>
                <table width='100%'>
                    <tr>
                        <td width="20%" class="kulia"  style="text-align:right">Mode of Transport</td>
                        <td>
                            <select name="modeoftransprot">
                                <option ></option>
                                <option <?php if ($modeoftransprot == 'Ambulatory') echo 'selected' ?>>Ambulatory</option>
                                <option <?php if ($modeoftransprot == 'Wheelchair') echo 'selected' ?>>Wheelchair</option>
                                <option <?php if ($modeoftransprot == 'Others') echo 'selected' ?>>Others</option>
                                <option <?php if ($modeoftransprot == 'Trolley') echo 'selected' ?>>Trolley</option>
                                <option <?php if ($modeoftransprot == 'Cuddled') echo 'selected' ?>>Cuddled</option>
                                <option <?php if ($modeoftransprot == 'Other') echo 'selected' ?>>Other</option>
                            </select>
                        </td>
                        <td width="20%" class="kulia"  style="text-align:right">Allergies</td>
                        <td ><textarea rows="3" name="Allegies"  id="Allegies"  style="resize:none;"><?php echo $Allegies ?></textarea></td>
                    </tr>
                    <tr>
                        <td width="20%" class="kulia"  style="text-align:right">Accompanied by</td>
                        <td ><input  class="kulia"  type="text" name="accomaniedby" value="<?php echo $accomaniedby ?>"/></td>
                        <td class="kulia" width="21%"  style="text-align:right">Special Condition / Chief Complaints</td>
                        <td><textarea rows="3" name="Special_Condition" id="Special_Condition" style="resize:none;" ><?php echo $Special_Condition ?></textarea></td>
                    </tr>
                    <tr>
                        <td width="20%" class="kulia"  style="text-align:right">Past Medical History / Surgeries</td>
                        <td ><textarea rows="3" name="pastmedhist" id="pastmedhist" style="resize:none;" ><?php echo $pastmedhist ?></textarea></td>
                        <td class="kulia" width="21%"  style="text-align:right">Current Medications</td>
                        <td><textarea rows="3" name="current_medications" id="current_medications" style="resize:none;" ><?php echo $current_medications ?></textarea></td>
                    </tr>
                </table>
                <table border='1' width='100%' style="float:right">
                    <tr >
                       <!-- <td width="15%" ><b>Direction</b></td>
                        <td width="35%" rowspan="1">
                            <select name="Patient_Direction" id="Patient_Direction" onChange='getDoctor(this.value)'>
                                <option <?php
                                if ($Patient_Direction == 'Direct To Doctor Via Nurse Station' || $Patient_Direction == 'Direct To Doctor') {
                                    ?>selected='selected'
                                        <?php
                                    }
                                    ?>
                                    >Direct To Doctor</option>
                                <option <?php
                                if ($Patient_Direction == 'Direct To Clinic Via Nurse Station' || $Patient_Direction == 'Direct To Clinic') {
                                    ?>  selected='selected'
                                        <?php
                                    }
                                    ?>
                                    >Direct To Clinic</option>

                            </select>
                        </td>-->
                                    <td rowspan=3 class="hide">
                            <fieldset>
                                <table border="1">
                                    <caption><b>Procedure From </b></caption>
                                    <tr >
                                        <td>Doctor</td><td >Non Doctor</td> 
                                    </tr>
                                    <tr>
                                        <td ><input type="text" disabled='disabled' /></td>
                                        <td ><input type="text" disabled='disabled' /></td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr ><tr >
                        <!--<td ><b>Consultant</b></td>
                        <td rowspan="">
                            <select name="Consultant" id="Consultant_ID" width="100%">
                                <?php
                                if ($Patient_Direction == 'Direct To Doctor Via Nurse Station' || $Patient_Direction == 'Direct To Doctor') {
                                    $consult = mysqli_query($conn,"Select * from tbl_employee where Employee_Type='Doctor'");
                                    while ($row = mysqli_fetch_array($consult)) {
                                        $Employee_ID = $row['Employee_ID'];
                                        $Employee_Name = $row['Employee_Name'];
                                        ?>
                                        <option 
                                        <?php
                                        if ($Consultant_ID == $Employee_ID) {
                                            ?> selected='selected' <?php
                                            }
                                            ?> value="<?php echo $Employee_ID; ?>" >
                                                <?php echo $Employee_Name; ?>		 			 
                                        </option>
                                        <?php
                                    }
                                } elseif ($Patient_Direction == 'Direct To Clinic Via Nurse Station' || $Patient_Direction == 'Direct To Clinic') {
                                    $consult = mysqli_query($conn,"SELECT * FROM tbl_clinic");
                                    while ($row = mysqli_fetch_array($consult)) {
                                        $Clinic_ID = $row['Clinic_ID'];
                                        $Clinic_Name = $row['Clinic_Name'];
                                        ?>
                                        <option 
                                        <?php
                                        if ($Consultant_ID == $Clinic_ID) {
                                            ?> selected='selected' <?php
                                            }
                                            ?> value="<?php echo $Clinic_ID; ?>" >
                                                <?php echo $Clinic_Name; ?>		 			 
                                        </option>
                                        <?php
                                    }
                                }
                                ?>
                            </select>
                        </td>-->
                    </tr >
                    <tr>
                        <td>
                            Comment / Patient Assessments
                        </td>
                        <td colspan="2">
                            <textarea name="nurse_comment" placeholder="Your comment / patient assessments goes here..." id="nurse_comment"> <?php if (!empty($nurse_comment)) echo $nurse_comment; ?></textarea>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span style="color:red;font-weight:bold ">Triage Status</span>
                        </td>
                        <td colspan="3">
                            <select name="emergency" id="emergency">
                                <option value="Green" <?php if ($emergency == 'Green') echo 'selected' ?>>Green</option>
                                <option value="Emergency" <?php if ($emergency == 'Emergency') echo 'selected' ?>>Emergency(RED)</option>
                                <option value="Yellow" <?php if ($emergency == 'Yellow') echo 'selected' ?>>Yellow</option>
                                <option value="Black" <?php if ($emergency == 'Black') echo 'selected' ?>>Black</option>
                            </select>
                        </td>
                    </tr>
                </table>
            </fieldset>
        </td>
        </tr>
        </table>
        <hr>
        <div style="width:100%;text-align:right;padding-right:10px;">
            <input type="hidden" name="Patient_Payment_Item_List_ID" value="<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>"/>
            <input type="hidden" name="Registration_ID" value="<?php echo $_GET['Registration_ID'] ?>"/>
            <input type="submit" class="art-button-green"   name='submit' id='submit' value='SAVE' onclick="return confirm('Are sure you want to save information?')"   >
            <input type='hidden' name='submitnurseform' value='true'/> 
        </div>
        </fieldset>
    </form>
</center>
<!-- SCript of BMI calculate-->	
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
<!-- End of script of BMI -->	

<script type="text/javascript" language="javascript">
    function getDoctor(direction) {
        if (window.XMLHttpRequest) {
            mm = new XMLHttpRequest();
        } else if (window.ActiveXObject) {
            mm = new ActiveXObject('Microsoft.XMLHTTP');
            mm.overrideMimeType('text/xml');
        }
        mm.onreadystatechange = AJAXP3; //specify name of function that will handle server response....
        mm.open('GET', 'Get_clinic_employee.php?direction=' + direction, true);
        mm.send();
    }
    function AJAXP3() {
        var data = mm.responseText;
        document.getElementById('Consultant_ID').innerHTML = data;
    }
</script>
<script>
    //copy this function below 
    function Show_Preview(obj){
        window.open(
        'vital_report.php?patient_id='+obj,
        '_blank' 
        );
    }
</script>
<script type="text/javascript">
    function Show_Vitals(Vital_ID) {
        var winClose = popupwindow('show_vital_history.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Vital_ID=' + Vital_ID + '&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>', 'Vital History', 1000, 700);
    }
</script>
<script type="text/javascript">
    function Show_Bmi() {
        var winClose = popupwindow('show_bmi_history.php?Registration_ID=<?php echo $_GET['Registration_ID']; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>', 'BMI History', 1000, 700);
    }

</script>
<script>
    function popupwindow(url, title, w, h) {
        var wLeft = window.screenLeft ? window.screenLeft : window.screenX;
        var wTop = window.screenTop ? window.screenTop : window.screenY;

        var left = wLeft + (window.innerWidth / 2) - (w / 2);
        var top = wTop + (window.innerHeight / 2) - (h / 2);
        var mypopupWindow = window.showModalDialog(url, title, 'dialogWidth:' + w + '; dialogHeight:' + h + '; center:yes;dialogTop:' + top + '; dialogLeft:' + left);//'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=no, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

        return mypopupWindow;
    }

    function closeTab() {
        window.close();
    }
</script>
<?php
include("./includes/footer.php");
?>