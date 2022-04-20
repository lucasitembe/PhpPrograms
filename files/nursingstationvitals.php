<?php
include("./includes/header.php");
include("./includes/connection.php");
if (!isset($_SESSION['userinfo'])) {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}
if (isset($_SESSION['userinfo'])) {
    if (isset($_SESSION['userinfo']['Nurse_Station_Works']) && isset($_SESSION['userinfo']['Doctors_Page_Outpatient_Work'])) {
        if ($_SESSION['userinfo']['Nurse_Station_Works'] != 'yes' && $_SESSION['userinfo']['Doctors_Page_Outpatient_Work'] != 'yes') {
            header("Location: ./index.php?InvalidPrivilege=yes");
        }
    } else {
        header("Location: ./index.php?InvalidPrivilege=yes");
    }
} else {
    @session_destroy();
    header("Location: ../index.php?InvalidPrivilege=yes");
}


if (isset($_GET['Patient_Payment_ID'])) {
    $Patient_Payment_ID = $_GET['Patient_Payment_ID'];
} else {
    $Patient_Payment_ID = 0;
}

if (isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Patient_Payment_Item_List_ID = $_GET['Patient_Payment_Item_List_ID'];
} else {
    $Patient_Payment_Item_List_ID = 0;
}
?>
<!-- new date function (Contain years, Months and days)--> 
<?php
//Error_reporting(E_ERROR|E_PARSE);
$Today_Date = mysqli_query($conn,"select now() as today");
while ($row = mysqli_fetch_array($Today_Date)) {
    $original_Date = $row['today'];
    $new_Date = date("Y-m-d", strtotime($original_Date));
    $Today = $new_Date;
    $Age = '';
}

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
?>
<!-- end of the function -->

<a href='doctorspageoutpatientwork.php?Registration_ID=<?php echo $_GET['Registration_ID'] ?>&Patient_Payment_ID=<?php echo $_GET['Patient_Payment_ID'] ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID'] ?>' class='art-button-green'>
    BACK
</a>

<?php
//DATE(Trans_Date_Time)='".DATE("Y-m-d")."' 
//to select patient into from patient table and sponsor
if (isset($_GET['Registration_ID']) && isset($_GET['Patient_Payment_Item_List_ID'])) {
    $Registration_ID = $_GET['Registration_ID'];
    $select_Patients = mysqli_query($conn,"SELECT 
					    N.Nurse_DateTime,n.Nurse_ID,Patient_Name,n.Registration_ID, pr.Registration_ID,
					    Guarantor_Name,Gender,Date_Of_Birth,
					    Allegies,Special_Condition,bmi,nurse_comment,emergency,ppil.Patient_Direction,
                                            Clinic_ID,Consultant_ID,modeoftransprot,
                                        accomaniedby,pastmedhist,current_medications 
                                        ,Employee_Name AS nurseServed
				    from 
						tbl_Patient_Registration pr,tbl_sponsor sp,tbl_nurse n,tbl_employee em,tbl_patient_payment_item_list ppil
				    where  
					      n.Registration_ID = pr.Registration_ID AND 
                                              n.Patient_Payment_Item_List_ID = ppil.Patient_Payment_Item_List_ID AND 
					      pr.sponsor_ID = sp.sponsor_ID AND 
                                              em.Employee_ID = n.Employee_ID AND
					     n.Patient_Payment_Item_List_ID='" . $_GET['Patient_Payment_Item_List_ID'] . "' AND
					      n.Registration_ID = '$Registration_ID'") or die(mysqli_error($conn));
    $no = mysqli_num_rows($select_Patients);

    if ($no > 0) {
        while ($row = mysqli_fetch_array($select_Patients)) {
            $Registration_ID = $row['Registration_ID'];
            $Patient_Name = $row['Patient_Name'];
            $Date_Of_Birth = $row['Date_Of_Birth'];
            $Gender = $row['Gender'];
            $Guarantor_Name = $row['Guarantor_Name'];
            $Allegies = $row['Allegies'];
            $Special_Condition = $row['Special_Condition'];
            $bmi = $row['bmi'];
            $Nurse_ID = $row['Nurse_ID'];
            $nurse_comment = $row['nurse_comment'];
            $emergency = $row['emergency'];
            $Consultant_ID = $row['Consultant_ID'];
            $Clinic_ID = $row['Clinic_ID'];
            $Patient_Direction = $row['Patient_Direction'];

            $nurseServed = $row['nurseServed'];

            $modeoftransprot = $row['modeoftransprot'];
            $accomaniedby = $row['accomaniedby'];
            $pastmedhist = $row['pastmedhist'];
            $current_medications = $row['current_medications'];

            $Nurse_DateTime = date("Y-m-d", strtotime($row['Nurse_DateTime']));

            $Direction_Type = '';

            if ($Patient_Direction == 'Direct To Clinic') {
                $consult = mysqli_query($conn,"SELECT Clinic_Name FROM tbl_clinic WHERE Clinic_ID='$Clinic_ID'");
                $row = mysqli_fetch_array($consult);
                $Direction_Name = $row['Clinic_Name'];
                $Direction_Type = 'Clinic';
            } elseif ($Patient_Direction == 'Direct To Doctor') {
                $consult = mysqli_query($conn,"Select Employee_Name from tbl_employee where Employee_ID='$Consultant_ID'");
                $row = mysqli_fetch_array($consult);
                $Direction_Name = $row['Employee_Name'];
                $Direction_Type = 'Consultant';
            }
        }
        $Age = floor((strtotime(date('Y-m-d')) - strtotime($Date_Of_Birth)) / 31556926) . " Years";
        if ($Age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $Age = $diff->m . " Months";
        }
        if ($Age == 0) {
            $date1 = new DateTime($Today);
            $date2 = new DateTime($Date_Of_Birth);
            $diff = $date1->diff($date2);
            $Age = $diff->d . " Days";
        }
    } else {
        $Registration_ID = '';
        $Patient_Name = '';
        $Date_Of_Birth = '';
        $Gender = '';
        $Guarantor_Name = '';
        $Allegies = '';
        $Special_Condition = '';
        $bmi = '';
        $Nurse_ID = '';
        $Patient_Direction = '';
        $Consultant_ID = '';
        $Clinic_ID = '';
        $nurseServed = '';

        $Direction_Name = '';
        $Direction_Type = '';
    }
} else {
    $Registration_ID = '';
    $Patient_Name = '';
    $Date_Of_Birth = '';
    $Gender = '';
    $Guarantor_Name = '';
    $Allegies = '';
    $Special_Condition = '';
    $bmi = '';
    $Nurse_ID = '';
    $Patient_Direction = '';
    $Consultant_ID = '';
    $Clinic_ID = '';
    $Direction_Name = '';
    $Direction_Type = '';
    $nurseServed = '';
}
?>	
<style>
    .bmi-propert{
        font-size: large;
        font-weight: bold;
        display: block;
        margin: 10px;
        background-color: white;
        border: 1px solid rgb(204, 204, 204);
        border-radius: 3px;
        padding: 5px;
        text-align: center;
    }
</style>
<br/><br/>
<center><br/>
    <fieldset >
        <legend align=right><b>NURSE STATION</b></legend>
        <center>
            <table  class='hiv_table'  style="width:100%;">
                <tr>
                    <td width='8%' class="kulia"  style="text-align:right">Patient Name</td>
                    <td width='20%' colspan="3"><input type="text" name='Patient_Name'  disabled='disabled' id='Patient_Name' value="<?php echo$Patient_Name; ?>" /> </td>

                    <td width='8%' class="kulia"  style="text-align:right">Patient No</td>
                    <td width='10%'><input type="text" name='Registration_ID'id="Registration_No" disabled='disabled'  value="<?php echo$Registration_ID; ?>" /> </td>

                    <td width='6%' class="kulia"  style="text-align:right">Sponsor</td>
                    <td width='10%' colspan="2"><input type="text" disabled='disabled' name="Guarantor_Name" id="Guarantor_Name" value="<?php echo$Guarantor_Name; ?>" ></td>
                </tr>
                <tr>
                    <td width='8%' class="kulia"  style="text-align:right">Gender</td>
                    <td><input type="text"  name="Gender" id="Gender"  value="<?php echo$Gender; ?>" disabled='disabled' ></td>

                    <td width='8%' class="kulia"  style="text-align:right">Age</td>
                    <td><input type="text" disabled='disabled' name="Age" id="Age" value="<?php echo$Age; ?>" ></td>

                    <td width='8%' class="kulia"  style="text-align:right">Visit Date</td>
                    <td width='6%'><input type="text" disabled='disabled'  name="Visit_Date" id="Visit_Date" value="<?php echo $Nurse_DateTime; ?>" ></td>

                </tr>
                <tr>
                    <td  style='text-align:right'>Last served by</td>
                    <td colspan='5'><input type='text' value='<?php echo $nurseServed?>' /></td>
                </tr>;
            </table >
        </center>
        <hr>
        <table class='hiv_table' style="width:100%;text-align:right;margin-top:5px;" >
            <tr>
                <td rowspan='2'>
                    <fieldset class='vital' style="height:300px;overflow-y:scroll;">
                        <?php
                        $result = mysqli_query($conn,"SELECT * FROM tbl_vital") or die(mysqli_error($conn));

                        echo "<table border='0'  bgcolor='white' >
		<tr>
		    <th>SN</th>
		    <th>Vital</th>
		    <th width='25%'>Value</th>
		    <th width='20%' colspan='2'>Evolution</th>
		</tr>";
                        $i = 1;
                        $j = 1;

                        while ($row = mysqli_fetch_array($result)) {

                            if (isset($_GET['Patient_Payment_Item_List_ID'])) {
                                $select_Patients = mysqli_query($conn,"SELECT Vital_Value 
				from 
					tbl_nurse n,tbl_nurse_vital nv
				where 
					n.Nurse_ID = nv.Nurse_ID AND 
					n.Registration_ID = '" . $_GET['Registration_ID'] . "' AND
				        n.Patient_Payment_Item_List_ID='" . $_GET['Patient_Payment_Item_List_ID'] . "' AND									
					nv.Vital_ID ='" . $row['Vital_ID'] . "' ") or die(mysqli_error($conn));
                                $nos = mysqli_num_rows($select_Patients);

                                if ($nos > 0) {

                                    while ($rows = mysqli_fetch_array($select_Patients)) {
                                        $Vital_Value = $rows['Vital_Value'];

                                        echo "<tr>";
                                        echo "<td>" . $i . "</td>";
                                        echo "<td>" . $row['Vital'] . "</td>";
                                        echo "<td> <input type='text' name='Vital_Value[]' id='Vital_Value' value='$Vital_Value' readonly='readonly'> </td>";
                                        if (isset($_GET['Registration_ID'])) {
                                            ?>
                                            <td>
                                                <div class="nurse_tabs" style="width:100px;height:30px;padding:0px;">
                                                    <input type='button' name='Show_Vitals' id='patient_file' value='History' onclick='Show_Vitals(<?php echo $row['Vital_ID']; ?>)' class='art-button-green' />

                                                </div>
                                            </td>
                                            <?php
                                        } else {
                                            ?>
                                            <td>
                                                <a href="#" class='art-button-green'>History</a>	
                                            </td>
                                            <?php
                                        }
                                        $i++;
                                    }
                                } else {
                                    $Vital_Value = '';
                                }
                            } else {
                                //$i=1;
                                $Vital_Value = '';
                                echo "<tr>";
                                echo "<td>" . $j . "</td>";
                                echo "<td>" . $row['Vital'] . "</td>";
                                echo "<td> <input type='text' name='Vital_Value[]' id='Vital_Value' value='$Vital_Value' readonly='readonly'> </td>";
                                echo "<td><a href='#' class='art-button-green'>History</a></td>";

                                echo "</tr>";

                                $j++;
                            }
                        }
                        ?>	
            </tr>
        </table>	
    </fieldset >
    <?php
    if (isset($_GET['Registration_ID'])) {

        $category = '';
        if ($bmi < 18.5) {
            $category = '<span style="color: rgb(222, 31, 31);" class="bmi-propert">Underweight</span>';
        } else if ($bmi >= 18.5 && $bmi <= 24.9) {
            $category = '<span style="color:green;" class="bmi-propert">Normal or Helathy Weight</span>';
        } else if ($bmi >= 25 && $bmi <= 29.9) {
            $category = '<span style="color:blue;" class="bmi-propert">Overweight</span>';
        } else if ($bmi >= 30) {
            $category = '<span style="color:red;" class="bmi-propert">Obese</span>';
        }
        ?>
        <div><span style="font-size:14px;margin-left:4px;margin-right:2px;"><b>BMI</b></span>
            <span><input type="text" name="bmi" id="bmi" value="<?php echo $bmi; ?>" disabled='disabled' style="width:100px">

                <span class="nurse_tabs" style="width:120px;height:30px;">
                    <input type='button' name='Show_Vitals' id='patient_file' value='History' onclick='Show_Bmi()' class='art-button-green' />
                </span>
            </span><br/>

            <?php echo $category ?>
        </div>
        <?php
    } else {
        ?>
        <div><span style="font-size:14px;margin-left:4px;margin-right:2px;"><b>BMI</b></span>
            <span><input type="text" name="bmi" id="bmi" value="<?php echo $bmi; ?>" style="width:100px">

            </span>
            <span style="" class="bmi-propert">Bmi status</span>
        </div>
        <?php
    }
    ?>	
</td>
<td>
    <fieldset>
        <table width='100%'>
            <tr>
                <td width="20%" class="kulia"  style="text-align:right">Mode of Transport</td>
                <td>
                    <input type="text" value="<?php echo $modeoftransprot ?>">
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
        <table border='1' width='100%' bgcolor='#D3D3D3' style="float:right">
            <tr>
                <td width="15%" ><b>Direction</b></td>
                <td width="35%" rowspan="1">
                    <input type="text" disabled='disabled' name="Patient_Direction"  value="<?php echo $Patient_Direction; ?>">
                </td>

                <td rowspan=2>
                    <fieldset>
                        <table border="1">
                            <caption><b>Procedure From </b></caption>
                            <tr >
                                <td >Doctor</td><td >Non Doctor</td> 
                            </tr>
                            <tr>
                                <td ><input type="text" disabled='disabled' value="" /></td>
                                <td ><input type="text" disabled='disabled' value="" /></td>
                            </tr>
                        </table>
                    </fieldset>
                </td>
            </tr >
            <tr >
                <td ><b><?php echo $Direction_Type; ?></b></td>
                <td width="4%">
                    <input type="text" name="Consultant" disabled='disabled' value="<?php echo $Direction_Name; ?>" >
                </td>
            </tr >
            <tr>
                <td>
                    &nbsp;
                </td>
                <td colspan="2">
                    <textarea name="nurse_comment" disabled placeholder="Your comment goes here..." id="nurse_comment"><?php echo $nurse_comment ?></textarea>
                </td>
            </tr>
            <tr>
                <td>
                    <span style="color:red;font-weight:bold ">Emergency?</span>
                </td>
                <td colspan="3">
                    <input type="text" name="emergency" disabled='disabled' value="<?php echo ucfirst(strtolower($emergency)); ?>" >

                </td>
            </tr>
        </table>
    </fieldset>
</td>
</tr>
</table>			
</fieldset><br/>

</center>
<script>
    function Show_Vitals(Vital_ID) {
        var winClose = popupwindow('show_vital_history.php?Registration_ID=<?php echo $Registration_ID; ?>&Vital_ID=' + Vital_ID + '&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>', 'Vital History', 1000, 700);
    }
</script>
<script>
    function Show_Bmi() {
        var winClose = popupwindow('show_bmi_history.php?Registration_ID=<?php echo $Registration_ID; ?>&Patient_Payment_Item_List_ID=<?php echo $_GET['Patient_Payment_Item_List_ID']; ?>', 'BMI History', 1000, 700);
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
</script>
<?php
include("./includes/footer.php");
?>